<?php

try {
    $argv[1] / $argv[2];
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}

echo match ($res = fdiv($argv[1], $argv[2])) {
    INF => 'Infinite',
    -INF => 'Minus infinite',
    default => round($res, 2)
};

echo "\n";
