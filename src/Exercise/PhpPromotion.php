<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

use _HumbugBoxfb21822734fc\Roave\BetterReflection\Reflection\Adapter\ReflectionObject;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\ProvidesInitialCode;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
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
        $statements = $this->parser->parse((string)file_get_contents($input->getRequiredArgument('program')));

        if (null === $statements || empty($statements)) {
            return Failure::fromNameAndReason($this->getName(), 'No code was found');
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor = new class extends NodeVisitorAbstract {
            public bool $hasClasSNode = false;
            public string $classname = '';

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Stmt\Class_) {
                    $this->hasClasSNode = true;
                    $this->classname    = $node->name->name;
                }
            }
        });

        $traverser->traverse($statements);

        if (!$visitor->hasClasSNode) {
            return Failure::fromNameAndReason($this->getName(), 'No class was found');
        }

        return (function () use ($input, $visitor): ResultInterface {
            require $input->getRequiredArgument('program');

            $class     = new ReflectionClass($visitor->classname);
            $construct = $class->getMethod('__construct');
            $params    = $construct->getParameters();

            $promotedProperties = [];
            $expected = ['key', 'visitor'];
            $failed   = [];
            $missing  = ['key', 'visitor'];
            $visibility = [];
            $types = ['visitor' => 'Closure', 'key' => 'string', 'basePath' => 'string'];
            $typeErrors = [];

            foreach ($params as $param) {
                if ($param->isPromoted()) {
                    $promotedProperties[] = $param->getName();
                }

                if (($key = array_search($param->getName(), $missing, true)) !== false) {
                    unset($missing[$key]);
                }

                if (!in_array($param->getName(), $expected)) {
                    continue;
                }

                if (!$param->isPromoted()) {
                    $failed[] = $param->getName();
                }

                if (!$class->getProperty($param->getName())->isPrivate()) {
                    $visibility[] = $param->getName();
                }

                $expectedType = $types[$param->getName()];
                $propertyType = $class->getProperty($param->getName())->getType();
                $validProperty = $propertyType && $propertyType instanceof \ReflectionNamedType;
                if ($validProperty && $expectedType !== $propertyType->getName()) {
                    $typeErrors[] = $param->getName();
                }
            }

            if (count($missing) > 0) {
                $plural = count($missing) > 1;
                return Failure::fromNameAndReason($this->getName(), sprintf(
                    'Propert%s "%s" %s missing',
                    $plural ? 'ies' : 'y',
                    implode('" & "', $missing),
                    $plural ? 'were' : 'was'
                ));
            }

            if (count($failed) > 0) {
                $plural = count($failed) > 1;
                return Failure::fromNameAndReason($this->getName(), sprintf(
                    'Propert%s "%s" %s not promoted',
                    $plural ? 'ies' : 'y',
                    implode('" & "', $failed),
                    $plural ? 'were' : 'was'
                ));
            }

            if (count($visibility) > 0) {
                $plural = count($visibility) > 1;
                return Failure::fromNameAndReason($this->getName(), sprintf(
                    'Propert%s "%s" visibility changed',
                    $plural ? 'ies' : 'y',
                    implode('" & "', $visibility)
                ));
            }

            if (in_array('config', $promotedProperties, true)) {
                return Failure::fromNameAndReason($this->getName(), 'Property "config" should not be promoted');
            }

            if (count($typeErrors) > 0) {
                $plural = count($typeErrors) > 1;
                return Failure::fromNameAndReason($this->getName(), sprintf(
                    'Propert%s "%s" should not have changed type',
                    $plural ? 'ies' : 'y',
                    implode('" & "', $typeErrors)
                ));
            }

            // Test Invocation
            $params = [
                'visitor' => \Closure::fromCallable(fn (array $line) => $line[2]),
                'key' => 'value',
                'config' => ['basePath' => '/some/base/path']
            ];

            $obj = new $visitor->classname(...$params);

            // Need to pull updates so can use this maybe
            //collect($class->getProperties())->map()->flatten()->filter(); or flatMap->filter

            $actual = array_merge([], ...array_map(function ($property) use ($obj) {
                $property->setAccessible(true);
                $name = $property->getName();
                $value = $property->isInitialized($obj) ? $property->getValue($obj) : null;

                return [$name => $value];
            }, $class->getProperties()));

            $dataFailures = array_filter([
                'visitor' => $params['visitor'] !== $actual['visitor'],
                'key' => $params['key'] !== $actual['key'],
                'basePath' => $params['config']['basePath'] !== $actual['basePath'],
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
        })();
    }
}
