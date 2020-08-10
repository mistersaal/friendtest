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
        $user->test->questions->map(function($question) use ($user) {
            if ($question->defaultQuestion) {
                $question->defaultQuestion->value = str_replace('[name]', $user->first_name, $question->defaultQuestion->value);
            }
        });
        return $user->test;
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
