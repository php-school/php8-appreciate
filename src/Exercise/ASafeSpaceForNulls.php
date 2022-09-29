<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator as FakerGenerator;
use Faker\Provider\en_US\Address;
use Faker\Provider\en_US\Person;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Check\FileComparisonCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\SubmissionPatchable;
use PhpSchool\PhpWorkshop\ExerciseCheck\FileComparisonExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Patch;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;

class ASafeSpaceForNulls extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    SubmissionPatchable,
    FileComparisonExerciseCheck,
    SelfCheck
{
    private ?Patch $patch = null;

    public function __construct(private Parser $parser, private FakerGenerator $faker)
    {
    }

    public function getName(): string
    {
        return 'A Safe Space for Nulls';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s Null Safe Operator';
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    public function getArgs(): array
    {
        return [];
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FileComparisonCheck::class);
    }

    public function getPatch(): Patch
    {
        if ($this->patch) {
            return $this->patch;
        }


        $factory = new BuilderFactory();

        $statements = [];
        $statements[] = $factory->class('User')
            ->addStmt($factory->property('firstName')->setType('string')->makePublic())
            ->addStmt($factory->property('lastName')->setType('string')->makePublic())
            ->addStmt($factory->property('age')->setType(new NullableType('int'))->makePublic()->setDefault(null))
            ->addStmt($factory->property('address')
                ->setType(new NullableType('Address'))
                ->makePublic()
                ->setDefault(null))
            ->getNode();

        $statements[] = $factory->class('Address')
            ->addStmt($factory->property('number')->setType('int')->makePublic())
            ->addStmt($factory->property('addressLine1')->setType('string')->makePublic())
            ->addStmt($factory->property('addressLine2')
                ->setType(new NullableType('string'))
                ->makePublic()
                ->setDefault(null))
            ->getNode();

        $addressFaker = new Address($this->faker);
        $personFaker = new Person($this->faker);

        $statements[] = new Expression(
            new Assign($factory->var('user'), $factory->new('User'))
        );
        $statements[] = new Expression(
            new Assign(
                $factory->propertyFetch($factory->var('user'), 'firstName'),
                $factory->val($personFaker->firstName())
            )
        );
        $statements[] = new Expression(
            new Assign(
                $factory->propertyFetch($factory->var('user'), 'lastName'),
                $factory->val($personFaker->lastName())
            )
        );

        if ($this->faker->boolean()) {
            $statements[] = new Expression(
                new Assign(
                    $factory->propertyFetch($factory->var('user'), 'age'),
                    $factory->val($this->faker->numberBetween(18, 100))
                )
            );
        }

        if ($this->faker->boolean()) {
            $statements[] = new Expression(
                new Assign(
                    $factory->propertyFetch($factory->var('user'), 'address'),
                    $factory->new('Address')
                )
            );
            $statements[] = new Expression(
                new Assign(
                    $factory->propertyFetch(
                        $factory->propertyFetch($factory->var('user'), 'address'),
                        'number'
                    ),
                    $factory->val($addressFaker->buildingNumber())
                )
            );
            $statements[] = new Expression(
                new Assign(
                    $factory->propertyFetch(
                        $factory->propertyFetch($factory->var('user'), 'address'),
                        'addressLine1'
                    ),
                    $factory->val($addressFaker->streetName())
                )
            );

            if ($this->faker->boolean()) {
                $statements[] = new Expression(
                    new Assign(
                        $factory->propertyFetch(
                            $factory->propertyFetch($factory->var('user'), 'address'),
                            'addressLine2'
                        ),
                        $factory->val($addressFaker->secondaryAddress())
                    )
                );
            }
        }

        return $this->patch = (new Patch())
            ->withTransformer(function (array $originalStatements) use ($statements) {
                return array_merge($statements, $originalStatements);
            });
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        $ageFetch = $this->findNullSafePropFetch($statements, 'user', 'age');
        $addressFetch = $this->findAllNullSafePropertyFetch($statements, 'user', 'address');

        if ($ageFetch === null) {
            return new Failure(
                $this->getName(),
                'The $user->age property should be accessed with the null safe operator'
            );
        }

        if (count($addressFetch) < 3) {
            return new Failure(
                $this->getName(),
                'The $user->address property should always be accessed with the null safe operator'
            );
        }

        $props = [
            '$user->address->number' => $this->findNestedNullSafePropFetch($statements, 'user', 'number'),
            '$user->address->addressLine1' => $this->findNestedNullSafePropFetch($statements, 'user', 'addressLine1'),
            '$user->address->addressLine2' => $this->findNestedNullSafePropFetch($statements, 'user', 'addressLine2'),
        ];

        foreach ($props as $prop => $node) {
            if ($node === null) {
                return new Failure(
                    $this->getName(),
                    "The $prop property should be accessed with the null safe operator"
                );
            }
        }

        return new Success($this->getName());
    }

    /**
     * @param array<Node> $statements
     */
    private function findNullSafePropFetch(array $statements, string $variableName, string $propName): ?Node
    {
        $nodes = $this->findAllNullSafePropertyFetch($statements, $variableName, $propName);
        return count($nodes) > 0 ? $nodes[0] : null;
    }

    /**
     * @param array<Node> $statements
     * @return array<Node>
     */
    private function findAllNullSafePropertyFetch(array $statements, string $variableName, string $propName): array
    {
        return (new NodeFinder())->find($statements, function (Node $node) use ($variableName, $propName) {
            return $node instanceof NullsafePropertyFetch
                && $node->var instanceof Variable
                && $node->var->name === $variableName
                && $node->name instanceof Identifier
                && $node->name->name === $propName;
        });
    }

    /**
     * @param array<Node> $statements
     */
    private function findNestedNullSafePropFetch(array $statements, string $variableName, string $propName): ?Node
    {
        return (new NodeFinder())->findFirst($statements, function (Node $node) use ($variableName, $propName) {
            return $node instanceof NullsafePropertyFetch
                && $node->var instanceof NullsafePropertyFetch
                && $node->var->var instanceof Variable
                && $node->var->var->name === $variableName
                && $node->name instanceof Identifier
                && $node->name->name === $propName;
        });
    }

    public function getFilesToCompare(): array
    {
        return [
            'users.csv'
        ];
    }
}
