<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT . '/functions/auth.php';
requireAdmin();

global $DB;

if (!isset($_GET['id'])) {
    header('Location: ' . ROOT_URL . '/views/backend/thematiques/list.php');
    exit;
}

$id = (int)$_GET['id'];

// Soft delete - mise à la corbeille
$stmt = $DB->prepare("UPDATE THEMATIQUE SET delLogiq = 1, dtDelLogThem = NOW() WHERE numThem = ?");
$stmt->execute([$id]);

$_SESSION['success'] = "Thématique mise à la corbeille";
header('Location: ' . ROOT_URL . '/views/backend/thematiques/list.php');
exit;
