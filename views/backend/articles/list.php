<?php
$pageTitle = 'Gestion des articles';
$breadcrumb = [['label' => 'Articles']];
require_once dirname(__DIR__) . '/includes/header.php';
require_once ROOT . '/functions/query/select.php';
require_once ROOT . '/functions/motcle.php';

// Récupérer tous les articles avec leur thématique
$sql = "SELECT a.*, t.libThem 
        FROM ARTICLE a 
        LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem 
        ORDER BY a.dtCreaArt DESC";

global $DB;
$stmt = $DB->query($sql);
$articles = $stmt->fetchAll();
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text me-2"></i>Gestion des articles</h1>
    <div class="btn-group">
        <a href="<?= ROOT_URL ?>/views/backend/articles/create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouvel article
        </a>
    </div>
</div>

<!-- Articles Table -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="bi bi-list-ul me-2"></i>Liste des articles (<?= count($articles) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">N°</th>
                        <th>Titre</th>
                        <th>Thématique</th>
                        <th>Mots-clés</th>
                        <th>Date création</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($articles)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun article trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($articles as $art): ?>
                            <?php $motscles = getMotsClesArticle($art['numArt']); ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= $art['numArt'] ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($art['libTltArt']) ?></strong>
                                </td>
                                <td>
                                    <?php if ($art['libThem']): ?>
                                        <span class="badge bg-info"><?= htmlspecialchars($art['libThem']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($motscles)): ?>
                                        <?php foreach ($motscles as $mc): ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($mc['libMotCle']) ?></span>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?= date('d/m/Y', strtotime($art['dtCreaArt'])) ?>
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group-actions">
                                        <a href="edit.php?id=<?= $art['numArt'] ?>" 
                                           class="btn btn-action btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $art['numArt'] ?>" 
                                           class="btn btn-action btn-outline-danger" 
                                           title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
        </tr>
    <?php endforeach; ?>
</tbody>