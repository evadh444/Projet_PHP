<?php

// choper l'id de la prestation
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$query = "SELECT PrestationId, nomPrestation, description, prix, dureeMinute FROM Prestation ORDER BY nomPrestation";

$statement = $pdo->prepare($sql);
$statement->execute([$id]);

$prestation = $statement->fetch();

if(!$prestation) {
    die("Prestation introuvable");
}

$values = [
    'nomPrestation' => $prestation['nomPrestation'],
    'description' => $prestation['description'],
    'prix' => $prestation['prix'],
    'dureeMinute' => $prestation['dureeMinute']
];

$errors = [];

// Gérer Formulaire

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    foreach (array_keys($_POST) as $field) {
        $values[$field] = trim($_POST[$field] ?? '');
    }

    //Valider les champs obligatoires
    if ($values['nomPrestation'] === '') {
        $errors['nomPrestation'] = "Le nom est obligatoire";
    } else if (strlen($values['nomPrestation']) > 50) {
        $errors['nomPrestation'] = "Le nom de la prestation ne peut pas dépasser 50 caractères";
    }
}

    if($values['prix'] !== '') {
        $prix = filter_var($values[$prix], FILTER_VALIDATE_FLOAT);
        if(!$prix || $prix < 0){
            $errors['prix'] = "Le prix doit être positif";
        }
    }

    if($value['dureeMinute'] === '') {
        $errors['dureeMinute'] = "La durée est obligatoire";
    }

// Update

    if(!$errors) {
        try {
            $sql = "UPDATE Prestation
                    SET nomPrestation = ?,
                        description = ?,
                        prix = ?,
                        dureeMinute = ?,
                    WHERE PrestationId = ?";

            $statement = $pdo->prepare($sql);
            $statement->execute([
                $values['nomPrestation'],
                $values['description'],
                $values['prix'],
                $values['dureeMinute'],
                $id
            ]);
            header('Location: index.php?page=prestations-details&id=' . $id);
        } catch (PDOException $e) {
            $errors['database'] = "Une erreur est survenue lors de la modification de la prestation";
        }
    }

?>

<style>
  form * {
    display: block;
    margin: 10px 0;
    }
</style>

<h1>Modification d'une préstation</h1>

<form method="post">
    <div>
        <label for="nomPrestation">Nom de la préstation</label>
        <input type="text" name="nomPrestation" id="nomPrestation" required value="<?= $values['nomPrestation'] ?>">
        <?php if (isset($errors['nomPrestation'])) : ?>
            <span class="error"><?= $errors['nomPrestation'] ?></span>
        <?php endif ?>
    </div>