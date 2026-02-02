<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

require_once '../../functions/query/insert.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Redirection vers la liste des mots-clés
    header('Location: ../../views/backend/mot-cles/list.php');
    exit;
}

// Remplacement des variables numThem -> numMotCle et libThem -> libMotCle
$numMotCle = $_POST['numMotCle'] ?? null;
$libMotCle = trim($_POST['libMotCle'] ?? '');

$errors = [];

// Mise à jour des messages d'erreur (Thématique -> Mot-clé)
if (!$numMotCle) {
    $errors[] = "Le numéro de Mot-clé est obligatoire";
}

if (empty($libMotCle)) {
    $errors[] = "Le libellé est obligatoire";
}

if (strlen($libMotCle) > 60) {
    $errors[] = "Le libellé ne peut pas dépasser 60 caractères";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    // Redirection vers le formulaire de création
    header('Location: ../../views/backend/mot-cles/create.php');
    exit;
}

// Préparation des données pour l'insertion
$data = [
    'numMotCle' => $numMotCle,
    'libMotCle' => $libMotCle
];

try {
    // Remplacement du nom de la table THEMATIQUE -> MOTCLE
    $result = insert('MOTCLE', $data);
    if ($result) {
        $_SESSION['success'] = "Mot-clé créé avec succès";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

// Redirection finale vers la liste
header('Location: ../../views/backend/mot-cles/list.php');
exit;
?>