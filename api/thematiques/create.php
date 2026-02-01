<?php
session_start();
require_once '../../functions/query/insert.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}

$numThem = $_POST['numThem'] ?? null;
$libThem = trim($_POST['libThem'] ?? '');

$errors = [];

if (!$numThem) {
    $errors[] = "Le numéro de thématique est obligatoire";
}

if (empty($libThem)) {
    $errors[] = "Le libellé est obligatoire";
}

if (strlen($libThem) > 60) {
    $errors[] = "Le libellé ne peut pas dépasser 60 caractères";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../../views/backend/thematiques/create.php');
    exit;
}

$data = [
    'numThem' => $numThem,
    'libThem' => $libThem
];

try {
    $result = insert('THEMATIQUE', $data);
    if ($result) {
        $_SESSION['success'] = "Thématique créée avec succès";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/thematiques/list.php');
exit;
?>