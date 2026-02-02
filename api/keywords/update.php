<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

require_once '../../functions/query/update.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirection vers la liste des mots-clés
    header('Location: ../../views/backend/mot-cles/list.php');
    exit;
}

// Remplacement numThem -> numMotCle et libThem -> libMotCle
$numMotCle = $_POST['numMotCle'] ?? null;
$libMotCle = trim($_POST['libMotCle'] ?? '');

$errors = [];

if (!$numMotCle) {
    $errors[] = "ID manquant";
}

if (empty($libMotCle)) {
    $errors[] = "Le libellé est obligatoire";
}

if (strlen($libMotCle) > 60) {
    $errors[] = "Le libellé ne peut pas dépasser 60 caractères";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    // Redirection vers la page d'édition avec le bon ID
    header('Location: ../../views/backend/mot-cles/edit.php?id=' . $numMotCle);
    exit;
}

// Seul le libellé est mis à jour dans le tableau $data
$data = ['libMotCle' => $libMotCle];

try {
    // Mise à jour de la table MOTCLE sur la colonne numMotCle
    $result = update('MOTCLE', $data, 'numMotCle', $numMotCle);
    if ($result) {
        $_SESSION['success'] = "Mot-clé mis à jour";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

// Redirection finale vers la liste
header('Location: ../../views/backend/mot-cles/list.php');
exit;
?>