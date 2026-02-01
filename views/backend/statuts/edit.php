<?php
// 1. Récupérer l'ID du statut depuis l'URL
$numStat = $_GET['id'] ?? null;

if (!$numStat) {
    header('Location: list.php');
    exit;
}

// 2. Charger les données du statut
require_once '../../functions/query/select.php';
$statut = selectOne('STATUT', 'numStat', $numStat);

if (!$statut) {
    die('Statut introuvable');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modifier un statut</title>
</head>
<body>
    <h1>Modifier le statut</h1>
    
    <form method="POST" action="../../api/statuts/update.php">
        <!-- Champ caché pour l'ID -->
        <input type="hidden" name="numStat" value="<?= $statut['numStat'] ?>">
        
        <label>Libellé du statut :</label>
        <input type="text" 
               name="libStat" 
               value="<?= htmlspecialchars($statut['libStat']) ?>" 
               required
               maxlength="25">
        
        <button type="submit">Mettre à jour</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>