<?php

namespace App\Http\Controllers;

use App\Services\UserService;

class MainController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function info(UserService $userService)
    {
        if (auth()->validate()) {
            $user = $userService->updateUser(auth()->user());
        } else {
            $user = $userService->createNewUser(auth()->getVkIdentifier());
        }
        $user->test;
        return compact('user');
    }
}
