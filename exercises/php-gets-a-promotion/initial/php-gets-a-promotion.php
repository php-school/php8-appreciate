<?php

class RowVisitor
{
    private $visitor;

    private $key;

    protected $basePath;

    public function __construct(\Closure $visitor, string $key, array $config = [])
    {
        $this->visitor = $visitor;
        $this->key = $key;
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
