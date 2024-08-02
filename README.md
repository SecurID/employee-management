# Employee Management API

## Installation

1. Clone the repository
2. Run `composer install`
3. Set up `.env` file with your database credentials
4. Run `php artisan migrate` to set up the database
5. Run `php artisan queue:work` to start the queue worker
6. Run `php artisan serve` to start the application

## API Endpoints

- `POST /api/employee` - Import employees via CSV
- `GET /api/employee` - List all employees
- `GET /api/employee/{id}` - Get a specific employee
- `DELETE /api/employee/{id}` - Delete a specific employee

## Notes

- The import CSV file should follow the structure provided.
- For the import endpoint, use the following example:
  ```bash
  curl -X POST -H 'Content-Type: text/csv' --data-binary @import.csv http://{yourapp}/api/employee
