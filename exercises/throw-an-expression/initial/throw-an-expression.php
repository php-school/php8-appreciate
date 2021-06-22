<?php

if (str_starts_with($_SERVER['REQUEST_URI'], '/forbidden')) {
    echo 'Welcome!';
} else {
    throw new InvalidArgumentException('Access denied!');
}
