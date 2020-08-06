<?php

namespace App\Policies;

use App\Test;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TestAnswerPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Test $test)
    {
        return $test->isAuthor($user)
            ? Response::deny('Вы автор этого теста.')
            : Response::allow();
    }

    public function answer(User $user, Test $test)
    {
        return $test->hasUserAnswer($user) || $test->isAuthor($user)
            ? Response::deny('Вы уже отвечали или являетесь автором.')
            : Response::allow();
    }

    public function info(User $user)
    {
        return $user->test
            ? Response::allow()
            : Response::deny('У вас нет теста.');
    }
}
