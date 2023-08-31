<?php

namespace App\Http\Requests;

use App\Enum\Role;
use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpsertUserRequest extends FormRequest
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

        $rules              = (new RegistrationRequest())->rules();
        $rules['role']      = ['required', new Enum(Role::class)];
        $rules['user_name'] = ['required', 'string', 'unique:users,user_name,'.($userId ?? 0), 'min:1', 'max:50'];
        $rules['email']     = ['required', 'email', 'unique:users,email,'.($userId ?? 0)];

        $this->setSometimeOnPut($rules);

        return $rules;
    }
}
