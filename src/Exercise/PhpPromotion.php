<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

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

class PhpPromotion extends AbstractExercise implements ExerciseInterface, ProvidesInitialCode, CliExercise, SelfCheck
{
    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'PHP gets a Promotion';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s Constructor Property Promotion';
    }

    public function getInitialCode(): SolutionInterface
    {
        return SingleFileSolution::fromFile(__DIR__ . '/../../exercises/php-promotion/initial/php-7-class.php');
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
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var Class_|null $node */
        $className = (new NodeFinder())->findFirstInstanceOf($statements, Class_::class)?->name->name;

        if (null === $className) {
            return Failure::fromNameAndReason($this->getName(), 'No class was found');
        }

        (static fn () => require $input->getRequiredArgument('program'))();

        $reflectionClass = new ReflectionClass($className);
        $params = collect($reflectionClass->getMethod('__construct')->getParameters());
        $params = $params->flatMap(fn (\ReflectionParameter $prop) => [$prop->getName() => $prop]);

        if ($missing = array_diff(['visitor', 'key', 'config'], $params->keys()->getArrayCopy())) {
            $plural = count($missing) > 1;
            return Failure::fromNameAndReason($this->getName(), sprintf(
                'Propert%s "%s" %s missing',
                $plural ? 'ies' : 'y',
                implode('" & "', $missing),
                $plural ? 'were' : 'was'
            ));
        }

        $promoted = $params->filter(fn (\ReflectionParameter $prop) => $prop->isPromoted());

        if ($notPromoted = array_diff(['visitor', 'key'], $promoted->keys()->getArrayCopy())) {
            $plural = count($notPromoted) > 1;
            return Failure::fromNameAndReason($this->getName(), sprintf(
                'Propert%s "%s" %s not promoted',
                $plural ? 'ies' : 'y',
                implode('" & "', $notPromoted),
                $plural ? 'were' : 'was'
            ));
        }

        if ($promoted->get('config')) {
            return Failure::fromNameAndReason($this->getName(), 'Property "config" should not be promoted');
        }

        $properties = collect($reflectionClass->getProperties());
        $properties = $properties->flatMap(fn (\ReflectionProperty $prop) => [$prop->getName() => $prop]);
        $private = $properties->filter(fn (\ReflectionProperty $prop) => $prop->isPrivate());

        // TODO: New test case, basePath changing visibility?!
        if ($notPrivate = array_diff(['visitor', 'key'], $private->keys()->getArrayCopy())) {
            $plural = count($notPrivate) > 1;
            return Failure::fromNameAndReason($this->getName(), sprintf(
                'Propert%s "%s" visibility changed',
                $plural ? 'ies' : 'y',
                implode('" & "', $notPrivate)
            ));
        }

        $types = $properties->map(fn (\ReflectionProperty $prop) => $prop->getType()->getName());
        $expected = ['visitor' => 'Closure', 'key' => 'string', 'basePath' => 'string'];
        if ([] !== $typeDiff = array_diff_assoc($expected, $types->getArrayCopy())) {
            $plural = count($typeDiff) > 1;
            return Failure::fromNameAndReason($this->getName(), sprintf(
                'Propert%s "%s" should not have changed type',
                $plural ? 'ies' : 'y',
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
            ->map(fn (\ReflectionProperty $prop) => $prop->isInitialized($obj) ? $prop->getValue($obj) : null)
            ->getArrayCopy();

        $dataFailures = array_filter([
            'visitor' => $args['visitor'] !== $actual['visitor'],
            'key' => $args['key'] !== $actual['key'],
            'basePath' => $args['config']['basePath'] !== $actual['basePath'],
        ]);

        if (count($dataFailures)) {
            $plural = count($dataFailures) > 1;
            return Failure::fromNameAndReason($this->getName(), sprintf(
                'Data not correctly set on propert%s "%s"',
                $plural ? 'ies' : 'y',
                implode('" & "', array_keys($dataFailures))
            ));
        }

        return new Success($this->getName());
    }
}
