<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\BaseExerciseTrait;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\DefaultExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseAssets;
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

class AMatchMadeInHeaven extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    ProvidesInitialCode,
    SelfCheck
{
    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'A Match Made In Heaven';
    }

    public function getDescription(): string
    {
        return 'A Match Made In Heaven';
    }

    public function getArgs(): array
    {
        $runs = [
            ['enter'],
            ['esc'],
            ['up'],
            ['down'],
        ];

        shuffle($runs);

        return $runs;
    }

    public function getInitialCode(): SolutionInterface
    {
        return SingleFileSolution::fromFile(
            __DIR__ . '/../../exercises/a-match-made-in-heaven/initial/a-match-made-in-heaven.php'
        );
    }

    public function check(Input $input): ResultInterface
    {
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        if (null === $statements || empty($statements)) {
            return Failure::fromNameAndReason($this->getName(), 'No code was found');
        }

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor = new class extends NodeVisitorAbstract {
            public bool $matchFound = false;
            public bool $switchFound = false;
            public bool $matchHasDefault = false;

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Expr\Match_) {
                    $this->matchFound = true;

                    $this->matchHasDefault = array_reduce($node->arms, function (bool $hasDefault, Node\MatchArm $arm) {
                        return $arm->conds === null || $hasDefault;
                    }, false);
                }

                if ($node instanceof Node\Stmt\Switch_) {
                    $this->switchFound = true;
                }

                return null;
            }
        });

        $traverser->traverse($statements);

        if ($visitor->switchFound) {
            return Failure::fromNameAndReason($this->getName(), 'Switch found');
        }

        if (!$visitor->matchFound) {
            return Failure::fromNameAndReason($this->getName(), 'Match not found');
        }

        if (!$visitor->matchHasDefault) {
            return Failure::fromNameAndReason($this->getName(), 'Match default not found');
        }

        return new Success($this->getName());
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }
}
