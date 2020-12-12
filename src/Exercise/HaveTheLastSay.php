<?php

declare(strict_types=1);

namespace PhpSchool\PHP8Appreciate\Exercise;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\NodeFinder;
use PhpParser\Parser;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\Exercise\TemporaryDirectoryTrait;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\SelfCheck;
use PhpSchool\PhpWorkshop\Input\Input;
use PhpSchool\PhpWorkshop\Result\Failure;
use PhpSchool\PhpWorkshop\Result\FailureInterface;
use PhpSchool\PhpWorkshop\Result\ResultInterface;
use PhpSchool\PhpWorkshop\Result\Success;

class HaveTheLastSay extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    FunctionRequirementsExerciseCheck,
    SelfCheck
{
    use TemporaryDirectoryTrait;

    public function __construct(private Parser $parser)
    {
    }

    public function getName(): string
    {
        return 'Have the last say';
    }

    public function getDescription(): string
    {
        return 'Use named arguments to specify the last to a specific parameter';
    }

    public function getArgs(): array
    {
        $file = $this->getTemporaryPath();

        $countries = [
            ['UK', 'London'],
            ['Austria', 'Vienna'],
            ['France', 'Paris'],
            ['Turkey', 'Istanbul'],
            ['Morocco', 'Rabat'],
            ['Georgia', 'Tbilisi'],
            ['Kyrgyzstan', 'Bishkek'],
            ['Serbia', 'Belgrade'],
            ['Uzbekistan', 'Tashkent'],
            ['Belarus', 'Minsk'],
        ];

        file_put_contents(
            $file,
            implode("\n", array_map(
                fn ($row) => implode("|", $row),
                $this->getRandomCountries($countries))
            )
        );

        return [
            [$file]
        ];
    }

    private function getRandomCountries(array $countries): array
    {
        return array_intersect_key(
            $countries,
            array_flip(array_rand($countries, random_int(3, 7)))
        );
    }

    public function tearDown(): void
    {
        unlink($this->getTemporaryPath());
    }

    public function getType(): ExerciseType
    {
        return new ExerciseType(ExerciseType::CLI);
    }

    public function getRequiredFunctions(): array
    {
        return ['fopen', 'fgetcsv'];
    }

    public function getBannedFunctions(): array
    {
        return [];
    }

    public function check(Input $input): ResultInterface
    {
        $statements = $this->parser->parse((string) file_get_contents($input->getRequiredArgument('program')));

        if (null === $statements || empty($statements)) {
            return Failure::fromNameAndReason($this->getName(), 'No code was found');
        }

        $check = new FunctionRequirementsCheck($this->parser);
        $result = $check->check($this, $input);

        if ($result instanceof FailureInterface) {
            return $result;
        }

        $funcCall = (new NodeFinder())->findFirst($statements, function ($node) {
            return $node instanceof FuncCall && $node->name->toString() === 'fgetcsv';
        });

        if ($funcCall->args[0]->name !== null) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'The stream argument must be specified using a positional parameter'
            );
        }

        if (count($funcCall->args) > 2) {
            return Failure::fromNameAndReason(
                $this->getName(),
                'You should only specify the stream and separator arguments, no others'
            );
        }

        if (!isset($funcCall->args[1])) {
            return Failure::fromNameAndReason($this->getName(), 'The separator argument has not been specified');
        }

        if (!$funcCall->args[1]->name) {
            return Failure::fromNameAndReason($this->getName(), 'The second positional argument should not be specified');
        }

        if ($funcCall->args[1]->name->name !== 'separator') {
            return Failure::fromNameAndReason(
                $this->getName(),
                'A named argument has been used, but not for the separator argument'
            );
        }

        return new Success($this->getName());
    }
}
