<?php

namespace App\Http\Requests\Traits;

trait SetSometimesOnPut
{
    public function setSometimeOnPut(array &$rules): void
    {
        if ($this->isMethod('put')) {
            foreach ($rules as $key => $rule) {
                if (!in_array('sometimes', $rule, true)) {
                    $rules[$key][] = 'sometimes';
                }
            }
        }
    }
}
