<?php

$fp = fopen($argv[1], 'r');

while (!feof($fp)) {
    $row = fgetcsv($fp, 0);
    echo "Country: {$row[0]}, Capital: {$row[1]}\n";
}
