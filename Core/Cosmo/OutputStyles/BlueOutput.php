<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class BlueOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $blue_formatter = new OutputFormatterStyle('white', '#002bff');
        $output->getFormatter()->setStyle('bluebkg', $blue_formatter);
    }
}
