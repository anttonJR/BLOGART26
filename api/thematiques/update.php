<?php
session_start();
require_once '../../functions/query/update.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}

$numThem = $_POST['numThem'] ?? null;
$libThem = trim($_POST['libThem'] ?? '');

$errors = [];

if (!$numThem) {
    $errors[] = "ID manquant";
}

if (empty($libThem)) {
    $errors[] = "Le libellé est obligatoire";
}

if (strlen($libThem) > 60) {
    $errors[] = "Le libellé ne peut pas dépasser 60 caractères";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../../views/backend/thematiques/edit.php?id=' . $numThem);
    exit;
}

$data = ['libThem' => $libThem];

try {
    $result = update('THEMATIQUE', $data, 'numThem', $numThem);
    if ($result) {
        $_SESSION['success'] = "Thématique mise à jour";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/thematiques/list.php');
exit;
?>