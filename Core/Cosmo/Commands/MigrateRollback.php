<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Database\Schema;
use Core\Helpers\ClassManager;
use Core\Helpers\CommandMounter;
use Core\Helpers\FileDirManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'migrate:rollback',
    description: 'This command rollback the last step Migration files.'
)]
class MigrateRollback extends Command
{
    protected string $file_name = '';
    private array $rollback_migrations = [];
    private array $ran_migrations = [];
    private mixed $step = null;
    private Cosmo $cosmo;
    private const MIGRATION_ROOT_PATH = 'App\\Migrations\\';

    public function __construct()
    {
        parent::__construct();
        $this->cosmo = new Cosmo();
    }

    protected function configure()
    {
        $this->setHelp('Run rollback migration to execute migration down.')
            ->addOption('filename', null, InputOption::VALUE_OPTIONAL, 'Select the migration to rollback')
            ->addOption('step', null, InputOption::VALUE_OPTIONAL, 'Select the step to rollback');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);
        $this->cosmo->title('migration', 'rollback');
        $this->cosmo->indexRow('migration', 'status');

        foreach (Schema::select('migrations')->make() as $migration) {
            $this->ran_migrations[] = $migration['migration'];
        };

        $required_step = $input->getOption('step') ?? null;
        $this->file_name = $input->getOption('filename') ?? '';

        if ($required_step !== null) {
            foreach (Schema::select('migrations')->where('step', $required_step)->make() as $migration) {
                $this->rollback_migrations[] = $migration['migration'];
            }
        }

        if (empty($this->rollback_migrations) && $this->file_name === '') {
            $this->getLastStep();
            if ($this->step === null) {
                $this->cosmo->fileFailRow('nothing to rollback', 'fail');
                $this->cosmo->finish();
                $this->cosmo->commandFail('nothing ran');
            } else {
                foreach (Schema::select('migrations')->where('step', $this->step)->make() as $migration) {
                    $this->rollback_migrations[] = $migration['migration'];
                }

                $this->rollbackMigrationsByStep();
                $this->cosmo->finish();
                $this->cosmo->commandSuccess('rollback');
            }
        } elseif ($this->file_name !== '') {
            if (in_array($this->file_name, FileDirManager::retrieveFilesByDirectory(self::MIGRATION_ROOT_PATH))) {
                if (in_array($this->file_name, $this->ran_migrations)) {
                    include self::MIGRATION_ROOT_PATH . $this->file_name;
                    $classes = get_declared_classes();
                    $count = count($classes) - 2;
                    $class = $classes[$count];

                    ClassManager::callStaticFunction($class, 'down');

                    Schema::delete('migrations', 'migration', $this->file_name);
                    $this->cosmo->fileSuccessRow($this->file_name, 'rollback');
                } else {
                    $this->cosmo->fileSuccessRow($this->file_name, 'not rain');
                }
                $this->cosmo->finish();
                $this->cosmo->commandSuccess('rollback');
            } else {
                $this->cosmo->finish();
                $this->cosmo->commandFail('not found');
            }
        } elseif (!empty($this->rollback_migrations)) {
            $this->rollbackMigrationsByStep();
            $this->cosmo->finish();
            $this->cosmo->commandSuccess('rollback');
        }

        return Command::SUCCESS;
    }

    private function getLastStep()
    {
        $this->step = empty(Schema::last('migrations')) ? null : Schema::last('migrations')[0]['step'];
    }

    private function rollbackMigrationsByStep()
    {
        foreach ($this->rollback_migrations as $migration) {
            if (in_array($migration, $this->ran_migrations)) {
                include self::MIGRATION_ROOT_PATH . $migration;
                $classes = get_declared_classes();
                $count = count($classes) - 2;
                $class = $classes[$count];

                ClassManager::callStaticFunction($class, 'down');

                Schema::delete('migrations', 'migration', $migration);

                $this->cosmo->fileSuccessRow($migration, 'rollback');
            } else {
                $this->cosmo->fileFailRow($migration, 'not rain');
            }
        }
    }
}
