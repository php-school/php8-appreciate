<?php

class RowVisitorDataNotSet
{
    protected string $basePath;

    public function __construct(
        private \Closure $visitor,
        private string $key,
        array $config = []
    ) {}

    public function readCsv(string $filePath)
    {
        // noop
    }
}
