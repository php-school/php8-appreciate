<?php

$request = json_decode(file_get_contents('php://input'), true);

if (!$request['success']) {
    echo "Status: {$request['status']} \nError: {$request['error']}";
}
