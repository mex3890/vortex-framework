<?php

namespace Core\Cosmo;

use Core\Abstractions\Enums\PhpExtra;

abstract class CommandConstructor
{
    /** @var string Like php, npm, composer, [ . . . ] */
    protected string $main_option;
    protected array $command_parameters;

    private function mountCommand(): string
    {
        $command = $this->main_option;

        foreach ($this->command_parameters as $parameter) {
            $command .= PhpExtra::WHITE_SPACE->value . $parameter;
        }

        return $command;
    }

    public function execute(): bool|string|null
    {
        $command = $this->mountCommand();

        return shell_exec($command);
    }
}