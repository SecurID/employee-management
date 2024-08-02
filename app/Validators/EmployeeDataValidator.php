<?php

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class EmployeeDataValidator
{
    public static function validate(array $data)
    {
        $rules = [
            'Emp ID' => 'integer',
            'User Name' => 'string|max:255',
            'Name Prefix' => 'string|max:10',
            'First Name' => 'string|max:255',
            'Middle Initial' => 'string|max:1',
            'Last Name' => 'string|max:255',
            'Gender' => 'string|max:1|in:M,F',
            'E Mail' => 'email|max:255',
            'Date of Birth' => 'required|date',
            'Time of Birth' => 'required|date_format:h:i:s A',
            'Age in Yrs.' => 'numeric|min:0',
            'Date of Joining' => 'required|date',
            'Age in Company (Years)' => 'numeric|min:0',
            'Phone No. ' => 'string|max:20',
            'Place Name' => 'string|max:255',
            'County' => 'string|max:255',
            'City' => 'string|max:255',
            'Zip' => 'string|max:10',
            'Region' => 'string|max:50',
        ];

        return Validator::make($data, $rules);
    }
}
