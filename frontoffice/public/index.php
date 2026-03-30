<?php
// Point d'entree du frontoffice
header('Content-Type: text/html; charset=utf-8');
header('X-UA-Compatible: IE=edge');

$frontBaseUrl = getenv('FRONT_BASE_URL') ?: 'http://localhost:8050';
$backBaseUrl = getenv('BACK_BASE_URL') ?: 'http://localhost:8051';

define('FRONT_BASE_URL', rtrim($frontBaseUrl, '/'));
define('BACK_BASE_URL', rtrim($backBaseUrl, '/'));
define('APP_BASE_URL', FRONT_BASE_URL);
define('ADMIN_BASE_URL', BACK_BASE_URL);

require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/route/routes.php';
