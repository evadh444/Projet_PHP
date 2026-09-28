<?php

session_start();

// Routes

$routes = [

    '' => [
        'file' => 'pages/home.php',
        'title' => 'Accueil'
    ],

    // Prestations (read, details, create, edit, delete)
    'Prestations' => [
        'file' => 'pages/2.prestations/prestations-list.php',
        'title' => 'Toutes les prestations',
        'roles' => ['user', 'admin']
    ],                                  //Liste(ou Read)

    'Prestation-details' => [
        'file' => 'pages/2.prestations/prestations-details.php',
        'title' => 'Détails de la préstation',
        'roles' => ['user', 'admin']
    ],                                  //Détails

    'Prestation-create' => [
        'file' => 'pages/2.prestations/prestations-create.php',
        'title' => 'Créer une préstation',
        'roles' => ['admin']

    ],
    
    'Prestation-edit' => [
        'file' => 'pages/2.prestations/prestations-edit.php',
        'title' => 'Modifier une préstation',
        'role' => ['ADMIN']
    ],
    
    'Prestation-delete' => [
        'file' => 'pages/2.prestations/prestations-delete.php',
        'title' => 'Supprimer une préstation',
        'role' => ['ADMIN']
    ],
    

    // Réservations ()
    'Reservations' => [
        'file' => 'pages/reservations.php',
        'title' => 'Réserver un créneau',
        'roles' => ['user', 'admin']
    ],


    // Ateliers (read, details, create, edit, delete)
    // Ateliers
    'Ateliers' => [
        'file' => 'pages/3.ateliers/ateliers-list.php',
        'title' => 'Liste des ateliers',
        // 'roles' => ['user', 'admin']
    ],

    'Atelier-details' => [
        'file' => 'pages/3.ateliers/ateliers-details.php',
        'title' => 'Détails de l\'atelier'
    ],

    
    'Inscriptions' => [
        'file' => 'pages/inscriptions.php',
        'title' => 'Inscription à un atelier',
        // 'roles' => ['user', 'admin']
    ],




    // Authentification

    'register' => [
        'file' => 'pages/1.auth/register.php',
        'title' => 'S\'enregistrer',
    ],

    'login' => [
        'file' => 'pages/1.auth/login.php',
        'title' => 'Se connecter',
    ],

    'logout' => [
        'file' => 'pages/1.auth/logout.php',
        'title' => 'Se déconnecter'
    ],
];

$page = $_GET['page'] ?? '';
$route = $routes[$page] ?? null; //Ici on peut par ex. afficher une page 404

if ($route === null) {
    $route = [
        'file' => 'pages/errors/not-found.php',
        'title' => "404 NOT FOUND"
    ];
}

$requiredRoles = $route['role'] ?? null;
if ($requiredRoles !== null) {
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?page=login");
        exit;
    }

    if (!in_array($_SESSION['user']['role'], $requiredRoles)) {
        $route = [
            'file' => 'pages/errors/forbidden.php',
            'title' => "403 forbidden"
        ];
    }
}



$file = $route["file"];
$title = $route["title"];

require_once 'config/database.php';
// Assembler pages

ob_start();
require_once $file;
$content = ob_get_clean();


require_once 'partials/header.php';
echo $content;
require_once 'partials/footer.php';
