<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexEmployee()
    {
        Employee::factory()->count(10)->create();
        $response = $this->get('/api/employee');

        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    public function testShowSingleEmployee()
    {
        $employee = Employee::factory()->create();
        $response = $this->get('/api/employee/' . $employee->employee_id);

        $response->assertStatus(200);
        $response->assertJson($employee->toArray());
    }

    public function testDeleteEmployee()
    {
        $employee = Employee::factory()->create();
        $response = $this->delete('/api/employee/' . $employee->employee_id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('employees', $employee->toArray());
    }
}
