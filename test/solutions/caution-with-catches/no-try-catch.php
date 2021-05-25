<?php

if (!verify_password($argv[1])) {
    echo 'Given password is invalid, please try again';
}