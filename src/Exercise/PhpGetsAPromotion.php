<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\ProvidesInitialCode;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\SingleFileSolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;
use ReflectionClass;

class PhpGetsAPromotion extends AbstractExercise implements ExerciseInterface, ProvidesInitialCode, CliExercise, SelfCheck
{
    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'PHP Gets a Promotion';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s Constructor Property Promotion';
    }

    public function getInitialCode(): SolutionInterface
    {
        return SingleFileSolution::fromFile(__DIR__ . '/../../exercises/php-gets-a-promotion/initial/php-gets-a-promotion.php');
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function getArgs(): array
    {
        return [];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));
        /** @var Class_|null $classNode */
        $classNode = (new NodeFinder())->findFirstInstanceOf($statements, Class_::class);

        if (null === $classNode || null === $className = $classNode->name?->name) {
            return Failure::fromNameAndReason($this->getName(), 'No class was found');
        }

        (static fn () => require $input->getRequiredArgument('program'))();

        /** @var class-string $className */
        $reflectionClass = new ReflectionClass($className);
        $params = collect($reflectionClass->getMethod('__construct')->getParameters());
        $params = $params->flatMap(fn (\ReflectionParameter $prop) => [$prop->getName() => $prop]);

        if ($missing = array_diff(['visitor', 'key', 'config'], $params->keys()->getArrayCopy())) {
            return Failure::fromNameAndReason($this->getName(), pluralise(
                'Property "%s" was missing',
                $missing,
                implode('" & "', $missing)
            ));
        }

        $promoted = $params->filter(fn (\ReflectionParameter $prop) => $prop->isPromoted());

        if ($notPromoted = array_diff(['visitor', 'key'], $promoted->keys()->getArrayCopy())) {
            return Failure::fromNameAndReason($this->getName(), pluralise(
                'Property "%s" was not promoted',
                $notPromoted,
                implode('" & "', $notPromoted)
            ));
        }

        if ($promoted->get('config')) {
            return Failure::fromNameAndReason($this->getName(), 'Property "config" should not be promoted');
        }

        $expectedProperties = [
            'basePath' => 'protected',
            'key' => 'private',
            'visitor' => 'private',
        ];

        $properties = collect($reflectionClass->getProperties());
        $visibilities = $properties->flatMap(fn (\ReflectionProperty $prop) => [
            $prop->getName() => $this->getPropertyVisibility($prop)
        ])->ksort();

        if ($changedVisibility = array_keys(array_diff_assoc($expectedProperties, $visibilities->getArrayCopy()))) {
            return Failure::fromNameAndReason($this->getName(), pluralise(
                'Visibility changed for property "%s"',
                $changedVisibility,
                implode('" & "', $changedVisibility)
            ));
        }

        $types = $properties->flatMap(fn (\ReflectionProperty $prop) => [
            $prop->getName() => $this->getPropertyType($prop)
        ]);
        $expected = ['visitor' => 'Closure', 'key' => 'string', 'basePath' => 'string'];
        if ([] !== $typeDiff = array_diff_assoc($expected, $types->getArrayCopy())) {
            return Failure::fromNameAndReason($this->getName(), pluralise(
                'Property "%s" should not have changed type',
                array_keys($typeDiff),
                implode('" & "', array_keys($typeDiff))
            ));
        }

        // Test Invocation
        $args = [
            'visitor' => \Closure::fromCallable(fn (array $line) => $line[2]),
            'key' => 'value',
            'config' => ['basePath' => '/some/base/path']
        ];

        $obj = new $className(...$args);

        $actual = $properties
            ->each(fn (\ReflectionProperty $prop) => $prop->setAccessible(true))
            ->flatMap(fn (\ReflectionProperty $prop) => [
                $prop->getName() => $prop->isInitialized($obj) ? $prop->getValue($obj) : null
            ])
            ->getArrayCopy();

        /** @var array{string?: string} $dataFailures */
        $dataFailures = array_filter([
            'visitor' => $args['visitor'] !== $actual['visitor'],
            'key' => $args['key'] !== $actual['key'],
            'basePath' => $args['config']['basePath'] !== $actual['basePath'],
        ]);

        if (count($dataFailures)) {
            return Failure::fromNameAndReason($this->getName(), pluralise(
                'Data not correctly set on property "%s"',
                array_keys($dataFailures),
                implode('" & "', array_keys($dataFailures))
            ));
        }

        return new Success($this->getName());
    }

    private function getPropertyVisibility(\ReflectionProperty $prop): string
    {
        return match (true) {
            $prop->isPrivate() => 'private',
            $prop->isProtected() => 'protected',
            default => 'public',
        };
    }

    private function getPropertyType(\ReflectionProperty $prop): string
    {
        $type = $prop->getType();

        if (null === $type || !$type instanceof \ReflectionNamedType) {
            throw new \RuntimeException(sprintf('Invalid property "%s"', $prop->getName()));
        }
        /** @var \ReflectionNamedType $type */
        return $type->getName();
    }
}
