<?php


use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

defined('BASE_URL') ?: define('BASE_URL', 'http://api');

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

defined('IS_DEBUG') ?: define('IS_DEBUG', $_ENV['APP_ENV'] === 'dev');

$paths = [__DIR__ . '/src/Entity'];
$dbParams = [
    'driver' => $_ENV['DATABASE_DRIVER'],
    'user' => $_ENV['DATABASE_USER'],
    'password' => $_ENV['DATABASE_PASSWORD'],
    'dbname' => $_ENV['DATABASE_NAME'],
    'host' => $_ENV['DATABASE_HOST'],
    'port' => $_ENV['DATABASE_PORT'],
];

$config = ORMSetup::createAttributeMetadataConfiguration($paths, IS_DEBUG);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);
return $entityManager;