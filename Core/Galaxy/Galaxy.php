<?php

namespace Core\Galaxy;

use Core\Abstractions\Enums\DirPath;
use Smarty;
use SmartyException;

class Galaxy
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../../../../../' . DirPath::VIEWS->value);
        $this->smarty->setConfigDir(DirPath::SMARTY_CONFIG->value);
        $this->smarty->setCompileDir(DirPath::SMARTY_COMPILE->value);
        $this->smarty->setCacheDir(DirPath::SMARTY_CACHE->value);
    }

    /**
     * @throws SmartyException
     */
    public function render(string $view, array $args = []): void
    {
        $view = __DIR__ . '/../../../../../Resources/views/' . $view;
        $this->smarty->assign($args);
        $this->smarty->display($view);
    }
}
