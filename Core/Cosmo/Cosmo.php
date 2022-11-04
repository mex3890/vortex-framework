<?php

namespace Core\Cosmo;

use Core\Cosmo\OutputStyles\BlueOutput;
use Core\Cosmo\OutputStyles\BrightBlueOutput;
use Core\Cosmo\OutputStyles\GrayOutput;
use Core\Cosmo\OutputStyles\GreenBkgOutput;
use Core\Cosmo\OutputStyles\GreenOutput;
use Core\Cosmo\OutputStyles\RedBkgOutput;
use Core\Cosmo\OutputStyles\RedOutput;
use Core\Cosmo\OutputStyles\VortexOutput;
use Core\Cosmo\OutputStyles\YellowOutput;
use Core\Helpers\CommandMounter;
use Core\Helpers\DateTime;
use Core\Helpers\StringFormatter;
use Symfony\Component\Console\Output\OutputInterface;

class Cosmo
{
    private bool $with_time = false;
    private bool $with_change_counter = false;
    private int $start_time;
    private int $duration_time;
    private int $changes_counter = 0;
    private OutputInterface $output;

    public function title(string $main_title, string $secondary_title): void
    {
        $main_title = ucfirst($main_title);
        $secondary_title = ucfirst($secondary_title);
        $this->output->writeln("\n <bluebkg> $main_title </bluebkg><brightbluebkg> $secondary_title </brightbluebkg><vortex> VORTEX </vortex>\n");
    }

    public function fileSuccessRow(string $filename, string $status): void
    {
        //TODO use helper to calculate points
        $this->changes_counter += 1;
        $points = CommandMounter::retrieveLoadPoints(11 + strlen($filename . $status));
        $status = strtoupper($status);
        $this->output->writeln("   <success>$filename</success>" . $points . "   <info> $status </info>   ");
    }

    public function fileFailRow(string $filename, string $status): void
    {
        $points = CommandMounter::retrieveLoadPoints(11 + strlen($filename . $status));
        $status = strtoupper($status);
        $this->output->writeln("   <fire>$filename</fire>" . $points . "   <comment> $status </comment>   ");
    }

    public function indexRow(string $first_index, string $second_index): void
    {
        $points = CommandMounter::retrieveLoadPoints(9 + strlen($first_index . $second_index));
        $first_index = StringFormatter::absoluteUpperFistLetter($first_index);
        $second_index = StringFormatter::absoluteUpperFistLetter($second_index);
        $this->output->writeln("<gray>   $first_index $points $second_index    </gray>\n");
    }

    public function commandSuccess(string $main_title): void
    {
        $information_string = $this->retrieveInformation();
        $main_title = StringFormatter::absoluteUpperFistLetter($main_title);

        if (is_null($information_string)) {
            $this->output->writeln("\n <bluebkg> $main_title </bluebkg><greenbkg> Successfully </greenbkg><vortex> VORTEX </vortex>");
        } else {
            $this->output->writeln("\n <bluebkg> $main_title </bluebkg><greenbkg> Successfully </greenbkg>  $information_string");
        }
    }

    public function commandFail(string $main_title): void
    {
        $main_title = StringFormatter::absoluteUpperFistLetter($main_title);
        $information_string = $this->retrieveInformation();

        if (is_null($information_string)) {
            $this->output->writeln("\n <redbkg> $main_title </redbkg><greenbkg> Failed </greenbkg><vortex> VORTEX </vortex>");
        } else {
            $this->output->writeln("\n <redbkg> $main_title </redbkg><greenbkg> Failed </greenbkg>  $information_string");
        }
    }

    private function retrieveInformation(): ?string
    {
        if ($this->with_time && $this->with_change_counter) {
            $information_string = "({$this->duration_time}ms, " . $this->changes_counter . ' changes)';
        } elseif ($this->with_time) {
            $information_string = "({$this->duration_time}ms)";
        } elseif ($this->with_change_counter) {
            $information_string = "($this->changes_counter changes)";
        } else {
            $information_string = null;
        }

        return $information_string;
    }

    public function start(OutputInterface $output, bool $with_time = false, bool $with_change_counter = false): void
    {
        $this->output = $output;
        $this->loadStyles($output);

        if ($with_time) {
            $this->with_time = true;
            $this->start_time = DateTime::retrieveCurrentMillisecond();
        }

        if ($with_change_counter) {
            $this->with_change_counter = true;
        }
    }

    public function finish(): void
    {
        if ($this->with_time) {
            $this->duration_time = DateTime::retrieveCurrentMillisecond() - $this->start_time;
        }
    }

    private function loadStyles(OutputInterface $output): void
    {
        new GrayOutput($output);
        new YellowOutput($output);
        new RedOutput($output);
        new BlueOutput($output);
        new VortexOutput($output);
        new GreenOutput($output);
        new GreenBkgOutput($output);
        new RedBkgOutput($output);
        new BrightBlueOutput($output);
    }
}
