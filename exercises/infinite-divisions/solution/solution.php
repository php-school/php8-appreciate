<?php

try {
    $argv[1] / $argv[2];
} catch (DivisionByZeroError $e) {
    echo $e->getMessage() . "\n";
}

echo match ($res = fdiv($argv[1], $argv[2])) {
    INF => 'Infinite',
    -INF => 'Minus Infinite',
    default => $res
};

echo "\n";
