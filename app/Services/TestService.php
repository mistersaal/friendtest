<?php


namespace App\Services;


use App\User;
use App\Test;

class TestService
{
    public function getUserTest(User $user): ?Test
    {
        if (!$user->test) {
            return null;
        }
        $user->test->load('questions.defaultQuestion');
        $user->test->setRelation('questions', $user->test->questions->keyBy('id'));
        return $user->test->setRelation('questions', $user->test->questions->keyBy('id'));
    }

    public function getUserTestWithoutAnswers(User $user): ?Test
    {
        $test = $this->getUserTest($user);
        if (!$test) {
            return null;
        }
        $test->questions->makeHidden('answer');
        return $test;
    }

    public function createTest(User $user, array $questions): void
    {
        /** @var Test $test */
        $test = $user->test()->create();
        $test->questions()->createMany($questions);
    }

    public function deleteTest(User $user): void
    {
        $user->test()->delete();
    }
}
