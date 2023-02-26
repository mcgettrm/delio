# Delio Tech Test
This Laravel app was developed by Michael McGettrick for Delio as part of their recruitment process.

### Additional Documentation
Additional documentation is available in the "misc" folder in the root of this repo, including:
* Initial design of the system
* Copy of the original brief (also available at https://github.com/deliowales/php-technical-test)
* Specific Finnhub endpoint documentation: https://finnhub.io/docs/api/quote

### Setup Instructions
* Register for an API Token at https://finnhub.io/register
* Add your database connection details to the .env file in the root of this project
* Add your API Token to the .env file in the root of this project
* Run `php artisan migrate` to set up the database

### Dependencies
- PHP 8.1.16
- MySQL 5.7.26

### Further Considerations:
- Use Docker
- Move Controller into 
- .env file the best place for API token?
- Use Finnhub PHP library? // Ran into problems with Laravel Guzzle version conflicts
- DTOs a bit too loose?
- Tempted to return a 201 response on success because persistent records were created // Decided not to as this may be intentional
- Caching?
- Remove users table and Laravel bootstrapping?

### TODO:
- Basic API endpoint
- Register with Finnhub
- Use ORM to set up database
- Domain Design
  - Adapter pattern for API wrapper
  - Strategy pattern for calculations
  - Facade pattern for providing access to the subsystem and abstracting the below complexity
  - Repository (?) -- Over engineering in context of ORM?
- Unit Tests
  - Handle API failure gracefully
  - Test Strategy and Facade
  - Mock Adapter
- Automated integration tests (?)
- Documentation
  - Lucid Chart of Domain Design
  - Project brief
  - Further considerations
  - Swagger/OpenAPI doc to describe API endpoints
  - List and dependencies not described in composer.json


