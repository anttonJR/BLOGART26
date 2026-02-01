<?php
session_start();
require_once '../../functions/query/delete.php';
require_once '../../functions/query/select.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}

$numThem = $_POST['numThem'] ?? null;

if (!$numThem) {
    $_SESSION['error'] = "ID manquant";
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}

// Vérifier les CIR : pas d'articles associés
$sql = "SELECT COUNT(*) as count FROM ARTICLE WHERE numThem = :numThem";
$pdo = getConnection();
$stmt = $pdo->prepare($sql);
$stmt->execute(['numThem' => $numThem]);
$result = $stmt->fetch();

if ($result['count'] > 0) {
    $_SESSION['error'] = "Impossible de supprimer : des articles sont associés à cette thématique";
    header('Location: ../../views/backend/thematiques/list.php');
    exit;
}

try {
    $result = delete('THEMATIQUE', 'numThem', $numThem);
    if ($result) {
        $_SESSION['success'] = "Thématique supprimée";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/backend/thematiques/list.php');
exit;
?>