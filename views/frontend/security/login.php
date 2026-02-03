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
    <title>Connexion - Millésime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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

        .login-container {
            max-width: 450px;
            margin: 100px auto;
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
        <div class="login-container">
            <h1><i class="bi bi-person-circle"></i> Connexion</h1>
            
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
            
            <form method="POST" action="/BLOGART26/api/security/login.php">
                <?php csrfField(); ?>
                
                <div class="mb-3">
                    <label class="form-label">Pseudo</label>
                    <input type="text" 
                           name="pseudoMemb" 
                           class="form-control" 
                           required 
                           minlength="6" 
                           maxlength="70">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" 
                           name="passMemb" 
                           class="form-control" 
                           required 
                           minlength="8" 
                           maxlength="15">
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </button>
                
                <div class="text-center">
                    <a href="/BLOGART26/views/frontend/security/signup.php" class="text-decoration-none">Pas encore inscrit ? S'inscrire</a>
                    <br>
                    <a href="/BLOGART26/index.php" class="text-decoration-none">Retour à l'accueil</a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>