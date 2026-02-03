<?php
session_start();
header('Content-Type: application/json');

require_once '../../config.php';

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer les données JSON
$data = json_decode(file_get_contents('php://input'), true);
$numArt = $data['numArt'] ?? null;

// Validation de base
if (!$numArt) {
    echo json_encode(['success' => false, 'message' => 'Article non spécifié']);
    exit;
}

// Pour la démo, on utilise un ID membre par défaut si pas connecté
// En production, vérifier l'authentification
$numMemb = $_SESSION['user']['numMemb'] ?? 1; // ID par défaut pour la démo

try {
    global $DB;
    
    // Vérifier si le like existe déjà
    $sqlCheck = "SELECT likeA FROM LIKEART WHERE numMemb = ? AND numArt = ?";
    $stmtCheck = $DB->prepare($sqlCheck);
    $stmtCheck->execute([$numMemb, $numArt]);
    $existing = $stmtCheck->fetch();
    
    if ($existing) {
        // Le like existe → toggle (0 → 1 ou 1 → 0)
        $newValue = $existing['likeA'] == 1 ? 0 : 1;
        $sqlUpdate = "UPDATE LIKEART SET likeA = ? WHERE numMemb = ? AND numArt = ?";
        $stmtUpdate = $DB->prepare($sqlUpdate);
        $stmtUpdate->execute([$newValue, $numMemb, $numArt]);
        $liked = $newValue == 1;
    } else {
        // Le like n'existe pas → créer
        $sqlInsert = "INSERT INTO LIKEART (numMemb, numArt, likeA) VALUES (?, ?, 1)";
        $stmtInsert = $DB->prepare($sqlInsert);
        $stmtInsert->execute([$numMemb, $numArt]);
        $liked = true;
    }
    
    // Compter le nombre total de likes pour cet article
    $sqlCount = "SELECT COUNT(*) as total FROM LIKEART WHERE numArt = ? AND likeA = 1";
    $stmtCount = $DB->prepare($sqlCount);
    $stmtCount->execute([$numArt]);
    $countResult = $stmtCount->fetch();
    $totalLikes = $countResult['total'];
    
    echo json_encode([
        'success' => true,
        'liked' => $liked,
        'likes' => $totalLikes,
        'message' => $liked ? 'Article liké !' : 'Like retiré'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur : ' . $e->getMessage()
    ]);
}
exit;
?>