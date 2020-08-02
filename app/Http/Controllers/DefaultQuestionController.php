<?php

namespace App\Http\Controllers;

use App\DefaultQuestion;
use Illuminate\Http\Request;

class DefaultQuestionController extends Controller
{
    public function index()
    {
        return DefaultQuestion::whereVisible(true)->get()->pluck('value', 'id');
    }
}
