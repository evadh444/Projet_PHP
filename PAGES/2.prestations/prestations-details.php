<?php
 // Récupérer l'id dans l'URL

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$prestation = false;

if($id !== false && $id !== null) {

    $sql = "SELECT
                PrestationId,
                nomPrestation,
                description,
                prix,
                dureeMinute
            FROM Prestation
            WHERE PrestationId = ?";
    
    $request = $pdo->prepare($sql);
    $request->execute([$id]);

    $prestation = $request->fetch();
}
?>

<!-- Affichage -->
<?php if (!$prestation) : ?>
    <?php http_response_code(404); ?>
    <h1>Préstation introuvable</h1>
    <p>Aucune préstation ne correspond à l'id <?= $id ?>.</p>
<?php else : ?>
    <h1>Détails de <?= htmlspecialchars($prestation['nomPrestation']) ?></h1>

    <dl>
        <dt>Description:</dt>
        <dd><?= htmlspecialchars($prestation['description'] ?? '') ?></dd>

        <dt>Prix:</dt>
        <dd><?= number_format($prestation['prix'], 2, ',', ' ') ?>&euro;</dd>

        <dt>Durée de la prestation:</dt>
        <dd><?= number_format($prestation['dureeMinute'], 0, ',', ' ') ?>minutes</dd>

    </dl>

    <a href="?page=Prestations" class="btn btn-back">Retour à la liste</a>

<?php endif ?>