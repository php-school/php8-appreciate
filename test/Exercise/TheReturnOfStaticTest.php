<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\TheReturnOfStatic;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class TheReturnOfStaticTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return TheReturnOfStatic::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testFailureWhenNoClassNameFile(): void
    {
        $this->runExercise('no-file-class.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The method withPermissions cannot be found');
    }

    public function testFailureWhenNoWithPermissionsMethod(): void
    {
        $this->runExercise('no-with-permissions-method.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The method withPermissions cannot be found');
    }

    public function testFailureWhenFirstStatementIsNotAnAssign(): void
    {
        $this->runExercise('no-assign-statement.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The first statement in withPermissions is not an assign');
    }

    public function testFailureWhenAssignIsNotClone(): void
    {
        $this->runExercise('no-clone.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The first statement is not a clone of `$this`');
    }

    public function testFailureWhenAssignIsNotCloneOfThis(): void
    {
        $this->runExercise('no-clone-this.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The first statement is not a clone of `$this`');
    }

    public function testSuccessfulSolution(): void
    {
        $this->runExercise('solution-correct.php');

        $this->assertVerifyWasSuccessful();
    }
}
