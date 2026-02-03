<?php
$pageTitle = 'Gestion des membres';
$breadcrumb = [['label' => 'Membres']];
require_once dirname(__DIR__) . '/includes/header.php';
require_once ROOT . '/functions/auth.php';

requireAdmin();

global $DB;

// Récupérer tous les membres avec leur statut
$sql = "SELECT m.*, s.libStat 
        FROM MEMBRE m 
        LEFT JOIN STATUT s ON m.numStat = s.numStat 
        ORDER BY m.dtCreaMemb DESC";

$stmt = $DB->query($sql);
$membres = $stmt->fetchAll();
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-people me-2"></i>Gestion des membres</h1>
    <div class="btn-group">
        <a href="<?= ROOT_URL ?>/views/backend/members/create.php" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Nouveau membre
        </a>
    </div>
</div>

<!-- Members Table -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="bi bi-list-ul me-2"></i>Liste des membres (<?= count($membres) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Pseudo</th>
                        <th>Nom / Prénom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Inscription</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($membres)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun membre trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($membres as $membre): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= $membre['numMemb'] ?></span>
                                </td>
                                <td>
                                    <strong>@<?= htmlspecialchars($membre['pseudoMemb']) ?></strong>
                                </td>
                                <td>
                                    <?= htmlspecialchars($membre['nomMemb'] ?? '') ?> 
                                    <?= htmlspecialchars($membre['prenomMemb'] ?? '') ?>
                                </td>
                                <td>
                                    <small><?= htmlspecialchars($membre['eMailMemb'] ?? '') ?></small>
                                </td>
                                <td>
                                    <?php 
                                    $badgeClass = 'bg-secondary';
                                    if ($membre['numStat'] == 3) $badgeClass = 'bg-danger';
                                    elseif ($membre['numStat'] == 2) $badgeClass = 'bg-warning text-dark';
                                    elseif ($membre['numStat'] == 1) $badgeClass = 'bg-success';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>">
                                        <?= htmlspecialchars($membre['libStat'] ?? 'Non défini') ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= isset($membre['dtCreaMemb']) ? date('d/m/Y', strtotime($membre['dtCreaMemb'])) : '—' ?>
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group-actions">
                                        <a href="edit.php?id=<?= $membre['numMemb'] ?>" 
                                           class="btn btn-action btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $membre['numMemb'] ?>" 
                                           class="btn btn-action btn-outline-danger" 
                                           title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?')">
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