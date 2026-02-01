<?php
session_start();
require_once '../../functions/query/select.php';

$thematiques = selectAll('THEMATIQUE', 'numThem');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des thématiques</title>
</head>
<body>
    <h1>Liste des thématiques</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <a href="create.php">Nouvelle thématique</a>
    
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Libellé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($thematiques as $them): ?>
                <tr>
                    <td><?= $them['numThem'] ?></td>
                    <td><?= htmlspecialchars($them['libThem']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $them['numThem'] ?>">Modifier</a>
                        <a href="delete.php?id=<?= $them['numThem'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>