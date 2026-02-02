<?php
require_once '../../functions/query/select.php';

$numMotCle = $_GET['motcle'] ?? null;

if (!$numMotCle) {
    header('Location: index.php');
    exit;
}

// Récupérer le mot-clé
$motcle = selectOne('MOTCLE', 'numMotCle', $numMotCle);

if (!$motcle) {
    die('Mot-clé introuvable');
}

// Récupérer les articles associés
$sql = "SELECT a.*, t.libThem
        FROM ARTICLE a
        INNER JOIN MOTCLEARTICLE mca ON a.numArt = mca.numArt
        LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
        WHERE mca.numMotCle = ?
        ORDER BY a.dtCreaArt DESC";

$pdo = getConnection();
$stmt = $pdo->prepare($sql);
$stmt->execute([$numMotCle]);
$articles = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Articles : <?= htmlspecialchars($motcle['libMotCle']) ?></title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Articles avec le mot-clé : <span class="badge bg-primary"><?= htmlspecialchars($motcle['libMotCle']) ?></span></h1>
        
        <p class="lead"><?= count($articles) ?> article(s) trouvé(s)</p>
        
        <?php if (empty($articles)): ?>
            <div class="alert alert-info">
                Aucun article trouvé avec ce mot-clé.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($articles as $art): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if ($art['urlPhotArt']): ?>
                                <img src="../../../src/uploads/<?= htmlspecialchars($art['urlPhotArt']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($art['libTltArt']) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($art['libTltArt']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($art['libChapArt'], 0, 150)) ?>...</p>
                                <a href="article1.php?id=<?= $art['numArt'] ?>" class="btn btn-primary">Lire la suite</a>
                            </div>
                            <div class="card-footer text-muted">
                                <?= date('d/m/Y', strtotime($art['dtCreaArt'])) ?> | 
                                <?= htmlspecialchars($art['libThem'] ?? 'Sans thématique') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <a href="index.php" class="btn btn-secondary mt-3">Retour à l'accueil</a>
    </div>
</body>
</html>