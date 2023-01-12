<?php

namespace Core\Routes;

use Core\Exceptions\CsrfTokensDoNotMatch;
use Core\Exceptions\MissingCsrfToken;
use Core\Exceptions\MissingRequestMethodDefinition;
use Core\Helpers\ClassManager;
use Core\Request\Csrf;
use Core\Request\Request;
use Exception;

class Route
{
    private array $routes;

    /**
     * @throws MissingCsrfToken
     */
    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !isset($_POST['csrf_token'])) {
            throw new MissingCsrfToken();
        }

        if (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' && isset($_REQUEST['vortex_method'])) {
            $_SERVER['REQUEST_METHOD'] = $_REQUEST['vortex_method'];
        }
    }

    public function middleware(array|string $middlewareClasses): static
    {
        $count = count($this->routes);
        $this->routes[$count - 1]['middlewares'] = $middlewareClasses;

        return $this;
    }

    public function runMiddleware(array|string $middlewareClasses): void
    {
        if (is_array($middlewareClasses)) {
            foreach ($middlewareClasses as $class) {
                ClassManager::callStaticFunction($class, 'handle');
            }
        }

        ClassManager::callStaticFunction($middlewareClasses, 'handle');
    }

    public function name(string $name): static
    {
        $count = count($this->routes);
        $this->routes[$count - 1]['name'] = $name;

        return $this;
    }

    /**
     * @throws CsrfTokensDoNotMatch
     * @throws MissingRequestMethodDefinition
     * @throws MissingCsrfToken
     */
    public function mount(): void
    {
        define('ROUTES', $this->routes);

        foreach ($this->routes as $route) {
            if ($route['method'] === 'GET') {
                $_GET['LAST_ROUTE'] = $route['route'];
            } else {
                $_REQUEST['LAST_ROUTE'] = $_POST['vortex_redirect'] ?? '/';
            }

            $this->route($route);
        }
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return $this
     */
    public function get(string $route, $path_to_include): static
    {
        $this->routes[] = [
            'method' => 'GET',
            'route' => $route,
            'path_to_include' => $path_to_include,
            'name' => null,
            'middlewares' => null,
            'is_default' => false
        ];

        return $this;
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return $this
     */
    public function post(string $route, $path_to_include): static
    {
        $this->routes[] = [
            'method' => 'POST',
            'route' => $route,
            'path_to_include' => $path_to_include,
            'name' => null,
            'middlewares' => null,
            'is_default' => false
        ];

        return $this;
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return $this
     */
    public function put(string $route, $path_to_include): static
    {
        $this->routes[] = [
            'method' => 'PUT',
            'route' => $route,
            'path_to_include' => $path_to_include,
            'name' => null,
            'middlewares' => null,
            'is_default' => false
        ];

        return $this;
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return $this
     */
    public function patch(string $route, $path_to_include): static
    {
        $this->routes[] = [
            'method' => 'PATCH',
            'route' => $route,
            'path_to_include' => $path_to_include,
            'name' => null,
            'middlewares' => null,
            'is_default' => false
        ];

        return $this;
    }

    /**
     * @param string $route
     * @param $path_to_include
     * @return $this
     */
    public function delete(string $route, $path_to_include): static
    {
        $this->routes[] = [
            'method' => 'DELETE',
            'route' => $route,
            'path_to_include' => $path_to_include,
            'name' => null,
            'middlewares' => null,
            'is_default' => false
        ];

        return $this;
    }

    /**
     * @param string $route
     * @param callable $path_to_include
     * @return $this
     */
    public function default(string $route, callable $path_to_include): static
    {
        $this->routes[] = [
            'method' => 'GET',
            'route' => $route,
            'path_to_include' => $path_to_include,
            'name' => null,
            'middlewares' => null,
            'is_default' => true
        ];

        return $this;
    }

    /**
     * @param array $route_parameters
     * @return void
     * @throws CsrfTokensDoNotMatch
     * @throws MissingCsrfToken
     * @throws MissingRequestMethodDefinition
     */
    private function route(array $route_parameters): void
    {
        $route = $route_parameters['route'];
        $callback = $route_parameters['path_to_include'];
        $middlewares = $route_parameters['middlewares'];


        global $request;
        $request = new Request();
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = rtrim($request_url, '/');
        $request_url = strtok($request_url, '?');
        $route_parts = explode('/', $route);
        $request_url_parts = explode('/', $request_url);
        array_shift($route_parts);
        array_shift($request_url_parts);

//        if ($route == "/404") {
        if ($route_parameters['is_default']) {
            call_user_func($callback, $request);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $request_url === $route) {
            Csrf::verifyIfRequestTokenMatchWithSessionToken();
        }

        if ($route_parts[0] === '' && count($request_url_parts) === 0) {
            if (!is_null($middlewares)) {
                $this->runMiddleware($middlewares);
            }

            if ($route_parameters['method'] !== $_SERVER['REQUEST_METHOD']) {
                throw new MissingRequestMethodDefinition($route_parameters['method'], $_SERVER['REQUEST_METHOD']);
            }

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
            if (!is_null($middlewares)) {
                $this->runMiddleware($middlewares);
            }

            if ($route_parameters['method'] !== $_SERVER['REQUEST_METHOD']) {
                throw new MissingRequestMethodDefinition($route_parameters['method'], $_SERVER['REQUEST_METHOD']);
            }

            call_user_func("$callback[0]::$callback[1]", $request);

            exit();
        }

        /* Routes without parameters */
        if (is_array($callback) && $galaxy_params === []) {
            if (!is_null($middlewares)) {
                $this->runMiddleware($middlewares);
            }

            if ($route_parameters['method'] !== $_SERVER['REQUEST_METHOD']) {
                throw new MissingRequestMethodDefinition($route_parameters['method'], $_SERVER['REQUEST_METHOD']);
            }

            call_user_func("$callback[0]::$callback[1]", $request);
            exit();
        }

        /* Routes without parameters */
        if (is_array($callback) && $galaxy_params !== []) {
            $request->setParameters($galaxy_params);

            if (!is_null($middlewares)) {
                $this->runMiddleware($middlewares);
            }

            if ($route_parameters['method'] !== $_SERVER['REQUEST_METHOD']) {
                throw new MissingRequestMethodDefinition($route_parameters['method'], $_SERVER['REQUEST_METHOD']);
            }

            call_user_func("$callback[0]::$callback[1]", $request);

            exit();
        }
        /* Routes with anonymous function */
        if (is_callable($callback) && $galaxy_params === []) {
            if (!is_null($middlewares)) {
                $this->runMiddleware($middlewares);
            }

            if ($route_parameters['method'] !== $_SERVER['REQUEST_METHOD']) {
                throw new MissingRequestMethodDefinition($route_parameters['method'], $_SERVER['REQUEST_METHOD']);
            }

            call_user_func($callback, $request);

            exit();
        }

        /* Routes with parameters */
        if (is_callable($callback)) {
            $request->setParameters($galaxy_params);

            if (!is_null($middlewares)) {
                $this->runMiddleware($middlewares);
            }

            if ($route_parameters['method'] !== $_SERVER['REQUEST_METHOD']) {
                throw new MissingRequestMethodDefinition($route_parameters['method'], $_SERVER['REQUEST_METHOD']);
            }

            call_user_func($callback, $request);

            exit();
        }
    }

    public function out(string $text): void
    {
        echo htmlspecialchars($text);
    }

    /**
     * @return void
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
