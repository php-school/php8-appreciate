<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\UnionType;
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
use Faker\Generator as FakerGenerator;

class UniteTheTypes extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    SelfCheck,
    SubmissionPatchable
{
    public function __construct(private Parser $parser, private FakerGenerator $faker)
    {
    }

    public function getName(): string
    {
        return 'Unite the Types';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s union types';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function getArgs(): array
    {
        $numbers = array_map(
            function (): string {
                if ($this->faker->boolean()) {
                    return (string) $this->faker->numberBetween(0, 50);
                }
                return (string) $this->faker->randomFloat(3, 0, 50);
            },
            range(0, random_int(5, 15))
        );

        return [$numbers];
    }

    public function getPatch(): Patch
    {
        $code = <<<'CODE'
        $first = array_shift($argv);
        $argv = array_merge([$first], array_map(function ($value) {
            return match (true) {
                (int) $value != (float) $value => (float) $value,
                (bool) random_int(0, 1) => (int) $value,
                default => (string) $value
            };
        }, $argv)); 
        CODE;

        $casterInsertion = new CodeInsertion(CodeInsertion::TYPE_BEFORE, $code);

        return (new Patch())
            ->withTransformer(new Patch\ForceStrictTypes())
            ->withInsertion($casterInsertion);
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var Function_|null $adder */
        $adder = (new NodeFinder())->findFirst($statements, function (\PhpParser\Node $node) {
            return $node instanceof Function_ && $node->name->toString() === 'adder';
        });

        if (null === $adder) {
            return Failure::fromNameAndReason($this->getName(), 'No function named adder was found');
        }

        if (!isset($adder->params[0])) {
            return Failure::fromNameAndReason($this->getName(), 'Function adder has no parameters');
        }

        /** @var \PhpParser\Node\Param $firstParam */
        $firstParam = $adder->params[0];

        if (!$firstParam->type instanceof UnionType) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'Function adder does not use a union type for it\'s first param'
            );
        }

        $incorrectTypes = array_filter(
            $firstParam->type->types,
            fn ($type) => !$type instanceof Identifier
        );

        if (count($incorrectTypes)) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'Union type is incorrect, it should only accept the required types'
            );
        }

        $types = array_map(
            fn (Identifier $type) => $type->__toString(),
            $firstParam->type->types
        );

        sort($types);

        if ($types !== ['float', 'int', 'string']) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'Union type is incorrect, it should only accept the required types'
            );
        }

        if (!$firstParam->variadic) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'Function adder\'s first parameter should be variadic in order to accept multiple arguments'
            );
        }

        return new Success('Union type for adder is correct');
    }
}
