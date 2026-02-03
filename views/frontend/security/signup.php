<?php
session_start();
require_once '../../../config.php';
require_once ROOT . '/functions/csrf.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Millésime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $_ENV['RECAPTCHA_SITE_KEY']; ?>"></script>
    <style>
        :root {
            --beige-light: #f4f1ea;
            --beige-medium: #e8e3d6;
            --bordeaux: #800000;
            --black: #12120c;
            --gold: #8f7f5e;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--beige-light);
            color: var(--black);
        }

        .signup-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border-top: 4px solid var(--bordeaux);
        }

        h1 {
            color: var(--bordeaux);
            margin-bottom: 30px;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--bordeaux);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--black);
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <h1><i class="bi bi-person-plus"></i> Inscription</h1>
            
            <?php if (isset($_SESSION['errors'])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <p class="mb-0"><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
            <?php endif; ?>
            
            <form method="POST" action="/BLOGART26/api/members/create.php">
                <?php csrfField(); ?>
                
                <div class="mb-3">
                    <label class="form-label">Pseudo *</label>
                    <input type="text" 
                           name="pseudoMemb" 
                           class="form-control" 
                           required 
                           minlength="6" 
                           maxlength="70"
                           pattern="^[a-zA-Z0-9_-]{6,70}$">
                    <small class="form-text text-muted">6 à 70 caractères (lettres, chiffres, _ et -)</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prénom *</label>
                        <input type="text" name="prenomMemb" class="form-control" required maxlength="70">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nomMemb" class="form-control" required maxlength="70">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email *</label>
                    <input type="email" name="eMailMemb" class="form-control" required maxlength="100">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirmer l'email *</label>
                    <input type="email" name="eMailMemb_confirm" class="form-control" required maxlength="100">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mot de passe *</label>
                    <input type="password" 
                           name="passMemb" 
                           class="form-control" 
                           required 
                           minlength="8"
                           maxlength="15">
                    <small class="form-text text-muted">
                        8 à 15 caractères avec au moins 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial
                    </small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe *</label>
                    <input type="password" name="passMemb_confirm" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input type="radio" name="accordMemb" value="1" id="rgpd_oui" class="form-check-input" required>
                        <label class="form-check-label" for="rgpd_oui">
                            J'accepte le stockage de mes données personnelles (RGPD) *
                        </label>
                    </div>
                    <div class="form-check">
                        <input type="radio" name="accordMemb" value="0" id="rgpd_non" class="form-check-input">
                        <label class="form-check-label" for="rgpd_non">
                            Je refuse
                        </label>
                    </div>
                </div>
                
                <input type="hidden" name="g-recaptcha-response" id="recaptchaResponse">
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-check-circle"></i> S'inscrire
                </button>
                
                <div class="text-center">
                    <a href="/BLOGART26/views/frontend/security/login.php" class="text-decoration-none">Déjà inscrit ? Se connecter</a>
                    <br>
                    <a href="/BLOGART26/index.php" class="text-decoration-none">Retour à l'accueil</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function(e) {
            if (form.dataset.submitting === '1') {
                return;
            }

            e.preventDefault();
            form.dataset.submitting = '1';
            submitBtn.disabled = true;

            grecaptcha.ready(function() {
                grecaptcha.execute('<?php echo $_ENV['RECAPTCHA_SITE_KEY']; ?>', {action: 'signup'}).then(function(token) {
                    document.getElementById('recaptchaResponse').value = token;
                    form.submit();
                }).catch(function() {
                    form.dataset.submitting = '0';
                    submitBtn.disabled = false;
                });
            });
        });
    </script>
</body>
</html>
