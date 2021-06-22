<?php

if (str_starts_with($_SERVER['REQUEST_URI'], '/forbidden')) {
    throw new InvalidArgumentException('Access denied!');
} else {
    echo 'Welcome!';
}
