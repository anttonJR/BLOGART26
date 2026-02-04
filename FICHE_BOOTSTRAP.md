# Fiche projet — basée sur le Cahier des Charges (PDF I, II, III)

Objectif : avoir une fiche **claire**, **simple** et **complète** pour expliquer le projet Blog’Art 2026.
On répartit tout le CdC en **5 personnes**, de manière équitable, et chaque personne explique :
- ce qu’elle a fait (avec les contraintes du CdC)
- ce qui se passe côté **back** et côté **front**
- les points importants (CIR/FK, RGPD, sessions, etc.)

---

## Avant de commencer (à dire en 10 secondes)
“Notre site a 2 parties : un **frontend** pour lire les articles et interagir, et un **backend admin** pour gérer tout le contenu. Toutes les actions passent par des scripts PHP (CRUD).”

---

## Personne 1 — CRUD “Référentiels” (STATUT, THEMATIQUE, MOTCLE) + règles de suppression (CIR)

### Mission (simple)
“Je gère les tables simples qui servent partout : statuts, thématiques, mots-clés. Et je m’assure que les suppressions respectent les contraintes (FK/CIR).”

### Ce que j’ai fait (très concret)
- J’ai fait les 4 écrans CRUD pour :
	- **STATUT** (admin/modérateur/membre)
	- **THEMATIQUE** (catégories d’articles)
	- **MOTCLE** (tags)
- Pour chaque suppression, je fais **toujours** :
	1) afficher l’élément avant suppression (pas “à l’aveugle”)
	2) vérifier les **CIR** : si une autre table dépend (FK), je bloque et j’affiche un message
	3) afficher un message clair : “suppression OK” ou “impossible car utilisé”

### CRUD (traduction CdC)
- Create : créer un statut/thématique/mot-clé (champs obligatoires)
- Read : afficher la liste + afficher l’élément avant suppression
- Update : modifier (champs obligatoires)
- Delete : supprimer seulement si aucune FK ne bloque

### Où ça se voit
- Endpoints : dossiers [api/statuts](api/statuts), [api/thematiques](api/thematiques), [api/keywords](api/keywords)
- Vues admin : dossiers [views/backend/statuts](views/backend/statuts), [views/backend/thematiques](views/backend/thematiques), [views/backend/keywords](views/backend/keywords)

### Mini script oral
“Moi je gère les référentiels : statuts, thématiques, mots-clés. J’ai fait le CRUD complet, et surtout les suppressions propres : on affiche avant, on vérifie les contraintes de la base, et on affiche un message si c’est bloqué.”

---

## Personne 2 — CRUD ARTICLE (les étapes du CdC) : thématique, BBCode, image upload, et suppression propre

### Mission (simple)
“Je gère tout ce qui concerne les articles : création, modification, suppression, avec thématique, BBCode et image, exactement comme demandé dans le CdC.”

### Ce que j’ai fait (en suivant les étapes du CdC)
Insertion d’un article (back) :
1) **Champs standards obligatoires** (titre, chapo, paragraphes…)
                                      UPDATE: Formulaire de modification (tous les champs modifiables)
                                      DELETE: Affichage avant suppression (tous les champs en disabled)



2) **BBCode** dans les textarea (ancres / liens)
              Utiliser les expressions régulières (REGEX)



3) **Choix de la thématique** via listbox (on enregistre la FK)
                              UPDATE: Afficher la thématique sélectionnée (checked)
                              DELETE: Afficher la thématique (disabled)



4) **Upload d’image** :
-Créer le formulaire d'upload (formats autorisés : JPG, PNG, GIF)
-Générer un nom unique pour l'image (à la volée)
-INSERT: Uploader l'image dans src/uploads/
-Enregistrer le nom de l'image en BDD
-UPDATE: Afficher l'image actuelle en miniature
-DELETE: Afficher l'image en miniature

5) **crud membre update** :
-il manque le css lors de la creation de membre 
-toute les contrainte sont fonctionnelle 
-tout ce connecte bien 

7) **crud mot clef insert**
-ca concerner plus Table de jointure MOTCLEARTICLE
- ya bien Minimum 3 mots-clés par article
-NSERT dans MOTCLEARTICLE (FK article + FK mot-clé)
- delet a bien etais fait aussi 




### Points CdC importants que j’ai respectés
- “Pas de suppression à l’aveugle” : affichage avant delete
- “Pas d’images orphelines” : supprimer le fichier lors d’un delete / update d’image

### Où ça se voit
- Vues admin articles : dossier [views/backend/articles](views/backend/articles)
- Endpoints articles : dossier [api/articles](api/articles)
- Upload image : [functions/upload.php](functions/upload.php)
- BBCode : [functions/bbcode.php](functions/bbcode.php)

### Mini script oral
“Moi j’ai fait les articles. J’ai suivi les étapes du CdC : champs obligatoires, BBCode, choix thématique, puis upload d’image dans `src/uploads`. En update, si on change l’image, on supprime l’ancienne. En delete, on affiche avant et on supprime aussi l’image pour ne pas laisser de fichiers orphelins.”

---

## Personne 3 — CRUD MEMBRE + inscription/connexion (front & back) + validations CdC + RGPD + reCAPTCHA

### Mission (simple)
“Je gère les comptes : création, connexion, sécurité des mots de passe, RGPD et anti-robot.”

### Ce que j’ai fait (conforme CdC)
Création de compte (front + back) :
- Pseudo : 6 à 70 caractères + **unique**
- Prénom / nom : obligatoires
- Date de création : automatique
- Email : validé + confirmation identique
- Mot de passe : validé par regex (8–15, maj/min/chiffre/spécial) + confirmation identique
- Stockage : **password_hash** en base
- RGPD : obligé d’accepter (sinon refus)
- Anti-robot : **reCAPTCHA**

Connexion (front + back) :
- login via pseudo + password
- vérification par **password_verify**

Suppression membre (back) :
- interdiction de supprimer l’admin
- suppression du membre + suppression de ses commentaires (CdC)

### Sessions (CdC)
- Mise en place des sessions pour garder la connexion entre les pages.

### Où ça se voit
- Inscription (front) : [views/frontend/security/signup.php](views/frontend/security/signup.php)
- Endpoint création membre : [api/members/create.php](api/members/create.php)
- Login/logout : dossier [api/security](api/security) + dossier [views/frontend/security](views/frontend/security)
- Rôles/droits : [functions/auth.php](functions/auth.php)

### Point à paramétrer (important)
- reCAPTCHA : il faut mettre les vraies clés (site + secret) dans la config/.env (sinon ça reste une démo).

### Mini script oral
“Moi j’ai fait les comptes : inscription et connexion. On valide le pseudo (taille + unique), email (double saisie), mot de passe (regex) puis on hash avec `password_hash`. On demande l’accord RGPD et un reCAPTCHA anti-robot. Ensuite on gère les sessions pour rester connecté.”

---

## Personne 4 — COMMENTAIRES : ajout côté front + modération côté back + suppression logique/physique

### Mission (simple)
“Je gère les commentaires : les membres commentent sur le front, et l’admin/modérateur valide sur le back.”

### Ce que j’ai fait (conforme CdC)
Insert commentaire (front) :
- uniquement si membre **connecté**
- sur un article choisi
- texte obligatoire + date auto
- support BBCode (ancres, etc.)
- commentaire **non visible** tant qu’il n’est pas modéré

Modération (back = update) :
- valider ou refuser
- date de modération auto
- si refus : notification possible (raison)

Suppression :
- suppression **logique** possible (archive/corbeille) + date
- suppression **physique** possible (admin)

### Où ça se voit
- Ajout commentaire (endpoint) : [api/comments/create.php](api/comments/create.php)
- Modération : [views/backend/moderation/comments.php](views/backend/moderation/comments.php) + [api/comments/moderate.php](api/comments/moderate.php)
- Suppression physique : [api/comments/delete.php](api/comments/delete.php)
- BBCode : [functions/bbcode.php](functions/bbcode.php)

### Mini script oral
“Moi j’ai géré les commentaires. Sur le front, un membre connecté peut commenter, et son commentaire est en attente. Sur le back, le modérateur ou l’admin valide ou refuse, avec une date et éventuellement une notification. On peut aussi archiver (suppression logique) ou supprimer définitivement.”

---

## Personne 5 — LIKE + recherche + pages obligatoires (cookies, contact, CGU/RGPD) + intégration UI

### Mission (simple)
“Je gère les interactions simples et les pages obligatoires : like, recherche, cookies, contact, CGU/RGPD, et l’intégration dans l’UI.”

### Ce que j’ai fait
Likes (front) :
- un membre connecté peut liker un article
- si on reclique : ça fait un **toggle** (like / unlike)

Recherche (front/back) :
- recherche par mot-clé, thématique, ou texte (selon les pages)

Cookies (CdC) :
- pop-up d’acceptation/refus (l’utilisateur doit répondre)

Pages front “obligatoires” :
- formulaire de contact
- CGU + Politique de confidentialité (RGPD)

### Où ça se voit
- Toggle like : [api/likes/toggle.php](api/likes/toggle.php)
- Pages recherche : [views/frontend/search.php](views/frontend/search.php) + [views/frontend/articles/recherche.php](views/frontend/articles/recherche.php)
- Cookie consent : [includes/libs/cookie-consent.php](includes/libs/cookie-consent.php) et [views/frontend/includes/cookie-consent.php](views/frontend/includes/cookie-consent.php)
- Contact : [views/frontend/contact.php](views/frontend/contact.php)
- CGU/RGPD : dossier [views/frontend/rgpd](views/frontend/rgpd)

### Mini script oral
“Moi j’ai géré les interactions et pages obligatoires : le like en toggle, la recherche, la pop-up cookies, et les pages contact + CGU/RGPD. Je les ai intégrées dans la navigation pour que ce soit accessible partout.”

---

## Conclusion (phrase équipe)
“On a respecté le CdC : CRUD back (statut/thématique/mot-clé/article), inscription/connexion front+back avec validations, commentaires avec modération, likes, recherche, sessions et cookies + pages RGPD/CGU.”


