<?php

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/vendor/autoload.php';

$output = new ConsoleOutput();
$table = new Table($output);

$table->setHeaders(['Function', 'Result']);
$table->setRows([
    ['str_contains', str_contains($argv[2], $argv[1]) ? 'true' : 'false'],
    ['str_starts_with', str_starts_with($argv[2], $argv[1]) ? 'true' : 'false'],
    ['str_ends_with', str_ends_with($argv[2], $argv[1]) ? 'true' : 'false'],
]);

$table->render();