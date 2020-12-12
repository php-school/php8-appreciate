<?php

declare(strict_types=1);

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\AMatchMadeInHeaven;
use PhpSchool\PHP8Appreciate\Exercise\HaveTheLastSay;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class HaveTheLastSayTest extends WorkshopExerciseTest
{
    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function getExerciseClass(): string
    {
        return HaveTheLastSay::class;
    }

    public function testWithNoCode(): void
    {
        $this->runExercise('solution-no-code.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No code was found');
    }

    public function testWithNoStreamArgument(): void
    {
        $this->runExercise('solution-no-stream-arg.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The stream argument must be specified using a positional parameter');
    }

    public function testWithStreamAsNamedArgument(): void
    {
        $this->runExercise('solution-stream-as-named-arg.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The stream argument must be specified using a positional parameter');
    }

    public function testWithSecondArgumentSpecified(): void
    {
        $this->runExercise('solution-second-arg-specified.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Second positional argument should not be specified');
    }

    public function testWithMoreThanTwoArguments(): void
    {
        $this->runExercise('solution-three-args-specified.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'You should only specify the stream and separator arguments, no others');
    }

    public function testWithNoSeparatorArgumentSpecified(): void
    {
        $this->runExercise('solution-no-separator-arg-specified.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The separator argument has not specified');
    }

    public function testWithWrongNamedArgument(): void
    {
        $this->runExercise('solution-wrong-named-arg.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'A named argument has been used, but not for the separator argument');
    }

    public function testWithNoFgetCsv(): void
    {
        $this->runExercise('solution-without-fgetcsv.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                return ['fgetcsv'] === $failure->getMissingFunctions();
            }
        );

        $this->assertOutputWasCorrect();
    }

    public function testWithIncorrectOutput(): void
    {
        $this->runExercise('solution-wrong-output.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertOutputWasIncorrect();
    }

    public function testWithCorrectSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
