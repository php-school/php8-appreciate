<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\TheAttributesOfSuccess;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;
use PHPUnit\Framework\TestCase;

class TheAttributesOfSuccessTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return TheAttributesOfSuccess::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function testSuccessfulSolution(): void
    {
        $this->runExercise('correct-solution/solution.php');

        $this->assertVerifyWasSuccessful();
    }

    public function testModifyingExternalCodeFails(): void
    {

    }

    public function testNotCallingDeserializeFails(): void
    {

    }

    public function testNotDumpingObjectFails(): void
    {

    }

    public function testWhenOutputIsIncorrectComparisonFails(): void
    {

    }
}
