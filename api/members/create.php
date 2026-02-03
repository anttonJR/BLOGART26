<?php
session_start();

require_once '../../config.php';
require_once '../../functions/csrf.php';

global $DB;
if (!$DB) {
    $_SESSION['errors'] = ["Connexion à la base de données impossible."];
    header('Location: ../../views/frontend/security/signup.php');
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (!verifyCSRFToken($token)) {
    $_SESSION['errors'] = ["Token CSRF invalide"];
    header('Location: ../../views/frontend/security/signup.php');
    exit;
}

require_once '../../functions/query/insert.php';
require_once '../../functions/query/select.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['errors'] = ["Méthode non autorisée"];
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
} else {
    $sql = "SELECT COUNT(*) as count FROM MEMBRE WHERE eMailMemb = ?";
    $stmt = $DB->prepare($sql);
    $stmt->execute([$eMailMemb]);
    $result = $stmt->fetch();
    
    if ($result['count'] > 0) {
        $errors[] = "Cet email est déjà utilisé";
    }
}

// === 5. VALIDATION PASSWORD ===
$passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$/';

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

// === 7. VALIDATION reCAPTCHA ===
if (isset($_POST['g-recaptcha-response'])) {
    $recaptchaToken = $_POST['g-recaptcha-response'];
    
    if (empty($recaptchaToken)) {
        $errors[] = "Token reCAPTCHA vide";
    } else {
        $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? getenv('RECAPTCHA_SECRET_KEY');
        
        if (empty($secretKey)) {
            $errors[] = "Clé secrète reCAPTCHA non configurée";
        } else {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $data = [
                'secret' => $secretKey,
                'response' => $recaptchaToken
            ];
            
            $options = [
                'http' => [
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'method' => 'POST',
                    'content' => http_build_query($data),
                    'timeout' => 10
                ]
            ];
            
            $context = stream_context_create($options);
            $result = @file_get_contents($url, false, $context);
            
            if ($result === false) {
                $errors[] = "Impossible de contacter le serveur reCAPTCHA";
            } else {
                $response = json_decode($result);
                
                if (!$response || empty($response->success)) {
                    if (isset($response->{'error-codes'}) && in_array('timeout-or-duplicate', $response->{'error-codes'}, true)) {
                        $errors[] = "Token reCAPTCHA expiré. Rechargez la page et réessayez.";
                    } else {
                        $errors[] = "Validation reCAPTCHA échouée. Réessayez.";
                    }
                } elseif (isset($response->score) && $response->score < 0.5) {
                    $errors[] = "Score reCAPTCHA trop bas. Réessayez.";
                }
            }
        }
    }
} else {
    $errors[] = "Validation reCAPTCHA manquante";
}

// === 8. SI ERREURS, RETOUR AU FORMULAIRE ===
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
$data = [
    'numMemb' => $numMemb,
    'prenomMemb' => $prenomMemb,
    'nomMemb' => $nomMemb,
    'pseudoMemb' => $pseudoMemb,
    'eMailMemb' => $eMailMemb,
    'passMemb' => $passMemb_hashed,
    'dtCreaMemb' => date('Y-m-d H:i:s'),
    'dtMajMemb' => null,
    'accordMemb' => 1,
    'numStat' => 1
];

try {
    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    
    $sql = "INSERT INTO MEMBRE ($columns) VALUES ($placeholders)";
    $stmt = $DB->prepare($sql);
    
    foreach ($data as $key => $value) {
        $stmt->bindValue(":$key", $value);
    }
    
    $result = $stmt->execute();
    
    if ($result) {
        $_SESSION['success'] = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        header('Location: ../../views/frontend/security/login.php');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['errors'] = ["Erreur lors de l'inscription : " . $e->getMessage()];
    header('Location: ../../views/frontend/security/signup.php');
    exit;
}
?>
