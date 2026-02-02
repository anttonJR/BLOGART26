<?php
session_start();
require_once '../../functions/query/select.php';

// Récupérer tous les articles avec leur thématique
$sql = "SELECT a.*, t.libThem 
        FROM ARTICLE a 
        LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem 
        ORDER BY a.dtCreaArt DESC";
$pdo = getConnection();
$stmt = $pdo->query($sql);
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Gestion des articles</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des articles</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <a href="create.php" class="btn btn-primary mb-3">Nouvel article</a>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Titre</th>
                    <th>Thématique</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $art): ?>
                    <tr>
                        <td><?= $art['numArt'] ?></td>
                        <td><?= htmlspecialchars($art['libTltArt']) ?></td>
                        <td><?= htmlspecialchars($art['libThem'] ?? 'Aucune') ?></td>
                        <td><?= date('d/m/Y', strtotime($art['dtCreaArt'])) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $art['numArt'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <a href="delete.php?id=<?= $art['numArt'] ?>" class="btn btn-sm btn-danger">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
require_once '../../functions/motcle.php';
?>

<!-- Dans le tableau, ajouter une colonne -->
<thead>
    <tr>
        <th>N°</th>
        <th>Titre</th>
        <th>Thématique</th>
        <th>Mots-clés</th>
        <th>Date création</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($articles as $art): ?>
        <?php $motscles = getMotsClesArticle($art['numArt']); ?>
        <tr>
            <td><?= $art['numArt'] ?></td>
            <td><?= htmlspecialchars($art['libTltArt']) ?></td>
            <td><?= htmlspecialchars($art['libThem'] ?? 'Aucune') ?></td>
            <td>
                <?php foreach ($motscles as $mc): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($mc['libMotCle']) ?></span>
                <?php endforeach; ?>
            </td>
            <td><?= date('d/m/Y', strtotime($art['dtCreaArt'])) ?></td>
            <td>
                <a href="edit.php?id=<?= $art['numArt'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                <a href="delete.php?id=<?= $art['numArt'] ?>" class="btn btn-sm btn-danger">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>