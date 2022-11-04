<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Helpers\CommandMounter;
use Core\Helpers\FileDirManager;
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
    private const SEEDER_ROOT_PATH = 'App\\Seeder\\';

    public function __construct(string|null $file_name = null)
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true, true);
        $this->cosmo->indexRow('seeder', 'run');

        $this->file_names = $input->getArgument('seed');

        $files = FileDirManager::retrieveFilesByDirectory('./App/Seeder');

        if ($this->file_names) {
            foreach ($this->file_names as $file_name) {
                $file_name .= '.php';

                if (in_array($file_name, $files)) {
                    echo CommandMounter::mountMethodCallerCommand(
                        self::SEEDER_ROOT_PATH . $file_name,
                        'up');

                    $this->cosmo->fileSuccessRow($file_name, 'run');
                } else {
                    $this->cosmo->fileFailRow($file_name, 'not found');
                }
            }
        } else {
            foreach ($files as $file) {
                echo CommandMounter::mountMethodCallerCommand(
                    self::SEEDER_ROOT_PATH . $file,
                    'up');

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
