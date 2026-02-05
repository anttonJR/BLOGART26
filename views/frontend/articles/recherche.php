<?php
session_start();
include '../includes/cookie-consent.php';
require_once '../../../config.php';
require_once '../../../functions/csrf.php';
$pageTitle = 'Recherche d\'articles - Millésime Blog\'Art';
include '../includes/header.php';

// Récupération des paramètres de recherche
$searchQuery = $_GET['q'] ?? '';
$thematique = $_GET['thematique'] ?? '';
$keyword = $_GET['keyword'] ?? '';
$order = $_GET['order'] ?? 'recent';

// Construction de la requête SQL
$where = '1=1';
$params = [];

if ($searchQuery) {
    $where .= " AND (a.libTitrArt LIKE ? OR a.libChapoArt LIKE ?)";
    $params[] = '%' . $searchQuery . '%';
    $params[] = '%' . $searchQuery . '%';
}

if ($thematique) {
    $where .= " AND a.numThem = ?";
    $params[] = $thematique;
}

// Order by
$orderBy = match($order) {
    'ancien' => 'a.dtCreaArt ASC',
    'populaire' => 'nb_likes DESC',
    default => 'a.dtCreaArt DESC'
};

// Récupération des articles
if ($keyword) {
    // Recherche par mot-clé
    $sql = "SELECT a.*, t.libThem,
            (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
            FROM ARTICLE a
            LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
            INNER JOIN MOTCLEARTICLE mca ON mca.numArt = a.numArt
            INNER JOIN MOTCLE mc ON mc.numMotCle = mca.numMotCle AND mc.libMotCle = ?
            WHERE " . $where . "
            ORDER BY " . $orderBy;
    array_unshift($params, $keyword);
} else {
    $sql = "SELECT a.*, t.libThem,
            (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
            FROM ARTICLE a
            LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
            WHERE " . $where . "
            ORDER BY " . $orderBy;
}

global $DB;
$stmt = $DB->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll();
?>

<!-- Page Header -->
<div class="page-header" style="background: linear-gradient(135deg, var(--beige-medium) 0%, var(--beige-light) 100%); padding: 60px 0; margin-bottom: 40px;">
    <div class="container">
        <h1 style="color: var(--bordeaux); font-size: 2.5rem;">
            <i class="bi bi-search"></i> Recherche
        </h1>
        <p class="lead mb-0"><?= count($articles) ?> article(s) trouvé(s)</p>
    </div>
</div>

<!-- Articles -->
<div class="container mb-5">
    <?php if (empty($articles)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Aucun article ne correspond à votre recherche.
        </div>
        <a href="../../../index.php" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Retour à l'accueil
        </a>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($articles as $article): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <?php if (!empty($article['urlPhotArt'])): ?>
                            <img src="../../../src/uploads/<?= htmlspecialchars($article['urlPhotArt']) ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($article['libTitrArt'] ?? '') ?>"
                                 style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <?php if ($article['libThem']): ?>
                                <span class="badge bg-bordeaux mb-2"><?= htmlspecialchars($article['libThem']) ?></span>
                            <?php endif; ?>
                            <h3 class="h5"><?= htmlspecialchars($article['libTitrArt']) ?></h3>
                            <p class="text-muted small">
                                <i class="bi bi-calendar"></i> <?= date('d M Y', strtotime($article['dtCreaArt'])) ?>
                            </p>
                            <p class="flex-grow-1"><?= htmlspecialchars(substr(strip_tags($article['libChapoArt'] ?? ''), 0, 120)) ?>...</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <span class="text-muted">
                                    <i class="bi bi-heart"></i> <?= $article['nb_likes'] ?> likes
                                </span>
                                <a href="article1.php?id=<?= $article['numArt'] ?>" class="btn btn-primary btn-sm">Lire</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-5 text-center">
            <a href="../../../index.php" class="btn btn-outline-dark">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    <?php endif; ?>
</div>

<?php
include '../includes/footer.php';
?>