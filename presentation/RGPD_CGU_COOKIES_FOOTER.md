# RGPD / CGU / Cookies / Footer — Fonctionnement concret

Date : 04/02/2026

---

## 1) Fichier `views/frontend/rgpd/rgpd.php`

### Structure PHP du fichier
```
<?php
$pageTitle = "Politique de Confidentialité - BlogArt";
require_once dirname(__DIR__) . '/includes/header.php';
?>

[... HTML de la page ...]

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
```

**Explication :**
- `$pageTitle` : variable qui sera utilisée dans le header pour le `<title>` de la page.
- `dirname(__DIR__)` : remonte d'un dossier (de `/rgpd/` vers `/frontend/`) pour inclure le header.
- `require_once` : inclut le fichier une seule fois (évite les doublons).

### Bootstrap utilisé

**Grille et mise en page :**
- `row justify-content-center` : crée une ligne et centre son contenu horizontalement.
- `col-lg-10` : sur grand écran, la colonne prend 10/12 de la largeur (laisse des marges).

**Card (boîte blanche) :**
- `card border-0 shadow-sm` : carte sans bordure avec ombre légère.
- `card-body p-5` : contenu de la carte avec padding de 5 (grand espace intérieur).

**Tableau des données :**
- `table table-bordered` : tableau avec bordures sur toutes les cellules.
- `thead` avec style inline : en-tête bordeaux avec texte blanc.

**Alertes :**
- `alert alert-info` : bandeau bleu d'information en haut.

**Icônes Bootstrap Icons :**
- `bi bi-shield-check` : icône bouclier (titre).
- `bi bi-info-circle` : icône info (alerte).
- `bi bi-check-circle text-success` : icône check verte (liste des droits).

**Bouton :**
- `btn btn-bordeaux` : bouton avec classe custom (définie dans le CSS du site).

### PHP dynamique dans le HTML
```php
<?= date('d/m/Y') ?>
```
→ Affiche la date du jour au format "04/02/2026".

```php
<?= ROOT_URL ?>/views/frontend/contact.php
```
→ `ROOT_URL` est une constante définie dans config.php (ex: `/BLOGART26`), utilisée pour construire les liens absolus.

---

## 2) Fichier `views/frontend/rgpd/cgu.php`

### Structure PHP du fichier
Identique à rgpd.php :
```
<?php
$pageTitle = "Conditions Générales d'Utilisation - BlogArt";
require_once dirname(__DIR__) . '/includes/header.php';
?>

[... HTML de la page ...]

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
```

### Bootstrap utilisé

**Même structure que rgpd.php :**
- `row justify-content-center` + `col-lg-10` : contenu centré.
- `card border-0 shadow-sm` + `card-body p-5` : boîte blanche avec ombre.

**Sections :**
- Chaque section est une balise `<section class="mb-5">` : margin-bottom de 5 (espace entre sections).

**Titres :**
- `<h2 style="color: #800000;">` : titres en bordeaux (couleur du thème).

**Listes :**
- `<ul>` + `<li>` : listes à puces standard Bootstrap.

**Icône titre :**
- `bi bi-file-text` : icône document.

### PHP dynamique
Même chose que rgpd.php :
- `<?= date('d/m/Y') ?>` pour la date.
- `<?= ROOT_URL ?>` pour les liens.

---

## 3) Fichier `includes/libs/cookie-consent.php`

### Structure PHP du fichier
```php
<?php
if (!isset($_COOKIE['cookie_consent'])) {
?>
    [... HTML du bandeau ...]
    [... JavaScript ...]
<?php
}
?>
```

**Explication :**
- `$_COOKIE['cookie_consent']` : variable PHP qui contient la valeur du cookie si il existe.
- `!isset()` : vérifie si le cookie N'existe PAS.
- Si le cookie n'existe pas → le PHP génère le HTML du bandeau.
- Si le cookie existe → le PHP ne génère rien (bandeau invisible).

### Bootstrap utilisé

**Positionnement :**
- `position-fixed` : le bandeau reste fixe même quand on scroll.
- `bottom-0` : collé en bas de l'écran.
- `start-0` : commence à gauche.
- `w-100` : prend 100% de la largeur.

**Couleurs :**
- `bg-dark` : fond noir/gris foncé.
- `text-white` : texte blanc.

**Espacement :**
- `p-3` : padding de 3 tout autour.

**Grille dans le bandeau :**
- `container` : centre le contenu avec marges.
- `row align-items-center` : ligne avec éléments centrés verticalement.
- `col-md-8` : texte prend 8/12 sur écran moyen+.
- `col-md-4 text-end` : boutons prennent 4/12, alignés à droite.

**Boutons :**
- `btn btn-success` : bouton vert (Accepter).
- `btn btn-secondary` : bouton gris (Refuser).
- `me-2` : margin-end de 2 (espace entre les boutons).

### JavaScript dans le fichier
```javascript
function acceptCookies() {
    document.cookie = "cookie_consent=accepted; max-age=31536000; path=/";
    document.getElementById('cookie-consent').style.display = 'none';
}

function refuseCookies() {
    document.cookie = "cookie_consent=refused; max-age=31536000; path=/";
    document.getElementById('cookie-consent').style.display = 'none';
}
```

**Explication :**
- `document.cookie = "..."` : crée un cookie côté navigateur.
- `cookie_consent=accepted` : nom et valeur du cookie.
- `max-age=31536000` : durée en secondes (= 1 an).
- `path=/` : le cookie est valable pour tout le site.
- `style.display = 'none'` : cache le bandeau immédiatement après le clic.

---

## 4) Fichier `views/frontend/includes/footer.php`

### Structure PHP/HTML du fichier
```php
    </div>
</main>

<footer class="mt-5 py-4" style="background-color: #12120c; color: #f4f1ea;">
    <div class="container">
        <div class="row">
            [... 3 colonnes ...]
        </div>
        <hr style="border-color: #8f7f5e;">
        <div class="text-center">
            <p class="mb-0">© <?= date('Y') ?> Blog'Art - Tous droits réservés</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

### Bootstrap utilisé

**Footer :**
- `mt-5` : margin-top de 5 (espace au-dessus du footer).
- `py-4` : padding vertical (haut et bas) de 4.

**Container et grille :**
- `container` : centre le contenu.
- `row` : ligne pour les 3 colonnes.
- `col-md-4` : chaque colonne prend 4/12 (= 1/3) sur écran moyen+.

**Texte :**
- `text-center` : centre le texte.
- `mb-0` : margin-bottom de 0 (colle le texte).
- `text-light` : texte clair (pour les liens).
- `text-decoration-none` : enlève le soulignement des liens.
- `list-unstyled` : liste sans puces.

**Séparateur :**
- `<hr>` : ligne horizontale, stylée en doré avec `border-color: #8f7f5e`.

**Icônes réseaux sociaux :**
- `bi bi-facebook`, `bi bi-twitter`, `bi bi-instagram` : icônes Bootstrap Icons.
- `me-3` : margin-end de 3 (espace entre icônes).

### PHP dynamique
```php
<?= date('Y') ?>
```
→ Affiche l'année en cours (2026). Se met à jour automatiquement chaque année.

```php
<?= ROOT_URL ?>/views/frontend/rgpd/cgu.php
```
→ Construit le lien vers la page CGU en utilisant ROOT_URL.

### Script Bootstrap
```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
```
→ Charge le JavaScript de Bootstrap (nécessaire pour les dropdowns, modals, etc.).

---

## Résumé des classes Bootstrap utilisées

| Classe | Effet |
|--------|-------|
| `container` | Centre le contenu avec marges auto |
| `row` | Crée une ligne de grille |
| `col-md-X` | Colonne de X/12 sur écran moyen+ |
| `justify-content-center` | Centre horizontalement |
| `align-items-center` | Centre verticalement |
| `card` | Boîte avec bordure et fond |
| `shadow-sm` | Petite ombre |
| `p-X` / `m-X` | Padding / Margin (1 à 5) |
| `mb-X` / `mt-X` / `me-X` | Margin bottom / top / end |
| `text-center` / `text-end` | Alignement texte |
| `btn btn-success/secondary` | Boutons vert / gris |
| `position-fixed` | Position fixe (scroll) |
| `bottom-0` / `start-0` | Collé en bas / à gauche |
| `w-100` | Largeur 100% |
| `bg-dark` / `text-white` | Fond sombre / texte blanc |
