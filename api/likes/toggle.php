<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

require_once '../../functions/auth.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    $_SESSION['error'] = "Vous devez être connecté pour liker un article";
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '../../views/frontend/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/frontend/index.php');
    exit;
}

// Récupération des données
$numArt = $_POST['numArt'] ?? null;
$numMemb = $_SESSION['user']['numMemb'];

// Validation
if (!$numArt) {
    $_SESSION['error'] = "Article non spécifié";
    header('Location: ../../views/frontend/index.php');
    exit;
}

try {
    $pdo = getConnection();
    
    // Vérifier si le like existe déjà
    $sqlCheck = "SELECT likeA FROM LIKEART WHERE numMemb = ? AND numArt = ?";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute([$numMemb, $numArt]);
    $existing = $stmtCheck->fetch();
    
    if ($existing) {
        // Le like existe → toggle (0 → 1 ou 1 → 0)
        $newValue = $existing['likeA'] == 1 ? 0 : 1;
        $sqlUpdate = "UPDATE LIKEART SET likeA = ? WHERE numMemb = ? AND numArt = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$newValue, $numMemb, $numArt]);
        
        $_SESSION['success'] = $newValue == 1 ? "Vous aimez cet article !" : "Like retiré";
    } else {
        // Le like n'existe pas → créer
        $sqlInsert = "INSERT INTO LIKEART (numMemb, numArt, likeA) VALUES (?, ?, 1)";
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([$numMemb, $numArt]);
        
        $_SESSION['success'] = "Vous aimez cet article !";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/frontend/articles/article1.php?id=' . $numArt);
exit;
?>