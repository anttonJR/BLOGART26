<?php
$pageTitle = 'Corbeille - Thématiques';
require_once __DIR__ . '/../includes/header.php';
require_once ROOT . '/functions/auth.php';
requireAdmin();

global $DB;

// Thématiques dans la corbeille
$stmt = $DB->query("
    SELECT * FROM THEMATIQUE 
    WHERE delLogiq = 1
    ORDER BY dtDelLogThem DESC
");
$thematiques = $stmt->fetchAll();
?>

<div class="page-header">
    <h1><i class="bi bi-trash me-2"></i>Corbeille - Thématiques</h1>
    <div class="d-flex gap-2">
        <a href="<?= ROOT_URL ?>/views/backend/thematiques/list.php" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Retour aux thématiques
        </a>
        <?php if (!empty($thematiques)): ?>
        <a href="<?= ROOT_URL ?>/views/backend/thematiques/empty-trash.php" class="btn btn-danger" onclick="return confirm('Vider définitivement la corbeille ? Cette action est irréversible.')">
            <i class="bi bi-trash-fill me-1"></i>Vider la corbeille
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    Les thématiques dans la corbeille seront définitivement supprimées après 30 jours. Vous pouvez les restaurer à tout moment.
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
                    <th>Supprimé le</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($thematiques)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="bi bi-trash text-muted" style="font-size: 3rem;"></i>
                            <p class="mt-3 text-muted">La corbeille est vide</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($thematiques as $them): ?>
                        <tr>
                            <td><?= $them['numThem'] ?></td>
                            <td><strong class="text-muted"><?= htmlspecialchars($them['libThem']) ?></strong></td>
                            <td>
                                <?php if (isset($them['dtDelLogThem'])): ?>
                                    <?= date('d/m/Y H:i', strtotime($them['dtDelLogThem'])) ?>
                                    <br><small class="text-muted">
                                        <?php 
                                        $daysLeft = 30 - floor((time() - strtotime($them['dtDelLogThem'])) / 86400);
                                        echo $daysLeft > 0 ? "Expire dans $daysLeft jours" : "Expiré";
                                        ?>
                                    </small>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <div class="btn-group-actions">
                                    <a href="<?= ROOT_URL ?>/views/backend/thematiques/restore.php?id=<?= $them['numThem'] ?>" class="btn btn-action btn-outline-success" title="Restaurer">
                                        <i class="bi bi-arrow-counterclockwise"></i>
                                    </a>
                                    <a href="<?= ROOT_URL ?>/views/backend/thematiques/permanent-delete.php?id=<?= $them['numThem'] ?>" class="btn btn-action btn-outline-danger" onclick="return confirm('Supprimer définitivement cette thématique ?')" title="Supprimer définitivement">
                                        <i class="bi bi-x-lg"></i>
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

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
