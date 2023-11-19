<?php 
require('../config/bdd/bdd.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de réception du paiement - Les Fabricants Français</title>
    <style>
        /* Style CSS pour l'animation d'attente */
        body {
            text-align: center;
            padding: 20px;
        }
        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="loader"></div>
    <h1>Attendez la confirmation du paiement...</h1>
    <?php
    // Vérifiez que vous avez reçu les données de paiement.
    if (isset($_POST['code-retour'], $_POST['reference'])) {
        // Stockez les données de paiement dans des variables.
        $reference = $_POST['reference'];
        $montant = $_POST['montant'];
        $statut = $_POST['code-retour'];

        // Vérifiez si la référence existe dans la table de paiement
        $sql_check = "SELECT * FROM paiement WHERE reference = :reference";
        $stmt = $bdd->prepare($sql_check);
        $stmt->bindParam(':reference', $reference);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // La référence existe, effectuer une mise à jour en fonction du statut
            if ($statut === 'payetest') {
                $sql_update = "UPDATE paiement SET etat = 'paye' WHERE reference = :reference";
            } else {
                $sql_update = "UPDATE paiement SET etat = 'annule' WHERE reference = :reference";
            }

            $stmt = $bdd->prepare($sql_update);
            $stmt->bindParam(':reference', $reference);
            $stmt->execute();
        } else {
            // La référence n'existe pas, créez une nouvelle ligne dans la table paiement
            $sql_insert = "INSERT INTO paiement (reference, montant, etat) VALUES (:reference, :montant, :etat)";
            $stmt = $bdd->prepare($sql_insert);
            $etat = ($statut === 'payetest') ? 'paye' : 'annule';
            $stmt->bindParam(':reference', $reference);
            $stmt->bindParam(':montant', $montant);
            $stmt->bindParam(':etat', $etat);
            $stmt->execute();
        }
    } else {
        // Les données de paiement n'ont pas été reçues, vous pouvez afficher un message d'erreur.
        echo 'Les données de paiement n\'ont pas été reçues.';
    }
?>

</body>

</html>
