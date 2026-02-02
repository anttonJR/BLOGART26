<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

require_once '../../functions/auth.php';
require_once '../../functions/query/update.php';

requireModerator();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/backend/moderation/comments.php');
    exit;
}

$numCom = $_POST['numCom'] ?? null;

if (!$numCom) {
    $_SESSION['error'] = "Commentaire non spécifié";
    header('Location: ../../views/backend/moderation/comments.php');
    exit;
}

// Suppression logique (archivage)
$data = [
    'dtDelLogCom' => date('Y-m-d H:i:s')
];

try {
    $result = update('COMMENT', $data, 'numCom', $numCom);
    if ($result) {
        $_SESSION['success'] = "Commentaire archivé (suppression logique)";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/moderation/comments.php');
exit;
?>