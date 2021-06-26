<?php

require_once __DIR__ . '/deserialize.php';
require_once __DIR__ . '/attributes.php';


#[Attribute(Attribute::TARGET_PROPERTY)]
class Obfuscate {
    public string $key;
    public function __construct(string $key)
    {
        $this->key = $key;
    }
}

#[Deserialize]
class Review {
    public string $comment;

    #[Map('rating')]
    public string $starRating;

    public string $date;

    #[Skip()]
    public string $id;

    public ?string $reviewer = null;

    #[Obfuscate('reviewer')]
    public function obfuscateReviewer(string $reviewer): string
    {
        return md5($reviewer);
    }
}

$object = deserialize($argv[1], Review::class);

var_dump($object);