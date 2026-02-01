<?php
session_start();
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
    <title>Supprimer une thématique</title>
</head>
<body>
    <h1>Confirmer la suppression</h1>
    
    <p><strong>Attention !</strong> Cette action est irréversible.</p>
    
    <dl>
        <dt>Numéro :</dt>
        <dd><?= $them['numThem'] ?></dd>
        
        <dt>Libellé :</dt>
        <dd><?= htmlspecialchars($them['libThem']) ?></dd>
    </dl>
    
    <form method="POST" action="../../api/thematiques/delete.php">
        <input type="hidden" name="numThem" value="<?= $them['numThem'] ?>">
        
        <button type="submit">Confirmer la suppression</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>