<?php
session_start();
require_once '../../functions/csrf.php';
require_once '../../functions/query/select.php';

// Récupération de l'ID via numMotCle
$numMotCle = $_GET['id'] ?? null;
if (!$numMotCle) {
    header('Location: list.php');
    exit;
}

// Recherche du mot-clé dans la table MOTCLE
$motCle = selectOne('MOTCLE', 'numMotCle', $numMotCle);
if (!$motCle) {
    die('Mot-clé introuvable');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modifier un Mot-clé</title>
</head>
<body>
    <h1>Modifier le Mot-clé</h1>
    
    <form method="POST" action="../../api/mot-cles/update.php">
        <?php csrfField(); ?>
        <input type="hidden" name="numMotCle" value="<?= $motCle['numMotCle'] ?>">
        
        <label>Libellé :</label>
        <input type="text" 
               name="libMotCle" 
               value="<?= htmlspecialchars($motCle['libMotCle']) ?>" 
               required 
               maxlength="60">
        
        <button type="submit">Mettre à jour</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>