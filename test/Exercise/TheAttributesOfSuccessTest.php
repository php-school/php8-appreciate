<?php

namespace PhpSchool\PHP8AppreciateTest\Exercise;

use PhpSchool\PHP8Appreciate\Exercise\TheAttributesOfSuccess;
use PhpSchool\PhpWorkshop\Application;
use PhpSchool\PhpWorkshop\Result\Cli\RequestFailure;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FileComparisonFailure;
use PhpSchool\PhpWorkshop\Result\FunctionRequirementsFailure;
use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

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
        $this->assertOutputWasCorrect();
    }

    public function testSuccessfulSolutionWithPromotedProperty(): void
    {
        $this->runExercise('correct-solution-promoted/solution.php');

        $this->assertVerifyWasSuccessful();
        $this->assertOutputWasCorrect();
    }

    public function testModifyingExternalCodeFails(): void
    {
        $this->runExercise('modified-external-code/solution.php');

        $this->assertVerifyWasNotSuccessful();
        $this->assertOutputWasCorrect();

        $this->assertResultsHasFailureAndMatches(
            FileComparisonFailure::class,
            function (FileComparisonFailure $failure) {
                self::assertEquals('deserialize.php', $failure->getFileName());

                return true;
            }
        );
    }

    public function testNotCallingDeserializeFails(): void
    {
        $this->runExercise('no-deserialize-call/solution.php');

        $this->assertVerifyWasNotSuccessful();
        $this->assertOutputWasIncorrect();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertSame(['deserialize'], $failure->getMissingFunctions());
                return true;
            }
        );
    }

    public function testNotDumpingObjectFails(): void
    {
        $this->runExercise('no-var-dump/solution.php');

        $this->assertVerifyWasNotSuccessful();
        $this->assertOutputWasIncorrect();

        $this->assertResultsHasFailureAndMatches(
            FunctionRequirementsFailure::class,
            function (FunctionRequirementsFailure $failure) {
                self::assertSame(['var_dump'], $failure->getMissingFunctions());
                return true;
            }
        );
    }

    public function testWhenOutputIsIncorrectComparisonFails(): void
    {
        $this->runExercise('incorrect-output/solution.php');

        $this->assertVerifyWasNotSuccessful();
        $this->assertOutputWasIncorrect();

        $output = $this->getOutputResult();

        self::assertCount(1, $output->getResults());
        self::assertInstanceOf(RequestFailure::class, $output->getResults()[0]);
    }

    public function testWhenNoClassNamedReviewDefined(): void
    {
        $this->runExercise('no-review-class/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'A class named Review was not found');
    }

    public function testWhenNoMethodNamedObfuscateReviewerDefined(): void
    {
        $this->runExercise('no-obfuscate-method/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'A method named obfuscateReviewer was not found');
    }

    public function testWhenNoAttributeDefinedOnObfuscateReviewerMethod(): void
    {
        $this->runExercise('no-attributes/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No attributes found on method obfuscateReviewer');
    }

    public function testWhenNoAttributedNamedObfuscateUsedOnMethod(): void
    {
        $this->runExercise('no-attribute-named-obfuscate/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'No attribute named Obfuscate found on method obfuscateReviewer'
        );
    }

    public function testWhenNoArgumentsPassedToObfuscateAttribute(): void
    {
        $this->runExercise('no-arguments-obfuscate-attribute/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'No property name argument was passed to the Obfuscate attribute'
        );
    }

    public function testWhenIncorrectPropertyPassedToObfuscateAttribute(): void
    {
        $this->runExercise('invalid-arg-obfuscate-attribute/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The Obfuscate attribute was not passed the correct data property'
        );
    }

    public function testWhenObfuscateAttributeNotDefined(): void
    {
        $this->runExercise('no-obfuscate-class/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'A class named Obfuscate was not found');
    }

    public function testWhenObfuscateHasNoAttributes(): void
    {
        $this->runExercise('obfuscate-no-attributes/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No attributes found on class Obfuscate');
    }

    public function testWhenObfuscateAttributeIncorrectlyDefined(): void
    {
        $this->runExercise('obfuscate-attribute-incorrect/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'The Obfuscate class was not defined as an Attribute');
    }

    public function testWhenObfuscateAttributeHasNoFlags(): void
    {
        $this->runExercise('obfuscate-attribute-no-flags/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(Failure::class, 'No flags were passed to Obfuscate Attribute definition');
    }

    public function testWhenObfuscateAttributeConfigurationIsWrong(): void
    {
        $this->runExercise('obfuscate-attribute-wrong-target/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The Obfuscate Attribute was not configured as Attribute::TARGET_METHOD'
        );
    }

    public function testWhenObfuscateAttributeHasNoPublicPropertyNamedKey(): void
    {
        $this->runExercise('no-public-property-named-key/solution.php');

        $this->assertVerifyWasNotSuccessful();

        $this->assertResultsHasFailure(
            Failure::class,
            'The Obfuscate Attribute has no public property named "key"'
        );
    }
}
