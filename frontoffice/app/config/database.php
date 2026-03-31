<?php
// Configuration de la base de données

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'irannews');
define('DB_PORT', getenv('DB_PORT') ?: 3306);

// Configuration générale
define('APP_NAME', 'WarNews');
define('APP_URL', 'http://localhost');
define('APP_DEBUG', true);

// Sessions
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
