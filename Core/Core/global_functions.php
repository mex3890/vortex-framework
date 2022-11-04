<?php

use Core\Galaxy\Galaxy;
use JetBrains\PhpStorm\NoReturn;

/**
 * @throws SmartyException
 */
function view(string $view_name, array $params = []): void
{
    $galaxy = new Galaxy();

    if (!strpos($view_name, '.php') && !strpos($view_name, '.galaxy.tpl')) {
        $view_name .= '.galaxy.tpl';
    }

    $galaxy->render($view_name, $params);
}

#[NoReturn] function dd(...$vars): void
{
    var_dump($vars);
    die();
}
