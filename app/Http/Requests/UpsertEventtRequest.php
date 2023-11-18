<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Foundation\Http\FormRequest;

class UpsertEventtRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules = [];

        $rules['name'] = ['required', 'string', 'min: 3', 'max: 100'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }
}
