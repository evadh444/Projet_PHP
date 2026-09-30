<?php

$query = "SELECT PrestationId, nomPrestation, prix, dureeMinute FROM Prestation ORDER BY PrestationId ASC"; //ASC=ordre croissant
$prestations = $pdo->query($query)->fetchAll();

//print_r($prestations);

?>

<h1>Liste des Préstations</h1>

<?php if (
    isset($_SESSION['user']) &&
    $_SESSION['user']['role'] === 'ADMIN'
) : ?>

    <a href="index.php?page=Prestation-create" class="btn">Ajouter une préstation</a>

<?php endif ?>

<p><?= count($prestations) ?> préstation(s) disponible(s)</p>

<div class="cards">
    <?php foreach ($prestations as $prestation) : ?>

        <article class="card">
            <h2><?=  $prestation ["nomPrestation"] ?></h2>
            <p><?= $prestation["prix"] ?> €</p>
            <p><?=  $prestation["dureeMinute"] ?>min.</p>

            <div class="actions">
                <a href="index.php?page=Prestation-details&amp;id=<?= $prestation['PrestationId'] ?>" class="btn">Détails</a>

                <?php if (isset($_SESSION['user'])) : ?>
                    <a href="index.php?page=Reservations&amp;id=<?= $prestation['PrestationId'] ?>" class="btn">Réserver</a>
                <?php else : ?>
                    <a href="index.php?page=login" class="btn">Réserver</a>
                <?php endif ?>  <!-- 2 reserver car lecture du bouton possible si pas connecté mais demande de connexion lors du clic -->

                <?php if (
                    isset($_SESSION['user']) &&
                    $_SESSION['user']['role'] === 'ADMIN') : ?>
                    <a href="index.php?page=Prestation-edit&amp;id=<?= $prestation['PrestationId'] ?>" class="btn">Modifier</a>

                    <form 
                    method="post" 
                    action="index.php?page=Prestation-delete"
                    onsubmit="return confirm('Voulez-vous supprimer <?= $prestation['nomPrestation'] ?> ?')">

                    <input type="hidden" name="id" value="<?= $prestation['PrestationId'] ?>">
                    <button class="btn">🗑️</button>
                    </form>

                <?php endif ?>
            </div>
        </article>
    <?php endforeach ?>
</div>




