<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Test;
use App\User;

class TestController extends Controller
{
    public function index()
    {
        /** @var Test $test */
        $test = auth()->user()->test()->with('questions.defaultQuestion')->first() ??
            abort(404, "У вас нет пока теста.");
        return $test->setRelation('questions', $test->questions->keyBy('id'));
    }

    public function view(User $user)
    {
        $user->test->questions->load('defaultQuestion');
        $user->test->questions->makeHidden('answer');
        $user->test->setRelation('questions', $user->test->questions->keyBy('id'));
        return $user->test;
    }

    public function store(TestRequest $request)
    {
        $this->authorize('store', Test::class);

        /** @var Test $test */
        $test = auth()->user()->test()->create();
        $test->questions()->createMany($request->questions);
    }

    public function destroy()
    {
        $this->authorize('destroy', Test::class);

        auth()->user()->test()->delete();
    }
}
