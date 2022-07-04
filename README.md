### php-factpt-cli ###
PHP API Client implementation for the Online invoicing System solution provided by [FACT](www.fact.pt).


## Install

The package is hosted on [packagist](packagist.org). To install run:
```
composer install digfish/php-factpt-cli
```


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
