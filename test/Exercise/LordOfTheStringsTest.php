<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\LordOfTheStrings;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class LordOfTheStringsTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return LordOfTheStrings::class;
    }

    public function testWithNoComposerFile(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();
        $this->assertResultsHasFailure(Failure::class, 'No composer.json file found');
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('no-code/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testUsingBannedFunction(): void
    {
        $this->runExercise('banned-functions/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertSame(
                    [
                        ['function' => 'strpos', 'line' => 14],
                        ['function' => 'strpos', 'line' => 15],
                        ['function' => 'substr', 'line' => 16],
                    ],
                    $failure->getBannedFunctions()
                );

                self::assertSame(
                    ['str_contains', 'str_starts_with', 'str_ends_with'],
                    $failure->getMissingFunctions()
                );

                return true;
            }
        );
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('correct-solution/solution.php');

        $this->assertVerifyWasSuccessful();
    }
}
