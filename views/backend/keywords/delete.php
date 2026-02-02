<?php
session_start();
require_once '../../functions/csrf.php';
require_once '../../functions/query/select.php';

// Récupération de l'identifiant du mot-clé
$numMotCle = $_GET['id'] ?? null;
if (!$numMotCle) {
    header('Location: list.php');
    exit;
}

// Vérification de l'existence du mot-clé dans la table MOTCLE
$motCle = selectOne('MOTCLE', 'numMotCle', $numMotCle);
if (!$motCle) {
    die('Mot-clé introuvable');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Supprimer un Mot-clé</title>
</head>
<body>
    <h1>Confirmer la suppression</h1>
    
    <p><strong>Attention !</strong> Cette action est irréversible.</p>
    
    <dl>
        <dt>Numéro :</dt>
        <dd><?= $motCle['numMotCle'] ?></dd>
        
        <dt>Libellé :</dt>
        <dd><?= htmlspecialchars($motCle['libMotCle']) ?></dd>
    </dl>
    
    <form method="POST" action="../../api/mot-cles/delete.php">
        <?php csrfField(); ?>
        <input type="hidden" name="numMotCle" value="<?= $motCle['numMotCle'] ?>">
        
        <button type="submit">Confirmer la suppression</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>