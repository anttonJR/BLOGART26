<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

require_once '../../functions/query/delete.php';
require_once '../../functions/query/select.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/backend/mot-cles/list.php');
    exit;
}

// Remplacement de numThem par numMotCle
$numMotCle = $_POST['numMotCle'] ?? null;

if (!$numMotCle) {
    $_SESSION['error'] = "ID manquant";
    header('Location: ../../views/backend/mot-cles/list.php');
    exit;
}

// Vérifier les CIR : on vérifie si des articles pointent encore vers ce numMotCle
$sql = "SELECT COUNT(*) as count FROM ARTICLE WHERE numMotCle = :numMotCle";
$pdo = getConnection();
$stmt = $pdo->prepare($sql);
$stmt->execute(['numMotCle' => $numMotCle]);
$result = $stmt->fetch();

if ($result['count'] > 0) {
    $_SESSION['error'] = "Impossible de supprimer : des articles sont associés à ce mot-clé";
    header('Location: ../../views/backend/mot-cles/list.php');
    exit;
}

try {
    // Suppression dans la table MOTCLE
    $result = delete('MOTCLE', 'numMotCle', $numMotCle);
    if ($result) {
        $_SESSION['success'] = "Mot-clé supprimé";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/mot-cles/list.php');
exit;
?>