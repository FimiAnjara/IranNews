<?php
// Point d'entrée de l'application
header('Content-Type: text/html; charset=utf-8');
header('X-UA-Compatible: IE=edge');

require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../app/route/routes.php';