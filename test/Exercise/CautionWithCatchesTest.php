<?php

declare(strict_types=1);

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\CautionWithCatches;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class CautionWithCatchesTest extends WorkshopExerciseTest
{

    public function getExerciseClass(): string
    {
        return CautionWithCatches::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testFailureWhenNoTryCatch()
    {
        $this->runExercise('no-try-catch.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No try/catch statement was found');
    }

    public function testFailureWhenCapturingException()
    {
        $this->runExercise('captures-exception.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Exception variable was captured');
    }

    public function testSuccessfulSolution()
    {
        $this->runExercise('solution.php');

        $this->assertVerifyWasSuccessful();
    }
}
