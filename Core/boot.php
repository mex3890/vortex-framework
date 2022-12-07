<?php

namespace Core;

use Core\Abstractions\Model;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';
$env = Dotenv::createImmutable(__DIR__ . '/..');
$env->load();
