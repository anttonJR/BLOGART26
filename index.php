<?php 
require_once 'header.php';
sql_connect();


?>




<?php require_once 'footer.php'; ?>

<?php
require_once '../functions/query/select.php';

// Récupérer tous les articles avec leur nombre de likes
$sql = "SELECT a.*, t.libThem, 
        (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
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
    <title>BlogArt - Accueil</title>
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Blog'Art - Tous les articles</h1>
        
        <div class="row mt-4">
            <?php foreach ($articles as $art): ?>
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <?php if ($art['urlPhotArt']): ?>
                            <img src="../../src/uploads/<?= htmlspecialchars($art['urlPhotArt']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($art['libTltArt']) ?>" 
                                 style="height: 250px; object-fit: cover;">
                        <?php endif; ?>
                        
                        <div class="card-body">
                            <span class="badge bg-primary mb-2"><?= htmlspecialchars($art['libThem'] ?? 'Sans catégorie') ?></span>
                            
                            <h5 class="card-title"><?= htmlspecialchars($art['libTltArt']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($art['libChapArt'], 0, 150)) ?>...</p>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="articles/article1.php?id=<?= $art['numArt'] ?>" class="btn btn-primary">Lire la suite</a>
                                <span class="text-muted">
                                    ❤️ <?= $art['nb_likes'] ?> like<?= $art['nb_likes'] > 1 ? 's' : '' ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-footer text-muted">
                            <?= date('d/m/Y', strtotime($art['dtCreaArt'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>