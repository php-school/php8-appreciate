<?php

try {
    verify_password($argv[1]);
} catch (InvalidPasswordException $e) {
    echo $e->getMessage();
}