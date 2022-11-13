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

        if (key_exists('OLD_ATTRIBUTES', $_SESSION)) {
            $_SESSION['OLD_ATTRIBUTES'] = '';
        }

        if(key_exists('ERROR', $_SESSION)) {
            $_SESSION['ERROR'] = '';
        }
    }
}
