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
$action = $_POST['action'] ?? null;
$notifComKOAff = trim($_POST['notifComKOAff'] ?? '');

if (!$numCom || !$action) {
    $_SESSION['error'] = "Données manquantes";
    header('Location: ../../views/backend/moderation/comments.php');
    exit;
}

if ($action === 'approve') {
    // VALIDATION du commentaire
    $data = [
        'attModOK' => 1,
        'dtModCom' => date('Y-m-d H:i:s'),
        'notifComKOAff' => null
    ];
    
    try {
        $result = update('COMMENT', $data, 'numCom', $numCom);
        if ($result) {
            $_SESSION['success'] = "Commentaire validé et visible sur le site";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }
    
} elseif ($action === 'reject') {
    // REJET du commentaire (reste attModOK = 0)
    $data = [
        'attModOK' => 0,
        'dtModCom' => date('Y-m-d H:i:s'),
        'notifComKOAff' => $notifComKOAff ?: 'Commentaire refusé'
    ];
    
    try {
        $result = update('COMMENT', $data, 'numCom', $numCom);
        if ($result) {
            $_SESSION['success'] = "Commentaire rejeté";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }
}

header('Location: ../../views/backend/moderation/comments.php');
exit;
?>