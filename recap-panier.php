<?php 
require('../config/bdd/bdd.php');
require('../config/secu-user.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require('../includes/head.php'); ?>
    <title>Détail utilisateur</title>
    
</head>
<body>
    <?php require ('nav-user-index.php'); ?>
    <article>
    <div class="rafal">
        <div class="tableau">
            <table class="table table-striped">
                <thead class="ab">
                    <tr>
                        <th style="text-align:left;" >Nom du produit</th>
                        <th style="text-align:left;" >Photo</th>
                        <th style="text-align:left;" >Adresse</th>
                        <th style="text-align:left;" >Prix du produit</th>
                        <th style="text-align:left;" >Prix de livraison</th>
                        <th style="text-align:left;" >Options</th>
                        <th style="text-align:left;" >Total par produit</th>
                    </tr>
                </thead>
                <?php 
                require('recup-commande.php');?>
                <tbody>
                <tr>
                <td><?= $nomProduit; ?></td>
                <td><img src="../upload/<?= $image; ?>" width="150px" height="150px" alt="..."></td>
                <td><?= $num; ?> <?= $rue; ?> <?= $ville; ?> <?= $cp; ?> <?= $pays; ?></td>
                <td><?= $prixProduit; ?>€</td>
                <td><?= $prixLivraison; ?>€</td>
                <td><?= implode(', ', $options); ?></td>
                <td><?= $totalProduit; ?>€</td>
                </tr>
                <!-- Ligne TVA -->
                <tr>
                <td colspan="6" style="text-align: right;"><small>tva 20%</small></td>
                <td><?= number_format($tva, 2); ?> €</td>
                </tr>

                <!-- Ligne Tarif Total de la Commande -->
                <tr>
                <td colspan="6" style="text-align: right;">Tarif Total de la Commande</td>
                <td><?= number_format($totalCommande); ?> €</td>
                </tr>
            </tbody>
        </table>
        <form method="post" name="Monetico" target="_top" action="https://p.monetico-services.com/test/paiement.cgi ">
            <input type="hidden" name="version" value="3.0">
            <input type="hidden" name="TPE" value="7589889">
            <input type="hidden" name="date" value="<?=$date;?>">
            <input type="hidden" name="montant" value="<?= $totalCommande;?>EUR">
            <input type="hidden" name="reference" value="<?= $idCommande;?>">
            <input type="hidden" name="MAC" value="<?= $MAC;?> ">
            <input type="hidden" name="lgue" value="FR">
            <input type="hidden" name="societe" value="<?= $societe;?>">
            <input type="hidden" name="contexte_commande" value="<?= $json_base64;?>">
            <input type="hidden" name="texte-libre" value="Merci pour nos artisans.">  
            <input type="hidden" name="mail" value="<?=$mail;?>">                 
            <button type="submit" name="bouton" value="Paiement CB" id="lucifer">
            Paiement CB
            </button> 
        </form>
    </div>
    </div>
</article>


<article>
    <div class="concorde">
        <?php
        require('recup-commande.php');
       ?>
                    <div class="card" id="produc">
                        <img src="../upload/<?= $image; ?>" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title"><?= $nomProduit; ?></h5>

                            <div class="mobile-info">
                                <p class="red1">Adresse de livraison: <br>
                                    <?= $num; ?> <?= $rue; ?><br>
                                    <?= $ville; ?> <?= $cp; ?><br>
                                    <?= $pays; ?></p>

                                <table class="table table-striped">
                                    <tr>
                                        <th>Quantité</th>
                                        <td><?= $quantite; ?></td>
                                    </tr>
                                    <tr>
                                        <th>Prix</th>
                                        <td><?= $prixProduit; ?> €</td>
                                    </tr>
                                    <tr>
                                        <th>Livraison</th>
                                        <td><?= $prixLivraison; ?> €</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td><?= $totalProduit; ?> €</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                <div>
                <table class="table table-striped">
                <tr>
                    <td colspan="6" style="text-align: right;">TVA 20%</td>
                    <td><?= number_format($tva, 2); ?> €</td>
                </tr>
                <!-- Ligne Tarif Total de la Commande -->
                <tr>
                    <td colspan="6" style="text-align: right;">Tarif Total de la Commande</td>
                    <td><?= number_format($totalCommande); ?> €</td>
                </tr>
            </table>
        </div>
    </div>
</article>
<?php require '../includes/footer/footer.php'; ?>
</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</html>				