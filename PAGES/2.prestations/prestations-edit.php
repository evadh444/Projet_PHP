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
    // Mapping données
    foreach (array_keys($_POST) as $field) {
        $values[$field] = trim($_POST[$field] ?? '');
    }

    //Valider les champs obligatoires

    // 1. Nom Prestation
    if ($values['nomPrestation'] === '') {
        $errors['nomPrestation'] = "Le nom est obligatoire";
    } else if (strlen($values['nomPrestation']) > 50) {
        $errors['nomPrestation'] = "Le nom de la prestation ne peut pas dépasser 50 caractères";
    }

    // 2.Description Prestation -  ALLOW NULL (pas obligatoire)
    if ($values['description'] === '') {
        $values['description'] = null;
    }
    // 3.Prix
    if($values['prix'] !== '') {
        $prix = filter_var($values[$prix], FILTER_VALIDATE_FLOAT);
        if(!$prix || $prix < 0){
            $errors['prix'] = "Le prix doit être positif";
        }
    }

    // 4.Durée Prestation
    if($values['dureeMinute'] === '') {
        $errors['dureeMinute'] = "La durée est obligatoire";
    }                       // à l'avenir ajouter les heures en + 


// Update si pas d'erreurs

    if(!$errors) {
    
    try {
        $sql = "UPDATE Prestation
                SET 
                    nomPrestation = ?,
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
}
?>

<!-- Affichage -->

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

    <div>
        <label for="prix">Prix : </label>
        <input type="number" name="prix" id="prix" required value="<?= $values['prix'] ?>">
        <?php if (isset($errors['prix'])) : ?>
            <span class="error"><?= $errors['prix'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="dureeMinute">Durée de la préstation</label>
        <input type="number" name="dureeMinute" id="dureeMinute" required value="<?= $values['dureeMinute'] ?>">
        <?php if (isset($errors['dureeMinute'])) : ?>
            <span class="error"><?=  $errors['dureeMinute'] ?></span>
        <?php endif ?>
    </div>

    <button>Modifier la préstation</button>

    <p>* champ obligatoire</p>
</form>