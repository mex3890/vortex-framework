<?php

use Core\Exceptions\InvalidRequestMethod;
use Core\Exceptions\MissingCsrfToken;
use Core\Exceptions\MissingPaginationLinks;
use Core\Exceptions\RouteNotFound;
use Core\Galaxy\Galaxy;
use Core\Helpers\Environment;
use Core\Request\Csrf;
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

    header('Location: ' . Environment::appUrl() . $route);
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

/**
 * @throws MissingPaginationLinks
 */
function getPaginationLinks(): string
{
    if (isset($_GET['PAGINATION_LINKS'])) {
        return $_GET['PAGINATION_LINKS'];
    }

    throw new MissingPaginationLinks();
}

/**
 * @throws MissingCsrfToken
 */
function csrf(): string
{
    $current_session_token = Csrf::getSessionTokenIfExist();
    $csrf_token = "<input name='csrf_token' type='hidden' value='$current_session_token'>";
    $csrf_token .= vortexRedirect();
    return $csrf_token;
}

function vortexRedirect(): string
{
    $last_route = $_GET['LAST_ROUTE'];

    return "<input name='vortex_redirect' type='hidden' value='$last_route'>";
}

/**
 * @throws InvalidRequestMethod
 */
function method(string $method): string
{
    $method = strtoupper($method);

    if (in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'])) {
        return "<input name='vortex_method' type='hidden' value='$method'>";
    } else {
        throw new InvalidRequestMethod($method);
    }
}

/**
 * @throws RouteNotFound
 */
function route(string $route_name, array $parameters = []): string
{
    foreach (ROUTES as $route) {
        if ($route['name'] === $route_name) {
            $route_path = $route['route'];

            foreach ($parameters as $key => $value) {
                $route_path = str_replace("\${$key}", $value, $route_path);
            }

            return \Core\Helpers\Uri::getRootPath() . ($route_path === false ? $route['route'] : $route_path);
        }
    }

    throw new RouteNotFound($route_name);
}
