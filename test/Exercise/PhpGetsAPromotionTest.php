<?php

declare(strict_types=1);

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\PhpGetsAPromotion;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class PhpGetsAPromotionTest extends WorkshopExerciseTest
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

    public function testWithNoPropertyPromotion(): void
    {
        $this->runExercise('solution-no-property-promotion.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Properties "visitor" & "key" were not promoted');
    }

    public function testWithNotAllPropertiesPromoted(): void
    {
        $this->runExercise('solution-not-all-promoted.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "key" was not promoted');
    }

    public function testFailureWhenPropertiesMissing(): void
    {
        $this->runExercise('solution-properties-missing.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "key" was missing');
    }

    public function testFailureIfNoClassStatement(): void
    {
        $this->runExercise('solution-no-class.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No class was found');
    }

    public function testFailureWhenPropertyVisibilityChanges(): void
    {
        $this->runExercise('solution-visibility-mutated.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Visibility changed for properties "basePath" & "key"');
    }

    public function testBadPropertyPromotion(): void
    {
        $this->runExercise('solution-incorrect-promotion.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "config" should not be promoted');
    }

    public function testFaliureIfPropertiesChangeType(): void
    {
        $this->runExercise('solution-type-mutated.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Property "key" should not have changed type');
    }

    public function testFailureIfPropertyDataIncorrect(): void
    {
        $this->runExercise('solution-data-not-set.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Data not correctly set on property "basePath"');
    }

    public function testSuccessWithCorrectSolution(): void
    {
        $this->runExercise('solution.php');

        $this->assertVerifyWasSuccessful();
    }
}
