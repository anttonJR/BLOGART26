<?php
// Paramètres de connexion
define('DB_HOST', 'localhost');
define('DB_NAME', 'blogart26');
define('DB_USER', 'root');  // Par défaut sur XAMPP
define('DB_PASS', '');      // Vide par défaut sur XAMPP
define('DB_CHARSET', 'utf8mb4');

// Test de connexion (à retirer en production)
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    echo "Connexion réussie à la base de données !";
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

//define ROOT_PATH
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('ROOT_URL', 'http://' . $_SERVER['HTTP_HOST']);

//Load env
require_once ROOT . '/includes/libs/DotEnv.php';
(new DotEnv(ROOT.'/.env'))->load();

//defines
require_once ROOT . '/config/defines.php';

//debug
if (getenv('APP_DEBUG') == 'true') {
    require_once ROOT . '/config/debug.php';
}

//load functions
require_once ROOT . '/functions/global.inc.php';

//load security
require_once ROOT . '/config/security.php';

?>