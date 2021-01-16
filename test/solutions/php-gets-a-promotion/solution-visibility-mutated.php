<?php

class RowVisitorPropertyTypeChanged
{
    private string $basePath;

    public function __construct(
        private \Closure $visitor,
        protected string $key,
        array $config = []
    ) {
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
