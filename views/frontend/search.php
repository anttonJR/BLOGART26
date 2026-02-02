<?php
include 'includes/cookie-consent.php';
$pageTitle = 'Recherche - BlogArt';
include 'includes/header.php';
require_once '../functions/query/select.php';

// Récupération des paramètres
$searchQuery = trim($_GET['q'] ?? '');
$searchType = $_GET['type'] ?? 'all';
$numThem = $_GET['thematique'] ?? null;
$numMotCle = $_GET['motcle'] ?? null;

// Récupérer les thématiques pour le filtre
$thematiques = selectAll('THEMATIQUE', 'numThem');

// Récupérer les mots-clés pour le filtre
$motscles = selectAll('MOTCLE', 'numMotCle');

// Effectuer la recherche si nécessaire
$results = [];
if (!empty($searchQuery) || $numThem || $numMotCle) {
    $pdo = getConnection();
    
    if ($searchType === 'titre' && !empty($searchQuery)) {
        // Recherche par titre
        $sql = "SELECT DISTINCT a.*, t.libThem,
                (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
                FROM ARTICLE a
                LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
                WHERE a.libTltArt LIKE ?
                ORDER BY a.dtCreaArt DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['%' . $searchQuery . '%']);
        $results = $stmt->fetchAll();
        
    } elseif ($numThem) {
        // Recherche par thématique
        $sql = "SELECT a.*, t.libThem,
                (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
                FROM ARTICLE a
                LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
                WHERE a.numThem = ?
                ORDER BY a.dtCreaArt DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$numThem]);
        $results = $stmt->fetchAll();
        
    } elseif ($numMotCle) {
        // Recherche par mot-clé
        $sql = "SELECT DISTINCT a.*, t.libThem,
                (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
                FROM ARTICLE a
                INNER JOIN MOTCLEARTICLE mca ON a.numArt = mca.numArt
                LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
                WHERE mca.numMotCle = ?
                ORDER BY a.dtCreaArt DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$numMotCle]);
        $results = $stmt->fetchAll();
        
    } else {
        // Recherche globale
        $sql = "SELECT DISTINCT a.*, t.libThem,
                (SELECT COUNT(*) FROM LIKEART l WHERE l.numArt = a.numArt AND l.likeA = 1) as nb_likes
                FROM ARTICLE a
                LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem
                LEFT JOIN MOTCLEARTICLE mca ON a.numArt = mca.numArt
                LEFT JOIN MOTCLE mc ON mca.numMotCle = mc.numMotCle
                WHERE a.libTltArt LIKE ? 
                   OR a.libChapArt LIKE ?
                   OR mc.libMotCle LIKE ?
                ORDER BY a.dtCreaArt DESC";
        $stmt = $pdo->prepare($sql);
        $searchPattern = '%' . $searchQuery . '%';
        $stmt->execute([$searchPattern, $searchPattern, $searchPattern]);
        $results = $stmt->fetchAll();
    }
}
?>

<div class="container mt-5">
    <h1>Recherche d'articles</h1>
    
    <!-- Formulaire de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="search.php">
                <div class="row">
                    <!-- Recherche textuelle -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rechercher</label>
                        <input type="text" 
                               name="q" 
                               class="form-control" 
                               placeholder="Titre, contenu, mot-clé..." 
                               value="<?= htmlspecialchars($searchQuery) ?>">
                    </div>
                    
                    <!-- Type de recherche -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control">
                            <option value="all" <?= $searchType === 'all' ? 'selected' : '' ?>>Tout</option>
                            <option value="titre" <?= $searchType === 'titre' ? 'selected' : '' ?>>Titre uniquement</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Rechercher</button>
                    </div>
                </div>
                
                <!-- Filtres -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thématique</label>
                        <select name="thematique" class="form-control">
                            <option value="">Toutes</option>
                            <?php foreach ($thematiques as $them): ?>
                                <option value="<?= $them['numThem'] ?>" <?= $numThem == $them['numThem'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($them['libThem']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mot-clé</label>
                        <select name="motcle" class="form-control">
                            <option value="">Tous</option>
                            <?php foreach ($motscles as $mc): ?>
                                <option value="<?= $mc['numMotCle'] ?>" <?= $numMotCle == $mc['numMotCle'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($mc['libMotCle']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Résultats -->
    <?php if (!empty($searchQuery) || $numThem || $numMotCle): ?>
        <h3><?= count($results) ?> résultat(s) trouvé(s)</h3>
        
        <?php if (empty($results)): ?>
            <div class="alert alert-info">
                Aucun article ne correspond à votre recherche.
            </div>
        <?php else: ?>
            <div class="row mt-4">
                <?php foreach ($results as $art): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if ($art['urlPhotArt']): ?>
                                <img src="../../src/uploads/<?= htmlspecialchars($art['urlPhotArt']) ?>" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= htmlspecialchars($art['libThem'] ?? 'Sans catégorie') ?></span>
                                <h5 class="card-title"><?= htmlspecialchars($art['libTltArt']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars(substr($art['libChapArt'], 0, 120)) ?>...</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="articles/article1.php?id=<?= $art['numArt'] ?>" class="btn btn-primary">Lire</a>
                                    <span class="text-muted">❤️ <?= $art['nb_likes'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>