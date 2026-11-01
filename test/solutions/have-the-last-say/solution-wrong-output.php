<?php

$fp = fopen($argv[1], 'r');

while (!feof($fp)) {
    $row = fgetcsv($fp, separator: "|", escape: "");
    echo "Capital: {$row[0]}, Country: {$row[1]}\n";
}
