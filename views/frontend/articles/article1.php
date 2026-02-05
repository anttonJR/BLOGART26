<?php
session_start();
include '../includes/cookie-consent.php';
require_once __DIR__ . '/../../../config.php';
require_once ROOT . '/functions/csrf.php';
require_once ROOT . '/functions/auth.php';
require_once ROOT . '/functions/bbcode.php';
require_once ROOT . '/functions/motcle.php';

$pageTitle = 'Article - Millésime Blog\'Art';
include '../includes/header.php';

$numArt = $_GET['id'] ?? null;
if (!$numArt) {
    header('Location: ' . ROOT_URL . '/index.php');
    exit;
}

// Récupérer l'article
global $DB;
$sqlArt = "SELECT a.*, t.libThem FROM ARTICLE a 
           LEFT JOIN THEMATIQUE t ON a.numThem = t.numThem 
           WHERE a.numArt = ?";
$stmtArt = $DB->prepare($sqlArt);
$stmtArt->execute([$numArt]);
$art = $stmtArt->fetch();

if (!$art) {
    die('Article introuvable');
}

// Récupérer les mots-clés
$motscles = getMotsClesArticle($numArt);

// Récupérer le nombre de likes
$sqlLikes = "SELECT COUNT(*) as nb_likes FROM LIKEART WHERE numArt = ? AND likeA = 1";
$stmtLikes = $DB->prepare($sqlLikes);
$stmtLikes->execute([$numArt]);
$nbLikes = $stmtLikes->fetch()['nb_likes'];

// Vérifier si l'utilisateur a déjà liké
$userLiked = false;
if (isLoggedIn()) {
    $sqlUserLike = "SELECT likeA FROM LIKEART WHERE numMemb = ? AND numArt = ?";
    $stmtUserLike = $DB->prepare($sqlUserLike);
    $stmtUserLike->execute([$_SESSION['user']['numMemb'], $numArt]);
    $userLike = $stmtUserLike->fetch();
    $userLiked = $userLike && $userLike['likeA'] == 1;
}

// Récupérer les commentaires validés
$sqlCom = "SELECT c.*, m.pseudoMemb, m.prenomMemb
           FROM COMMENT c
           INNER JOIN MEMBRE m ON c.numMemb = m.numMemb
           WHERE c.numArt = ? AND c.attModOK = 1 AND c.dtDelLogCom IS NULL
           ORDER BY c.dtCreaCom DESC";
$stmtCom = $DB->prepare($sqlCom);
$stmtCom->execute([$numArt]);
$commentaires = $stmtCom->fetchAll();
?>

<!-- Contenu article -->
<div class="container mt-5">
        <!-- Bouton retour -->
        <a href="recherche.php" class="btn btn-outline-secondary mb-4">
            <i class="bi bi-arrow-left"></i> Retour aux articles
        </a>

        <!-- Titre -->
        <h1 class="mb-3"><?= htmlspecialchars($art['libTitrArt'] ?? '') ?></h1>

        <!-- Thématique -->
        <?php if ($art['libThem']): ?>
            <span class="badge bg-bordeaux mb-3"><?= htmlspecialchars($art['libThem']) ?></span>
        <?php endif; ?>

        <!-- Bouton Like -->
        <div class="d-flex align-items-center mb-4">
            <?php if (isLoggedIn()): ?>
                <form method="POST" action="<?= ROOT_URL ?>/api/likes/toggle.php" class="d-inline">
                    <?php csrfField(); ?>
                    <input type="hidden" name="numArt" value="<?= $numArt ?>">
                    <button type="submit" class="btn btn-<?= $userLiked ? 'danger' : 'outline-danger' ?>">
                        <?= $userLiked ? '❤️ J\'aime' : '🤍 Aimer' ?>
                    </button>
                </form>
            <?php else: ?>
                <button class="btn btn-outline-secondary" disabled>🤍 Aimer</button>
            <?php endif; ?>
            <span class="ms-3"><strong><?= $nbLikes ?></strong> like<?= $nbLikes > 1 ? 's' : '' ?></span>
        </div>

        <!-- Image -->
        <?php if (!empty($art['urlPhotArt'])): ?>
            <div class="text-center mb-4">
                <img src="<?= ROOT_URL ?>/src/uploads/<?= htmlspecialchars($art['urlPhotArt']) ?>" 
                     alt="<?= htmlspecialchars($art['libTitrArt'] ?? '') ?>" 
                     class="img-fluid rounded shadow" style="max-height: 450px; max-width: 100%; object-fit: contain;">
            </div>
        <?php endif; ?>

        <!-- Chapô -->
        <p class="lead"><?= bbcode_to_html($art['libChapoArt'] ?? '') ?></p>
        
        <!-- Accroche -->
        <?php if ($art['libAccrochArt']): ?>
            <blockquote class="blockquote border-start border-4 border-danger ps-3 my-4">
                <?= bbcode_to_html($art['libAccrochArt']) ?>
            </blockquote>
        <?php endif; ?>
        
        <!-- Paragraphe 1 -->
        <div class="mt-4">
            <?= bbcode_to_html($art['parag1Art']) ?>
        </div>
        
        <!-- Titre éditorial -->
        <?php if (!empty($art['libSsTitr1Art'])): ?>
            <h3 class="mt-4 text-bordeaux"><?= htmlspecialchars($art['libSsTitr1Art']) ?></h3>
        <?php endif; ?>
        
        <!-- Paragraphe 2 -->
        <?php if ($art['parag2Art']): ?>
            <div class="mt-3"><?= bbcode_to_html($art['parag2Art']) ?></div>
        <?php endif; ?>
        
        <!-- Paragraphe 3 -->
        <?php if ($art['parag3Art']): ?>
            <div class="mt-3"><?= bbcode_to_html($art['parag3Art']) ?></div>
        <?php endif; ?>
        
        <!-- Conclusion -->
        <?php if (!empty($art['libConclArt'])): ?>
            <div class="mt-4 alert alert-secondary">
                <strong>Conclusion :</strong>
                <?= bbcode_to_html($art['libConclArt']) ?>
            </div>
        <?php endif; ?>

        <!-- Mots-clés -->
        <?php if (!empty($motscles)): ?>
            <div class="mt-4">
                <h5>Mots-clés :</h5>
                <div>
                    <?php foreach ($motscles as $mc): ?>
                        <a href="recherche.php?keyword=<?= urlencode($mc['libMotCle']) ?>" 
                           class="badge bg-bordeaux me-2 mb-2 text-decoration-none">
                            <?= htmlspecialchars($mc['libMotCle']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <hr>
        <p class="text-muted">
            <i class="bi bi-calendar"></i> Publié le <?= date('d/m/Y à H:i', strtotime($art['dtCreaArt'])) ?>
        </p>

        <!-- Section Commentaires -->
        <hr class="my-5">
        <h3><i class="bi bi-chat-dots"></i> Commentaires</h3>

        <!-- Formulaire de commentaire (si connecté) -->
        <?php if (isLoggedIn()): ?>
            <div class="card mb-4">
                <div class="card-header bg-bordeaux text-white">
                    Poster un commentaire
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['comment_error'])): ?>
                        <div class="alert alert-danger">
                            <?= $_SESSION['comment_error'] ?>
                        </div>
                        <?php unset($_SESSION['comment_error']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['comment_success'])): ?>
                        <div class="alert alert-success">
                            <?= $_SESSION['comment_success'] ?>
                        </div>
                        <?php unset($_SESSION['comment_success']); ?>
                    <?php endif; ?>
                    
                    <form method="POST" action="<?= ROOT_URL ?>/api/comments/create.php">
                        <?php csrfField(); ?>
                        <input type="hidden" name="numArt" value="<?= $numArt ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Votre commentaire</label>
                            <textarea name="libCom" 
                                      class="form-control" 
                                      rows="4" 
                                      required 
                                      placeholder="Partagez votre avis..."></textarea>
                            <small class="form-text text-muted">
                                Vous pouvez utiliser le BBCode : [b]gras[/b], [i]italique[/i], etc.
                            </small>
                        </div>
                        
                        <button type="submit" class="btn btn-bordeaux">Envoyer</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Vous souhaitez commenter ?</strong> 
                <a href="<?= ROOT_URL ?>/views/frontend/security/login.php" class="alert-link">Connectez-vous</a> 
                ou <a href="<?= ROOT_URL ?>/views/frontend/security/signup.php" class="alert-link">créez un compte</a> pour poster un commentaire.
            </div>
        <?php endif; ?>

        <!-- Liste des commentaires -->
        <h4 class="mt-4"><?= count($commentaires) ?> commentaire(s)</h4>

        <?php if (empty($commentaires)): ?>
            <p class="text-muted">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
        <?php else: ?>
            <?php foreach ($commentaires as $com): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <strong><?= htmlspecialchars($com['prenomMemb']) ?></strong> 
                        <small class="text-muted">(@<?= htmlspecialchars($com['pseudoMemb']) ?>)</small>
                        <span class="float-end text-muted">
                            <?= date('d/m/Y à H:i', strtotime($com['dtCreaCom'])) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <?= bbcode_to_html($com['libCom']) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

<?php
include '../includes/footer.php';
?>
