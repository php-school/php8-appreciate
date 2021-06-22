<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator as FakerGenerator;
use PhpParser\Node;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Check\FileComparisonCheck;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\ProvidesInitialCode;
use PhpSchool\PhpWorkshop\ExerciseCheck\FileComparisonExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\DirectorySolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;

class TheAttributesOfSuccess extends AbstractExercise implements
    ExerciseInterface,
    ProvidesInitialCode,
    CliExercise,
    FunctionRequirementsExerciseCheck,
    FileComparisonExerciseCheck,
    SelfCheck
{
    public function __construct(private Parser $parser,  private FakerGenerator $faker)
    {
    }

    public function getName(): string
    {
        return 'The Attributes of Success';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s Attributes';
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FileComparisonCheck::class);
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
    }

    public function getInitialCode(): SolutionInterface
    {
        return DirectorySolution::fromDirectory(
            __DIR__ . '/../../exercises/the-attributes-of-success/initial',
            entryPoint: 'the-attributes-of-success.php'
        );
    }

    public function getSolution(): SolutionInterface
    {
        return DirectorySolution::fromDirectory(
            realpath(__DIR__ . '/../../exercises/the-attributes-of-success/solution'),
        );
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function getArgs(): array
    {
        return [
            json_encode(
                [
                    'comment' => $this->faker->sentence(4),
                    'rating' => $this->faker->numberBetween(0, 5),
                    'reviewer' => $this->faker->userName(),
                    'date' => $this->faker->date('d-m-Y')
                ],
                JSON_THROW_ON_ERROR
            )
        ];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var Class_|null $classStmt */
        $classStmt = (new NodeFinder())->findFirst($statements, function (Node $node) {
            return $node instanceof Class_ && $node->name && $node->name->name === 'Review';
        });

        //not even sure we need this the var_dump will cover it
        if ($classStmt === null) {
            return new Failure($this->getName(), 'A class named Review was not found');
        }

        return new Success($this->getName());
    }

    public function getRequiredFunctions(): array
    {
        return ['deserialize'];
    }

    public function getBannedFunctions(): array
    {
        return [];
    }

    public function getFilesToCompare(): array
    {
        return [
            'attributes.php',
            'deserialize.php'
        ];
    }
}
