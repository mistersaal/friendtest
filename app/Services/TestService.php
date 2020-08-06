<?php


namespace App\Services;


use App\User;
use App\Test;
use Illuminate\Support\Collection;

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

    public function createTest(User $user, Collection $questions): void
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
