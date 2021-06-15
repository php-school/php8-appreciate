<?php

function logParameter(mixed $parameter): void
{
    file_put_contents(
        'param.log',
        sprintf(
            "%s: Got: %s\n",
            (new \DateTime())->format('H:i:s'),
            gettype($parameter)
        ),
        FILE_APPEND
    );
}