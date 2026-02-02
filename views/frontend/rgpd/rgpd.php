<?php
include '../includes/cookie-consent.php';
$pageTitle = 'Politique de confidentialité - BlogArt';
include '../includes/header.php';
?>

<div class="container mt-5">
    <h1>Politique de confidentialité (RGPD)</h1>
    <p class="text-muted">Dernière mise à jour : <?= date('d/m/Y') ?></p>
    
    <div class="mt-4">
        <h3>1. Responsable du traitement</h3>
        <p>
            Le responsable du traitement des données personnelles est Blog'Art (MMI28).
        </p>
        
        <h3 class="mt-4">2. Données collectées</h3>
        <p>Nous collectons les données suivantes lors de l'inscription :</p>
        <ul>
            <li>Pseudo</li>
            <li>Prénom et nom</li>
            <li>Adresse email</li>
            <li>Mot de passe (crypté)</li>
        </ul>
        
        <h3 class="mt-4">3. Finalité du traitement</h3>
        <p>Vos données sont utilisées pour :</p>
        <ul>
            <li>Gérer votre compte utilisateur</li>
            <li>Publier vos commentaires et likes</li>
            <li>Vous envoyer des notifications (si activées)</li>
            <li>Assurer la sécurité du site</li>
        </ul>
        
        <h3 class="mt-4">4. Base légale</h3>
        <p>
            Le traitement de vos données repose sur votre consentement explicite donné lors de l'inscription (case RGPD).
        </p>
        
        <h3 class="mt-4">5. Durée de conservation</h3>
        <p>
            Vos données sont conservées tant que votre compte est actif. Vous pouvez demander la suppression de votre compte à tout moment.
        </p>
        
        <h3 class="mt-4">6. Vos droits</h3>
        <p>Conformément au RGPD, vous disposez des droits suivants :</p>
        <ul>
            <li><strong>Droit d'accès :</strong> obtenir une copie de vos données</li>
            <li><strong>Droit de rectification :</strong> corriger vos données</li>
            <li><strong>Droit à l'effacement :</strong> supprimer votre compte</li>
            <li><strong>Droit d'opposition :</strong> refuser le traitement de vos données</li>
            <li><strong>Droit à la portabilité :</strong> récupérer vos données</li>
        </ul>
        
        <h3 class="mt-4">7. Cookies</h3>
        <p>
            Nous utilisons des cookies pour améliorer votre expérience. Vous pouvez accepter ou refuser les cookies 
            via la popup affichée lors de votre première visite.
        </p>
        
        <h3 class="mt-4">8. Sécurité</h3>
        <p>
            Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos données 
            (cryptage des mots de passe, protection contre les injections SQL, etc.).
        </p>
        
        <h3 class="mt-4">9. Contact</h3>
        <p>
            Pour exercer vos droits ou pour toute question, contactez-nous via notre <a href="../contact.php">formulaire de contact</a>.
        </p>
    </div>
</div>

<?php include '../includes/footer.php'; ?>