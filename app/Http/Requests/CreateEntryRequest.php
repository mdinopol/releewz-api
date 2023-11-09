<?php

namespace App\Http\Requests;

use App\Enum\Currency;
use App\Enum\License;
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
        $rules['contestants'] = [
            'array',
            Rule::exists('contestant_game', 'contestant_id')->where('game_id', $this->game->id),
        ];
        $rules['extra_predictions']    = ['nullable', 'array'];
        $rules['license_at_creation']  = ['required', new Enum(License::class)];
        $rules['currency_at_creation'] = ['required', new Enum(Currency::class)];

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $contestants = $validator->safe(['contestants'])['contestants'];

                // Total value of selected contestants
                $requestTotalValue = $this->game->contestants()->whereIn('contestant_id', $contestants)->sum('value');

                if ($requestTotalValue > ($valueLimit = $this->game->max_entry_value)) {
                    $validator->errors()->add(
                        'contestants',
                        "Contestants' total value exceeded entry's value limit of ".$valueLimit.'.'
                    );
                }
            },
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => htmlspecialchars($this->name),
        ]);
    }
}
