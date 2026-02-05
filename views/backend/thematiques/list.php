<?php
$pageTitle = 'Thématiques';
require_once __DIR__ . '/../includes/header.php';
require_once ROOT . '/functions/auth.php';
requireAdmin();

global $DB;

// Exclure les thématiques dans la corbeille
$stmt = $DB->query("SELECT * FROM THEMATIQUE WHERE delLogiq = 0 OR delLogiq IS NULL ORDER BY numThem");
$thematiques = $stmt->fetchAll();

// Compter les thématiques dans la corbeille
$stmtTrash = $DB->query("SELECT COUNT(*) FROM THEMATIQUE WHERE delLogiq = 1");
$trashCount = $stmtTrash->fetchColumn();
?>

<div class="page-header">
    <h1><i class="bi bi-tags me-2"></i>Thématiques</h1>
    <div class="d-flex gap-2">
        <a href="<?= ROOT_URL ?>/views/backend/thematiques/trash.php" class="btn btn-outline-secondary">
            <i class="bi bi-trash me-1"></i>Corbeille
            <?php if ($trashCount > 0): ?><span class="badge bg-danger"><?= $trashCount ?></span><?php endif; ?>
        </a>
        <a href="<?= ROOT_URL ?>/views/backend/thematiques/create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouvelle thématique
        </a>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<div class="admin-card">
    <div class="card-body p-0">
        <table class="table admin-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Libellé</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($thematiques)): ?>
                    <tr><td colspan="3" class="text-center py-4 text-muted">Aucune thématique</td></tr>
                <?php else: ?>
                    <?php foreach ($thematiques as $them): ?>
                        <tr>
                            <td><?= $them['numThem'] ?></td>
                            <td><strong><?= htmlspecialchars($them['libThem']) ?></strong></td>
                            <td class="text-end">
                                <div class="btn-group-actions">
                                    <a href="<?= ROOT_URL ?>/views/backend/thematiques/edit.php?id=<?= $them['numThem'] ?>" class="btn btn-action btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="<?= ROOT_URL ?>/views/backend/thematiques/delete.php?id=<?= $them['numThem'] ?>" class="btn btn-action btn-outline-danger" onclick="return confirm('Supprimer ?')"><i class="bi bi-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
