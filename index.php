<?php

session_start();

$errors = [];
if (!isset($_SESSION['saved_photo'])) {
    $_SESSION['saved_photo'] = [];
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$ipDirName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $ip);
$storageRoot = __DIR__ . '/storage';
$storageDir = $storageRoot . '/' . $ipDirName;
$responsesFile = $storageDir . '/responses.txt';
$alreadyResponded = is_file($responsesFile);
$savedPhotoPath = $_SESSION['saved_photo'][$ipDirName] ?? null;
$photoAlreadySaved = $savedPhotoPath && is_file($savedPhotoPath);
if (!$photoAlreadySaved && isset($_SESSION['saved_photo'][$ipDirName])) {
    unset($_SESSION['saved_photo'][$ipDirName]);
}

if ($alreadyResponded && !isset($_POST['soumettre'])) {
    $errors[] = 'Vous avez déjà répondu à ce formulaire depuis cette adresse IP.';
}

if (isset($_POST['soumettre'])) {
    if ($alreadyResponded) {
        $errors[] = 'Vous avez déjà répondu à ce formulaire depuis cette adresse IP.';
    }

    if (empty($_POST['Prenom'])) {
        $errors[] = 'Veuillez renseigner votre prénom.';
    }
    if (empty($_POST['Nom'])) {
        $errors[] = 'Veuillez renseigner votre nom.';
    }
    if (empty($_POST['mdp'])) {
        $errors[] = 'Veuillez renseigner votre mot de passe.';
    } elseif (strlen($_POST['mdp']) < 3) {
        $errors[] = 'Veuillez mettre un mot de passe supérieur à 3 caractères.';
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
    $PhotoSaved = !empty($_FILES['photo']['name']) && is_uploaded_file($_FILES['photo']['tmp_name']);
    if (!$PhotoSaved && !$photoAlreadySaved) {
        $errors[] = 'Veuillez sélectionner une photo.';
    }

    if ($PhotoSaved) {
        if (!is_dir($storageRoot) && !mkdir($storageRoot, 0777, true)) {
            $errors[] = 'Impossible de créer le dossier de stockage racine.';
        }

        if (empty($errors) && !is_dir($storageDir) && !mkdir($storageDir, 0777, true)) {
            $errors[] = 'Impossible de créer le dossier de stockage.';
        }

        if (empty($errors)) {
            $photoPath = $storageDir . '/photo.png';
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                $errors[] = 'Impossible d’enregistrer la photo.';
            } else {
                $_SESSION['saved_photo'][$ipDirName] = $photoPath;
                $photoAlreadySaved = true;
                $savedPhotoPath = $photoPath;
            }
        }
    }

    if (empty($errors) && !$PhotoSaved && $photoAlreadySaved) {
        $photoPath = $savedPhotoPath;
    }

    if (empty($errors)) {
        $date = date('Y-m-d H:i:s');
        $sports = 'Aucun';
        if (isset($_POST['sport']) && is_array($_POST['sport']) && count($_POST['sport']) > 0) {
            $sports = implode(', ', array_map('htmlspecialchars', $_POST['sport']));
        }

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

require 'index-view.php';

?>