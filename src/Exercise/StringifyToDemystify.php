<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

use GuzzleHttp\Psr7\Request;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TryCatch;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\CodeInsertion;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CgiExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\SubmissionPatchable;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Patch;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;
use Psr\Http\Message\RequestInterface;
use Stringable;

class StringifyToDemystify extends AbstractExercise implements
    ExerciseInterface,
    CgiExercise,
    SubmissionPatchable,
    FunctionRequirementsExerciseCheck,
    SelfCheck
{
    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'Stringify to Demystify';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s Stringable Interface';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CGI();
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
    }

    /**
     * @return array<RequestInterface>
     */
    public function getRequests(): array
    {
        return array_map(
            fn () => new Request(
                'POST',
                'https://phpschool.io/api',
                [],
                json_encode($this->getRandRequest(), JSON_THROW_ON_ERROR)
            ),
            array_fill(0, random_int(3, 6), null)
        );
    }

    /**
     * @return array{'success': bool, 'status': int, 'error'?: string, 'body'?: string}
     * @throws \Exception
     */
    public function getRandRequest(): array
    {
        return [
            [
                'success' => true,
                'status' => 200,
                'body' => ['{fake: "data"}', '{stub: "payload"}'][random_int(0, 1)]
            ],
            [
                'success' => false,
                'status' => 400,
                'error' => ['Bad Request: Incorrect mime type', 'Bad Request: Invalid key "id"'][random_int(0, 1)]
            ],
            [
                'success' => false,
                'status' => 401,
                'error' => ['Unauthorized: Not authorised for this resource', 'Bad API token'][random_int(0, 1)]
            ],
            [
                'success' => false,
                'status' => 403,
                'error' => ['Forbidden: Access forbidden', 'Forbidden: ACL not granted for resource'][random_int(0, 1)]
            ],
            [
                'success' => false,
                'status' => 500,
                'error' => ['Server Error: Contact webmaster', 'Server Error: err_code: 3289327'][random_int(0, 1)]
            ],
        ][random_int(0, 4)];
    }

    public function getPatch(): Patch
    {
        $code = <<<CODE
        function log_failure(\Stringable \$error) {
            echo \$error;
        }
        CODE;

        return (new Patch())->withInsertion(new CodeInsertion(CodeInsertion::TYPE_BEFORE, $code));
    }

    public function getRequiredFunctions(): array
    {
        return ['log_failure'];
    }

    public function getBannedFunctions(): array
    {
        return [];
    }

    public function check(Input $input): ResultInterface
    {
        /** @var Stmt[] $statements */
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        /** @var Class_|null $classStmt */
        $classStmt = (new NodeFinder())->findFirstInstanceOf($statements, Class_::class);

        if (!$classStmt) {
            return new Failure($this->getName(), 'No class statement could be found');
        }

        $implements = array_filter($classStmt->implements, fn (Name $name) => $name->toString() === Stringable::class);

        if (empty($implements)) {
            return new Failure($this->getName(), 'Your class should implement the Stringable interface');
        }

        return new Success($this->getName());
    }
}
