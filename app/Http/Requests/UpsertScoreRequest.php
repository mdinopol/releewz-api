<?php

namespace App\Http\Requests;

use App\Enum\Achievement;
use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpsertScoreRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules = [];

        $rules['matchup_id']  = ['required', 'exists:matchups,id'];
        $rules['achievement'] = [
            'required', new Enum(Achievement::class),
            Rule::unique('scores', 'achievement')
                ->where('matchup_id', $this->input('matchup_id') ?? $this->score->matchup_id),
        ];
        $rules['home_score']  = ['nullable', 'numeric'];
        $rules['home_points'] = ['nullable', 'numeric'];
        $rules['away_score']  = ['nullable', 'numeric'];
        $rules['away_points'] = ['nullable', 'numeric'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }
}
