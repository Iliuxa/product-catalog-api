<?php

namespace App\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Migrations\Tools\Console\Command\DoctrineCommand;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'fixtures:load', description: 'Loading fixtures.')]
class FixtureLoadCommand extends DoctrineCommand
{
    protected static $defaultName = 'fixtures:load';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $loader = new Loader();
            $loader->loadFromDirectory(dirname(__DIR__) . '/DataFixtures');

            $executor = new ORMExecutor($this->getDependencyFactory()->getEntityManager(), new ORMPurger());
            $executor->execute($loader->getFixtures());

            $output->writeln('<info>Фикстуры загружены успешно!</info>');
            return 0;
        } catch (Exception $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');
        }
    }
}