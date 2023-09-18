<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertBoutRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules['game_id'] = ['required', 'integer', 'exists:games,id'];
        $rules['name']    = [
            'required',
            'string',
            Rule::unique('bouts', 'name')->where('game_id', $this->request->get('game_id') ?? $this->bout->game_id),
        ];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Bout already exists for the selected game.',
        ];
    }
}
