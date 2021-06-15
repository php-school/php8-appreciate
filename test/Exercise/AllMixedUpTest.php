<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\AllMixedUp;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FileComparisonFailure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class AllMixedUpTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return AllMixedUp::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function tearDown(): void
    {
        $this->removeSolutionAsset('param.log');
        parent::tearDown();
    }

    public function testSuccessfulSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }

    public function testFailureWhenNoFunctionNamedLogParam(): void
    {
        $this->runExercise('no-log-param-function.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No function named logParameter was found');
    }

    public function testFailureWhenLogParamFunctionHasNoParams(): void
    {
        $this->runExercise('no-function-params.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Function logParameter has no parameters'
        );
    }

    public function testFailureWhenLogParamFunctionFirstParamHasNoType(): void
    {
        $this->runExercise('no-type-param.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Function logParameter has no type for it\'s for first parameter'
        );
    }

    public function testFailureWhenLogParamFunctionFirstParamIsNotMixed(): void
    {
        $this->runExercise('no-mixed-type-param.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Function logParameter does not use the mixed type for it\'s first param'
        );
    }

    public function testFailureWhenNotUsingGetDebugTypeFunction(): void
    {
        $this->runExercise('no-get-debug-type-function.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertEquals(['get_debug_type'], $failure->getMissingFunctions());

                return true;
            }
        );
    }

    public function testFailureWhenLogFileNotWritten(): void
    {
        $this->runExercise('no-log-file-written.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'File: "param.log" does not exist'
        );
    }

    public function testFailureWhenLogFileNotCorrect(): void
    {
        $this->runExercise('log-file-wrong.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(
            FileComparisonFailure::class,
            function (FileComparisonFailure $failure) {
                self::assertEquals('param.log', $failure->getFileName());

                return true;
            }
        );
    }

    public function testFailureWhenLogFileNotCorrectTimes(): void
    {
        $this->runExercise('log-file-wrong-times.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(
            FileComparisonFailure::class,
            function (FileComparisonFailure $failure) {
                self::assertEquals('param.log', $failure->getFileName());

                return true;
            }
        );
    }
}
