<?php

$result = str_starts_with($_SERVER['REQUEST_URI'], '/forbidden')
    ? 'Welcome!'
    : 'Access denied!';

echo $result;

