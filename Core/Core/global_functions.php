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

#[NoReturn] function redirect(string $route, int $code = 200): void
{
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $route;
    require __DIR__ . '/../../../../../public/index.php';
    die();
}

#[NoReturn] function back(): void
{
    redirect($_SESSION['LAST_ROUTE']);
}

#[NoReturn] function dd(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre>' . var_export($var, true) . '</pre>';
    }
    die();
}
