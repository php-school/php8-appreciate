<?php

class RowVisitor
{
    private \Closure $visitor;

    private string $key;

    protected string $basePath;

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
