<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\CodeInsertion;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\SubmissionPatchable;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Patch;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;

class CautionWithCatches extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    SelfCheck,
    SubmissionPatchable
{
    private string $password = '';

    public function __construct(private Parser $parser, private Generator $faker)
    {
    }

    public function getName(): string
    {
        return 'Caution with Catches';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s Non-capturing Catches';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function getArgs(): array
    {
        $this->password = $this->faker->password();
        return [[$this->password]];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var TryCatch|null $tryCatch */
        $tryCatch = (new NodeFinder())->findFirstInstanceOf($statements, TryCatch::class);

        if (null === $tryCatch) {
            return Failure::fromNameAndReason($this->getName(), 'No try/catch statement was found');
        }

        if (count($tryCatch->catches) > 0 && $tryCatch->catches[0]->var !== null) {
            return Failure::fromNameAndReason($this->getName(), 'Exception variable was captured');
        }

        return new Success($this->getName());
    }

    public function getPatch(): Patch
    {
        $code = <<<CODE
        class InvalidPasswordException extends \RuntimeException {}
        function verify_password(string \$password) {
            throw new InvalidPasswordException(sprintf('The password "%s" is invalid', \$password));
        }
        CODE;

        $passwordVerifyInsertion = new CodeInsertion(CodeInsertion::TYPE_BEFORE, $code);

        return (new Patch())->withInsertion($passwordVerifyInsertion);
    }
}
