<?php

use Core\Galaxy\Galaxy;
use Faker\Factory;
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

#[NoReturn] function redirect(string $route, array $args = null, array $errors = null, int $code = 200): void
{
    $old_attributes = [];

    foreach ($_POST as $key => $arg) {
        if ($arg !== '' && $key !== 'vortex_redirect') {
            $old_attributes[$key] = $arg;
        }
    }

    $_GET['OLD_ATTRIBUTES'] = $old_attributes;
    
    if (!empty($args)) {
        foreach ($args as $key => $arg) {
            $_GET[$key] = $arg;
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $key => $error) {
            $_GET['ERROR'][$key] = $error;
        }
    }

    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $route;
    $_GET['LAST_ROUTE'] = $route;

    header('Location: ' . \Core\Helpers\Environment::appUrl() . $route);
    require __DIR__ . '/../../../../../public/index.php';
}

#[NoReturn] function back(array $args = null, array $errors = null): void
{
    $old_attributes = [];

    foreach ($_POST as $key => $arg) {
        if ($arg !== '' && $key !== 'vortex_redirect') {
            $old_attributes[$key] = $arg;
        }
    }

    $_GET['OLD_ATTRIBUTES'] = $old_attributes;

    if (!empty($args)) {
        foreach ($args as $key => $arg) {
            $_GET[$key] = $arg;
        }
    }

    if (!empty($errors)) {
        foreach ($errors as $key => $error) {
            $_GET['ERROR'][$key] = $error;
        }
    }
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = $_GET['LAST_ROUTE'] ?? $_REQUEST['LAST_ROUTE'];
    $_GET['LAST_ROUTE'] = $_GET['LAST_ROUTE'] ?? $_REQUEST['LAST_ROUTE'];
    require __DIR__ . '/../../../../../public/index.php';
}

#[NoReturn] function dd(...$vars): void
{
    foreach ($vars as $var) {
        echo '<pre>' . var_export($var, true) . '</pre>';
    }
    die();
}

function old(string $key)
{
    return $_GET['OLD_ATTRIBUTES'][$key] ?? '';
}

function error(string $key = null)
{
    if ($key && key_exists($key, $_GET['ERROR'])) {
        return $_GET['ERROR'][$key][0];
    } elseif ($key) {
        return '';
    }

    return $_GET['ERROR'] ?? '';
}

function hasError(string $key = null): bool
{
    if ($key && !empty($_GET['ERROR'])) {
        if ($_GET['ERROR'] != '') {
            return key_exists($key, $_GET['ERROR']);
        }
    } elseif (!empty($_GET['ERROR'])) {
        return true;
    }

    return false;
}

function content(string $path): string
{
    return $_ENV['APP_URL'] . "/$path";
}

function faker(): \Faker\Generator
{
    return Factory::create();
}
