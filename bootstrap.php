<?php

use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use function FastRoute\simpleDispatcher;
use function DI\autowire;


require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/exceptionHandler.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$paths = [__DIR__ . '/src/Entity'];
$isDevMode = true;

$dbParams = [
    'driver'   => $_ENV['DATABASE_DRIVER'],
    'user'     => $_ENV['DATABASE_USER'],
    'password' => $_ENV['DATABASE_PASSWORD'],
    'dbname'   => $_ENV['DATABASE_NAME'],
    'host'   => $_ENV['DATABASE_HOST'],
    'port'   => $_ENV['DATABASE_PORT'],
];

$config = ORMSetup::createAttributeMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection($dbParams, $config);
$entityManager = new EntityManager($connection, $config);

$containerBuilder = new ContainerBuilder();

$containerBuilder->addDefinitions([
    'App\\Controller\\' => autowire(),
    'App\\Service\\' => autowire(),
    'Doctrine\\ORM\\EntityManager' => $entityManager,
]);

$container = $containerBuilder->build();

$routes = require __DIR__ . '/config/routes.php';
$dispatcher = simpleDispatcher($routes);

