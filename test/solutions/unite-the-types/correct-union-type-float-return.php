<?php

function adder(string|float|int ...$numbers): float {
    return array_sum($numbers);
}

$nums = $argv;
array_shift($nums);

echo adder(...$nums) . "\n";
