<?php

namespace Core\Cosmo\Commands;

use Core\Core\Log\Log;
use Core\Cosmo\Cosmo;
use Core\Helpers\Environment;
use Exception;
use Monolog\Level;
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
    private const DEFAULT_SERVER_PORT = 8000;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->cosmo->start($output, true);
        $this->cosmo->title('Vortex', 'Server');

        $server_port = Environment::appLocalhostServerPort() ?? self::DEFAULT_SERVER_PORT;

        try {
            shell_exec('php -S localhost:' . $server_port . ' -t ' . __DIR__ . '/../../../../../../public/');
            $this->cosmo->commandSuccess('server');
        } catch (Exception $exception) {
            Log::make('Failed to up php server on localhost:' . $server_port, Level::Notice->value);
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
