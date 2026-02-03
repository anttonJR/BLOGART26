<?php
$pageTitle = 'Gestion des statuts';
$breadcrumb = [['label' => 'Statuts']];
require_once dirname(__DIR__) . '/includes/header.php';
require_once ROOT . '/functions/global.inc.php';

// Load all statuts
$statuts = sql_select("STATUT", "*");
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-person-badge me-2"></i>Gestion des statuts</h1>
    <div class="btn-group">
        <a href="<?= ROOT_URL ?>/views/backend/statuts/create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouveau statut
        </a>
    </div>
</div>

<!-- Statuts Table -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="bi bi-list-ul me-2"></i>Liste des statuts (<?= count($statuts) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Nom du statut</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($statuts)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun statut trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($statuts as $statut): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= $statut['numStat'] ?></span>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($statut['libStat']) ?></strong>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group-actions">
                                        <a href="edit.php?numStat=<?= $statut['numStat'] ?>" 
                                           class="btn btn-action btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?numStat=<?= $statut['numStat'] ?>" 
                                           class="btn btn-action btn-outline-danger" 
                                           title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce statut ?')">
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