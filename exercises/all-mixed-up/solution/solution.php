<?php

if (file_exists('param.log')) {
    unlink('param.log');
}

function logParameter(mixed $parameter): void
{
    file_put_contents(
        'param.log',
        sprintf(
            "%s: Got: %s\n",
            (new \DateTime())->format('H:i:s'),
            get_debug_type($parameter)
        ),
        FILE_APPEND
    );
}