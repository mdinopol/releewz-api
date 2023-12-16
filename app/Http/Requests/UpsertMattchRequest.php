<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use App\Models\Mattch;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpsertMattchRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules                  = [];
        $rules['tournament_id'] = ['required', 'integer', 'exists:tournaments,id'];
        $rules['home_id']       = ['required', 'integer', 'exists:contestants,id'];
        $rules['away_id']       = ['required', 'integer', 'exists:contestants,id'];
        $rules['date']    = ['required', 'date', 'after_or_equal:today'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $input      = $validator->validated();
                $tournament = $input['tournament_id'] ?? $this->mattch->tournament_id;
                $home       = $input['home_id'] ?? $this->mattch->home_id;
                $away       = $input['away_id'] ?? $this->mattch->away_id;
                $date       = $input['date'] ?? $this->mattch->date;

                if (Mattch::where([
                    'tournament_id' => $tournament,
                    'home_id'       => $home,
                    'away_id'       => $away,
                    'date'          => $date,
                ])->count() > 0) {
                    $validator->errors()->add(
                        'tournament_id',
                        'Match already exist'
                    );
                }
            },
        ];
    }
}
