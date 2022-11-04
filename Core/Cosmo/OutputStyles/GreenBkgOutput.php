<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class GreenBkgOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $blue_formatter = new OutputFormatterStyle('white', 'green', ['bold']);
        $output->getFormatter()->setStyle('greenbkg', $blue_formatter);
    }
}
