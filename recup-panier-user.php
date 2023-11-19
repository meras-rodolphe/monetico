<?php
if (isset($_GET['idCommande'])) {
    $idCommande = $_GET['idCommande'];
    $recupDetail = $bdd->prepare('SELECT * FROM commandedetail INNER JOIN produits ON commandedetail.idProduit = produits.idProduit WHERE idCommande = ?');
    $recupDetail->execute(array($idCommande));
    if ($recupDetail->rowCount() > 0) {
        while ($detail = $recupDetail->fetch()) {
            $idproduit = $detail['idProduit'];
            $prix = $detail['prix'];
            $quantite = $detail['qts'];
            $description = $detail['descriptif'];
            $idliv = $detail['idLivraison'];

            $recuOP = $bdd->prepare('SELECT * FROM commandeop WHERE idCommande = ?');
            $recuOP->execute(array($idCommande));
            $idOP = $recuOP->fetch();
            $idOP = $idOP['idDetailOp'];

            $recupdetaiop = $bdd->prepare('SELECT * FROM detailoption WHERE idDetailOp = ?');
            $recupdetaiop->execute(array($idOP));
            $detaiop = $recupdetaiop->fetch();
            $option = $detaiop['detailOp'];
            $tarif = $detaiop['tarif'];

            $recuprIdAd = $bdd->prepare('SELECT * FROM adresse INNER JOIN commandes ON adresse.idAd = commandes.idAd WHERE idCommande = ?');
            $recuprIdAd->execute(array($idCommande));
            $idAd = $recuprIdAd->fetch();
            $idAd = $idAd['idAd'];
                    
            $recupProduit = $bdd->prepare('SELECT * FROM produits WHERE idProduit = ?');
            $recupProduit->execute(array($idproduit));
            $produit = $recupProduit->fetch();
            $image = $produit['name'];
            $produit = $produit['nomProduits'];

            // Récupérez les informations de livraison pour ce produit
            $recupliv = $bdd->prepare('SELECT * FROM livraisondetail WHERE idLivraison = ?');
            $recupliv->execute(array($idliv));
            $liv = $recupliv->fetch();
            $nomliv = $liv['entreprise'];
            $duree = $liv['delait'];
            $prixliv = $liv['price'];

            // Calculez le total pour ce produit
            $total = $prix * $quantite + $prixliv + $tarif;

            // Récupérez les informations de l'adresse de livraison
            $recupAd = $bdd->prepare('SELECT * FROM adresse WHERE idAd = ?');
            $recupAd->execute(array($idAd));
            $adresse = $recupAd->fetch();
            $num = $adresse['num'];
            $rue = $adresse['voie'];
            $ville = $adresse['city'];
            $cp = $adresse['cp'];
            $pays = $adresse['pays'];
?>
            <!-- Affichage des informations pour chaque produit -->
            <tr>
                <td><?=$description;?></td>
                <td><img src="../upload/<?=$image;?>" width="150px" height="150px" alt="..."></td>
                <td><?=$num;?> <?=$rue;?> <?=$ville;?> <?=$cp;?> <?=$pays;?></td> 
                <td><?=$prix;?>€</td>
                <td><?=$quantite;?></td>
                <td><?=$option;?> <?=$tarif;?>€</td>
                <td><?=$nomliv;?> (Délai : <?=$duree;?>)</td>
                <td><?=$total;?>€</td>
            </tr>
<?php
        }
    } else {
        echo "Aucune commande";
    }
}
?>
