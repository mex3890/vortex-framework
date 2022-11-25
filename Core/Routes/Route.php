<?php

namespace Core\Routes;

use Core\Exceptions\CsrfTokensDoNotMatch;
use Core\Exceptions\MissingCsrfToken;
use Core\Helpers\ClassManager;
use Core\Request\Csrf;
use Core\Request\Request;
use Exception;
use SmartyException;

class Route
{
    public function middleware(array|string $middlewareClasses): static
    {
        if (is_array($middlewareClasses)) {
            foreach ($middlewareClasses as $class) {
                ClassManager::callStaticFunction($class, 'handle');
            }

            return $this;
        }

        ClassManager::callStaticFunction($middlewareClasses, 'handle');

        return $this;
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public function get(string $route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $_GET['LAST_ROUTE'] = $route;

            $this->route($route, $path_to_include);
        }
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public function post(string $route, $path_to_include): void
    {
        $_REQUEST['LAST_ROUTE'] = $_POST['vortex_redirect'] ?? '/';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->route($route, $path_to_include);
        }
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public function put(string $route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $this->route($route, $path_to_include);
        }
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public function patch(string $route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
            $this->route($route, $path_to_include);
        }
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public function delete(string $route, $path_to_include): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $this->route($route, $path_to_include);
        }
    }

    /**
     * @param string $route
     * @param callable $path_to_include
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    public function default(string $route, callable $path_to_include): void
    {
        $this->route($route, $path_to_include);
    }

    /**
     * @param string $route
     * @param $callback
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     */
    private function route(string $route, $callback): void
    {
        global $request;
        $request = new Request();
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);

        if ($route == "/404") {
            call_user_func($callback, $request);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $request_url === $route) {
            Csrf::verifyIfRequestTokenMatchWithSessionToken();
        }

        if ($route_parts[0] === '' && count($request_url_parts) === 0) {
            call_user_func($callback, $request);
            exit();
        }

        if (count($route_parts) != count($request_url_parts)) {
            return;
        }

        $galaxy_params = [];
        for ($i = 0; $i < count($route_parts); $i++) {
            $route_part = $route_parts[$i];
            if (preg_match("/^[$]/", $route_part)) {
                $galaxy_params[str_replace('$', '', $route_part)] = $request_url_parts[$i];
                $route_part = ltrim($route_part, '$');
                $$route_part = $request_url_parts[$i];
            } else if ($route_parts[$i] != $request_url_parts[$i]) {
                return;
            }
        }

        if ($route_parts[0] === '' && count($request_url_parts) !== 0) {
            call_user_func("$callback[0]::$callback[1]", $request);
            exit();
        }

        /* Routes without parameters */
        if (is_array($callback) && $galaxy_params === []) {
            call_user_func("$callback[0]::$callback[1]", $request);
            exit();
        }

        /* Routes without parameters */
        if (is_array($callback) && $galaxy_params !== []) {
            $request->setParameters($galaxy_params);
            call_user_func("$callback[0]::$callback[1]", $request);
            exit();
        }
        /* Routes with anonymous function */
        if (is_callable($callback) && $galaxy_params === []) {
            call_user_func($callback, $request);
            exit();
        }

        /* Routes with parameters */
        if (is_callable($callback)) {
            $request->setParameters($galaxy_params);
            call_user_func($callback, $request);
            exit();
        }
    }

    public function out(string $text): void
    {
        echo htmlspecialchars($text);
    }

    /**
     * @throws Exception
     */
    public function set_csrf(): void
    {
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }
        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }

    public function is_csrf_valid(): bool
    {
        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        }
        if ($_SESSION['csrf'] != $_POST['csrf']) {
            return false;
        }
        return true;
    }
}
