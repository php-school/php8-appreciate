<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator as FakerGenerator;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Node\VarLikeIdentifier;
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
use PhpSchool\PhpWorkshop\Utils\System;

class TheAttributesOfSuccess extends AbstractExercise implements
    ExerciseInterface,
    ProvidesInitialCode,
    CliExercise,
    FunctionRequirementsExerciseCheck,
    FileComparisonExerciseCheck,
    SelfCheck
{
    public function __construct(private Parser $parser, private FakerGenerator $faker)
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
            System::realpath(__DIR__ . '/../../exercises/the-attributes-of-success/solution')
        );
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function getArgs(): array
    {
        return [
            [
                json_encode(
                    [
                        'id' => random_int(0, 100),
                        'comment' => $this->faker->sentence(4),
                        'rating' => $this->faker->numberBetween(0, 5),
                        'reviewer' => $this->faker->userName(),
                        'date' => $this->faker->date('d-m-Y')
                    ],
                    JSON_THROW_ON_ERROR
                )
            ]
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

        if ($classStmt === null) {
            return new Failure($this->getName(), 'A class named Review was not found');
        }

        /** @var ClassMethod|null $method */
        $method = (new NodeFinder())->findFirst($statements, function (Node $node) {
            return $node instanceof ClassMethod && $node->name->name === 'obfuscateReviewer';
        });

        if ($method === null) {
            return new Failure($this->getName(), 'A method named obfuscateReviewer was not found');
        }

        if (!isset($method->attrGroups[0]->attrs[0])) {
            return new Failure($this->getName(), 'No attributes found on method obfuscateReviewer');
        }

        $attribute = $method->attrGroups[0]->attrs[0];

        if ($attribute->name->toString() !== 'Obfuscate') {
            return new Failure($this->getName(), 'No attribute named Obfuscate found on method obfuscateReviewer');
        }

        if (!isset($attribute->args[0])) {
            return new Failure($this->getName(), 'No property name argument was passed to the Obfuscate attribute');
        }

        if (!$attribute->args[0]->value instanceof String_ || $attribute->args[0]->value->value !== 'reviewer') {
            return new Failure($this->getName(), 'The Obfuscate attribute was not passed the correct data property');
        }

        /** @var Class_|null $attributeClass */
        $attributeClass = (new NodeFinder())->findFirst($statements, function (Node $node) {
            return $node instanceof Class_ && $node->name && $node->name->name === 'Obfuscate';
        });

        if ($attributeClass === null) {
            return new Failure($this->getName(), 'A class named Obfuscate was not found');
        }

        if (!isset($attributeClass->attrGroups[0]->attrs[0])) {
            return new Failure($this->getName(), 'No attributes found on class Obfuscate');
        }

        $attribute = $attributeClass->attrGroups[0]->attrs[0];

        if ($attribute->name->toString() !== 'Attribute') {
            return new Failure($this->getName(), 'The Obfuscate class was not defined as an Attribute');
        }

        if (!isset($attribute->args[0])) {
            return new Failure($this->getName(), 'No flags were passed to Obfuscate Attribute definition');
        }

        /** @var ClassConstFetch $value */
        $value = $attribute->args[0]->value;

        if (
            $value->class->toString() !== 'Attribute'
            || !$value->name instanceof Identifier
            || $value->name->name !== 'TARGET_METHOD'
        ) {
            return new Failure(
                $this->getName(),
                'The Obfuscate Attribute was not configured as Attribute::TARGET_METHOD'
            );
        }

        $prop = (new NodeFinder())->findFirst($attributeClass->getProperties(), function (Node $node) {
            return $node instanceof Property
                && $node->isPublic()
                && $node->type instanceof Identifier
                && $node->type->name === 'string'
                && $node->props[0] instanceof PropertyProperty
                && $node->props[0]->name instanceof VarLikeIdentifier
                && $node->props[0]->name->name === 'key';
        });

        $promotedProp = (new NodeFinder())->findFirst($attributeClass->getMethods(), function (Node $node) {
            return $node instanceof ClassMethod
                && $node->name->name === '__construct'
                && isset($node->params[0])
                && $node->params[0]->flags === 1
                && $node->params[0]->var instanceof Variable
                && $node->params[0]->var->name === 'key'
                && $node->params[0]->type instanceof Identifier
                && $node->params[0]->type->name === 'string';
        });

        if ($prop === null && $promotedProp === null) {
            return new Failure(
                $this->getName(),
                'The Obfuscate Attribute has no public property named "key"'
            );
        }


        return new Success($this->getName());
    }

    public function getRequiredFunctions(): array
    {
        return [
            'deserialize',
            'var_dump'
        ];
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
