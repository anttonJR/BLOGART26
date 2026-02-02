<?php
session_start();
require_once '../../functions/csrf.php';
require_once '../../functions/query/select.php';

$numThem = $_GET['id'] ?? null;
if (!$numThem) {
    header('Location: list.php');
    exit;
}

$them = selectOne('THEMATIQUE', 'numThem', $numThem);
if (!$them) {
    die('Thématique introuvable');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier une thématique</title>
</head>
<body>
    <h1>Modifier la thématique</h1>
    
    <form method="POST" action="../../api/thematiques/update.php">
        <?php csrfField(); ?>
        <input type="hidden" name="numThem" value="<?= $them['numThem'] ?>">
        
        <label>Libellé :</label>
        <input type="text" 
               name="libThem" 
               value="<?= htmlspecialchars($them['libThem']) ?>" 
               required 
               maxlength="60">
        
        <button type="submit">Mettre à jour</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>