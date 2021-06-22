<?php

$result = str_starts_with($_SERVER['REQUEST_URI'], '/forbidden')
    ? 'Access denied!'
    : 'Welcome!';

echo $result;

