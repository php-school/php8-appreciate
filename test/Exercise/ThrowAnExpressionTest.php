<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\ThrowAnExpression;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Cgi\GenericFailure;
use PhpSchool\PhpWorkshop\Result\Cgi\RequestFailure;
use PhpSchool\PhpWorkshop\Result\Cgi\Success;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class ThrowAnExpressionTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return ThrowAnExpression::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testThrowingWrongException(): void
    {
        $this->runExercise('wrong-exception.php');

        $this->assertVerifyWasNotSuccessful();

        $output = $this->getOutputResult();

        self::assertInstanceOf(Success::class, $output->getResults()[0]);
        self::assertInstanceOf(GenericFailure::class, $output->getResults()[1]);

        self::assertMatchesRegularExpression(
            '/Fatal error:  Uncaught Exception: Access denied!/',
            $output->getResults()[1]->getReason()
        );

        $this->assertOutputWasIncorrect();
    }

    public function testUsingIfStatement(): void
    {
        $this->runExercise('if-statement.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'If statement found');

        $this->assertOutputWasCorrect();
    }

    public function testUsingNoTernary(): void
    {
        $this->runExercise('no-ternary.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No ternary statement found');

        $this->assertOutputWasIncorrect();
    }

    public function testNoThrowExpression(): void
    {
        $this->runExercise('no-throw-expression.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Ternary does not make use of throw expression');

        $this->assertOutputWasCorrect();
    }

    public function testSuccessfulSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
        $this->assertOutputWasCorrect();
    }
}
