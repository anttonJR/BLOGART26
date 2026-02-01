<?php
session_start();
require_once '../../functions/query/select.php';

// Remplacement de la table THEMATIQUE par MOTCLE et du tri par numMotCle
$motCles = selectAll('MOTCLE', 'numMotCle');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des Mot-clés</title>
</head>
<body>
    <h1>Liste des Mot-clés</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <a href="create.php">Nouveau Mot-clé</a>
    
    <table>
        <thead>
            <tr>
                <th>N°</th>
                <th>Libellé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($motCles as $motCle): ?>
                <tr>
                    <td><?= $motCle['numMotCle'] ?></td>
                    <td><?= htmlspecialchars($motCle['libMotCle']) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $motCle['numMotCle'] ?>">Modifier</a>
                        <a href="delete.php?id=<?= $motCle['numMotCle'] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>