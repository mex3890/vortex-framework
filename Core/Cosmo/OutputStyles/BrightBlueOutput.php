<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class BrightBlueOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $blue_formatter = new OutputFormatterStyle('white', 'bright-blue');
        $output->getFormatter()->setStyle('brightbluebkg', $blue_formatter);
    }
}
