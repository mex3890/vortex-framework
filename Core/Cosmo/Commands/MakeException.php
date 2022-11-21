<?php

namespace Core\Cosmo\Commands;

use Core\Core\Log\Log;
use Core\Cosmo\Cosmo;
use Core\Helpers\FileDirManager;
use Core\Helpers\StringFormatter;
use Monolog\Level;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:exception',
    description: 'Create a new Exception class.'
)]
class MakeException extends Command
{
    protected string|null $file_name = null;
    private const EXCEPTION_ROOT_PATH = 'App\\Exceptions\\';
    private const EXCEPTION_DUMMY = 'MountException';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\exception.php';
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
        $this->cosmo->title('exception', 'create');
        $this->cosmo->indexRow('exception', 'status');

        $class_name = $input->getArgument('exception');

        if (!FileDirManager::fileExistInDirectory("$class_name.php", self::EXCEPTION_ROOT_PATH)) {

            $class_name = StringFormatter::retrieveCamelCase($class_name);

            FileDirManager::createFileByTemplate(
                $class_name . '.php',
                self::EXCEPTION_ROOT_PATH,
                self::CONTENT_PATH,
                [self::EXCEPTION_DUMMY => $class_name]
            );

            $this->cosmo->fileSuccessRow($class_name, 'created');
        } else {
            $_SERVER['COMMAND'] = 'php cosmo make:exception ' . $class_name;
            Log::make('Exception ' . $class_name . ' already exist', Level::Notice->value);
            $this->cosmo->fileFailRow($class_name, 'already exist');
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('creation');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Exception.')
            ->addArgument('exception', InputArgument::REQUIRED, 'New Exception class name');
    }
}
