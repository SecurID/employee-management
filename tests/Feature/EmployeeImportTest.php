<?php

namespace Tests\Feature;

use App\Jobs\ProcessCSV;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EmployeeImportTest extends TestCase
{
    use RefreshDatabase;

    public function testAfterUploadCsvQueueJobsAreCreated()
    {
        $csvContent = "Emp ID,Name Prefix,First Name,Middle Initial,Last Name,Gender,E Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No. ,Place Name,County,City,Zip,Region,User Name\n";
        $csvContent .= "198429,Mrs.,Serafina,I,Bumgarner,F,serafina.bumgarner@exxonmobil.com,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,212-376-9125,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner\n";

        Queue::fake();

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->post('/api/employee', ['file' => $file]);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Import started. This will take some time.']);

        Queue::assertPushed(ProcessCSV::class, 1);

        Storage::delete('import.csv');
    }

    public function testNoJobsAreCreatedOnErrorsInCsv()
    {
        $csvContent = "Emp ID,Name Prefix,First Name,Middle Initial,Last Name,Gender,E Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No. ,Place Name,County,City,Zip,Region,User Name\n";
        $csvContent .= "198429,Mrs.,Serafina,I,Bumgarner,F,XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,000000000000,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner\n";
        $csvContent .= "198429,Mrs.,Serafina,I,Bumgarner,F,XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,000000000000,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner\n";

        Queue::fake();

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->post('/api/employee', ['file' => $file]);

        $response->assertStatus(422);

        Queue::assertNothingPushed();

        Storage::delete('import.csv');
    }

    public function testRouteWorksAsBinary()
    {
        $csvContent = "Emp ID,Name Prefix,First Name,Middle Initial,Last Name,Gender,E Mail,Date of Birth,Time of Birth,Age in Yrs.,Date of Joining,Age in Company (Years),Phone No. ,Place Name,County,City,Zip,Region,User Name\n";
        $csvContent .= "198429,Mrs.,Serafina,I,Bumgarner,F,serafina.bumgarner@exxonmobil.com,9/21/1982,01:53:14 AM,34.87,2/1/2008,9.49,212-376-9125,Clymer,Chautauqua,Clymer,14724,Northeast,sibumgarner\n";

        Queue::fake();

        $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

        $response = $this->post('/api/employee', ['file' => $file], ['Content-Type' => 'text/csv']);

        $response->assertStatus(201);
        $response->assertJson(['message' => 'Import started. This will take some time.']);

        Queue::assertPushed(ProcessCSV::class, 1);

        Storage::delete('import.csv');
    }
}
