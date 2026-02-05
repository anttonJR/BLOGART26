<?php
session_start();
require_once '../../config.php';
require_once '../../functions/query/insert.php';
require_once '../../functions/query/select.php';

global $DB;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/frontend/security/signup.php');
    exit;
}

// === 1. RÉCUPÉRATION DES DONNÉES ===
$pseudoMemb = trim($_POST['pseudoMemb'] ?? '');
$prenomMemb = trim($_POST['prenomMemb'] ?? '');
$nomMemb = trim($_POST['nomMemb'] ?? '');
$eMailMemb = trim($_POST['eMailMemb'] ?? '');
$eMailMemb_confirm = trim($_POST['eMailMemb_confirm'] ?? '');
$passMemb = $_POST['passMemb'] ?? '';
$passMemb_confirm = $_POST['passMemb_confirm'] ?? '';
$accordMemb = $_POST['accordMemb'] ?? 0;

$errors = [];

// === 2. VALIDATION DU PSEUDO ===
if (empty($pseudoMemb)) {
    $errors[] = "Le pseudo est obligatoire";
} elseif (strlen($pseudoMemb) < 6) {
    $errors[] = "Le pseudo doit contenir au moins 6 caractères";
} elseif (strlen($pseudoMemb) > 70) {
    $errors[] = "Le pseudo ne peut pas dépasser 70 caractères";
} else {
    // Vérifier l'unicité du pseudo
    $sql = "SELECT COUNT(*) as count FROM MEMBRE WHERE pseudoMemb = ?";
    $stmt = $DB->prepare($sql);
    $stmt->execute([$pseudoMemb]);
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        $errors[] = "Ce pseudo est déjà utilisé";
    }
}

// === 3. VALIDATION PRÉNOM ET NOM ===
if (empty($prenomMemb)) {
    $errors[] = "Le prénom est obligatoire";
}

if (empty($nomMemb)) {
    $errors[] = "Le nom est obligatoire";
}

// === 4. VALIDATION EMAIL ===
if (empty($eMailMemb)) {
    $errors[] = "L'email est obligatoire";
} elseif (!filter_var($eMailMemb, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "L'email n'est pas valide";
} elseif ($eMailMemb !== $eMailMemb_confirm) {
    $errors[] = "Les deux emails ne correspondent pas";
}

// === 5. VALIDATION PASSWORD ===
$passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/';

if (empty($passMemb)) {
    $errors[] = "Le mot de passe est obligatoire";
} elseif (!preg_match($passwordRegex, $passMemb)) {
    $errors[] = "Le mot de passe doit contenir entre 8 et 15 caractères, dont au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial";
} elseif ($passMemb !== $passMemb_confirm) {
    $errors[] = "Les deux mots de passe ne correspondent pas";
}

// === 6. VALIDATION RGPD ===
if ($accordMemb != 1) {
    $errors[] = "Vous devez accepter le stockage de vos données pour vous inscrire";
}

// === 7. VALIDATION reCAPTCHA (optionnel si clés non configurées) ===
$recaptchaSecret = getenv('RECAPTCHA_SECRET_KEY');
if ($recaptchaSecret && $recaptchaSecret !== 'your_secret_key_here') {
    if (isset($_POST['g-recaptcha-response'])) {
        $token = $_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $recaptchaSecret,
            'response' => $token
        ];
        
        $options = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result);
        
        if (!$response->success) {
            $errors[] = "Validation reCAPTCHA échouée. Êtes-vous un robot ?";
        }
    } else {
        $errors[] = "Validation reCAPTCHA manquante";
    }
}

// === 8. SI ERREURSsss, RETOUR AU FORMULAIRE ===
// === 8. SI ERREURSsss, RETOUR AU FORMULAIRE ===
// === 8. SI ERREURSsss, RETOUR AU FORMULAIRE ===
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    $_SESSION['old_data'] = $_POST;
    header('Location: ../../views/frontend/security/signup.php');
    exit;
}

// === 9. CRYPTAGE DU MOT DE PASSE ===
$passMemb_hashed = password_hash($passMemb, PASSWORD_DEFAULT);

// === 10. GÉNÉRATION DU NUMÉRO DE MEMBRE ===
$sql = "SELECT MAX(numMemb) as max FROM MEMBRE";
$stmt = $DB->query($sql);
$result = $stmt->fetch();
$numMemb = ($result['max'] ?? 0) + 1;

// === 11. INSERTION EN BASE ===
try {
    $sql = "INSERT INTO MEMBRE (numMemb, prenomMemb, nomMemb, pseudoMemb, eMailMemb, passMemb, dtCreaMemb, accordMemb, numStat) 
            VALUES (:numMemb, :prenomMemb, :nomMemb, :pseudoMemb, :eMailMemb, :passMemb, :dtCreaMemb, :accordMemb, :numStat)";
    $stmt = $DB->prepare($sql);
    $stmt->execute([
        ':numMemb' => $numMemb,
        ':prenomMemb' => $prenomMemb,
        ':nomMemb' => $nomMemb,
        ':pseudoMemb' => $pseudoMemb,
        ':eMailMemb' => $eMailMemb,
        ':passMemb' => $passMemb_hashed,
        ':dtCreaMemb' => date('Y-m-d H:i:s'),
        ':accordMemb' => 1,
        ':numStat' => 3
    ]);
    
    $_SESSION['success'] = "Votre compte a été créé avec succès ! Bienvenue " . htmlspecialchars($prenomMemb) . " 🎉 Vous pouvez maintenant vous connecter.";
    header('Location: ../../views/frontend/security/login.php');
} catch (Exception $e) {
    $_SESSION['error'] = "Erreur lors de l'inscription : " . $e->getMessage();
    header('Location: ../../views/frontend/security/signup.php');
}
exit;
?>
