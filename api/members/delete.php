<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

try {
    $pdo = getConnection();
    
    // 1. Supprimer tous les commentaires du membre
    $stmtComments = $pdo->prepare(
        "DELETE FROM COMMENT WHERE numMemb = ?"
    );
    $stmtComments->execute([$numMemb]);
    
    // 2. Supprimer le membre
    $result = delete('MEMBRE', 'numMemb', $numMemb);
    
    if ($result) {
        $_SESSION['success'] = "Membre et ses commentaires supprimés";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/members/list.php');
exit;
?>
<?php
try {
    $pdo = getConnection();
    
    // 1. Supprimer tous les commentaires du membre
    $stmtComments = $pdo->prepare("DELETE FROM COMMENT WHERE numMemb = ?");
    $stmtComments->execute([$numMemb]);
    
    // 2. Supprimer tous les likes du membre
    $stmtLikes = $pdo->prepare("DELETE FROM LIKEART WHERE numMemb = ?");
    $stmtLikes->execute([$numMemb]);
    
    // 3. Supprimer le membre
    $result = delete('MEMBRE', 'numMemb', $numMemb);
    
    if ($result) {
        $_SESSION['success'] = "Membre, commentaires et likes supprimés";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/members/list.php');
exit;
?>