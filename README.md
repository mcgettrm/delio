# Delio Tech Test
This Laravel app was developed by Michael McGettrick for Delio as part of their recruitment process.

### Setup Instructions
* Register for an API Token at https://finnhub.io/docs/api/quote
* Add your database connection details to the .env file in the root of this project
* Add your API Token to the .env file in the root of this project


### Dependencies
- PHP 8.1.16
- MySQL 5.7.26

### Further Considerations:
- Use Docker
- Move Controller into 

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


### Database
- Stock ID
- Date
