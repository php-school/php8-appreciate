<?php

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

    $object = new $className();

    $data = json_decode($data, true);

    $obfuscators = array_filter(
        $reflectionClass->getMethods(),
        fn (ReflectionMethod $m) => count($m->getAttributes(Obfuscate::class)) > 0
    );

    $obfuscators = array_combine(
        array_map(
            fn(ReflectionMethod $m) => $m->getAttributes(Obfuscate::class)[0]->newInstance()->key,
            $obfuscators
        ),
        $obfuscators
    );

    foreach ($data as $key => $value) {
        if (isset($obfuscators[$key])) {
            $data[$key] = $object->{$obfuscators[$key]->getName()}($value);
        }
    }

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