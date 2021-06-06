<?php

declare(strict_types=1);

function adder(string|float|int ...$numbers) {
    return array_sum($numbers);
}

$nums = $argv;
array_shift($nums);

echo adder(...$nums) . "\n";
