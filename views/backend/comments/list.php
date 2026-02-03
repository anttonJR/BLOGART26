<?php
$pageTitle = 'Gestion des commentaires';
$breadcrumb = [['label' => 'Commentaires']];
require_once dirname(__DIR__) . '/includes/header.php';
require_once ROOT . '/functions/bbcode.php';

global $DB;

// Récupérer tous les commentaires validés
$sql = "SELECT c.*, m.pseudoMemb, a.libTltArt 
        FROM COMMENT c 
        INNER JOIN MEMBRE m ON c.numMemb = m.numMemb 
        INNER JOIN ARTICLE a ON c.numArt = a.numArt 
        WHERE c.attModOK = 1 AND c.dtDelLogCom IS NULL
        ORDER BY c.dtCreaCoM DESC";

$stmt = $DB->query($sql);
$comments = $stmt->fetchAll();
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-chat-left-text me-2"></i>Gestion des commentaires</h1>
    <div class="btn-group">
        <a href="<?= ROOT_URL ?>/views/backend/moderation/comments.php" class="btn btn-warning">
            <i class="bi bi-shield-check me-1"></i>Modération
        </a>
    </div>
</div>

<!-- Comments Table -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="bi bi-list-ul me-2"></i>Commentaires validés (<?= count($comments) ?>)</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Article</th>
                        <th>Auteur</th>
                        <th>Commentaire</th>
                        <th>Date</th>
                        <th class="text-end" style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comments)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Aucun commentaire trouvé
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($comments as $com): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?= $com['numCom'] ?></span>
                                </td>
                                <td>
                                    <strong class="text-truncate d-inline-block" style="max-width: 150px;">
                                        <?= htmlspecialchars($com['libTltArt']) ?>
                                    </strong>
                                </td>
                                <td>
                                    <small>@<?= htmlspecialchars($com['pseudoMemb']) ?></small>
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 200px;">
                                        <?= htmlspecialchars(substr(strip_tags($com['libCom']), 0, 50)) ?>...
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($com['dtCreaCoM'])) ?>
                                    </small>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group-actions">
                                        <a href="edit.php?id=<?= $com['numCom'] ?>" 
                                           class="btn btn-action btn-outline-primary" 
                                           title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $com['numCom'] ?>" 
                                           class="btn btn-action btn-outline-danger" 
                                           title="Supprimer"
                                           onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
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