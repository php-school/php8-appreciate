<?php

class RowVisitorTypeMutated
{
    protected string $basePath;

    public function __construct(
        private \Closure $visitor,
        private \Stringable $key,
        array $config = []
    ) {
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
