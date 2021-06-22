<?php

echo !str_starts_with($_SERVER['REQUEST_URI'], '/forbidden')
    ? 'Welcome!'
    : throw new InvalidArgumentException('Access denied!');
