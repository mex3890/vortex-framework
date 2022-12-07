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
    name: 'make:seed',
    description: 'Create a new Seed class.'
)]
class MakeSeed extends Command
{
    protected string|null $file_name = null;
    private const SEED_ROOT_PATH = 'App\\Seeds\\';
    private const SEED_DUMMY = 'MountSeed';
    private const CONTENT_PATH = __DIR__ . '\\..\\..\\Stubs\\seed.php';
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);
        $this->cosmo->title('seed', 'create');
        $this->cosmo->indexRow('seed', 'status');

        $class_name = $input->getArgument('seed');

        if (!FileDirManager::fileExistInDirectory("$class_name.php", self::SEED_ROOT_PATH)) {

            $class_name = StringFormatter::retrieveCamelCase($class_name);

            FileDirManager::createFileByTemplate(
                $class_name . '.php',
                self::SEED_ROOT_PATH,
                self::CONTENT_PATH,
                [self::SEED_DUMMY => $class_name]
            );

            $this->cosmo->fileSuccessRow($class_name, 'created');
        } else {
            $_SERVER['COMMAND'] = 'php cosmo make:seed ' . $class_name;
            Log::make('Seed ' . $class_name . ' already exist', Level::Notice->value);
            $this->cosmo->fileFailRow($class_name, 'already exist');
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('creation');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Create a new Seed.')
            ->addArgument('seed', InputArgument::REQUIRED, 'New Seed class name');
    }
}
