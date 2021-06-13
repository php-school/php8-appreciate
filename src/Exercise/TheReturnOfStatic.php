<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Clone_;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
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

class TheReturnOfStatic extends AbstractExercise implements
    ExerciseInterface,
    ProvidesInitialCode,
    CliExercise,
    SelfCheck
{
    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'The Return of Static';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s static return types';
    }

    public function getInitialCode(): SolutionInterface
    {
        return SingleFileSolution::fromFile(
            __DIR__ . '/../../exercises/the-return-of-static/initial/the-return-of-static.php'
        );
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

        $finder = new NodeFinder();

        /** @var Stmt\Class_|null $class */
        $class = $finder->findFirst($statements, function (Node $node) {
            return $node instanceof Stmt\Class_ && $node->name && $node->name->toString() === 'File';
        });

        /** @var ClassMethod|null $method */
        $method = $finder->findFirst($class ? [$class] : [], function (Node $node) {
            return $node instanceof ClassMethod && $node->name->toString() === 'withPermissions';
        });

        if (!$class || !$method) {
            return new Failure($this->getName(), 'The method withPermissions cannot be found');
        }


        if (!($assign = $this->findAssignOnFirstLine($method))) {
            return new Failure($this->getName(), 'The first statement in withPermissions is not an assign');
        }

        if ($this->isCloneOfThis($assign)) {
            return new Success($this->getName());
        }

        return new Failure($this->getName(), 'The first statement is not a clone of `$this`');
    }

    private function findAssignOnFirstLine(ClassMethod $method): ?Assign
    {
        if (!isset($method->stmts[0])) {
            return null;
        }

        if (!$method->stmts[0] instanceof Expression) {
            return null;
        }

        if (!$method->stmts[0]->expr instanceof Assign) {
            return null;
        }

        return $method->stmts[0]->expr;
    }

    private function isCloneOfThis(Assign $assign): bool
    {
        if (!$assign->expr instanceof Clone_) {
            return false;
        }

        if (!$assign->expr->expr instanceof Variable) {
            return false;
        }

        return $assign->expr->expr->name === 'this';
    }
}
