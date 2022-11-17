<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Database\migrations\MigrationsTable;
use Dotenv\Dotenv;
use Exception;
use PDOException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'vortex:server',
    description: 'This command start php server.'
)]
class VortexServer extends Command
{
    private Cosmo $cosmo;

    public function __construct(string|null $file_name = null)
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true);
        $this->cosmo->title('Vortex', 'Server');

        try {
            shell_exec('composer dump-autoload');
            shell_exec('php -S localhost:8000 -t ' . __DIR__ . '/../../../../../../public/');
            $this->cosmo->commandSuccess('server');
        } catch (Exception $exception) {
            $this->cosmo->commandFail('server');
        }

        $this->cosmo->finish();

        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run vortex:server to start the php server.');
    }
}
