<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Database\migrations\MigrationsTable;
use Dotenv\Dotenv;
use Exception;
use PDOException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'vortex:install',
    description: 'This command install Vortex.'
)]
class VortexInstall extends Command
{
    private Cosmo $cosmo;

    public function __construct(string|null $file_name = null)
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true);
        $this->cosmo->title('Vortex', 'Install');
        $this->cosmo->indexRow('step', 'status');
        $this->loadEnvironment();
        $this->runFirstsMigrations();
        $this->setDefaultTimeZone();
        $this->cosmo->finish();
        $this->cosmo->commandSuccess('list');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run vortex:install to set the first configurations in your project.');
    }

    private function runFirstsMigrations(): void
    {
        try {
            MigrationsTable::up();
            $this->cosmo->fileSuccessRow('first migrations', 'success');
        } catch (PDOException $exception) {
            $this->cosmo->fileFailRow('first migrations', 'failed');
        }
    }

    private function loadEnvironment()
    {
        try {
            $env = Dotenv::createImmutable(__DIR__ . '/../../../../../../');
            $env->load();
            $this->cosmo->fileSuccessRow('load environment', 'success');
        } catch (Exception $exception) {
            $this->cosmo->fileFailRow('load environment', 'failed');
        }
    }

    private function setDefaultTimeZone()
    {
        try {
            date_default_timezone_set($_ENV['TIME_ZONE'] ?? 'America/Sao_Paulo');
            $this->cosmo->fileSuccessRow('set time zone', 'success');
        } catch (Exception $exception) {
            $this->cosmo->fileFailRow('set time zone', 'failed');
        }
    }
}
