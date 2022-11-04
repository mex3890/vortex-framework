<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class VortexOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $blue_formatter = new OutputFormatterStyle('cyan', '', ['bold']);
        $output->getFormatter()->setStyle('vortex', $blue_formatter);
    }
}
