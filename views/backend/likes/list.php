<?php
$pageTitle = 'Gestion des likes';
$breadcrumb = [['label' => 'Likes']];
require_once dirname(__DIR__) . '/includes/header.php';

global $DB;

// Récupérer tous les likes avec les infos articles et membres
$sql = "SELECT l.*, a.libTltArt, m.pseudoMemb 
        FROM LIKEART l 
        INNER JOIN ARTICLE a ON l.numArt = a.numArt 
        INNER JOIN MEMBRE m ON l.numMemb = m.numMemb 
        WHERE l.likeA = 1
        ORDER BY a.numArt";

$stmt = $DB->query($sql);
$likes = $stmt->fetchAll();

// Stats par article
$sqlStats = "SELECT a.numArt, a.libTltArt, COUNT(l.numMemb) as nb_likes
             FROM ARTICLE a
             LEFT JOIN LIKEART l ON a.numArt = l.numArt AND l.likeA = 1
             GROUP BY a.numArt, a.libTltArt
             ORDER BY nb_likes DESC";

$stmtStats = $DB->query($sqlStats);
$statsLikes = $stmtStats->fetchAll();
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-heart me-2"></i>Gestion des likes</h1>
</div>

<div class="row g-4">
    <!-- Stats par article -->
    <div class="col-lg-6">
        <div class="admin-card h-100">
            <div class="card-header">
                <h5><i class="bi bi-bar-chart me-2"></i>Likes par article</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Article</th>
                                <th class="text-end">Likes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($statsLikes)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-heart fs-1 d-block mb-2"></i>
                                        Aucun like trouvé
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($statsLikes as $index => $stat): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            $badgeClass = 'bg-light text-dark';
                                            if ($index === 0) $badgeClass = 'bg-warning';
                                            elseif ($index === 1) $badgeClass = 'bg-secondary';
                                            elseif ($index === 2) $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $index + 1 ?></span>
                                        </td>
                                        <td><?= htmlspecialchars($stat['libTltArt']) ?></td>
                                        <td class="text-end">
                                            <span class="badge bg-danger">
                                                <i class="bi bi-heart-fill me-1"></i><?= $stat['nb_likes'] ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Détail des likes -->
    <div class="col-lg-6">
        <div class="admin-card h-100">
            <div class="card-header">
                <h5><i class="bi bi-list-ul me-2"></i>Détail des likes (<?= count($likes) ?>)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table admin-table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Membre</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($likes)): ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">
                                        <i class="bi bi-heart fs-1 d-block mb-2"></i>
                                        Aucun like trouvé
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($likes as $like): ?>
                                    <tr>
                                        <td>
                                            <small class="text-truncate d-inline-block" style="max-width: 150px;">
                                                <?= htmlspecialchars($like['libTltArt']) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <small>@<?= htmlspecialchars($like['pseudoMemb']) ?></small>
                                        </td>
                                        <td class="text-end">
                                            <a href="delete.php?numArt=<?= $like['numArt'] ?>&numMemb=<?= $like['numMemb'] ?>" 
                                               class="btn btn-action btn-outline-danger" 
                                               title="Supprimer"
                                               onclick="return confirm('Supprimer ce like ?')">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>