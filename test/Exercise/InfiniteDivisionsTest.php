<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\InfiniteDivisions;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class InfiniteDivisionsTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return InfiniteDivisions::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testFailureWhenNoTryCatch(): void
    {
        $this->runExercise('no-try-catch.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No try/catch statement could be found');
    }

    public function testFailureWhenNoDivisionOperationFoundInTryBlock(): void
    {
        $this->runExercise('no-divide-in-try.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No division operation could be found in the try block');
    }

    public function testFailureWhenNoDivisionByZeroCatch(): void
    {
        $this->runExercise('no-division-by-zero-catch.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No catch block for the DivisionByZeroError exception found');
    }

    public function testSuccessfulSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
