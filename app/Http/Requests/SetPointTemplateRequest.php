<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPointTemplateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules['template']                 = ['required'];
        $rules['template.decisions']       = ['required'];
        $rules['template.fillables']       = ['required'];
        $rules['template.fillables.basic'] = ['required'];
        $rules['template.fillables.range'] = ['required'];
        $rules['template.extras']          = ['nullable'];

        return $rules;
    }
}
