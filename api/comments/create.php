<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

require_once '../../functions/auth.php';
require_once '../../functions/query/insert.php';

// Vérifier que l'utilisateur est connecté
if (!isLoggedIn()) {
    $_SESSION['comment_error'] = "Vous devez être connecté pour commenter";
    header('Location: ' . $_SERVER['HTTP_REFERER'] ?? '../../views/frontend/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/frontend/index.php');
    exit;
}

// Récupération des données
$numArt = $_POST['numArt'] ?? null;
$libCom = trim($_POST['libCom'] ?? '');
$numMemb = $_SESSION['user']['numMemb'];

// Validation
$errors = [];

if (!$numArt) {
    $errors[] = "Article non spécifié";
}

if (empty($libCom)) {
    $errors[] = "Le commentaire ne peut pas être vide";
}

if (strlen($libCom) < 10) {
    $errors[] = "Le commentaire doit contenir au moins 10 caractères";
}

if (!empty($errors)) {
    $_SESSION['comment_error'] = implode(', ', $errors);
    header('Location: ../../views/frontend/articles/article1.php?id=' . $numArt);
    exit;
}

// Génération du numéro de commentaire
$pdo = getConnection();
$sql = "SELECT MAX(numCom) as max FROM COMMENT";
$stmt = $pdo->query($sql);
$result = $stmt->fetch();
$numCom = ($result['max'] ?? 0) + 1;

// Préparation des données
$data = [
    'numCom' => $numCom,
    'dtCreaCoM' => date('Y-m-d H:i:s'),
    'libCom' => $libCom,
    'dtModCom' => null,
    'attModOK' => 0,  // Non validé par défaut
    'notifComKOAff' => null,
    'dtDelLogCom' => null,
    'numMemb' => $numMemb,
    'numArt' => $numArt
];

try {
    $result = insert('COMMENT', $data);
    if ($result) {
        $_SESSION['comment_success'] = "Votre commentaire a été envoyé. Il sera visible après validation par un modérateur.";
    }
} catch (Exception $e) {
    $_SESSION['comment_error'] = "Erreur : " . $e->getMessage();
}

header('Location: ../../views/frontend/articles/article1.php?id=' . $numArt);
exit;
?>