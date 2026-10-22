<?php

declare(strict_types=1);

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\StringifyToDemystify;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class StringifyToDemystifyTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return StringifyToDemystify::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testWithNoClass(): void
    {
        $this->runExercise('solution-no-class.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertSame(['log_failure'], $failure->getMissingFunctions());
                return true;
            }
        );
    }

    public function testWithNotImplementingStringable(): void
    {
        $this->runExercise('solution-no-stringable.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Your class should implement the Stringable interface');
    }

    public function testSolution(): void
    {
        $this->runExercise('solution.php');

        $this->assertVerifyWasSuccessful();
    }
}
