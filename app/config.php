<?php

use PhpSchool\PHP8Appreciate\AstService;
use PhpSchool\PHP8Appreciate\Exercise\AMatchMadeInHeaven;
use PhpSchool\PHP8Appreciate\Exercise\CautionWithCatches;
use PhpSchool\PHP8Appreciate\Exercise\HaveTheLastSay;
use PhpSchool\PHP8Appreciate\Exercise\PhpPromotion;
use PhpSchool\PHP8AppreciateTest\Exercise\LordOfTheStrings;
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
    PhpPromotion::class => function (ContainerInterface $c) {
        return new PhpPromotion($c->get(PhpParser\Parser::class));
    },
    CautionWithCatches::class => function (ContainerInterface $c) {
        return new CautionWithCatches($c->get(PhpParser\Parser::class), $c->get(\Faker\Generator::class));
    },
    LordOfTheStrings::class => function (ContainerInterface $c) {
        return new LordOfTheStrings($c->get(\Faker\Generator::class));
    },
];
