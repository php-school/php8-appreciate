<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator as FakerGenerator;
use GuzzleHttp\Psr7\Request;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\If_;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CgiExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\ProvidesInitialCode;
use PhpSchool\PhpWorkshop\Exercise\SubmissionPatchable;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Patch;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use PhpSchool\PhpWorkshop\Solution\SingleFileSolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;

class ThrowAnExpression extends AbstractExercise implements
    ExerciseInterface,
    CgiExercise,
    SelfCheck,
    ProvidesInitialCode,
    SubmissionPatchable
{
    public function __construct(private Parser $parser, private FakerGenerator $faker)
    {
    }

    public function getName(): string
    {
        return 'Throw an Expression';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s throw expression';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CGI();
    }

    public function getRequests(): array
    {
        return [
            (new Request('GET', 'https://top-secret.com/forbidden')),
            (new Request('GET', 'https://top-secret.com/blog'))
        ];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var array<Stmt> $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var If_|null $if */
        $if = (new NodeFinder())->findFirstInstanceOf($statements, If_::class);

        if ($if) {
            return Failure::fromNameAndReason($this->getName(), 'If statement found');
        }

        /** @var Ternary|null $ternary */
        $ternary = (new NodeFinder())->findFirstInstanceOf($statements, Ternary::class);

        if ($ternary === null) {
            return Failure::fromNameAndReason($this->getName(), 'No ternary statement found');
        }

        if (!$ternary->if instanceof Throw_ && !$ternary->else instanceof Throw_) {
            return Failure::fromNameAndReason($this->getName(), 'Ternary does not make use of throw expression');
        }

        return new Success($this->getName());
    }

    public function getInitialCode(): SolutionInterface
    {
        return SingleFileSolution::fromFile(
            __DIR__ . '/../../exercises/throw-an-expression/initial/throw-an-expression.php'
        );
    }

    public function getPatch(): Patch
    {
        return (new Patch())
            ->withTransformer(new Patch\WrapInTryCatch(\InvalidArgumentException::class));
    }
}
