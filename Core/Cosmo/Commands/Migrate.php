<?php

namespace Core\Cosmo\Commands;

use Core\Core\Log\Log;
use Core\Cosmo\Cosmo;
use Core\Database\Schema;
use Core\Helpers\ClassManager;
use Core\Helpers\FileDirManager;
use Monolog\Level;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'migrate',
    description: 'This command run the Migration files.'
)]
class Migrate extends Command
{
    private array $ran_migrations = [];
    private const MIGRATION_ROOT_PATH = 'App\\Migrations\\';
    private mixed $step = null;
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();

        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp('Run migrate to execute migration files.')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'This is the file to migrate');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loadRanMigration();
        $this->setCurrentStep();

        $this->cosmo->start($output, true, true);

        $this->cosmo->title('migration', 'run');

        $files = FileDirManager::retrieveFilesByDirectory(self::MIGRATION_ROOT_PATH);

        $this->cosmo->indexRow('migration', 'status');

        $file_name = $input->getOption('name');

        if ($file_name) {
            if (!in_array($file_name, $this->ran_migrations)) {
                include self::MIGRATION_ROOT_PATH . $file_name;
                $classes = get_declared_classes();
                $count = count($classes) - 2;
                $class = $classes[$count];

                ClassManager::callStaticFunction($class, 'up');

                Schema::insert('migrations', [
                    'migration' => "$file_name",
                    'step' => $this->step
                ])->get();

                $this->cosmo->fileSuccessRow($file_name, 'run');
            } else {
                $_SERVER['COMMAND'] = 'php cosmo migrate ' . $file_name;
                Log::make('Migration ' . $file_name . ' already ran', Level::Notice->value);
                $this->cosmo->fileFailRow($file_name, 'already ran');
            }
        } else {
            $index = 1;
            foreach ($files as $file) {
                if (!in_array($file, $this->ran_migrations)) {
                    include self::MIGRATION_ROOT_PATH . $file;
                    $classes = get_declared_classes();
                    if ($index === 1) {
                        $count = count($classes) - 2;
                    } else {
                        $count = count($classes) - 1;
                    }

                    $index++;
                    $class = $classes[$count];

                    ClassManager::callStaticFunction($class, 'up');
                    $this->cosmo->fileSuccessRow($file, 'run');

                    Schema::insert('migrations', [
                        'migration' => "$file",
                        'step' => $this->step
                    ])->get();
                } else {
                    $this->cosmo->fileFailRow($file, 'already ran');
                }
            }
        }

        $this->cosmo->finish();

        $this->cosmo->commandSuccess('migration');

        return Command::SUCCESS;
    }

    private function loadRanMigration()
    {
        foreach (Schema::select('migrations')->get() as $migration) {
            $this->ran_migrations[] = $migration['migration'];
        }
    }

    private function setCurrentStep()
    {
        $last_ran_migration = Schema::last('migrations');

        if ($last_ran_migration === false) {
            $this->step = 0;
        } else {
            $this->step = Schema::last('migrations')['step'];
            $this->step += 1;
        }
    }
}
