<?php

namespace App\Http\Requests;

use App\Enum\Achievement;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class GiveScoreRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [];

        $rules['score']               = ['required'];
        $rules['score.*.achievement'] = ['required', new Enum(Achievement::class)];
        $rules['score.*.home']        = ['required', 'numeric'];
        $rules['score.*.away']        = ['required', 'numeric'];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'score.*.achievement' => 'Invalid achievement.',
        ];
    }
}
