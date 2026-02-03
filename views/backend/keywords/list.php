<?php
$pageTitle = 'Gestion des mots-clés';
$breadcrumb = [['label' => 'Mots-clés']];
require_once dirname(__DIR__) . '/includes/header.php';
require_once ROOT . '/functions/query/select.php';

$motCles = selectAll('MOTCLE', 'numMotCle');
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-hash me-2"></i>Gestion des mots-clés</h1>
    <div class="btn-group">
        <a href="<?= ROOT_URL ?>/views/backend/keywords/create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouveau mot-clé
        </a>
    </div>
</div>

<!-- Keywords Table -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="bi bi-list-ul me-2"></i>Liste des mots-clés (<?= count($motCles) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">N°</th>
                        <th>Libellé</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($motCles)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun mot-clé trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($motCles as $motCle): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= $motCle['numMotCle'] ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-dark">#<?= htmlspecialchars($motCle['libMotCle']) ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group-actions">
                                        <a href="edit.php?id=<?= $motCle['numMotCle'] ?>" 
                                           class="btn btn-action btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $motCle['numMotCle'] ?>" 
                                           class="btn btn-action btn-outline-danger" 
                                           title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce mot-clé ?')">
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