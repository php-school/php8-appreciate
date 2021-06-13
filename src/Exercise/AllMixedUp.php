<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator as FakerGenerator;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Check\FileComparisonCheck;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\SubmissionPatchable;
use PhpSchool\PhpWorkshop\ExerciseCheck\FileComparisonExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Patch;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;

class AllMixedUp extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    SubmissionPatchable,
    FunctionRequirementsExerciseCheck,
    FileComparisonExerciseCheck,
    SelfCheck
{
    private ?Patch $patch = null;

    public function __construct(private Parser $parser, private FakerGenerator $faker)
    {
    }

    public function getName(): string
    {
        return 'All Mixed Up';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s mixed type';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
        $dispatcher->requireCheck(FileComparisonCheck::class);
    }

    public function getArgs(): array
    {
        return [];
    }

    public function getPatch(): Patch
    {
        if ($this->patch) {
            return $this->patch;
        }

        $factory = new BuilderFactory();

        $statements = [
            new Expression(
                new Assign(
                    $factory->var('items'),
                    $factory->val([
                        $this->faker->word(),
                        $this->faker->randomNumber(3),
                        $this->faker->words($this->faker->numberBetween(3, 7)),
                        $this->faker->randomFloat(),
                        $this->faker->boolean(),
                        null,
                        $factory->new('stdClass')
                    ])
                )
            )
        ];

        $statements[] = new Foreach_(
            $factory->var('items'),
            $factory->var('item'),
            [
                'stmts' => [
                    new Expression(
                        $factory->funcCall(
                            'logParameter',
                            $factory->args([$factory->var('item')])
                        )
                    )
                ]
            ]
        );

        return $this->patch = (new Patch())
            ->withTransformer(function (array $originalStatements) use ($statements) {
                return array_merge($originalStatements, $statements);
            });
    }

    public function getFilesToCompare(): array
    {
        return [
            'param.log' => [
                'strip' => '/\d{2}:\d{2}:\d{2}/' //strip out time strings
            ]
        ];
    }

    public function getRequiredFunctions(): array
    {
        return ['get_debug_type'];
    }

    public function getBannedFunctions(): array
    {
        return [];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var Function_|null $logger */
        $logger = (new NodeFinder())->findFirst($statements, function (\PhpParser\Node $node) {
            return $node instanceof Function_ && $node->name->toString() === 'logParameter';
        });

        if (null === $logger) {
            return Failure::fromNameAndReason($this->getName(), 'No function named logParameter was found');
        }

        if (!isset($logger->params[0])) {
            return Failure::fromNameAndReason($this->getName(), 'Function logParameter has no parameters');
        }

        if ($logger->params[0]->type === null) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'Function logParameter has no type for it\'s for first parameter'
            );
        }

        $type = $logger->params[0]->type;

        if (!$type instanceof Identifier || $type->name !== 'mixed') {
            return Failure::fromNameAndReason(
                $this->getName(),
                'Function logParameter does not use the mixed type for it\'s first param'
            );
        }

        return new Success($this->getName());
    }
}
