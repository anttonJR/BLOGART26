<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT . '/functions/auth.php';
requireAdmin();

global $DB;

if (!isset($_GET['id'])) {
    header('Location: ' . ROOT_URL . '/views/backend/thematiques/trash.php');
    exit;
}

$id = (int)$_GET['id'];

// Vérifier si des articles utilisent cette thématique
$stmt = $DB->prepare("SELECT COUNT(*) FROM ARTICLE WHERE numThem = ?");
$stmt->execute([$id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    $_SESSION['error'] = "Impossible de supprimer cette thématique : $count article(s) l'utilisent encore.";
    header('Location: ' . ROOT_URL . '/views/backend/thematiques/trash.php');
    exit;
}

// Supprimer définitivement la thématique
$stmt = $DB->prepare("DELETE FROM THEMATIQUE WHERE numThem = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Thématique supprimée définitivement";
header('Location: ' . ROOT_URL . '/views/backend/thematiques/trash.php');
exit;
