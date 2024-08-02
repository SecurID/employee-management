<?php

namespace App\Services;

use App\Jobs\ProcessCSV;
use App\Validators\EmployeeDataValidator;
use Illuminate\Support\Carbon;

class EmployeeFileProcessor
{
    protected array $header;
    protected array $data;
    protected array $processedData;
    public array $errors = [];

    public function __construct($file)
    {
        // Process the header and seperate it from the data
        $this->data = array_map('str_getcsv', $file);
        $this->header = array_shift($this->data);
        $this->header = array_map('trim', $this->header); // Trim headers to avoid extra spaces
    }

    public function validate(): bool
    {
        foreach($this->data as $num => $row) {
            try {
                $row = array_combine($this->header, $row);
            } catch (\Throwable $e) {
                unset($this->data[$num]);
                $this->errors[] = ['rowNumber' => $num+1, 'error' => $e->getMessage()];
                continue;
            }

            // Validate each row
            $validator = EmployeeDataValidator::validate($row);

            if($validator->fails()) {
                unset($this->data[$num]);
                $this->errors[] = ['rowNumber' => $num+1, 'error' => $validator->errors()->all()];
            }
        }

        return empty($this->errors);
    }

    public function prepare(): void
    {
        foreach ($this->data as $num => $row) {
            $row = array_combine($this->header, $row);

            $row['Date of Birth'] = Carbon::createFromFormat('m/d/Y', $row['Date of Birth'])->format('Y-m-d');
            $row['Date of Joining'] = Carbon::createFromFormat('m/d/Y', $row['Date of Joining'])->format('Y-m-d');
            $row['Time of Birth'] = Carbon::createFromFormat('h:i:s A', $row['Time of Birth'])->format('H:i:s');

            $this->processedData[] = [
                'employee_id' => $row['Emp ID'],
                'user_name' => $row['User Name'],
                'name_prefix' => $row['Name Prefix'],
                'first_name' => $row['First Name'],
                'middle_initial' => $row['Middle Initial'],
                'last_name' => $row['Last Name'],
                'gender' => $row['Gender'],
                'email' => $row['E Mail'],
                'date_of_birth' => $row['Date of Birth'],
                'time_of_birth' => $row['Time of Birth'],
                'age_in_years' => $row['Age in Yrs.'],
                'date_of_joining' => $row['Date of Joining'],
                'age_in_company' => $row['Age in Company (Years)'],
                'phone_number' => $row['Phone No.'],
                'place_name' => $row['Place Name'],
                'county' => $row['County'],
                'city' => $row['City'],
                'zip' => $row['Zip'],
                'region' => $row['Region'],
            ];

            unset($this->data[$num]); // Free memory
        }
    }

    public function insert(): void
    {
        foreach ($this->processedData as $row) {
            ProcessCSV::dispatch($row);
        }
        unset($this->processedData); // Free memory
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

}
