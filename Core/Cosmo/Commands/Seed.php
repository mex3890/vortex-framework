<?php

namespace Core\Cosmo\Commands;

use Core\Core\Log\Log;
use Core\Cosmo\Cosmo;
use Core\Helpers\ClassManager;
use Core\Helpers\FileDirManager;
use Monolog\Level;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'seed',
    description: 'This command run seed files.'
)]
class Seed extends Command
{
    protected array|null $file_names;
    private Cosmo $cosmo;
    private const SEEDER_ROOT_PATH = 'App\\Seeds\\';

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);

        $this->cosmo->title('seeder', 'run');

        $this->cosmo->indexRow('seeder', 'run');

        $this->file_names = $input->getArgument('seed');

        $files = FileDirManager::retrieveFilesByDirectory(self::SEEDER_ROOT_PATH);

        if ($this->file_names) {
            foreach ($this->file_names as $file_name) {
                $file_name .= '.php';

                if (in_array($file_name, $files)) {
                    include self::SEEDER_ROOT_PATH . $file_name;
                    $classes = get_declared_classes();
                    $count = count($classes) - 2;
                    $class = $classes[$count];

                    ClassManager::callStaticFunction($class, 'handler');

                    $this->cosmo->fileSuccessRow($file_name, 'run');
                } else {
                    $_SERVER['COMMAND'] = 'php cosmo seed ' . $this->file_names;
                    Log::make('Seed ' . $file_name . ' not found', Level::Notice->value);
                    $this->cosmo->fileFailRow($file_name, 'not found');
                }
            }
        } else {
            $index = 1;
            foreach ($files as $file) {
                include self::SEEDER_ROOT_PATH . $file;
                $classes = get_declared_classes();
                if ($index === 1) {
                    $count = count($classes) - 2;
                } else {
                    $count = count($classes) - 1;
                }

                $index++;
                $class = $classes[$count];

                ClassManager::callStaticFunction($class, 'handler');

                $this->cosmo->fileSuccessRow($file, 'run');
            }
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('seeder');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run seeder to execute seed files.')
            ->addArgument('seed', InputArgument::IS_ARRAY, 'Seed file name');
    }
}
