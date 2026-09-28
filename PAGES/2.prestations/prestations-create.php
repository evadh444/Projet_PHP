<?php

// Pas besoin de selecteur ici, mais dans reservations.php

$values = [
    'nomPrestation' => '',
    'description' => '',
    'prix' => '0',
    'dureeMinute' => '0',
];

$errors = [];

// Gérer formulaire 
if($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Mapping données
    foreach(array_keys($_POST) as $field) {
        $values[$field] = trim($_POST[$field]) ?? '';
    }

    // Valider les champs
    // 1.Nom Prestation
    if($values['nomPrestation'] === '') {
        $errors['nomPrestation'] = "Le nom est obligatoire";
    } else if (strlen($values['nomPrestation']) > 50) {
        $errors['nomPrestation'] = "Le nom de la préstation ne peut pas dépasser 50 caractères";
    }

    //2.Description(allow null) - pas obligatoire
    if ($values['description'] === '') {
        $values['description'] = 'null';
    }

    // 3.Prix
    if($values['prix'] !== '') {
        $prix = filter_var($values['prix'], FILTER_VALIDATE_FLOAT);
        if(!$prix || $prix < 0) {
            $errors['prix'] = "Le prix doit être positif";
        }
    }

    //4. Durée prestation
    if($values['dureeMinute'] === '') {
        $errors['dureeMinute'] = "La durée est obligatoire";
    } else {
        $duree = filter_var($values['dureeMinute'], FILTER_VALIDATE_INT);

        if($duree === false || $duree <= 0) {
            $erros['dureeMinute'] = "La durée doit être un nombre entier supérieur à 0";
        }
    }

    // Ajouter la prestation dans la DB si pas d'erreurs

    if(!$errors) {
        try {
            $sql = "
            INSERT INTO Prestations (nom, descritpion, prix, duree)
            VALUES (?, ?, ?, ?)
            ";
        
        $statement = $pdo->prepare($sql);
        $statement->execute([
            $values['nomPrestation'],
            $values['description'] !== '' ? $values['description'] : null,
            $values['prix'],
            $values['dureeMinute']
        ]);
        $newPrestationId = $pdo->lastInsertId();
        header('Location: index.php?page=Prestation-details&id=' . $newPrestationId);
        exit;
        } catch (PDOException $e) {
            $errors['database'] = "Une erreur est survenue lors de la création de la prestation";
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

<h1>Ajouter une Préstation</h1>
<form method="poste">
    
    <div>
        <label for="nomPrestation">Nom de la préstation: *</label>
        <input type="text" name="nomPrestation" id="nomPrestation" required value="<?= $values['nomPrestation'] ?>">
        <?php if (isset($errors['nomPrestation'])) : ?>
            <span class="error"><?= $errors['nomPrestation'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="description">Description: </label>
        <textarea name="description" id="descritpion"><?= $values['description'] ?></textarea>
    </div>

    <div>
        <label for="prix">Prix : *</label>
        <input type="number" min="0" step="0.01" name="prix" id="prix" required value="<?= $values['prix'] ?>">
        <?php if (isset($errors['prix'])) : ?>
            <span class="error"><?= $errors['prix'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="dureeMinute">Durée de la préstation</label>
        <input type="number" min="1" name="dureeMinute" id="dureeMinute" required value="<?= $values['dureeMinute'] ?>">
        <?php if (isset($errors['dureeMinute'])) : ?>
            <span class="error"><?=  $errors['dureeMinute'] ?></span>
        <?php endif ?>
    </div>

    <?php if (isset($errors['database'])) : ?>
        <span class="error"><?= $errors['database'] ?></span>
    <?php endif ?>

    <button>Créer la préstation</button>

    <p>* champ obligatoire</p>
</form>