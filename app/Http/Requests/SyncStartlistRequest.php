<?php

namespace App\Http\Requests;

use App\Enum\ContestantType;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncStartlistRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function rules(): array
    {
        $rules = [];

        if ($this->input('contestants')) {
            $rules['contestants.*.id'] = [
                'required',
                Rule::exists('contestants', 'id')
                    ->where(fn (Builder $query) => $query
                        ->where('sport', $this->game->sport->value)
                        ->where('contestant_type', $this->game->contestant_type)
                        ->when(
                            $this->game->contestant_type === ContestantType::TEAM_MEMBER,
                            fn (Builder $when) => $when->whereNotNull('parent_id')
                        )
                    )
            ];
            $rules['contestants.*.value'] = ['numeric'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'contestants.*.id.exists' => 'Check the sport and type of the contestant.',
        ];
    }
}
