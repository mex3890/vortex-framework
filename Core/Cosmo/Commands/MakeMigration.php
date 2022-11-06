<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Helpers\DateTime;
use Core\Helpers\FileDirManager;
use Core\Helpers\StringFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:migration',
    description: 'Create a new Migration.'
)]
class MakeMigration extends Command
{
    protected string|null $file_name = null;
    private const MIGRATION_ROOT_PATH = 'App\\Migrations\\';
    private const MIGRATION_DUMMY = 'MountMigration';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\migration.php';
    private Cosmo $cosmo;

    public function __construct(string|null $file_name = null)
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
        $this->file_name = $file_name;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);
        $this->cosmo->title('migration', 'create');
        $this->cosmo->indexRow('migration', 'status');

        $class_name = $input->getArgument('migration');
        $migration_name = $this->mountMigrationName($class_name);

        if (!FileDirManager::fileExistInDirectory($migration_name, self::MIGRATION_ROOT_PATH)) {

            $class_name = StringFormatter::retrieveCamelCase($class_name);

            FileDirManager::createFileByTemplate(
                $migration_name,
                self::MIGRATION_ROOT_PATH,
                self::CONTENT_PATH,
                [self::MIGRATION_DUMMY => $class_name]
            );

            $this->cosmo->fileSuccessRow($migration_name, 'created');
        } else {
            $this->cosmo->fileFailRow($migration_name, 'already exist');
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('creation');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new migration.')
            ->addArgument('migration', InputArgument::REQUIRED, 'New migration file name');
    }

    private function mountMigrationName(string $migration_name): string
    {
        $migration_name = lcfirst($migration_name);
        return StringFormatter::retrieveSnakeCase(DateTime::currentDate() . "_$migration_name") . '.php';
    }
}
