<?php

class RowVisitorTypeRemoved
{
    protected $basePath;

    public function __construct(
        private \Closure $visitor,
        private $key,
        array $config = []
    ) {
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
