<?php
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Maintenant tu définis tes constantes en utilisant $_ENV
define("DB",
    "mysql:host=" . $_ENV['DB_HOST'] .
    ";dbname=" . $_ENV['DB_NAME']);
define("USER", $_ENV['DB_USER']);
define("PWD", $_ENV['DB_PASS']);
?>
