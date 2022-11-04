<?php

namespace Core\Request;

class Request
{
    private array $parameters = [];
    private const SERVER_HOST = 'HTTP_HOST';
    private const HTTP_ORIGIN = 'HTTP_ORIGIN';
    private const SERVER_NAME = 'SERVER_NAME';
    private const SERVER_PORT = 'SERVER_PORT';
    private const SERVER_PROTOCOL = 'SERVER_PROTOCOL';
    private const SCRIPT_FILENAME = 'SCRIPT_FILENAME';
    private const REQUEST_METHOD = 'REQUEST_METHOD';
    private const REQUEST_URI = 'REQUEST_URI';

    public function attributes(): array
    {
        return $_POST;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function host(): array
    {
        return $_SERVER[self::SERVER_HOST];
    }

    public function origin()
    {
        return $_SERVER[self::HTTP_ORIGIN];
    }

    public function server()
    {
        return $_SERVER[self::SERVER_NAME];
    }

    public function serverPort()
    {
        return $_SERVER[self::SERVER_PORT];
    }

    public function protocol()
    {
        return $_SERVER[self::SERVER_PROTOCOL];
    }

    public function filename()
    {
        return $_SERVER[self::SCRIPT_FILENAME];
    }

    public function method()
    {
        return $_SERVER[self::REQUEST_METHOD];
    }

    public function uri()
    {
        return $_SERVER[self::REQUEST_URI];
    }

    public function url()
    {
        return $_SERVER[self::APP_URL];
    }
}
