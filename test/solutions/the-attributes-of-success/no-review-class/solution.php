<?php

require_once __DIR__ . '/deserialize.php';
require_once __DIR__ . '/attributes.php';


#[Attribute(Attribute::TARGET_METHOD)]
class Obfuscate {
    public string $key;
    public function __construct(string $key)
    {
        $this->key = $key;
    }
}
$object = deserialize($argv[1], Review::class);

var_dump($object);