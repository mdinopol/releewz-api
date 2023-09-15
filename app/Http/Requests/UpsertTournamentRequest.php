<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\SetSometimesOnPut;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpsertTournamentRequest extends FormRequest
{
    use SetSometimesOnPut;

    public function rules(): array
    {
        $rules['name']        = ['required', 'string', 'unique:tournaments,name', 'min:1', 'max:50'];
        $rules['description'] = ['nullable', 'string', 'min:1', 'max:250'];
        $rules['start_date']  = ['required', 'date'];
        $rules['end_date']    = ['required', 'date'];

        $this->setSometimeOnPut($rules);

        return $rules;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $input     = $validator->safe(['start_date', 'end_date']);
                $startDate = Carbon::parse($input['start_date'] ?? $this->tournament->start_date);
                $endDate   = Carbon::parse($input['end_date'] ?? $this->tournament->start_date);

                if ($startDate->isYesterday()) {
                    $validator->errors()->add(
                        'start_date',
                        'Starting date cannot be of yesterday.'
                    );
                }

                if ($endDate->lessThan($startDate)) {
                    $validator->errors()->add(
                        'end_date',
                        'End date should be equal or after the starting date.'
                    );
                }
            },
        ];
    }
}
