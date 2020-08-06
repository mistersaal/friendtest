<?php

namespace App\Http\Controllers;

use App\Exceptions\TestAnswerException;
use App\Http\Requests\TestAnswerRequest;
use App\Services\TestAnswerService;
use App\Test;
use App\TestAnswer;
use App\User;

class TestAnswerController extends Controller
{
    protected $testAnswerService;

    public function __construct(TestAnswerService $service)
    {
        $this->testAnswerService = $service;
    }

    public function view(User $user)
    {
        $this->authorize('view', [TestAnswer::class, $user->test]);

        return $this->testAnswerService->getUserTestForResponder($user, auth()->user()) ??
            abort(404, 'Тест ещё не существует или был удалён.');
    }

    public function answer(Test $test, TestAnswerRequest $request)
    {
        $this->authorize('answer', [TestAnswer::class, $test]);

        try {
            $this->testAnswerService->answer(
                $test,
                auth()->user(),
                collect($request->answers),
                $request->anonymously
            );
        } catch (TestAnswerException $e) {
            abort(400, $e->getMessage());
        }
    }

    public function info()
    {
        $this->authorize('info', TestAnswer::class);

        return $this->testAnswerService->getTestAnswersInfo(auth()->user());
    }
}
