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
    name: 'make:service',
    description: 'Create a new Service class.'
)]
class MakeService extends Command
{
    protected string|null $file_name = null;
    private const SERVICE_ROOT_PATH = 'App\\Services\\';
    private const SERVICE_DUMMY = 'MountService';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\service.php';
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);
        $this->cosmo->title('service', 'create');
        $this->cosmo->indexRow('service', 'status');

        $class_name = $input->getArgument('service');

        if (!FileDirManager::fileExistInDirectory("$class_name.php", self::SERVICE_ROOT_PATH)) {

            $class_name = StringFormatter::retrieveCamelCase($class_name);

            FileDirManager::createFileByTemplate(
                $class_name . '.php',
                self::SERVICE_ROOT_PATH,
                self::CONTENT_PATH,
                [self::SERVICE_DUMMY => $class_name]
            );

            $this->cosmo->fileSuccessRow($class_name, 'created');
        } else {
            $_SERVER['COMMAND'] = 'php cosmo make:service ' . $class_name;
            Log::make('Service ' . $class_name . ' already exist', Level::Notice->value);
            $this->cosmo->fileFailRow($class_name, 'already exist');
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('creation');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Service.')
            ->addArgument('service', InputArgument::REQUIRED, 'New Service file name');
    }
}
