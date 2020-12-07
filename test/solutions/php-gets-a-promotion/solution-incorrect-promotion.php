<?php

class RowVisitorNotAllowedPromotion
{
    public function __construct(
        private \Closure $visitor,
        private string $key,
        protected array $config = []
    ) {
        $this->basePath = $config['basePath'] ?? '';
    }

    public function readCsv(string $filePath)
    {
        // noop
    }
}
