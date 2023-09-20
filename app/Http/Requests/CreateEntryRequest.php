<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\License;
use App\Models\Pivots\Entry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Validator;

class CreateEntryRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [];

        $rules['name'] = [
            'required',
            'string',
            'min:3',
            'max:10',
            Rule::unique('entries', 'name')->where('game_id', $this->game->id),
        ];
        $rules['total_points']         = ['nullable', 'numeric'];
        $rules['points_history']       = ['nullable', 'array'];
        $rules['contestants']          = ['required', 'array'];
        $rules['extra_predictions']    = ['nullable', 'array'];
        $rules['license_at_creation']  = ['required', new Enum(License::class)];
        $rules['currency_at_creation'] = ['required', new Enum(Currency::class)];

        return $rules;
    }

    // protected function after(): void
    // {
    //     return [
    //         function (Validator $validator) {
    //             $input = $validator->safe(['name']);
    //             $name = $input['name'];

    //             $entry = Entry::where([
    //                 'game_id' =>
    //             ])
    //             if ()
    //         }
    //     ]
    // }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => htmlspecialchars($this->name),
        ]);
    }
}
