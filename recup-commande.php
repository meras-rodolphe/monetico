<?php
if (isset($_GET['idCommande'])) {
    $idCommande = $_GET['idCommande'];
    $idUser = $_SESSION['idUser'];
    $recupUserMail = $bdd->prepare('SELECT * FROM users WHERE idUser = ?');
    $recupUserMail->execute(array($idUser));
    $userMail = $recupUserMail->fetch();
    $mail = $userMail['email'];
    $nom = $userMail['nomUser'];
    $prenom = $userMail['prenomUser'];
    $tel = $userMail['telUser'];

    $recupDetail = $bdd->prepare('SELECT * FROM commandedetail WHERE idCommande = ?');
    $recupDetail->execute(array($idCommande));

        if ($recupDetail->rowCount() > 0) {
            $totalCommande = 0;

            while ($detail = $recupDetail->fetch()) {
                $idproduit = $detail['idProduit'];
                $prixProduit = $detail['prix'];
                $quantite = $detail['qts'];
                $idliv = $detail['idLivraison'];

                $options = array();  // Tableau pour stocker les options
                $tarifLivraison = 0;

                $recuOP = $bdd->prepare('SELECT idDetailOp FROM commandeOp WHERE idCommande = ?');
                $recuOP->execute(array($idCommande));

                $recuprIdAd = $bdd->prepare('SELECT * FROM adresse INNER JOIN commandes ON adresse.idAd = commandes.idAd WHERE idCommande = ?');
                $recuprIdAd->execute(array($idCommande));
                while($adresse = $recuprIdAd->fetch()){
                    $num = $adresse['num'];
                    $rue = $adresse['voie'];
                    $ville = $adresse['city'];
                    $cp = $adresse['cp'];
                    $pays = $adresse['pays'];
                }

                while ($idOP = $recuOP->fetch()) {
                    $idOP = $idOP['idDetailOp'];
                    $recupdetaiop = $bdd->prepare('SELECT * FROM detailoption WHERE idDetailOp = ?');
                    $recupdetaiop->execute(array($idOP));
                    $detaiop = $recupdetaiop->fetch();
                    $options[] = $detaiop['detailOp'];
                }

                    $recupProduit = $bdd->prepare('SELECT * FROM produits WHERE idProduit = ?');
                    $recupProduit->execute(array($idproduit));
                    $produit = $recupProduit->fetch();
                    $image = $produit['name'];
                    $nomProduit = $produit['nomProduits'];

                    $recupliv = $bdd->prepare('SELECT * FROM livraisondetail WHERE idLivraison = ?');
                    $recupliv->execute(array($idliv));
                    $liv = $recupliv->fetch();
                    $prixLivraison = $liv['price'];

                    $totalProduit = ($prixProduit + $prixLivraison) * $quantite;
                    $totalCommande += $totalProduit;
                                        

               $dateActuelle = new DateTime();
$date = $dateActuelle->format('d/m/Y:H:i:s');


// Définir les variables PHP
$billing = array(
    "firstName" => $nom,
    "lastName" => $prenom,
    "addressLine1" => $num . ' ' . $rue,
    "city" => $ville,
    "postalCode" => $cp
    "country" => "FR"
);

$shipping = array(
    "firstName" => $nom
    "lastName" => $prenom
    "addressLine1" => $num . ' ' . $rue
    "city" => $ville
    "postalCode" => $cp,
    "country" => "FR",
    "email" => $mail,
    "phone" => $tel,
    "shipIndicator" => "billing_address",
    "deliveryTimeframe" => "other",
    "matchBillingAddress" => true
);


// Créer un tableau associatif avec les données
$data = array(
    "billing" => $billing,
    "shipping" => $shipping
);

// Convertir le tableau associatif en JSON
$jsonData = json_encode($data, JSON_PRETTY_PRINT);
$json_base64 = base64_encode($json_billing);
// Afficher le JSON
echo $jsonData;

// Clé de sécurité représentée de façon externe
$cleExterneHex = "****************";

// Convertir la représentation hexadécimale de la clé en binaire
$cleBinaire = hex2bin($cleExterneHex);

// Données à utiliser pour générer le HMAC
$TPE = '*****';
$contexte_commande = $json_base64;
$montant = $totalCommande . 'EUR';
$reference = $idCommande;
$texte_libre = 'Merci-pour-nos-artisans.';
$version = '3.0';
$lgue = 'FR';
$societe = 'lesfabrica';
$mail = $mail;

// Concaténation des données
$dataToHash = "version=$version*TPE=$TPE*date=$date*montant=$montant*reference=$reference*lgue=$lgue*societe=$societe*contexte_commande=$json_base64*texte-libre=$texte_libre*mail=$mail";

// Calcul du HMAC en utilisant hash_hmac
$MAC = hash_hmac('sha1', $dataToHash, $cleBinaire);




        }

        // Calcul du total de la commande
            $tva = $totalCommande * 0.2;
                           
    } else {
     echo "Aucune commande";
    }
}

                
