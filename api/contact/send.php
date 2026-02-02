<?php
session_start();
require_once '../../functions/csrf.php';

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    die('Token CSRF invalide');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/frontend/contact.php');
    exit;
}

// Récupération
$nom = trim($_POST['nom'] ?? '');
$email = trim($_POST['email'] ?? '');
$sujet = trim($_POST['sujet'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
$errors = [];

if (empty($nom)) {
    $errors[] = "Le nom est obligatoire";
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email invalide";
}

if (empty($sujet)) {
    $errors[] = "Le sujet est obligatoire";
}

if (empty($message) || strlen($message) < 10) {
    $errors[] = "Le message doit contenir au moins 10 caractères";
}

if (!empty($errors)) {
    $_SESSION['contact_error'] = implode(', ', $errors);
    header('Location: ../../views/frontend/contact.php');
    exit;
}

// Envoi de l'email (simulé)
// En production, utiliser mail() ou une bibliothèque comme PHPMailer
$to = "contact@blogart.fr";
$headers = "From: $email";
$fullMessage = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";

// mail($to, $sujet, $fullMessage, $headers);

$_SESSION['contact_success'] = "Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.";
header('Location: ../../views/frontend/contact.php');
exit;
?>