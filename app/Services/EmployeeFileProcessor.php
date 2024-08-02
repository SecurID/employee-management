<?php

namespace App\Services;

use App\Jobs\ProcessCSV;
use App\Validators\EmployeeDataValidator;
use Illuminate\Support\Carbon;

class EmployeeFileProcessor
{
    protected array $header;
    protected array $data;
    protected array $processedData = [];
    public array $errors = [];

    public function __construct(array $file)
    {
        $this->initializeData($file);
    }

    protected function initializeData(array $file): void
    {
        $this->data = array_map('str_getcsv', $file);
        $this->header = $this->extractHeader();
    }

    protected function extractHeader(): array
    {
        $header = array_shift($this->data);
        return array_map('trim', $header);
    }

    public function validate(): bool
    {
        foreach ($this->data as $num => $row) {
            if ($this->validateRow($num, $row)) {
                $this->data[$num] = $this->mapRowToHeader($row);
            }
        }
        return empty($this->errors);
    }

    protected function validateRow(int $num, array $row): bool
    {
        try {
            $row = $this->mapRowToHeader($row);
            $validator = EmployeeDataValidator::validate($row);
            if ($validator->fails()) {
                $this->addError($num, $validator->errors()->all());
                return false;
            }
            return true;
        } catch (\Throwable $e) {
            $this->addError($num, $e->getMessage());
            return false;
        }
    }

    protected function mapRowToHeader(array $row): array
    {
        return array_combine($this->header, $row);
    }

    protected function addError(int $num, $error): void
    {
        $this->errors[] = ['rowNumber' => $num + 1, 'error' => $error];
    }

    public function prepare(): void
    {
        foreach ($this->data as $num => $row) {
            $this->processedData[] = $this->transformRow($row);
            unset($this->data[$num]); // Free memory
        }
    }

    protected function transformRow(array $row): array
    {
        return [
            'employee_id' => $row['Emp ID'],
            'user_name' => $row['User Name'],
            'name_prefix' => $row['Name Prefix'],
            'first_name' => $row['First Name'],
            'middle_initial' => $row['Middle Initial'],
            'last_name' => $row['Last Name'],
            'gender' => $row['Gender'],
            'email' => $row['E Mail'],
            'date_of_birth' => Carbon::createFromFormat('m/d/Y', $row['Date of Birth'])->format('Y-m-d'),
            'time_of_birth' => Carbon::createFromFormat('h:i:s A', $row['Time of Birth'])->format('H:i:s'),
            'age_in_years' => $row['Age in Yrs.'],
            'date_of_joining' => Carbon::createFromFormat('m/d/Y', $row['Date of Joining'])->format('Y-m-d'),
            'age_in_company' => $row['Age in Company (Years)'],
            'phone_number' => $row['Phone No.'],
            'place_name' => $row['Place Name'],
            'county' => $row['County'],
            'city' => $row['City'],
            'zip' => $row['Zip'],
            'region' => $row['Region'],
        ];
    }

    public function insert(): void
    {
        foreach ($this->processedData as $row) {
            ProcessCSV::dispatch($row);
        }
        $this->clearProcessedData();
    }

    protected function clearProcessedData(): void
    {
        $this->processedData = []; // Free memory
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
