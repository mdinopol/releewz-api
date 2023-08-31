<?php

namespace App\Http\Requests;

use App\Enum\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'user_name'       => ['required', 'string', 'unique:users,user_name', 'min:1', 'max:50'],
            'first_name'      => ['required', 'string', 'min:1', 'max:50'],
            'middle_name'     => ['nullable', 'string', 'min:1', 'max:50'],
            'last_name'       => ['required', 'string', 'min:1', 'max:50'],
            'email'           => ['required', 'email', 'unique:users,email'],
            'password'        => ['required', 'string', 'min:6', 'max:20'],
            'phone'           => ['nullable', 'string'],
            'date_of_birth'   => ['nullable', 'date'],
            'country_code'    => ['required', new Enum(Country::class)],
            'adress_city'     => ['nullable', 'string'],
            'adress_postal'   => ['nullable', 'string'],
            'adress_line_one' => ['nullable', 'string'],
            'adress_line_two' => ['nullable', 'string'],
        ];

        return $rules;
    }
}
