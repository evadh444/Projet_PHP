<nav>
    <ul>
        <li><a href="index.php">Accueil</a></li> 

        <li><a href="index.php?page=Prestations">Préstations</a></li>
        <li><a href="index.php?page=Ateliers">Ateliers</a></li>
    </ul>

    <!-- Rajouter les boutons s'inscrire / se connecter sauf si déjà le cas = se déconnecter -->
    <ul>
        <?php if (!isset($_SESSION['user'])) : ?>
        <li><a href="index.php?page=login">Se connecter</a></li>
        <li><a href="index.php?page=register">S'inscrire</a></li>
        <?php else : ?>
        <li>Connecté en tant que : <?= $_SESSION['user']['email'] ?> (<?=  $_SESSION['user']['role'] ?>)</li>
        <li><a href="index.php?page=logout">Se déconnecter</a></li>
        <?php endif ?>
    </ul>
</nav>