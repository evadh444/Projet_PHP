<?php

session_start();

// Routes

$route = [

    '' => [
        'file'=>'pages/home.php',
        'title'=>'Accueil'
    ],

// Prestations
    'Prestations' => [
        'file' => 'pages/2.prestations/prestations-list.php',
        'title' => 'Toute les prestations',
        'roles' => ['user', 'admin']
    ],                                  //Liste(ou Read)

    'Presation-details' => [
        'file' => 'pages/2.prestations/prestations-details.php',
        'title' => 'Détails de la préstation',
        'roles' => ['user', 'admin']
    ],                                  //Détails

    'Prestation-create' => [
        'file' => 'pages/2.prestations/prestations-create.php',
        'title' => 'Créer une préstation',
        'roles' => ['admin']

    ],                                  //Creer
                                        //Edit(ou Update)
                                        //Delete

    'Reservations' => [
        'file' => 'pages/reservations.php',
        'title' => 'Réserver un créneau',
        'roles' => ['user', 'admin']
    ],



// Ateliers
    'Ateliers' => [
        'file' => 'pages/3.ateliers/ateliers-list.php',
        'title' => 'Liste des ateliers',
        'roles' => ['user', 'admin']
    ],

    'Atelier-details' => [
        'file' => 'pages/3.ateliers/ateliers-details.php',
        'title' => 'Détails de l\'atelier'
    ],

    'Inscriptions' => [
        'file' => 'pages/inscriptions.php',
        'title' => 'Inscription à un atelier',
        'roles' => ['user', 'admin']
    ],


];