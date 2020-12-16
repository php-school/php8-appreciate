<?php

use PhpSchool\PHP8Appreciate\AstService;
use PhpSchool\PHP8Appreciate\Exercise\AMatchMadeInHeaven;
use PhpSchool\PHP8Appreciate\Exercise\HaveTheLastSay;
use Psr\Container\ContainerInterface;
use function DI\create;
use function DI\factory;
use function DI\object;

return [
    'basePath' => __DIR__ . '/../',

    //Define your exercise factories here
    AMatchMadeInHeaven::class => function (ContainerInterface $c) {
        return new AMatchMadeInHeaven($c->get(PhpParser\Parser::class));
    },
    HaveTheLastSay::class => function (ContainerInterface $c) {
        return new HaveTheLastSay($c->get(PhpParser\Parser::class));
    },

];
