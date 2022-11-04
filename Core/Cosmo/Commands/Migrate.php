<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Database\Schema;
use Core\Helpers\ClassManager;
use Core\Helpers\CommandMounter;
use Core\Helpers\FileDirManager;
use Core\Helpers\StringFormatter;
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
    private string|null $file_name;
    private array $ran_migrations = [];
    private const MIGRATION_ROOT_PATH = 'App\\Migrations\\';
    private mixed $step = null;
    private Cosmo $cosmo;

    public function __construct(string|null $file_name = null)
    {
        $this->cosmo = new Cosmo();
        $this->file_name = $file_name;
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

        $this->file_name = $input->getOption('name');

        if ($this->file_name) {
            if (!in_array($this->file_name, $this->ran_migrations)) {
                include self::MIGRATION_ROOT_PATH . $this->file_name;
                $classes = get_declared_classes();
                $class = end($classes);

                ClassManager::callStaticFunction($class, 'up');

                Schema::insert('migrations', [
                    'migration' => "$this->file_name",
                    'step' => $this->step
                ]);

                $this->cosmo->fileSuccessRow($this->file_name, 'run');
            } else {
                $this->cosmo->fileFailRow($this->file_name, 'already ran');
            }
        } else {
            foreach ($files as $file) {
                include self::MIGRATION_ROOT_PATH . $file;
                $classes = get_declared_classes();
                $class = end($classes);

                if (!in_array($file, $this->ran_migrations)) {
                    ClassManager::callStaticFunction($class, 'up');
                    $this->cosmo->fileSuccessRow($file, 'run');

                    Schema::insert('migrations', [
                        'migration' => "$file",
                        'step' => $this->step
                    ]);
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
        foreach (Schema::select('migrations')->make() as $migration) {
            $this->ran_migrations[] = $migration['migration'];
        };
    }

    private function setCurrentStep()
    {
        if (empty(Schema::last('migrations'))) {
            $this->step = 0;
        } else {
            $this->step = Schema::last('migrations')[0]['step'];
            $this->step += 1;
        }
    }
}
