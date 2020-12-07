<?php

class RowVisitor
{
    protected string $basePath;

    public function __construct(
        private \Closure $visitor,
        private string $key,
        array $config = []
    ) {
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
