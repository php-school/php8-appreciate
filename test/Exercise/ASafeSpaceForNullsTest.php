<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\ASafeSpaceForNulls;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FileComparisonFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

class ASafeSpaceForNullsTest extends WorkshopExerciseTest
{
    public function getExerciseClass(): string
    {
        return ASafeSpaceForNulls::class;
    }

    public function getApplication(): Application
    {
        return require __DIR__ . '/../../app/bootstrap.php';
    }

    public function tearDown(): void
    {
        $this->removeSolutionAsset('users.csv');
    }

    public function testFailureWhenAgeAccessedWithoutNullSafe(): void
    {
        $this->runExercise('no-null-safe-age.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The $user->age property should be accessed with the null safe operator'
        );
    }

    public function testFailureWhenAddressAccessedWithoutNullSafe(): void
    {
        $this->runExercise('no-null-safe-address.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The $user->address property should always be accessed with the null safe operator'
        );
    }

    public function testFailureWhenAddressNumberAccessedWithoutNullSafe(): void
    {
        $this->runExercise('no-null-safe-address-number.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The $user->address->number property should be accessed with the null safe operator'
        );
    }

    public function testFailureWhenAddressLine1AccessedWithoutNullSafe(): void
    {
        $this->runExercise('no-null-safe-address-line1.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The $user->address->addressLine1 property should be accessed with the null safe operator'
        );
    }

    public function testFailureWhenAddressLine2AccessedWithoutNullSafe(): void
    {
        $this->runExercise('no-null-safe-address-line2.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The $user->address->addressLine2 property should be accessed with the null safe operator'
        );
    }

    public function testFailureWhenCsvNotExported(): void
    {
        $this->runExercise('no-csv-export.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'File: "users.csv" does not exist'
        );
    }

    public function testFailureWhenCsvNotCorrect(): void
    {
        $this->runExercise('csv-wrong.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'File: "users.csv" does not exist'
        );

//        $this->assertResultsHasFailureAndMatches(
//            FileComparisonFailure::class,
//            function (FileComparisonFailure $failure) {
//                self::assertEquals('users.csv', $failure->getFileName());
//
//                return true;
//            }
//        );
    }
}
