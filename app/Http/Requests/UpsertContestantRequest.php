<?php

namespace App\Http\Requests;

use App\Enum\ContestantType;
use App\Enum\Country;
use App\Enum\Sport;
use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpsertContestantRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $input = $this->request->all();

        $contestantType = $input['contestant_type'] ?? $this->contestant->contestant_type->value;
        $sport          = $input['sport'] ?? $this->contestant->sport->value;

        $rules['parent_id'] = [
            'nullable',
            'integer',
            // A Team member should only be assignable to an existing TEAM from the same SPORT
            Rule::exists('contestants', 'id')
                ->where(
                    fn (Builder $query) => $query->where('contestant_type', ContestantType::TEAM)
                        ->where('sport', Sport::tryFrom($sport))
                ),
            // // A Contestant should be prohibited for team assignment if the contenstant is not of type TEAM_MEMBER
            Rule::prohibitedIf(
                $contestantType !== ContestantType::TEAM_MEMBER
            ),
        ];

        $rules['alias']           = ['nullable', 'string', 'min:1', 'max:100'];
        $rules['country_code']    = ['nullable', new Enum(Country::class)];
        $rules['contestant_type'] = ['required', new Enum(ContestantType::class)];
        $rules['sport']           = ['required', new Enum(Sport::class)];
        $rules['active']          = ['nullable', 'boolean'];
        $rules['image_path']      = ['nullable', 'string'];

        $rules['name'] = [
            'required',
            'string',
            // Contestant name should be unique by CONTESTANT TYPE and per sport
            Rule::unique('contestants', 'name')
                ->where(
                    fn (Builder $query) => $query
                        ->where('sport', Sport::tryFrom($sport))
                        ->where('contestant_type', ContestantType::tryFrom($contestantType))
                ),
            'min:1',
        ];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Contestant is already added for this sport.',
            'parent_id'   => [
                'exists'        => 'Cannot assign to a team from other sport.',
                'prohibited_if' => 'Cannot assign a non team-member to a team.',
            ],
        ];
    }
}
