<?php

$argv[1] / $argv[2];

echo match ($res = fdiv($argv[1], $argv[2])) {
    INF => 'Infinite',
    -INF => 'Minus infinite',
    default => $res
};

echo "\n";
