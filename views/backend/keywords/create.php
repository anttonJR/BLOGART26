<?php
session_start();
require_once '../../functions/csrf.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Créer un Mot-clé</title>
</head>
<body>
    <h1>Nouveau Mot-clé</h1>
    
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    
    <form method="POST" action="../../api/mot-cles/create.php">
        <?php csrfField(); ?>
        <label>Numéro de Mot-clé :</label>
        <input type="number" name="numMotCle" required>
        
        <label>Libellé du Mot-clé :</label>
        <input type="text" name="libMotCle" required maxlength="60">
        
        <button type="submit">Créer</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>