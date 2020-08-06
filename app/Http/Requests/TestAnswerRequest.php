<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestAnswerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'anonymously' => 'required|boolean',
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|distinct|exists:questions,id',
            'answers.*.answer' => 'required|boolean',
        ];
    }
}
