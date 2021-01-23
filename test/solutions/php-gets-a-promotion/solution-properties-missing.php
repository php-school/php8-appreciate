<?php

class RowVisitorMissingProperties
{
    protected string $basePath;

    public function __construct(
        private \Closure $visitor,
        array $config = []
    ) {
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
