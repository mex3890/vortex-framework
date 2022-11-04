<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class YellowOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $yellow_formatter = new OutputFormatterStyle('white', 'yellow', ['bold']);
        $output->getFormatter()->setStyle('yellow', $yellow_formatter);
    }
}
