# 🍪 Système de Consentement aux Cookies

## Fichier concerné
`includes/libs/cookie-consent.php`

---

## Comment ça marche ?

### Étape 1 : Vérification PHP
```php
if (!isset($_COOKIE['cookie_consent'])) {
    // Affiche la bannière
}
```
➡️ On vérifie si l'utilisateur a déjà fait son choix (cookie existant ou non)

---

### Étape 2 : Affichage de la bannière (HTML + Bootstrap)

```html
<div id="cookie-consent" class="position-fixed bottom-0 start-0 w-100 bg-dark text-white p-3">
```

| Classe Bootstrap | Ce qu'elle fait |
|------------------|-----------------|
| `position-fixed` | Reste fixe à l'écran |
| `bottom-0` | Collée en bas |
| `start-0` | Collée à gauche |
| `w-100` | Largeur 100% |
| `bg-dark` | Fond noir |
| `text-white` | Texte blanc |
| `p-3` | Padding de 1rem |

**Les 2 boutons :**
- ✅ `btn btn-success` → Bouton vert "Accepter"
- ⬜ `btn btn-secondary` → Bouton gris "Refuser"

---

### Étape 3 : Actions JavaScript

#### Fonction Accepter
```javascript
function acceptCookies() {
    document.cookie = "cookie_consent=accepted; max-age=31536000; path=/";
    document.getElementById('cookie-consent').style.display = 'none';
}
```

#### Fonction Refuser
```javascript
function refuseCookies() {
    document.cookie = "cookie_consent=refused; max-age=31536000; path=/";
    document.getElementById('cookie-consent').style.display = 'none';
}
```

**Paramètres du cookie :**
| Paramètre | Valeur | Signification |
|-----------|--------|---------------|
| Nom | `cookie_consent` | Identifiant du cookie |
| Valeur | `accepted` ou `refused` | Choix de l'utilisateur |
| `max-age` | `31536000` | Durée de vie = 1 an (en secondes) |
| `path` | `/` | Valide sur tout le site |

---

## Schéma du fonctionnement

```
┌─────────────────────────────────────────┐
│         Utilisateur arrive              │
└─────────────────┬───────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────┐
│   Cookie "cookie_consent" existe ?      │
└─────────────────┬───────────────────────┘
                  │
        ┌─────────┴─────────┐
        │                   │
       NON                 OUI
        │                   │
        ▼                   ▼
┌───────────────┐   ┌───────────────┐
│   Afficher    │   │  Ne rien      │
│   bannière    │   │  afficher     │
└───────┬───────┘   └───────────────┘
        │
        ▼
┌───────────────────────────────────────┐
│   Clic sur Accepter ou Refuser        │
└───────────────────┬───────────────────┘
                    │
                    ▼
┌───────────────────────────────────────┐
│  1. Créer cookie (valide 1 an)        │
│  2. Cacher la bannière                │
└───────────────────────────────────────┘
```

---

## Où est inclus ce fichier ?

Dans `views/frontend/includes/footer.php` :
```php
require_once dirname(dirname(dirname(__DIR__))) . '/includes/libs/cookie-consent.php';
```

➡️ La bannière apparaît sur **toutes les pages** du site (via le footer)

---

## Conformité RGPD

✅ Choix clair (Accepter / Refuser)  
✅ Pas de case pré-cochée  
✅ Lien vers la page RGPD pour plus d'infos  
✅ Consentement enregistré (cookie)  
