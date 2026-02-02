<?php
session_start();
require_once '../../functions/csrf.php';
require_once '../../functions/auth.php';
require_once '../../functions/query/select.php';
require_once '../../functions/bbcode.php';

requireModerator(); // Seuls modérateurs et admins

// Récupérer tous les commentaires en attente
$sql = "SELECT c.*, m.pseudoMemb, m.prenomMemb, a.libTltArt
        FROM COMMENT c
        INNER JOIN MEMBRE m ON c.numMemb = m.numMemb
        INNER JOIN ARTICLE a ON c.numArt = a.numArt
        WHERE c.attModOK = 0 AND c.dtDelLogCom IS NULL
        ORDER BY c.dtCreaCoM DESC";

$pdo = getConnection();
$stmt = $pdo->query($sql);
$comments_pending = $stmt->fetchAll();

// Récupérer les commentaires validés
$sql2 = "SELECT c.*, m.pseudoMemb, m.prenomMemb, a.libTltArt
         FROM COMMENT c
         INNER JOIN MEMBRE m ON c.numMemb = m.numMemb
         INNER JOIN ARTICLE a ON c.numArt = a.numArt
         WHERE c.attModOK = 1 AND c.dtDelLogCom IS NULL
         ORDER BY c.dtCreaCoM DESC
         LIMIT 20";

$stmt2 = $pdo->query($sql2);
$comments_approved = $stmt2->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modération des commentaires</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Modération des commentaires</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <!-- Onglets -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" 
                        id="pending-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#pending" 
                        type="button">
                    En attente (<?= count($comments_pending) ?>)
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" 
                        id="approved-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#approved" 
                        type="button">
                    Validés
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <!-- Onglet : En attente -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel">
                <div class="mt-3">
                    <?php if (empty($comments_pending)): ?>
                        <div class="alert alert-info">
                            Aucun commentaire en attente de modération.
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments_pending as $com): ?>
                            <div class="card mb-3 border-warning">
                                <div class="card-header bg-warning">
                                    <strong>Article :</strong> <?= htmlspecialchars($com['libTltArt']) ?><br>
                                    <strong>Auteur :</strong> <?= htmlspecialchars($com['prenomMemb']) ?> 
                                    (@<?= htmlspecialchars($com['pseudoMemb']) ?>)<br>
                                    <strong>Date :</strong> <?= date('d/m/Y à H:i', strtotime($com['dtCreaCoM'])) ?>
                                </div>
                                <div class="card-body">
                                    <?= bbcode_to_html($com['libCom']) ?>
                                </div>
                                <div class="card-footer">
                                    <form method="POST" action="../../api/comments/moderate.php" class="d-inline">
                                        <?php csrfField(); ?>
                                        <input type="hidden" name="numCom" value="<?= $com['numCom'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success">
                                            ✓ Valider
                                        </button>
                                    </form>
                                    
                                    <button type="button" 
                                            class="btn btn-danger" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#rejectModal<?= $com['numCom'] ?>">
                                        ✗ Rejeter
                                    </button>
                                    
                                    <!-- Modal de rejet -->
                                    <div class="modal fade" id="rejectModal<?= $com['numCom'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Rejeter le commentaire</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="../../api/comments/moderate.php">
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
                                                        <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Onglet : Validés -->
            <div class="tab-pane fade" id="approved" role="tabpanel">
                <div class="mt-3">
                    <?php if (empty($comments_approved)): ?>
                        <div class="alert alert-info">
                            Aucun commentaire validé.
                        </div>
                    <?php else: ?>
                        <?php foreach ($comments_approved as $com): ?>
                            <div class="card mb-3">
                                <div class="card-header bg-success text-white">
                                    <strong>Article :</strong> <?= htmlspecialchars($com['libTltArt']) ?><br>
                                    <strong>Auteur :</strong> <?= htmlspecialchars($com['prenomMemb']) ?> 
                                    (@<?= htmlspecialchars($com['pseudoMemb']) ?>)
                                </div>
                                <div class="card-body">
                                    <?= bbcode_to_html($com['libCom']) ?>
                                </div>
                                <div class="card-footer text-muted">
                                    Validé le <?= date('d/m/Y à H:i', strtotime($com['dtModCom'])) ?>
                                    
                                    <form method="POST" action="../../api/comments/archive.php" class="d-inline float-end">
                                        <?php csrfField(); ?>
                                        <input type="hidden" name="numCom" value="<?= $com['numCom'] ?>">
                                        <button type="submit" class="btn btn-sm btn-warning">Archiver</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../../../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>