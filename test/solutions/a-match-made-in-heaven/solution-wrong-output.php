<?php

echo match ($argv[1]) {
    'enter' => 13 + 10,
    'up' => 119 + 10,
    'down' => 73 + 10,
    'esc' => 27 + 10,
    default => 0 + 10
};
