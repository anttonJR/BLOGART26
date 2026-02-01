<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Créer une thématique</title>
</head>
<body>
    <h1>Nouvelle thématique</h1>
    
    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <?php foreach ($_SESSION['errors'] as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>
    
    <form method="POST" action="../../api/thematiques/create.php">
        <label>Numéro de thématique :</label>
        <input type="number" name="numThem" required>
        
        <label>Libellé de la thématique :</label>
        <input type="text" name="libThem" required maxlength="60">
        
        <button type="submit">Créer</button>
        <a href="list.php">Annuler</a>
    </form>
</body>
</html>