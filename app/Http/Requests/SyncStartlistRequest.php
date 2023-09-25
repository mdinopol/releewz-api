<?php

namespace App\Http\Requests;

use App\Enum\ContestantType;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SyncStartlistRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [];

        $rules['contestants'] = [
            Rule::exists('contestants', 'id')->where(
                fn (Builder $query) => $query
                ->where('contestant_type', $this->game->contestant_type)
                ->when(
                    $this->game->contestant_type === ContestantType::TEAM_MEMBER,
                    fn (Builder $whenQuery) => $whenQuery->whereNotNull('parent_id')
                )
            ),
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'contestants.exists' => [
                'Unable to sync <'.$this->game->contestant_type->value.'> type contestants.',
                'Unable to sync contestants without a team',
            ],
        ];
    }
}
