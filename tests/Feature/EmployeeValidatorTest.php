<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Validators\EmployeeDataValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class EmployeeValidatorTest extends TestCase
{
    public function testValidatorEliminatesDuplicateEmployeeId()
    {
        Employee::create([
            'employee_id' => 123,
            'user_name' => 'john_doe',
            'name_prefix' => 'Mr.',
            'first_name' => 'John',
            'middle_initial' => 'A',
            'last_name' => 'Doe',
            'gender' => 'M',
            'email' => 'john@example.com',
            'date_of_birth' => Carbon::createFromFormat('m/d/Y', '07/15/1985')->format('Y-m-d'),
            'time_of_birth' => Carbon::createFromFormat('h:i:s A', '03:00:00 PM')->format('H:i:s'),
            'age_in_years' => 36,
            'date_of_joining' => Carbon::createFromFormat('m/d/Y', '08/01/2010')->format('Y-m-d'),
            'age_in_company' => 11,
            'phone_number' => '123-456-7890',
            'place_name' => 'Someplace',
            'county' => 'Somecounty',
            'city' => 'Somecity',
            'zip' => '12345',
            'region' => 'SomeRegion',
        ]);

        $newEmployeeData = [
            'employee_id' => 123, // Duplicate ID
            'user_name' => 'jane_doe',
            'name_prefix' => 'Ms.',
            'first_name' => 'Jane',
            'middle_initial' => 'B',
            'last_name' => 'Doe',
            'gender' => 'F',
            'email' => 'jane@example.com',
            'date_of_birth' => Carbon::createFromFormat('m/d/Y', '05/10/1990')->format('Y-m-d'),
            'time_of_birth' => Carbon::createFromFormat('h:i:s A', '04:00:00 PM')->format('H:i:s'),
            'age_in_years' => 31,
            'date_of_joining' => Carbon::createFromFormat('m/d/Y', '01/15/2021')->format('Y-m-d'),
            'age_in_company' => 0,
            'phone_number' => '098-765-4321',
            'place_name' => 'Anotherplace',
            'county' => 'Anothercounty',
            'city' => 'Anothercity',
            'zip' => '54321',
            'region' => 'AnotherRegion',
        ];

        $validator = EmployeeDataValidator::validate($newEmployeeData);

        $this->assertTrue($validator->fails());
    }
}
