<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use App\Models\Matchup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpsertMatchupRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules = [];

        $rules['bout_id']    = ['required', 'integer', 'exists:bouts,id'];
        $rules['home_id']    = ['required', 'integer', 'exists:contestants,id'];
        $rules['away_id']    = ['required', 'integer', 'exists:contestants,id'];
        $rules['start_date'] = ['required', 'date', 'after_or_equal:today'];
        $rules['end_date']   = ['required', 'date', 'after_or_equal:start_date'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $input  = $validator->safe(['bout_id', 'home_id', 'away_id']);
                $boutId = $input['bout_id'] ?? $this->matchup->bout_id;
                $homeId = $input['home_id'] ?? $this->matchup->home_id;
                $awayId = $input['away_id'] ?? $this->matchup->away_id;

                if (Matchup::where([
                    'bout_id' => $boutId,
                    'home_id' => $homeId,
                    'away_id' => $awayId,
                ])->count() > 0) {
                    $validator->errors()->add(
                        'bout_id',
                        'Matchup is already created for this bout.'
                    );
                }
            },
        ];
    }
}
