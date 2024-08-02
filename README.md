# Employee Management API

## Installation

1. Clone the repository
2. Run `composer install`
3. Setup your local database
4. Set up `.env` file with your database credentials
5. Run `php artisan migrate` to set up the database
6. Run `php artisan queue:work` to start the queue worker
7. Run `php artisan serve` to start the application
8. Run `php artisan test` to run the tests

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
