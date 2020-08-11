<?php

namespace App\Http\Requests;

use App\DefaultQuestion;
use Illuminate\Foundation\Http\FormRequest;

class TestRequest extends FormRequest
{
    public function rules()
    {
        return [
            'questions' => 'required|array|size:' . config('settings.questionsCount'),
            'questions.*.value' => 'required_without:questions.*.default_question_id|string|max:' .
                config('settings.maxQuestionLength'),
            'questions.*.default_question_id' => 'required_without:questions.*.value|distinct|exists:' .
                DefaultQuestion::class . ',id',
            'questions.*.answer' => 'required|boolean',
        ];
    }
}
