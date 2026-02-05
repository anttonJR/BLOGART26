<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT . '/functions/auth.php';
requireAdmin();

global $DB;

// Récupérer toutes les thématiques dans la corbeille
$stmt = $DB->query("SELECT numThem FROM THEMATIQUE WHERE delLogiq = 1");
$thematiques = $stmt->fetchAll(PDO::FETCH_COLUMN);

$deleted = 0;
$skipped = 0;

foreach ($thematiques as $numThem) {
    // Vérifier si des articles utilisent cette thématique
    $stmtCheck = $DB->prepare("SELECT COUNT(*) FROM ARTICLE WHERE numThem = ?");
    $stmtCheck->execute([$numThem]);
    $count = $stmtCheck->fetchColumn();
    
    if ($count == 0) {
        // Supprimer la thématique
        $stmtDel = $DB->prepare("DELETE FROM THEMATIQUE WHERE numThem = ?");
        $stmtDel->execute([$numThem]);
        $deleted++;
    } else {
        $skipped++;
    }
}

if ($skipped > 0) {
    $_SESSION['success'] = "Corbeille vidée : $deleted thématique(s) supprimée(s), $skipped ignorée(s) car utilisée(s) par des articles.";
} else {
    $_SESSION['success'] = "Corbeille vidée avec succès ($deleted thématique(s) supprimée(s))";
}

header('Location: ' . ROOT_URL . '/views/backend/thematiques/list.php');
exit;
