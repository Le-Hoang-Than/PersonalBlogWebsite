<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// SendGrid
define('SENDGRID_API_KEY', $_ENV['SENDGRID_API_KEY']);
define('FROM_EMAIL', $_ENV['FROM_EMAIL']);
define('FROM_NAME', $_ENV['FROM_NAME']);
define('TO_EMAIL', $_ENV['TO_EMAIL']);
define('TO_NAME', $_ENV['TO_NAME']);

// DB
define('DB_HOST', $_ENV['DB_HOST']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASS']);

