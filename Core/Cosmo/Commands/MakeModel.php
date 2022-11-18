<?php

namespace Core\Cosmo\Commands;

use Core\Core\Log\Log;
use Core\Cosmo\Cosmo;
use Core\Helpers\ClassManager;
use Core\Helpers\FileDirManager;
use Core\Helpers\StringFormatter;
use Monolog\Level;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:model',
    description: 'Create a new Model class.'
)]
class MakeModel extends Command
{
    protected string|null $file_name = null;
    private const MODEL_ROOT_PATH = 'App\\Models\\';
    private const MODEL_DUMMY = 'MountModel';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\model.php';
    private const MODEL_TABLE_NAME = 'table_name';
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
        $this->cosmo->title('model', 'create');
        $this->cosmo->indexRow('model', 'status');

        $class_name = $input->getArgument('model');

        if (!FileDirManager::fileExistInDirectory("$class_name.php", self::MODEL_ROOT_PATH)) {

            $class_name = StringFormatter::retrieveCamelCase($class_name);

            $table_name = $this->getDefaultTableName($class_name);

            FileDirManager::createFileByTemplate(
                $class_name . '.php',
                self::MODEL_ROOT_PATH,
                self::CONTENT_PATH,
                [
                    self::MODEL_DUMMY => $class_name,
                    self::MODEL_TABLE_NAME => $table_name
                ]
            );

            $this->cosmo->fileSuccessRow($class_name, 'created');
        } else {
            Log::make('Model ' . $class_name . ' already exist', Level::Notice->value);
            $this->cosmo->fileFailRow($class_name, 'already exist');
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('creation');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Model.')
            ->addArgument('model', InputArgument::REQUIRED, 'New Model class name');
    }

    private function getDefaultTableName(string $class_name): string
    {
        $class_name = explode('\\', $class_name);
        $class_name = $class_name[count($class_name) - 1];
        $class_name = StringFormatter::retrieveSnakeCase($class_name);
        return StringFormatter::pluralize($class_name);
    }
}
