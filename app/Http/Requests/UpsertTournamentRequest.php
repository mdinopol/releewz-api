<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertTournamentRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $name      = $this->request->get('name') ?? $this->tournament->name;
        $startDate = $this->request->get('start_date') ?? $this->tournament->start_date;

        $rules['name'] = [
            'required',
            'string',
            Rule::unique('tournaments')->where(
                fn (Builder $query) => $query
                        ->where('name', $name)
                        ->where('start_date', '<=', $startDate)
            ),
            'min:1',
            'max:50',
        ];
        $rules['description'] = ['nullable', 'string', 'min:1', 'max:250'];
        $rules['start_date']  = ['required', 'date', 'after_or_equal:today'];
        $rules['end_date']    = ['required', 'date', 'after:start_date'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Tournament name already exists and overlaps with its starting date.',
        ];
    }
}
