<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Créer un article</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Nouvel article</h1>
        
       <?php if (isset($_SESSION['errors'])): ?> <!-- Si la session contient des erreurs -->
            <div class="alert alert-danger"> <!-- Bloc d'alerte Bootstrap (style "erreur" rouge) -->
                <?php foreach ($_SESSION['errors'] as $error): ?> <!-- Parcourt chaque message d'erreur -->
                    <p><?= $error ?></p> <!-- Affiche l'erreur (raccourci de echo) -->
                <?php endforeach; ?> <!-- Fin de la boucle foreach -->
            </div>
            <?php unset($_SESSION['errors']); ?> <!-- Supprime les erreurs après affichage (message "flash") -->
        <?php endif; ?> <!-- Fin du if -->

        
        <form method="POST" action="../../api/articles/create.php">
            <!-- Numéro d'article -->
            <div class="mb-3">
                <label class="form-label">Numéro d'article *</label>
                <input type="number" 
                       name="numArt" 
                       class="form-control" 
                       required>
            </div>
            
            <!-- Titre -->
            <div class="mb-3">
                <label class="form-label">Titre de l'article *</label>
                <input type="text" 
                       name="libTltArt" 
                       class="form-control" 
                       maxlength="100" 
                       required>
            </div>
            
            <!-- Chapô -->
            <div class="mb-3">
                <label class="form-label">Chapô (introduction) *</label>
                <textarea name="libChapArt" 
                          class="form-control" 
                          rows="3" 
                          required></textarea>
            </div>
            
            <!-- Accroche -->
            <div class="mb-3">
                <label class="form-label">Accroche</label>
                <input type="text" 
                       name="libAccrochArt" 
                       class="form-control" 
                       maxlength="100">
            </div>
            
            <!-- Paragraphe 1 -->
            <div class="mb-3">
                <label class="form-label">Paragraphe 1 *</label>
                <textarea name="parag1Art" 
                          class="form-control" 
                          rows="5" 
                          required></textarea>
            </div>
            
            <!-- Titre éditorial -->
            <div class="mb-3">
                <label class="form-label">Titre éditorial (sous-titre)</label>
                <input type="text" 
                       name="libSsTitl1Art" 
                       class="form-control" 
                       maxlength="100">
            </div>
            
            <!-- Paragraphe 2 -->
            <div class="mb-3">
                <label class="form-label">Paragraphe 2</label>
                <textarea name="parag2Art" 
                          class="form-control" 
                          rows="5"></textarea>
            </div>
            
            <!-- Paragraphe 3 -->
            <div class="mb-3">
                <label class="form-label">Paragraphe 3</label>
                <textarea name="parag3Art" 
                          class="form-control" 
                          rows="5"></textarea>
            </div>
            
            <!-- Conclusion -->
            <div class="mb-3">
                <label class="form-label">Conclusion</label>
                <textarea name="libConcArt" 
                          class="form-control" 
                          rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Créer l'article</button>
            <a href="list.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>