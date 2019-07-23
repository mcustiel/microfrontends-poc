<?php

declare(strict_types=1);

use Mcustiel\MicrofrontendsComposer\Application;
use Mcustiel\MicrofrontendsComposer\Factory;

$start = microtime(true);
require_once __DIR__ . '/../vendor/autoload.php';

$app = new Application(new Factory());

$app->main();

echo sprintf('Time taken: %0.5f seconds', (microtime(true) - $start));
