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

// Restaurer la thématique
$stmt = $DB->prepare("UPDATE THEMATIQUE SET delLogiq = 0, dtDelLogThem = NULL WHERE numThem = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Thématique restaurée avec succès";
header('Location: ' . ROOT_URL . '/views/backend/thematiques/trash.php');
exit;
