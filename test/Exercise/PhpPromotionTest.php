<?php

declare(strict_types=1);

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\PhpGetsAPromotion;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class PhpPromotionTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return PhpGetsAPromotion::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testWithNoPropertyPromotion()
    {
        $this->runExercise('solution-no-property-promotion.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Properties "visitor" & "key" were not promoted');
    }

    public function testWithNotAllPropertiesPromoted()
    {
        $this->runExercise('solution-not-all-promoted.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "key" was not promoted');
    }

    public function testFailureWhenPropertiesMissing()
    {
        $this->runExercise('solution-properties-missing.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "key" was missing');
    }

    public function testFailureIfNoClassStatement()
    {
        $this->runExercise('solution-no-class.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No class was found');
    }

    public function testFailureWhenPropertyVisibilityChanges()
    {
        $this->runExercise('solution-visibility-mutated.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Visibility changed for properties "basePath" & "key"');
    }

    public function testBadPropertyPromotion()
    {
        $this->runExercise('solution-incorrect-promotion.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "config" should not be promoted');
    }

    public function testFaliureIfPropertiesChangeType()
    {
        $this->runExercise('solution-type-mutated.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "key" should not have changed type');
    }

    public function testFailureIfPropertyDataIncorrect()
    {
        $this->runExercise('solution-data-not-set.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Data not correctly set on property "basePath"');
    }

    public function testSuccessWithCorrectSolution()
    {
        $this->runExercise('solution.php');

        $this->assertVerifyWasSuccessful();
    }
}
