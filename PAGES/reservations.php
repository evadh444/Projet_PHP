<?php

//Selecteur prestations
$sql = "SELECT PrestationId, nomPrestation, description, prix, dureeMinute
        FROM Prestation
        WHERE estActif = 1        -- si prestation active donc disponible
        ORDER BY nomPrestation ASC";

$prestations = $pdo->query($sql)->fetchAll();  // si valeurs invariables, autre methode (statement-prepare-execute) si variables

// Récuperer l'id et la prestation
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$prestation = false;

if($id !== false && $id !== null) {
    $sql ="SELECT 
                PrestationId,
                nomPrestation,
                description,
                prix,
                dureeMinute
            FROM Prestation
            WHERE PrestationId = ?";
    
    $statement = $pdo->prepare($sql);
    $statement->execute([$id]);

    $prestation = $statement->fetch();
}

if (!$prestation) {
    die("Préstation introuvable");
}

// Préparer les valeurs du formulaire
$values = [
    'dateRdv' => '',
    'heureRdv' => '',
    'commentaire' => ''
];

$errors = [];

// Validation des champs
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array_keys($_POST) as $field) {
        $values[$field] = trim($_POST[$field] ?? '');
    }
    if ($values['dateRdv'] === '') {
        $errors['dateRdv'] = "La date du RDV est obligatoire";
    }
    if ($values['heureRdv'] === '') {
        $errors['heureRdv'] = "L'heure de RDV est obligatoire";
    }


// Envoi dans la DB si pas d'erreurs
if(!$errors) {
    try {
        $sql = "INSERT INTO Reservation
                            (dateRdv,
                            heureRdv,
                            commentaire,
                            PrestationId,
                            UserId)
                            VALUES (?, ?, ?, ?, ?)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            $values['dateRdv'],
            $values['heureRdv'],
            $values['commentaire'] !== '' ? $values['commentaire'] : null,
            $id,
            $_SESSION['user']['id']
        ]);

        header("Location: index.php?page=Prestations");
        exit;
        } catch (PDOException $e) {
        $errors['database'] = "Une erreur est survenue";
        }
}
}

?>


<style>
    form * {
        display: block;
        margin: 10px 0;
    }
</style>

<h1>Réserver une préstation</h1>
<!-- <h2><?=  htmlspecialchars($prestation['nomPrestation']) ?></h2>  Si affichage simple, mais je voulais un selecteur donc dans <form>

<p>Prix : <?=  htmlspecialchars($prestation['prix']) ?> €</p>
<p>Durée : <?=  htmlspecialchars($prestation['dureeMinute']) ?> min.</p> -->

<form method="post">

    <label for="PrestationId">Préstation : </label>
    <select name="PrestationId" id="PrestationId">
        <?php foreach ($prestations as $prestation) : ?>
            <option value="
                <?= $prestation['PrestationId'] ?>"  
                data-prix="<?=  $prestation['prix'] ?>"
                data-duree="<?= $prestations['dureeMinute'] ?>"
                data-description="<?= htmlspecialchars($prestation['description'] ?? '') ?>"
                <?=  $prestation['PrestationId'] == $id ? 'selected' : '' ?>>
                <?= htmlspecialchars($prestation['nomPrestation']) ?>
            </option>
        <?php endforeach ?>
    </select>

    <div class="infos-prestation">
        <p>Prix : <span id="prix"></span> €</p>
        <p>Durée : <span id="duree"></span> min.</p>
        <p>Description : <span id="description"></span>/p>
    </div>

    <div>
        <label for="dateRdv">Date du RDV : * </label>
        <input type="date" name="dateRdv" id="dateRdv" required value="<?= htmlspecialchars($values['dateRdv']) ?>">

        <?php if (isset($errors['dateRdv'])) : ?>
            <span class="error"><?= $errors['dateRdv'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="heureRdv">Heure du RDV : * </label>
        <input type="time" name="heureRdv" id="heureRdv" required value="<?= htmlspecialchars($values['heureRdv']) ?>">

        <?php if (isset($errors['heureRdv'])) : ?>
            <span class="error"><?= $errors['heureRdv'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="commentaire">Commentaire : </label>
        <textarea name="commentaire" id="commentaire"><?=  htmlspecialchars($values['commentaire']) ?></textarea>
    </div>

    <?php if (isset($errors['database'])) : ?>
        <span class="error"><?= $errors['database'] ?></span>
    <?php endif ?>

    <button>Réserver cette préstation</button>

    <p>* champ obligatoire</p>
</form>

<script>  // ajout d'un peu de JavaScript pour mon selecteur (data-) pour que les infos liés à la prestation s'affichent en dessous et pas dans le selecteur 
    const select = document.getElementById('PrestationId');    // aller chercher l'id et le mettre dans 'select'

    function afficherInfos() {
        const option = select.options[select.selectedIndex]; //Récuperer chaque option (data-) dans 'select'

        document.getElementById('prix').textContent = option.dataset.prix;
        document.getElementById('duree').textContent = option.dataset.duree;
        document.getElementById('description').textContent = option.dataset.description;
    }

    select.addEventListener('change', afficherInfos); // afficher les infos correspondantes quand l'utilisateur change de prestation
    afficherInfos(); // Lancer la fonction à l'ouverture de la page pour pas que les infos soient vides
</script>