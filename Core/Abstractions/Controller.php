<?php

namespace Core\Abstractions;

use Core\Request\Request;

abstract class Controller
{
    private Request $request;
    public static array $rules = [];
    public static array $feedback = [];
}
