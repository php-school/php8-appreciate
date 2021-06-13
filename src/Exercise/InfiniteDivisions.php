<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use DivisionByZeroError;
use Faker\Generator as FakerGenerator;
use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Div;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Catch_;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;

class InfiniteDivisions extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    FunctionRequirementsExerciseCheck,
    SelfCheck
{
    public function __construct(private Parser $parser, private FakerGenerator $faker)
    {
    }

    public function getName(): string
    {
        return 'Infinite Divisions';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s fdiv function and DivisionByZeroError exception';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
    }

    public function getArgs(): array
    {
        return [
            [
                (string) $this->faker->numberBetween(10, 100),
                (string) $this->faker->numberBetween(0, 10)
            ]
        ];
    }

    public function getRequiredFunctions(): array
    {
        return ['fdiv'];
    }

    public function getBannedFunctions(): array
    {
        return [];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        $finder = new NodeFinder();

        /** @var Stmt\TryCatch|null $tryCatch */
        $tryCatch = $finder->findFirstInstanceOf($statements, Stmt\TryCatch::class);

        if (!$tryCatch) {
            return new Failure($this->getName(), 'No try/catch statement could be found');
        }

        /** @var Div|null $divOp */
        $divOp = $finder->findFirstInstanceOf($tryCatch->stmts, Div::class);

        if (!$divOp) {
            return new Failure($this->getName(), 'No division operation could be found in the try block');
        }

        /** @var Catch_|null $catch */
        $catch = $finder->findFirst($tryCatch->catches, function (Node $node) {
            if ($node instanceof Catch_) {
                return in_array(
                    DivisionByZeroError::class,
                    array_map(fn (Name $n) => $n->toString(), $node->types),
                    true
                );
            }

            return false;
        });

        if (!$catch) {
            return new Failure($this->getName(), 'No catch block for the DivisionByZeroError exception found');
        }

        return new Success($this->getName());
    }
}
