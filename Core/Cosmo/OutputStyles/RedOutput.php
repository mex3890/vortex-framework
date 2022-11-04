<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class RedOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $red_formatter = new OutputFormatterStyle('red', '', ['bold']);
        $output->getFormatter()->setStyle('fire', $red_formatter);
    }
}
