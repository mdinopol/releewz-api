<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SetPointTemplateRequest extends FormRequest
{
    public function rules(): array
    {
        $rules['template']           = ['required', 'json'];
        $rules['template.decisions'] = ['required', 'json'];
        $rules['template.fillables'] = ['required', 'json'];
        $rules['template.extras']    = ['required', 'json'];

        return $rules;
    }
}
