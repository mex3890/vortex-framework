<?php

namespace Core\Cosmo\Commands;

use Core\Core\Log\Log;
use Core\Cosmo\Cosmo;
use Core\Database\migrations\MigrationsTable;
use Dotenv\Dotenv;
use Exception;
use Monolog\Level;
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
    private int $steps = 0;
    private const SUCCESS_STEP_COUNT = 6;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true);
        $this->cosmo->title('Vortex', 'Install');

        $steps_show = [
            $this->loadEnvironment(),
            $this->runFirstsMigrations(),
            $this->setDefaultTimeZone(),
            $this->composerInstall(),
            $this->npmInstall(),
            $this->npmCompile()
        ];

        $this->cosmo->indexRow('step', 'status');

        foreach ($steps_show as $step) {
            if ($step[0] === 1) {
                $this->cosmo->fileSuccessRow($step[1], 'success');
            } else {
                $this->cosmo->fileFailRow($step[1], 'failed');
            }
        }

        $this->cosmo->finish();

        if ($this->steps === self::SUCCESS_STEP_COUNT) {
            $this->cosmo->commandSuccess('installation');
        } else {
            $this->cosmo->commandFail('installation');
        }

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run vortex:install to set the first configurations in your project.');
    }

    private function runFirstsMigrations(): array
    {
        try {
            MigrationsTable::up();
            $this->steps++;
            return [1, 'first migrations'];
        } catch (Exception $exception) {
            Log::make('Fail on Run First Migrations', Level::Notice->value);
            return [0, 'first migrations'];
        }
    }

    private function loadEnvironment(): array
    {
        try {
            $env = Dotenv::createImmutable(__DIR__ . '/../../../../../../');
            $env->load();
            $this->steps++;
            return [1, 'load environment'];
        } catch (Exception $exception) {
            Log::make('Fail on load Environment Variables', Level::Notice->value);
            return [0, 'load environment'];
        }
    }

    private function setDefaultTimeZone(): array
    {
        try {
            date_default_timezone_set($_ENV['TIME_ZONE'] ?? 'America/Sao_Paulo');
            $this->steps++;
            return [1, 'set time zone'];
        } catch (Exception $exception) {
            Log::make('Fail on Set Default Time Zone', Level::Notice->value);
            return [0, 'set time zone'];
        }
    }

    private function npmInstall(): array
    {
        try {
            shell_exec('npm install');
            $this->steps++;
            return [1, 'npm install'];
        } catch (Exception $exception) {
            Log::make('Fail on install npm dependencies [command => npm install]', Level::Notice->value);
            return [0, 'npm install'];
        }
    }

    private function npmCompile(): array
    {
        try {
            shell_exec('npm run vortex');
            $this->steps++;
            return [1, 'npm compile'];
        } catch (Exception $exception) {
            Log::make('Fail on compile assets, [command => npm run vortex]', Level::Notice->value);
            return [0, 'npm compile'];
        }
    }

    private function composerInstall(): array
    {
        try {
            shell_exec('composer install');
            $this->steps++;
            return [1, 'composer install'];
        } catch (Exception $exception) {
            Log::make('Fail on install Composer dependencies, [command => composer install]', Level::Notice->value);
            return [0, 'composer install'];
        }
    }
}
