### php-factpt-cli ###
PHP API Client implementation for the Online invoicing System solution provided by [FACT](http://www.fact.pt).


## Install

The package is hosted on [packagist](http://packagist.org). To install run:
```
composer install digfish/php-factpt-cli
```

## Environment variables
The variable `FACTPT_TEST_API_KEY` should hold the value of your API key. You can set this via a .`env` file or your own code using `putenv` or `$_ENV['FACTPT_TEST_API_KEY']`. If there is no such a file, it is assumed that is running on production. You must set the env var `FACTPT_API_KEY` on your code for production.


## What is implemented ##

|   Method               |    API                 |
|------------------------|------------------------|
| listDocuments          | GET /documents         |
| createCustomer         | POST /clients          |
| getCustomer            | GET /clients/:id       |
| listCustomers          | GET /clients           |
| searchCustomers        | GET /clients?q=search  |
| createProduct          | POST /products         |
| getProduct             | GET /products/:id      |
| listProducts           | GET /products/         |
| searchProducts         | GET /products?q=search |
| createInvoice          | POST /documents/invoice| 
| getDocument            | GET /documents/:id     |
| listTaxes              | GET /taxes             |
| getTax                 |                        |
| searchTax              |                        |
--------------------------------------------------- 
