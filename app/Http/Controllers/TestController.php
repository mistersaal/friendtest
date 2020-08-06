<?php

namespace App\Http\Controllers;

use App\Http\Requests\TestRequest;
use App\Services\TestService;
use App\Test;
use App\User;

class TestController extends Controller
{
    protected $testService;

    public function __construct(TestService $service)
    {
        $this->testService = $service;
    }

    public function index()
    {
        return $this->testService->getUserTest(auth()->user()) ??
            abort(404, "Тест ещё не существует или был удалён.");
    }

    public function store(TestRequest $request)
    {
        $this->authorize('store', Test::class);

        $this->testService->createTest(auth()->user(), collect($request->questions));
    }

    public function destroy()
    {
        $this->authorize('destroy', Test::class);

        $this->testService->deleteTest(auth()->user());
    }
}
