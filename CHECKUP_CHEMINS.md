# Checkup des chemins - BlogArt26

## ✅ CHEMINS CORRECTS

### API Files
- `api/members/create.php` - Tous les chemins corrects
- `api/security/login.php` - Tous les chemins corrects
- `api/articles/create.php` - Chemins relatifs corrects
- `api/thematiques/*` - Chemins relatifs corrects
- `api/statuts/*` - Chemins relatifs corrects
- `api/keywords/*` - Chemins relatifs corrects
- `api/comments/*` - Chemins relatifs corrects
- `api/contact/send.php` - Chemins relatifs corrects

### Functions
- `functions/auth.php` - ✅ Corrigé avec `/BLOGART26/`

## ⚠️ PROBLÈMES DÉTECTÉS

### 1. header.php (Racine)
**Fichier:** `c:\UwAmp\www\BLOGART26\header.php`

```php
❌ Ligne 30: <a class="nav-link" href="/views/backend/dashboard.php">Admin</a>
   Devrait être: href="/BLOGART26/views/backend/dashboard.php"

❌ Ligne 39: <a class="btn btn-primary m-1" href="/views/backend/security/login.php">Login</a>
   Devrait être: href="/BLOGART26/views/frontend/security/login.php"

❌ Ligne 40: <a class="btn btn-dark m-1" href="/views/backend/security/signup.php">Sign up</a>
   Devrait être: href="/BLOGART26/views/frontend/security/signup.php"
```

### 2. Dashboard (views/backend/dashboard.php)
**Fichier:** `c:\UwAmp\www\BLOGART26\views\backend\dashboard.php`

**Liens du tableau (lignes 141-203) - TOUS manquent le préfixe /BLOGART26/**

```php
❌ Statuts:
   Ligne 141: href="/views/backend/statuts/list.php"
   → Devrait être: href="/BLOGART26/views/backend/statuts/list.php"
   
❌ Membres (lignes 153-156):
   href="/views/backend/members/*"
   → Devrait être: href="/BLOGART26/views/backend/members/*"
   
❌ Articles (lignes 163-166):
   href="/views/backend/articles/*"
   → Devrait être: href="/BLOGART26/views/backend/articles/*"
   
❌ Thématiques (lignes 173-176):
   href="/views/backend/thematiques/*"
   → Devrait être: href="/BLOGART26/views/backend/thematiques/*"
   
❌ Commentaires (lignes 183-186):
   href="/views/backend/comments/*"
   → Devrait être: href="/BLOGART26/views/backend/comments/*"
   
❌ Likes (lignes 193-196):
   href="/views/backend/likes/*"
   → Devrait être: href="/BLOGART26/views/backend/likes/*"
   
❌ Keywords (ligne 203):
   href="/views/backend/keywords/*"
   → Devrait être: href="/BLOGART26/views/backend/keywords/*"
```

**Note:** Les boutons en haut (lignes 110-112) utilisent des chemins relatifs `../` qui sont CORRECTS

### 3. Cookie Consent
**Fichier:** `c:\UwAmp\www\BLOGART26\includes\libs\cookie-consent.php`

```php
❌ Ligne 12: <a href="/views/frontend/rgpd/rgpd.php">En savoir plus</a>
   Devrait être: href="/BLOGART26/views/frontend/rgpd/rgpd.php"
```

### 4. Index.php (Racine)
**Vérification nécessaire** - Rechercher tous les liens href dans ce fichier

## 📝 RÉSUMÉ DES CORRECTIONS À FAIRE

### Actions prioritaires:

1. **header.php** (3 liens à corriger)
   - Lien Admin
   - Lien Login  
   - Lien Sign up

2. **dashboard.php** (environ 24 liens à corriger)
   - Tous les liens du tableau CRUD manquent `/BLOGART26/`

3. **cookie-consent.php** (1 lien à corriger)
   - Lien "En savoir plus"

4. **index.php** (à vérifier)
   - Rechercher tous les liens href="/views/"

## 🔧 SOLUTION GÉNÉRALE

Pour tous les liens ABSOLUS (commençant par `/`), ajouter le préfixe `/BLOGART26/`

**Exemple:**
```php
❌ href="/views/backend/dashboard.php"
✅ href="/BLOGART26/views/backend/dashboard.php"
```

**Les chemins relatifs (avec `../`) sont généralement corrects** et ne nécessitent pas de modification.

## ✅ DÉJÀ CORRIGÉS
- `views/frontend/security/login.php` - Chemin action formulaire ✅
- `views/frontend/security/signup.php` - Chemin action formulaire ✅
- `api/security/login.php` - Redirections ✅
- `functions/auth.php` - Redirections ✅
