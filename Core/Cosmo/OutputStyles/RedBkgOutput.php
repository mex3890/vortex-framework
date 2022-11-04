<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class RedBkgOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $red_formatter = new OutputFormatterStyle('white', '#FF1616', ['bold', 'conceal']);
        $output->getFormatter()->setStyle('redbkg', $red_formatter);
    }
}
