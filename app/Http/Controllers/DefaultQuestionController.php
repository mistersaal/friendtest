<?php

namespace App\Http\Controllers;

use App\DefaultQuestion;
use Illuminate\Http\Request;

class DefaultQuestionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return DefaultQuestion::whereVisible(true)->get()->pluck('value', 'id')->map(function ($question) use ($user) {
            return str_replace('[name]', $user->first_name, $question);
        });
    }
}
