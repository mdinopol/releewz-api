<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\License;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class CreateEntryRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [];

        $rules['user_id'] = ['required', 'integer'];
        $rules['game_id'] = ['required', 'integer', 'exists:games,id'];
        $rules['name']    = [
            'required',
            'string',
            'min:3',
            'max:10',
            Rule::unique('entries', 'name')->where('game_id', $this->input('game_id')),
        ];
        $rules['total_points']         = ['nullable', 'numeric'];
        $rules['points_history']       = ['nullable', 'array'];
        $rules['contestants']          = ['required', 'array'];
        $rules['extra_predictions']    = ['nullable', 'array'];
        $rules['license_at_creation']  = ['required', new Enum(License::class)];
        $rules['currency_at_creation'] = ['required', new Enum(Currency::class)];

        return $rules;
    }
}
