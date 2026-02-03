<?php
echo "<h1>Test de connexion MySQL</h1>";

// Test 1: Variables d'environnement
echo "<h2>1. Variables d'environnement du .env</h2>";
require_once __DIR__ . '/includes/libs/DotEnv.php';
(new DotEnv(__DIR__.'/.env'))->load();

echo "<pre>";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_USER: " . getenv('DB_USER') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') === false ? 'FALSE' : "'" . getenv('DB_PASSWORD') . "'") . "\n";
echo "DB_DATABASE: " . getenv('DB_DATABASE') . "\n";
echo "</pre>";

// Test 2: Constantes définies
echo "<h2>2. Constantes SQL_*</h2>";
require_once __DIR__ . '/config/defines.php';
echo "<pre>";
echo "SQL_HOST: " . SQL_HOST . "\n";
echo "SQL_USER: " . SQL_USER . "\n";
echo "SQL_PWD: '" . SQL_PWD . "' (longueur: " . strlen(SQL_PWD) . ")\n";
echo "SQL_DB: " . SQL_DB . "\n";
echo "</pre>";

// Test 3: Tentative de connexion
echo "<h2>3. Tentative de connexion</h2>";
try {
    $pdo = new PDO(
        'mysql:host=' . SQL_HOST . ';dbname=' . SQL_DB . ';charset=utf8',
        SQL_USER,
        SQL_PWD,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "<p style='color: green;'>✓ Connexion réussie !</p>";
    
    // Test requête
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM ARTICLE");
    $result = $stmt->fetch();
    echo "<p>Nombre d'articles dans la base : " . $result['total'] . "</p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Erreur : " . $e->getMessage() . "</p>";
    echo "<p>Essayez avec un mot de passe vide ou vérifiez votre configuration MySQL UwAmp</p>";
}

// Test 4: Fichier .env
echo "<h2>4. Contenu du fichier .env</h2>";
echo "<pre>";
echo htmlspecialchars(file_get_contents(__DIR__ . '/.env'));
echo "</pre>";
?>
