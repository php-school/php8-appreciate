<?php

$fp = fopen($argv[1], 'r');

while (!feof($fp)) {
    $row = explode("|", rtrim(fgets($fp), "\r\n"));
    echo "Country: {$row[0]}, Capital: {$row[1]}\n";
}
