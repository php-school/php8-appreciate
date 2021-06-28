<?php

#[Attribute(Attribute::TARGET_CLASS)]
class Deserialize {

}

#[Attribute(Attribute::TARGET_PROPERTY)]
class Map {
    public function __construct(public string $mapFrom)
    {
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
class Skip {

}