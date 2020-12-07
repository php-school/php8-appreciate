<?php

$asciiCode = null;
switch ($argv[0]) {
    case 'enter':
        $asciiCode = 13;
        break;
    case 'up':
        $asciiCode = 119;
    case 'down':
        $asciiCode = 73;
        break;
    case 'esc':
        $asciiCode = 27;
        break;
}

echo $asciiCode;
