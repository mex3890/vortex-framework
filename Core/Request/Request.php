<?php

namespace Core\Request;

class Request
{
    public string $method;
    public string $host;
    public string $origin;
    public string $server_port;
    public string $protocol;
    public string $filename;
    public string $url;
    public string $uri;
    public ?array $parameters;
    public ?array $attributes;
    public ?array $files;
    public ?array $_SERVER;
    public ?array $_COOKIE;
    public ?array $_SESSION;
    public ?array $_ENV;

    public const SERVER_HOST = 'HTTP_HOST';
    public const HTTP_ORIGIN = 'HTTP_ORIGIN';
    public const SERVER_PORT = 'SERVER_PORT';
    public const SERVER_PROTOCOL = 'SERVER_PROTOCOL';
    public const SCRIPT_FILENAME = 'SCRIPT_FILENAME';
    public const REQUEST_METHOD = 'REQUEST_METHOD';
    public const REQUEST_URI = 'REQUEST_URI';
    public const APP_URL = 'APP_URL';

    public function __construct()
    {
        $this->setHost();
        $this->setOrigin();
        $this->setServerPort();
        $this->setAttributes();
        $this->setMethod();
        $this->setProtocol();
        $this->setFilename();
        $this->setUrl();
        $this->setUri();
        $this->setFiles();
        $this->setServer();
        $this->setSession();
        $this->setCookie();
        $this->setEnv();
    }

    public function setServer(): void
    {
        $this->_SERVER = $_SERVER ?? null;
    }

    public function server(): ?array
    {
        return $this->_SERVER;
    }

    public function setCookie(): void
    {
        $this->_COOKIE = $_COOKIE ?? null;
    }

    public function cookie(): ?array
    {
        return $this->_COOKIE;
    }

    public function setSession(): void
    {
        $this->_SESSION = $_SESSION ?? null;
    }

    public function session(): ?array
    {
        return $this->_SESSION;
    }

    public function setEnv(): void
    {
        $this->_ENV = $_ENV ?? null;
    }

    public function env(): ?array
    {
        return $this->_ENV;
    }

    public function setAttributes(): void
    {
        $attributes = $_POST;

        if (isset($atributes['vortex_redirect'])) {
            unset($attributes['vortex_redirect']);
        }

        if (isset($attributes['vortex_method'])) {
            unset($attributes['vortex_method']);
        }

        $this->attributes = $attributes;
    }

    public function attributes(): array
    {
        return $this->attributes;
    }

    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    public function setHost(): void
    {
        $this->host = $_SERVER[self::SERVER_HOST];
    }

    public function host(): string
    {
        return $this->host;
    }

    public function setOrigin(): void
    {
        if (isset($_SERVER[self::HTTP_ORIGIN])) {
            $this->origin = $_SERVER[self::HTTP_ORIGIN];
        }
    }

    public function origin(): string
    {
        return $this->origin;
    }

    public function setServerPort(): void
    {
        $this->server_port = $_SERVER[self::SERVER_PORT];
    }

    public function serverPort(): string
    {
        return $this->server_port;
    }

    public function setProtocol(): void
    {
        $this->protocol = $_SERVER[self::SERVER_PROTOCOL];
    }

    public function protocol(): string
    {
        return $this->protocol;
    }

    public function setFilename(): void
    {
        $this->filename = $_SERVER[self::SCRIPT_FILENAME];
    }

    public function filename(): string
    {
        return $this->filename;
    }

    public function setMethod(): void
    {
        $this->method = $_SERVER[self::REQUEST_METHOD];
    }

    public function method(): string
    {
        return $this->method;
    }

    public function setUri(): void
    {
        $this->uri = $_SERVER[self::REQUEST_URI];
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function setUrl(): void
    {
        $this->url = $_SERVER[self::APP_URL];
    }

    public function url(): string
    {
        return $this->url;
    }

    public function setFiles(): void
    {
        $this->files = $_FILES ?? null;
    }

    public function files(): array
    {
        return $this->files;
    }

    /**
     * @param string $attribute_name
     * @return mixed|null
     */
    public function getAttribute(string $attribute_name): mixed
    {
        if (isset($this->attributes[$attribute_name])) {
            return $this->attributes[$attribute_name];
        }

        return null;
    }

    /**
     * @param string $parameter_name
     * @return mixed|null
     */
    public function getParameter(string $parameter_name): mixed
    {
        if (isset($this->parameters[$parameter_name])) {
            return $this->parameters[$parameter_name];
        }

        return null;
    }
}
