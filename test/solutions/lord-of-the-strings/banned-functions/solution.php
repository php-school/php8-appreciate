<?php


use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/vendor/autoload.php';

$output = new ConsoleOutput();
$table = new Table($output);

$table->setHeaders(['Function', 'Result']);
$table->setRows([
    ['str_contains', strpos($argv[2], $argv[1]) !== false ? 'true' : 'false'],
    ['str_starts_with', strpos($argv[2], $argv[1]) === 0 ? 'true' : 'false'],
    ['str_ends_with', substr($argv[2], -strlen($argv[1])) === $argv[1] ? 'true' : 'false'],
]);

$table->render();