<?php

echo str_starts_with($_SERVER['REQUEST_URI'], '/forbidden')
    ? throw new Exception('Access denied!')
    : 'Welcome!';
