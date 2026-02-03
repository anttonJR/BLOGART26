<?php
$pageTitle = 'Modération des commentaires';
$breadcrumb = [['label' => 'Modération']];
require_once dirname(__DIR__) . '/includes/header.php';
require_once ROOT . '/functions/csrf.php';
require_once ROOT . '/functions/auth.php';
require_once ROOT . '/functions/bbcode.php';

requireModerator();

global $DB;

// Récupérer tous les commentaires en attente
$sql = "SELECT c.*, m.pseudoMemb, m.prenomMemb, a.libTltArt
        FROM COMMENT c
        INNER JOIN MEMBRE m ON c.numMemb = m.numMemb
        INNER JOIN ARTICLE a ON c.numArt = a.numArt
        WHERE c.attModOK = 0 AND c.dtDelLogCom IS NULL
        ORDER BY c.dtCreaCoM DESC";

$stmt = $DB->query($sql);
$comments_pending = $stmt->fetchAll();

// Récupérer les commentaires validés
$sql2 = "SELECT c.*, m.pseudoMemb, m.prenomMemb, a.libTltArt
         FROM COMMENT c
         INNER JOIN MEMBRE m ON c.numMemb = m.numMemb
         INNER JOIN ARTICLE a ON c.numArt = a.numArt
         WHERE c.attModOK = 1 AND c.dtDelLogCom IS NULL
         ORDER BY c.dtCreaCoM DESC
         LIMIT 20";

$stmt2 = $DB->query($sql2);
$comments_approved = $stmt2->fetchAll();
?>

<!-- Page Header -->
<div class="page-header">
    <h1><i class="bi bi-shield-check me-2"></i>Modération des commentaires</h1>
</div>

<!-- Onglets -->
<ul class="nav nav-tabs mb-4" id="moderationTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button">
            <i class="bi bi-clock me-1"></i>
            En attente 
            <?php if (count($comments_pending) > 0): ?>
                <span class="badge bg-warning text-dark"><?= count($comments_pending) ?></span>
            <?php endif; ?>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button">
            <i class="bi bi-check-circle me-1"></i>
            Validés récents
        </button>
    </li>
</ul>

<div class="tab-content" id="moderationTabsContent">
    <!-- Onglet : En attente -->
    <div class="tab-pane fade show active" id="pending" role="tabpanel">
        <?php if (empty($comments_pending)): ?>
            <div class="admin-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-check-circle text-success fs-1 mb-3"></i>
                    <h5>Aucun commentaire en attente</h5>
                    <p class="text-muted">Tous les commentaires ont été modérés.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($comments_pending as $com): ?>
                    <div class="col-lg-6">
                        <div class="admin-card border-warning">
                            <div class="card-header bg-warning bg-opacity-10">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1"><?= htmlspecialchars($com['libTltArt']) ?></h6>
                                        <small class="text-muted">
                                            <i class="bi bi-person me-1"></i>
                                            <?= htmlspecialchars($com['prenomMemb']) ?> 
                                            (@<?= htmlspecialchars($com['pseudoMemb']) ?>)
                                        </small>
                                    </div>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($com['dtCreaCoM'])) ?>
                                    </small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="border rounded p-3 bg-light">
                                    <?= bbcode_to_html($com['libCom']) ?>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex gap-2">
                                    <form method="POST" action="<?= ROOT_URL ?>/api/comments/moderate.php" class="flex-grow-1">
                                        <?php csrfField(); ?>
                                        <input type="hidden" name="numCom" value="<?= $com['numCom'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="bi bi-check-lg me-1"></i>Valider
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            class="btn btn-outline-danger flex-grow-1" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal<?= $com['numCom'] ?>">
                                        <i class="bi bi-x-lg me-1"></i>Rejeter
                                    </button>
                                </div>
                                
                                <!-- Modal de rejet -->
                                <div class="modal fade" id="rejectModal<?= $com['numCom'] ?>" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-x-circle text-danger me-2"></i>
                                                    Rejeter le commentaire
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="<?= ROOT_URL ?>/api/comments/moderate.php">
                                                <?php csrfField(); ?>
                                                <input type="hidden" name="numCom" value="<?= $com['numCom'] ?>">
                                                <input type="hidden" name="action" value="reject">
                                                <div class="modal-body">
                                                    <label class="form-label">Raison du rejet (facultatif)</label>
                                                    <textarea name="notifComKOAff" 
                                                              class="form-control" 
                                                              rows="3" 
                                                              placeholder="Ex: Contenu inapproprié, hors sujet..."></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="bi bi-x-lg me-1"></i>Confirmer le rejet
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Onglet : Validés -->
    <div class="tab-pane fade" id="approved" role="tabpanel">
        <?php if (empty($comments_approved)): ?>
            <div class="admin-card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-chat-dots text-muted fs-1 mb-3"></i>
                    <h5>Aucun commentaire validé</h5>
                    <p class="text-muted">Les commentaires validés apparaîtront ici.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="admin-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table admin-table">
                            <thead>
                                <tr>
                                    <th>Article</th>
                                    <th>Auteur</th>
                                    <th>Commentaire</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($comments_approved as $com): ?>
                                    <tr>
                                        <td>
                                            <strong class="text-truncate d-inline-block" style="max-width: 120px;">
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
                                            <form method="POST" action="<?= ROOT_URL ?>/api/comments/archive.php" class="d-inline">
                                                <?php csrfField(); ?>
                                                <input type="hidden" name="numCom" value="<?= $com['numCom'] ?>">
                                                <button type="submit" class="btn btn-action btn-outline-warning" title="Archiver">
                                                    <i class="bi bi-archive"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>