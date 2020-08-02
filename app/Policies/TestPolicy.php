<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TestPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return !$user->test()->exists()
            ? Response::allow()
            : Response::deny('У вас уже существует тест.');
    }

    public function destroy(User $user)
    {
        return $user->test()->exists()
            ? Response::allow()
            : Response::deny('У вас нет теста.');
    }
}
