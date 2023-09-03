<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpsertAchievementRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        if (
            !($userId = $this->user?->id)
            && $this->isMethod('put')
        ) {
            $userId = $this->user()->id;
        }

        $rules['name'] = ['required', 'string', 'unique:achievements,name,'.($userId ?? 0), 'min:1', 'max:50'];
        $rules['alias'] = ['nullable', 'string', 'min:1', 'max:50'];
        $rules['short'] = ['nullable', 'string', 'min:1', 'max:10'];
        $rules['order'] = ['nullable', 'integer'];
        $rules['is_range'] = ['nullable', 'boolean'];
        $rules['description'] = ['nullable', 'string', 'min:5', 'max:255'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Achievement already exist',
        ];
    }
}
