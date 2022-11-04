<?php

namespace Core\Cosmo\OutputStyles;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;

class GrayOutput extends OutputFormatterStyle
{
    public function __construct(OutputInterface $output)
    {
        parent::__construct();
        $blue_formatter = new OutputFormatterStyle('gray', '');
        $output->getFormatter()->setStyle('gray', $blue_formatter);
    }
}
