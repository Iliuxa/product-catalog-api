<?php

use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require_once __DIR__ . '/bootstrap.php';

$config = new PhpFile('migrations.php');
$dependencyFactory = DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));

$commands = [
    new DiffCommand($dependencyFactory),
    new ExecuteCommand($dependencyFactory),
    new MigrateCommand($dependencyFactory),
    new StatusCommand($dependencyFactory),
    new VersionCommand($dependencyFactory),
];

ConsoleRunner::run(
    new SingleManagerProvider($entityManager),
    $commands
);
