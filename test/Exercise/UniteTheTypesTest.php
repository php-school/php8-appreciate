<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\UniteTheTypes;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class UniteTheTypesTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return UniteTheTypes::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testFailureWhenNoFunctionNamedAdder(): void
    {
        $this->runExercise('no-adder-function.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No function named adder was found');
    }

    public function testFailureWhenAdderFunctionHasNoParams(): void
    {
        $this->runExercise('no-function-params.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'Function adder has no parameters');
    }

    public function testFailureWhenAdderFunctionHasNoUnionTypeParam(): void
    {
        $this->runExercise('no-union-type-param.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Function adder does not use a union type for it\'s first param'
        );
    }

    public function testFailureWhenAdderFunctionHasClassTypeInUnion(): void
    {
        $this->runExercise('incorrect-union-class-type.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Union type is incorrect, it should only accept the required types'
        );
    }

    public function testFailureWhenAdderFunctionHasIncorrectUnion(): void
    {
        $this->runExercise('incorrect-union-scalar-type.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Union type is incorrect, it should only accept the required types'
        );
    }

    public function testFailureWhenAdderFunctionHasCorrectUnionWithExtraTypes(): void
    {
        $this->runExercise('incorrect-union-extra-type.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Union type is incorrect, it should only accept the required types'
        );
    }

    public function testFailureWhenAdderFunctionParamIsNotVariadic(): void
    {
        $this->runExercise('union-type-param-not-variadic.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'Function adder\'s first parameter should be variadic in order to accept multiple arguments'
        );
    }

    public function testSuccessfulSolution(): void
    {
        $this->runExercise('correct-union-type-same-order.php');

        $this->assertVerifyWasSuccessful();
    }

    public function testSuccessfulSolutionWithDifferentOrderUnion(): void
    {
        $this->runExercise('correct-union-type-diff-order.php');

        $this->assertVerifyWasSuccessful();
    }

    public function testSuccessfulSolutionWithFloatReturnType(): void
    {
        $this->runExercise('correct-union-type-float-return.php');

        $this->assertVerifyWasSuccessful();
    }
}
