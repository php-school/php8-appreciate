<?php

require_once __DIR__ . '/deserialize.php';
require_once __DIR__ . '/attributes.php';

#[Deserialize]
class Review {
    public string $comment;

    #[Map('rating')]
    public string $starRating;

    public string $date;

    #[Skip()]
    public ?string $reviewer = null;
}

$object = deserialize($argv[1], Review::class);

var_dump($object);