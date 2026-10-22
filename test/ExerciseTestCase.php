<?php

declare(strict_types=1);

namespace PhpSchool\PHP8AppreciateTest;

use PhpSchool\PhpWorkshop\TestUtils\WorkshopExerciseTest;

abstract class ExerciseTestCase extends WorkshopExerciseTest
{
    public function tearDown(): void
    {
        parent::tearDown();

        //Application::configure() installs a global error handler which PHPUnit flags as risky if left behind
        restore_error_handler();
    }
}
