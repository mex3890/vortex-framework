<?php

namespace Core\Cosmo\Commands;

use Core\Cosmo\Cosmo;
use Core\Database\Schema;
use Core\Helpers\FileDirManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'migrate:list',
    description: 'This command list all Migrations.'
)]
class MigrateList extends Command
{
    private array $ran_migrations = [];
    private Cosmo $cosmo;

    public function __construct()
    {
        $this->cosmo = new Cosmo();
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (Schema::select('migrations')->get() as $migration) {
            $this->ran_migrations[] = $migration['migration'];
        }

        $this->cosmo->start($output, true, true);
        $this->cosmo->title('migration', 'list');
        $this->cosmo->indexRow('migration', 'status');

        foreach (FileDirManager::retrieveFilesByDirectory('./App/Migrations') as $file) {
            if (!in_array($file, $this->ran_migrations)) {
                $this->cosmo->fileSuccessRow($file, 'needed ran');
            } else {
                $this->cosmo->fileFailRow($file, 'already ran');
            }
        }

        $this->cosmo->finish();
        $this->cosmo->commandSuccess('list');
        return Command::SUCCESS;
    }

    protected function configure()
    {
        $this->setHelp('Run migrate to execute migration files.');
    }
}
