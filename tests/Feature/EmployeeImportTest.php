<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeImportTest extends TestCase
{
    use RefreshDatabase;

    public function it_imports_employees_from_csv_file()
    {
        Storage::fake('local');
        $csvContent = "Employee ID,User Name,Name Prefix,First Name,Middle Initial,Last Name,Gender,E-Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No.,Place Name,County,City,Zip,Region\n";
        $csvContent .= "1,johndoe,Mr,John,A,Doe,M,john.doe@example.com,1980-01-01,08:00:00,41,2010-01-01,11,1234567890,Sample Place,Sample County,Sample City,12345,Sample Region\n";
        Storage::put('employees.csv', $csvContent);

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->post('/employees', ['file' => $file]);

        $response->assertStatus(201);
    }
}
