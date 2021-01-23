<?php

class RowVisitorNotAllPromoted
{
    private string $key;
    protected string $basePath;

    public function __construct(
        private \Closure $visitor,
        string $key,
        array $config = []
    ) {
        $this->key = $key;
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
