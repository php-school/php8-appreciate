<?php

ini_set('display_errors', 1);
date_default_timezone_set('Europe/London');
switch (true) {
    case (file_exists(__DIR__ . '/../vendor/autoload.php')):
        // Installed standalone
        require __DIR__ . '/../vendor/autoload.php';
        break;
    case (file_exists(__DIR__ . '/../../../autoload.php')):
        // Installed as a Composer dependency
        require __DIR__ . '/../../../autoload.php';
        break;
    case (file_exists('vendor/autoload.php')):
        // As a Composer dependency, relative to CWD
        require 'vendor/autoload.php';
        break;
    default:
        throw new RuntimeException('Unable to locate Composer autoloader; please run "composer install".');
}

use PhpSchool\PHP8Appreciate\Exercise\AllMixedUp;
use PhpSchool\PHP8Appreciate\Exercise\AMatchMadeInHeaven;
use PhpSchool\PHP8Appreciate\Exercise\ASafeSpaceForNulls;
use PhpSchool\PHP8Appreciate\Exercise\CautionWithCatches;
use PhpSchool\PHP8Appreciate\Exercise\HaveTheLastSay;
use PhpSchool\PHP8Appreciate\Exercise\InfiniteDivisions;
use PhpSchool\PHP8Appreciate\Exercise\PhpPromotion;
use PhpSchool\PHP8Appreciate\Exercise\LordOfTheStrings;
use PhpSchool\PHP8Appreciate\Exercise\TheAttributesOfSuccess;
use PhpSchool\PHP8Appreciate\Exercise\TheReturnOfStatic;
use PhpSchool\PHP8Appreciate\Exercise\ThrowAnExpression;
use PhpSchool\PHP8Appreciate\Exercise\UniteTheTypes;
use PhpSchool\PHP8Appreciate\Exercise\StringifyToDemystify;
use PhpSchool\PhpWorkshop\Application;

$app = new Application('PHP8 Appreciate', __DIR__ . '/config.php');

$app->addExercise(AMatchMadeInHeaven::class);
$app->addExercise(HaveTheLastSay::class);
$app->addExercise(PhpPromotion::class);
$app->addExercise(CautionWithCatches::class);
$app->addExercise(LordOfTheStrings::class);
$app->addExercise(UniteTheTypes::class);
$app->addExercise(InfiniteDivisions::class);
$app->addExercise(ASafeSpaceForNulls::class);
$app->addExercise(AllMixedUp::class);
$app->addExercise(TheReturnOfStatic::class);
$app->addExercise(ThrowAnExpression::class);
$app->addExercise(StringifyToDemystify::class);
$app->addExercise(TheAttributesOfSuccess::class);

$art = <<<ART

             ___    _  _     ___            ___   
    o O O   | _ \  | || |   | _ \          ( _ )  
   o        |  _/  | __ |   |  _/   ___    / _ \  
  TS__[O]  _|_|_   |_||_|  _|_|_   |___|   \___/  
 {======|_| """ |_|"""""|_| """ |_|"""""|_|"""""| 
./o--000'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-'"`-0-0-' 

                PHP 8 APPRECIATE
      DON'T LEAVE YOUR APPS IN THE STATION!                 
ART;

$app->setLogo($art);
$app->setFgColour('white');
$app->setBgColour('61');

return $app;
