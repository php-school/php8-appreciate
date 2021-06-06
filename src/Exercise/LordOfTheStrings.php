<?php

namespace PhpSchool\PHP8Appreciate\Exercise;

use Faker\Generator;
use PhpSchool\PhpWorkshop\Check\ComposerCheck;
use PhpSchool\PhpWorkshop\Check\FunctionRequirementsCheck;
use PhpSchool\PhpWorkshop\Exercise\AbstractExercise;
use PhpSchool\PhpWorkshop\Exercise\CliExercise;
use PhpSchool\PhpWorkshop\Exercise\ExerciseInterface;
use PhpSchool\PhpWorkshop\Exercise\ExerciseType;
use PhpSchool\PhpWorkshop\ExerciseCheck\ComposerExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseCheck\FunctionRequirementsExerciseCheck;
use PhpSchool\PhpWorkshop\ExerciseDispatcher;
use PhpSchool\PhpWorkshop\Solution\DirectorySolution;
use PhpSchool\PhpWorkshop\Solution\SolutionInterface;

class LordOfTheStrings extends AbstractExercise implements
    ExerciseInterface,
    CliExercise,
    FunctionRequirementsExerciseCheck,
    ComposerExerciseCheck
{

    public function __construct(private Generator $faker)
    {
    }

    public function getName(): string
    {
        return 'Lord of the Strings';
    }

    public function getDescription(): string
    {
        return 'PHP 8\'s new string functions';
    }

    public function getType(): ExerciseType
    {
        return ExerciseType::CLI();
    }

    public function configure(ExerciseDispatcher $dispatcher): void
    {
        $dispatcher->requireCheck(FunctionRequirementsCheck::class);
        $dispatcher->requireCheck(ComposerCheck::class);
    }

    public function getSolution(): SolutionInterface
    {
        return DirectorySolution::fromDirectory(__DIR__ . '/../../exercises/lord-of-the-strings/solution');
    }

    public function getArgs(): array
    {
        /** @var string $word */
        $word = $this->faker->words(1, true);

        /** @var string $sentence */
        $sentence = $this->faker->words(random_int(2, 5), true);

        $options = ['start', 'end', 'anywhere', 'nowhere'];
        $sentence = match ($options[array_rand($options)]) {
            'start' => "$word $sentence",
            'end' => "$sentence $word",
            'anywhere' => $this->insertWordInSentenceRandomly($word, $sentence),
            'nowhere' => $sentence
        };

        return [
            [$word, $sentence]
        ];
    }

    public function insertWordInSentenceRandomly(string $word, string $sentence): string
    {
        $words = explode(" ", $sentence);
        $randomIndex = random_int(0, count($words) - 1);
        array_splice($words, $randomIndex, 1, [$words[$randomIndex], $word]);
        return implode(" ", $words);
    }

    public function getRequiredFunctions(): array
    {
        return [
            'str_contains',
            'str_starts_with',
            'str_ends_with'
        ];
    }

    public function getBannedFunctions(): array
    {
        return [
            'substr',
            'strpos'
        ];
    }

    public function getRequiredPackages(): array
    {
        return ['symfony/console'];
    }
}
