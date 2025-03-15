<?php

use App\Service\DaDataService;
use DI\ContainerBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use function DI\autowire;
use function FastRoute\simpleDispatcher;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/exceptionHandler.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('IS_DEBUG', $_ENV['APP_ENV'] === 'dev');

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

$logger = new Logger('product_catalog_logger');
$logger->pushHandler(new StreamHandler(__DIR__ . '/var/log/product_catalog_api.log', IS_DEBUG ? Level::Debug : Level::Error));

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    'App\\Controller\\' => autowire(),
    'App\\Service\\' => autowire(),
    'Doctrine\\ORM\\EntityManager' => $entityManager,
]);
$container = $containerBuilder->build();
$container->set(DaDataService::class, fn() => new DaDataService($_ENV['DADATA_API_KEY']));
$container->set(Logger::class, fn() => $logger);

$routes = require __DIR__ . '/config/routes.php';
$dispatcher = simpleDispatcher($routes);

