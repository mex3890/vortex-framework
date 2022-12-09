<?php

namespace Core\Cosmo;

use Core\Helpers\CommandMounter;

class PhpCommand extends CommandMounter
{
    private const WITHOUT_SCRIPT_TAGS = '--run';
    private const WITHOUT_SCRIPT_TAGS_KEY = 'SCRIPT_TAGS';
    private const INTERACTIVE_MODE_KEY = 'INTERACTIVE_MODE';
    private const INTERACTIVE_MODE = '--interactive';

    protected string $main_option = 'php';
    protected array $command_parameters = [
        self::WITHOUT_SCRIPT_TAGS_KEY => null,
        self::INTERACTIVE_MODE_KEY => null

    ];

    /**
     * @return $this
     * Flag: -a | --interactive
     */
    public function interactiveMode(): static
    {
        $this->command_parameters[] = self::INTERACTIVE_MODE;

        return $this;
    }

    /**
     * @return $this <br>
     * Flag: -r | --run
     */
    public function withoutScriptTags(): static
    {
        $this->command_parameters[] = self::WITHOUT_SCRIPT_TAGS;

        return $this;
    }
}