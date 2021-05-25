<?php

try {
    verify_password($argv[1]);
} catch (InvalidPasswordException) {
    echo 'Given password is invalid, please try again';
}