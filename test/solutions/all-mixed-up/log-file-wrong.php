<?php

function logParameter(mixed $parameter): void
{
    file_put_contents(
        'param.log',
        sprintf(
            "%s: the type is: %s\n",
            (new \DateTime())->format('H:i:s'),
            get_debug_type($parameter)
        ),
        FILE_APPEND
    );
}