<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Helpers\FileDirManager;
use Core\Helpers\StringFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'make:controller',
    description: 'Create a new Controller class.'
)]
class MakeController extends Command
{
    protected string|null $file_name = null;
    private const CONTROLLER_ROOT_PATH = 'App\\Controllers\\';
    private const CONTROLLER_DUMMY = 'MountController';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\controller.php';
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
        $this->cosmo->title('controller', 'create');
        $this->cosmo->indexRow('controller', 'status');

        $class_name = $input->getArgument('controller');

        if (!FileDirManager::fileExistInDirectory("$class_name.php", self::CONTROLLER_ROOT_PATH)) {

            $class_name = StringFormatter::retrieveCamelCase($class_name);

            FileDirManager::createFileByTemplate(
                $class_name . '.php',
                self::CONTROLLER_ROOT_PATH,
                self::CONTENT_PATH,
                [self::CONTROLLER_DUMMY => $class_name]
            );

            $this->cosmo->fileSuccessRow($class_name, 'created');
        } else {
            $this->cosmo->fileFailRow($class_name, 'already exist');
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('creation');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Controller.')
            ->addArgument('controller', InputArgument::REQUIRED, 'New Controller file name');
    }
}
