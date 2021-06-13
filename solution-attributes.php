<?php

#[Attribute(Attribute::TARGET_CLASS)]
class Deserialize {}

#[Attribute(Attribute::TARGET_PROPERTY)]
class Map {
    public function __construct(public string $mapFrom)
    {
    }
}

#[Attribute(Attribute::TARGET_PROPERTY)]
class Skip {

}

#[Deserialize]
class CityBreak {
    public string $country;

    #[Map('town')]
    public string $city;

    public string $avgTemperature;

    #[Skip()]
    public ?string $bestNeighbourhood = null;
}

function camelCaseToSnakeCase(string $string): string
{
    return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
}

function deserialize(string $data, string $className): object
{
    $reflectionClass = new \ReflectionClass($className);
    $attrs = $reflectionClass->getAttributes(Deserialize::class);

    if (empty($attrs)) {
        throw new \RuntimeException('Class cannot be deserialized');
    }

    $attrs[0]->newInstance();

    $object = new $className;

    $data = json_decode($data, true);

    foreach ($reflectionClass->getProperties() as $property) {
        if ($map = $property->getAttributes(Map::class)) {
            $key = $map[0]->newInstance()->mapFrom;

            if (isset($data[$key])) {
                $object->{$property->getName()} = $data[$key];
            }

        } elseif ($skip = $property->getAttributes(Skip::class)) {
            continue;
        } elseif (isset($data[camelCaseToSnakeCase($property->getName())])) {
            $object->{$property->getName()} = $data[camelCaseToSnakeCase($property->getName())];
        }
    }

    return $object;
}

$object = deserialize(
    json_encode([
        'country' => 'Austria',
        'town' => 'Vienna',
        'avg_temperature' => '13',
        'best_neighbourhood' => 'Penha de França'
    ]),
    CityBreak::class
);

var_dump($object);

