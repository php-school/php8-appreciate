<?php

function adder(float|string|int ...$numbers) {
    return array_sum($numbers);
}

$nums = $argv;
array_shift($nums);

echo adder(...$nums) . "\n";
