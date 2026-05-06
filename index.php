<?php

session_start();

$errors = [];


$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
if ($ip === '::1') {
    $ip = '127.0.0.1';
}

$storageDir = __DIR__ . '/stockage/' .  preg_replace('/[^a-zA-Z0-9]/', '_', $ip);
$responsesFile = $storageDir . '/responses.txt';
$alreadyResponded = is_file($responsesFile);



if (isset($_POST['soumettre'])) {


    if (empty($_POST['Prenom'])) {
        $errors[] = 'Veuillez renseigner votre prénom.';
    }

    if (empty($_POST['Nom'])) {
        $errors[] = 'Veuillez renseigner votre nom.';
    }

    if (empty($_POST['mdp'])) {
        $errors[] = 'Veuillez renseigner votre mot de passe.';
    } elseif (strlen($_POST['mdp']) < 3) {
        $errors[] = 'Mot de passe trop court.';
    }

    if (empty($_POST['confirmation'])) {
        $errors[] = 'Veuillez confirmer votre mot de passe.';
    } elseif ($_POST['mdp'] !== $_POST['confirmation']) {
        $errors[] = 'La confirmation du mot de passe n’est pas correcte.';
    }

    if (!isset($_POST['civ'])) {
        $errors[] = 'Veuillez renseigner votre civilité.';
    }

    if (empty($_POST['Ville'])) {
        $errors[] = 'Veuillez renseigner votre ville.';
    }

    if (
        empty($_FILES['photo']['name']) ||
        !is_uploaded_file($_FILES['photo']['tmp_name'])
    ) {
        $errors[] = 'Veuillez sélectionner une photo.';
    }


    if (empty($errors)) {
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }
    }


    if (empty($errors)) {
        $photoPath = $storageDir . '/photo.png';

        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $errors[] = 'Impossible d’enregistrer la photo.';
        }
    }


    if (empty($errors)) {

        $sports = 'Aucun';
        if (!empty($_POST['sport']) && is_array($_POST['sport'])) {
            $sports = implode(', ', array_map('htmlspecialchars', $_POST['sport']));
        }

        $date = date('Y-m-d H:i:s', strtotime("+2 hours") );
        $content = "Date: $date\n";
        $content .= "IP: $ip\n";
        $content .= "Prénom: " . htmlspecialchars($_POST['Prenom']) . "\n";
        $content .= "Nom: " . htmlspecialchars($_POST['Nom']) . "\n";
        $content .= "Civilité: " . htmlspecialchars($_POST['civ']) . "\n";
        $content .= "Ville: " . htmlspecialchars($_POST['Ville']) . "\n";
        $content .= "Sports: $sports\n";
        $content .= "Description: " . htmlspecialchars($_POST['description'] ?? '') . "\n";
        $content .= "Photo: photo.png\n";

        if (file_put_contents($responsesFile, $content) === false) {
            $errors[] = 'Impossible d’enregistrer les données.';
        }
    }

    if (empty($errors)) {
        $_SESSION['has_responded'] = true;
        header('Location: merci.php');
        exit;
    }
}

require 'formulaire.php';