<?php

namespace App\Http\Requests;

use App\Enum\GameDuration;
use App\Enum\GameState;
use App\Enum\GameType;
use App\Enum\Sport;
use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpsertGameRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules['tournament_id'] = ['nullable', 'integer', 'exists:tournaments,id'];
        $rules['name'] = ['required', 'string', 'unique:games,name', 'min:1', 'max:100'];
        $rules['short'] = ['nullable', 'string', 'min:1', 'max:50'];
        $rules['slug'] = ['required', 'string', 'unique:games,slug', 'min:1', 'max:150'];
        $rules['description'] = ['nullable', 'string', 'min:1', 'max:255'];
        $rules['sport'] = ['required', new Enum(Sport::class)];
        $rules['game_state'] = ['required', new Enum(GameState::class)];
        $rules['duration_type'] = ['required', new Enum(GameDuration::class)];
        $rules['game_type'] = ['required', new Enum(GameType::class)];
        $rules['min_entry'] = ['required', 'integer'];
        $rules['max_entry'] = ['required', 'integer'];
        $rules['entry_contestants'] = ['required', 'integer'];
        $rules['max_entry_value'] = ['required', 'numeric'];
        $rules['entry_price'] = ['required', 'numeric'];
        $rules['initial_prize_pool'] = ['nullable', 'numeric'];
        $rules['current_prize_pool'] = ['nullable', 'numeric'];
        $rules['start_date'] = ['required', 'date', 'after_or_equal:today'];
        $rules['end_date'] = ['required', 'date', 'after:start_date'];
        $rules['points_template'] = ['nullable', 'json'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }
}
