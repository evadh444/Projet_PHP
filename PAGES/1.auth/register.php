<?php

$errors = [];

$values = [
    'nom' => '',
    'prenom' => '',
    'email' => '' ];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $values['nom'] = trim($_POST['nom']) ?? '';
    $values['prenom'] = trim($_POST['prenom']) ?? '';
    $values['email'] = trim($_POST['email']) ?? '';
    $password = trim($_POST['password']);
    $confirmation = trim($_POST['confirmation']);


    // Validation si pas d'erreur de format/contenu
    if ($values['email'] === '') {
        $errors['email'] = "L'email est obligatoire.";
    }
    else if (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Le format de l'email est incorrect.";
    }
    else if (strlen($values['email']) > 180) {
        $errors['email'] = "L'email ne peut pas excéder 180 caractères.";
    }

    if ($password === '') {
        $errors['password'] = "Le mot de passe est obligatoire.";
    }
    else if (strlen($password) < 8) {
        $errors['password'] = "Le mot de passe doit faire minimum 8 caractères.";
    }

    if ($password !== $confirmation) {
        $errors['confirmation'] = "Les deux mots de passe ne correspondent pas.";
    }

    // Enregistrer les données en DB
    if (!$errors) {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO [User](nom, prenom, email, passwordHash)
                    VALUES (?, ?, ?, ?)";
            
            $statement = $pdo->prepare($sql);
            $statement->execute([ $values['nom'], $values['prenom'], $values['email'], $password_hash]);

            $_SESSION['user'] = [
                'id' => $pdo->lastInsertId(),
                'email' => $values['email'],
                'role' => 'user'
            ];

            header("Location: index.php");
            exit();

        } catch (PDOException $e) {
            $errors['email'] = "L'email est déjà pris.";
        }
    }
}

?>

<!-- Template -->

<h1>S'inscrire</h1>

<form method="post">

    <div>
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?=  $values['nom'] ?>">
    </div>

    <div>
        <label for="nom">Prenom :</label>
        <input type="text" name="prenom" id="prenom" value="<?=  $values['prenom'] ?>">
    </div>

    <div>
        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" value="<?=  $values['email'] ?>">
        <?php if (isset($errors['email'])) : ?>
            <span class="error"><?=  $errors['email'] ?></span>
        <?php endif ?>
    </div>

    
    <div>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" id="password">
        <?php if(isset($errors['password'])) : ?>
            <span class="error"><?=  $errors['password'] ?></span>
        <?php endif ?>
    </div>

    <div>
        <label for="confirmation">Confirmation du mot de passe:</label>
        <input type="password" name="confirmation" id="confirmation">
        <?php if(isset($errors['confirmation'])) : ?>
            <span class="error"><?= $errors['confirmation'] ?></span>
        <?php endif ?>
    </div>

    <button>S'inscrire</button>

</form>

<p>Déjà inscrit ? <a href="index.php?pages=login">Connectez-vous !</a></p>