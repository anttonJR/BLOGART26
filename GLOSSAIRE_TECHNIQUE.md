# Glossaire technique (simple) — Blog’Art 2026

Ce document explique **les termes techniques** qu’on utilise dans le projet, avec des exemples concrets.

---

## 1) CRUD
**CRUD** = les 4 actions de base sur une table :
- **Create** : créer (INSERT)
- **Read** : lire/afficher (SELECT)
- **Update** : modifier (UPDATE)
- **Delete** : supprimer (DELETE)

Exemple : CRUD ARTICLE = créer un article, afficher la liste, modifier, supprimer.

---

## 2) SQL (SELECT / INSERT / UPDATE / DELETE)
**SQL** = langage pour parler à la base de données.
- `SELECT` : récupérer des données
- `INSERT` : ajouter une ligne
- `UPDATE` : modifier une ligne
- `DELETE` : supprimer une ligne

---

## 3) Clé primaire (PK)
**PK = Primary Key** (clé primaire) = l’identifiant unique d’une ligne.
- C’est un champ **unique** et **obligatoire**.
- Exemple : `ARTICLE.numArt` identifie un article.

Image simple :
- Table ARTICLE
  - `numArt = 12` → c’est l’article 12, unique.

---

## 4) Clé étrangère (FK)
**FK = Foreign Key** (clé étrangère) = un champ qui pointe vers la PK d’une autre table.
- Ça sert à relier les tables entre elles.

Exemples (dans l’idée du projet) :
- Un article a une thématique : `ARTICLE.numThem` (FK) → `THEMATIQUE.numThem` (PK)
- Un commentaire appartient à un article : `COMMENT.numArt` (FK) → `ARTICLE.numArt` (PK)
- Un commentaire appartient à un membre : `COMMENT.numMemb` (FK) → `MEMBRE.numMemb` (PK)

Phrase simple : “Une FK c’est un **lien** vers une autre table.”

---

## 5) CIR / Contraintes d’intégrité référentielle (important)
**CIR** = règles de la base qui empêchent des incohérences.

Exemple concret :
- Si une thématique est utilisée par des articles, la base peut **interdire** de la supprimer.
- Sinon on aurait des articles avec une thématique qui n’existe plus.

Donc en code, avant un DELETE, on doit souvent :
1) vérifier si la valeur est utilisée ailleurs (FK)
2) afficher un message si c’est impossible

Phrase simple : “Les CIR empêchent de casser les liens entre tables.”

---

## 6) Table d’association (jointure) : MOTCLEARTICLE
Quand il y a une relation **plusieurs-à-plusieurs** :
- Un article peut avoir plusieurs mots-clés
- Un mot-clé peut être sur plusieurs articles

On crée une table d’association : `MOTCLEARTICLE`.
- Elle contient souvent 2 FK : `numArt` et `numMotCle`.

Exemple :
- (numArt=12, numMotCle=3)
- (numArt=12, numMotCle=7)

Phrase simple : “La table d’association stocke les tags d’un article.”

---

## 7) Suppression physique vs suppression logique (corbeille)
- **Suppression physique** : on supprime vraiment la ligne en base (`DELETE`).
- **Suppression logique** : on garde la ligne mais on la “cache” avec un champ (ex : `delLogiq = 1`) + une date.

Pourquoi ?
- On peut restaurer.
- On évite de perdre des données trop vite.

---

## 8) Regex (Expressions régulières)
### 8.1 Définition
Une **regex** (expression régulière) = une règle pour vérifier un texte.

On l’utilise pour :
- vérifier un email
- vérifier un mot de passe (taille + majuscule + chiffre…)
- vérifier un format

### 8.2 Exemple très simple
Regex “au moins 8 caractères” :
- on teste la longueur et/ou une regex.

### 8.3 Exemple mot de passe (comme dans le CdC)
Règle : 8 à 15 caractères + majuscule + minuscule + chiffre + caractère spécial.

En PHP, on voit souvent :
- `preg_match($regex, $password)`

Phrase simple : “La regex, c’est le contrôleur qui dit si le texte est valide.”

---

## 9) BBCode
### 9.1 Définition
**BBCode** = une mini-syntaxe pour mettre en forme du texte sans HTML.

Exemples :
- `[b]gras[/b]` → texte en gras
- `[i]italique[/i]` → italique
- `[anchor]section1[/anchor]` → ancre
- `[goto=section1]Aller[/goto]` → lien vers l’ancre

### 9.2 Pourquoi on l’utilise ?
- Plus simple pour les utilisateurs.
- On évite de laisser écrire directement du HTML (plus risqué).

### 9.3 Dans le projet
On convertit BBCode → HTML avec une fonction (souvent via regex).

Phrase simple : “BBCode = mise en forme simple, ensuite on le transforme en HTML.”

---

## 10) Upload d’image (et images “orphelines”)
### 10.1 Upload
**Upload** = envoyer un fichier depuis le navigateur vers le serveur.

Étapes classiques :
1) formulaire avec `enctype="multipart/form-data"`
2) PHP reçoit `$_FILES`
3) on vérifie le format/erreurs
4) on déplace le fichier avec `move_uploaded_file`

### 10.2 Image orpheline
Une image **orpheline** = un fichier qui reste sur le serveur alors qu’il n’est plus lié à aucun article.

Le CdC demande :
- si on remplace une image : supprimer l’ancienne
- si on supprime l’article : supprimer l’image

Phrase simple : “On évite de remplir le serveur avec des images inutiles.”

---

## 11) Hashage de mot de passe (password_hash / password_verify)
### 11.1 Définition
Un **hash** = une version transformée du mot de passe, impossible à “relire” en clair.

Pourquoi ?
- si quelqu’un vole la base, il ne voit pas les mots de passe.

### 11.2 Fonctions PHP
- `password_hash()` : crée le hash
- `password_verify()` : vérifie qu’un mot de passe correspond au hash

Phrase simple : “On ne stocke jamais un mot de passe en clair.”

---

## 12) Sessions
### 12.1 Définition
Une **session** = un stockage côté serveur qui garde l’utilisateur “reconnu” entre les pages.

Exemple :
- après login, on met l’utilisateur dans `$_SESSION`
- ensuite on sait qu’il est connecté

Phrase simple : “La session permet de rester connecté.”

---

## 13) Cookies
### 13.1 Définition
Un **cookie** = une petite info stockée dans le navigateur.

### 13.2 Cookie consent (CdC)
On affiche une pop-up pour accepter/refuser les cookies.
- on enregistre le choix dans un cookie (ex : `cookie_consent=accepted/refused`)

Phrase simple : “Le cookie retient le choix de l’utilisateur.”

---

## 14) CSRF (Token)
### 14.1 Définition
**CSRF** = attaque où quelqu’un tente de faire envoyer un formulaire à ta place.

### 14.2 Protection
On met un **token CSRF** dans le formulaire :
- généré côté serveur
- envoyé en champ caché
- vérifié au moment du POST

Phrase simple : “Le token prouve que le formulaire vient bien de notre site.”

---

## 15) reCAPTCHA (anti-robot)
**reCAPTCHA v3** = système Google pour détecter si c’est un humain.
- on reçoit un score (souvent on accepte si `>= 0.5`)

Dans le CdC, il est demandé pour :
- création de compte
- modification
- suppression

Phrase simple : “Ça bloque les robots qui créent des comptes automatiquement.”

---

## 16) Rôles (Admin / Modérateur / Membre)
- **Admin** : tous les droits (gestion complète)
- **Modérateur** : modération (ex : valider/refuser commentaires)
- **Membre** : interagir sur le front (commenter, liker)

Phrase simple : “Le rôle décide ce que tu peux faire.”
