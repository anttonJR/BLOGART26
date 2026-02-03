<?php
session_start();
require_once '../../functions/csrf.php';
require_once '../../functions/auth.php';
require_once '../../functions/bbcode.php';
require_once '../../functions/query/select.php';

$numArt = $_GET['id'] ?? null;
if (!$numArt) {
    header('Location: index.php');
    exit;
}

$art = selectOne('ARTICLE', 'numArt', $numArt);
if (!$art) {
    die('Article introuvable');
}
?>

<?php
require_once '../../functions/motcle.php';

// ... récupération de l'article ...

$motscles = getMotsClesArticle($numArt);
?>

<!-- Ajouter après le contenu de l'article -->
<?php if (!empty($motscles)): ?>
    <div class="mt-4">
        <h5>Mots-clés :</h5>
        <div>
            <?php foreach ($motscles as $mc): ?>
                <a href="recherche.php?motcle=<?= urlencode($mc['numMotCle']) ?>" 
                   class="badge bg-primary me-2 mb-2 text-decoration-none" 
                   style="font-size: 1rem; padding: 0.5rem 1rem;">
                    <?= htmlspecialchars($mc['libMotCle']) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($art['libTltArt']) ?></title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Titre -->
        <h1><?= htmlspecialchars($art['libTltArt']) ?></h1>
        <!-- Après le titre, avant le chapô -->
<?php if ($art['urlPhotArt']): ?>
    <img src="../../../src/uploads/<?= htmlspecialchars($art['urlPhotArt']) ?>" 
         alt="<?= htmlspecialchars($art['libTltArt']) ?>" 
         class="img-fluid mb-4">
<?php endif; ?>
        <!-- Chapô -->
        <p class="lead"><?= bbcode_to_html($art['libChapArt']) ?></p>
        
        <!-- Accroche -->
        <?php if ($art['libAccrochArt']): ?>
            <blockquote class="blockquote">
                <?= bbcode_to_html($art['libAccrochArt']) ?>
            </blockquote>
        <?php endif; ?>
        
        <!-- Paragraphe 1 -->
        <div class="mt-4">
            <?= bbcode_to_html($art['parag1Art']) ?>
        </div>
        
        <!-- Titre éditorial -->
        <?php if ($art['libSsTitl1Art']): ?>
            <h3 class="mt-4"><?= htmlspecialchars($art['libSsTitl1Art']) ?></h3>
        <?php endif; ?>
        
        <!-- Paragraphe 2 -->
        <?php if ($art['parag2Art']): ?>
            <div class="mt-3">
                <?= bbcode_to_html($art['parag2Art']) ?>
            </div>
        <?php endif; ?>
        
        <!-- Paragraphe 3 -->
        <?php if ($art['parag3Art']): ?>
            <div class="mt-3">
                <?= bbcode_to_html($art['parag3Art']) ?>
            </div>
        <?php endif; ?>
        
        <!-- Conclusion -->
        <?php if ($art['libConcArt']): ?>
            <div class="mt-4 alert alert-secondary">
                <strong>Conclusion :</strong>
                <?= bbcode_to_html($art['libConcArt']) ?>
            </div>
        <?php endif; ?>
        
        <hr>
        <p class="text-muted">
            Publié le <?= date('d/m/Y à H:i', strtotime($art['dtCreaArt'])) ?>
        </p>
    </div>
</body>
</html>

<?php
session_start();
require_once '../../functions/auth.php';
require_once '../../functions/bbcode.php';
// ... code existant ...
?>

<!-- Après le contenu de l'article -->
<hr class="my-5">

<h3>Commentaires</h3>

<!-- Formulaire de commentaire (si connecté) -->
<?php if (isLoggedIn()): ?>
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
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
            
            <form method="POST" action="../../api/comments/create.php">
                <?php csrfField(); ?>
                <input type="hidden" name="numArt" value="<?= $numArt ?>">
                
                <div class="mb-3">
                    <label class="form-label">Votre commentaire</label>
                    <textarea name="libCom" 
                              class="form-control" 
                              rows="5" 
                              required 
                              placeholder="Partagez votre avis..."></textarea>
                    <small class="form-text text-muted">
                        Vous pouvez utiliser le BBCode : [b]gras[/b], [i]italique[/i], etc.
                    </small>
                </div>
                
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-info">
        Vous devez être <a href="../security/login.php">connecté</a> pour poster un commentaire.
    </div>
<?php endif; ?>

<!-- Liste des commentaires validés -->
<?php
// Récupérer les commentaires validés
$sql = "SELECT c.*, m.pseudoMemb, m.prenomMemb
        FROM COMMENT c
        INNER JOIN MEMBRE m ON c.numMemb = m.numMemb
        WHERE c.numArt = ? AND c.attModOK = 1 AND c.dtDelLogCom IS NULL
        ORDER BY c.dtCreaCoM DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$numArt]);
$commentaires = $stmt->fetchAll();
?>

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
                    <?= date('d/m/Y à H:i', strtotime($com['dtCreaCoM'])) ?>
                </span>
            </div>
            <div class="card-body">
                <?= bbcode_to_html($com['libCom']) ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php
session_start();
require_once '../../functions/auth.php';
// ... code existant ...

// Récupérer le nombre de likes
$sqlLikes = "SELECT COUNT(*) as nb_likes FROM LIKEART WHERE numArt = ? AND likeA = 1";
$stmtLikes = $pdo->prepare($sqlLikes);
$stmtLikes->execute([$numArt]);
$nbLikes = $stmtLikes->fetch()['nb_likes'];

// Vérifier si l'utilisateur a déjà liké
$userLiked = false;
if (isLoggedIn()) {
    $sqlUserLike = "SELECT likeA FROM LIKEART WHERE numMemb = ? AND numArt = ?";
    $stmtUserLike = $pdo->prepare($sqlUserLike);
    $stmtUserLike->execute([$_SESSION['user']['numMemb'], $numArt]);
    $userLike = $stmtUserLike->fetch();
    $userLiked = $userLike && $userLike['likeA'] == 1;
}
?>


<?php endif; ?>