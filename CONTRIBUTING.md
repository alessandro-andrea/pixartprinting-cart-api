# Technical stack
Cart API is developed onto a fresh installation of Laravel/Lumen framework (version 9.0).

Project's environment was composed by:
- PHP 8.1.0
- Composer 2.2.7
- MySQL 8.0.27

# The interface
I can suppose that a customer has only one opened cart at time by an ecommerce, in _created_ or _building_ status), that is identified by its _customer_id_ and an _ecommerce_id_.

To avoid decimal problems I save items price and cart's total in integer format. To get prices in currency format, I use the library [Money](https://github.com/moneyphp/money) that is great to manage currencies and to show prices in well-formatted way.

The interface has the role to manage all information about items and cart totals, and it follows REST/CRUD pattern to do it.

The interface has its own versioning system and, at this time, it's "v1": this slug is important and required for future updates or improvements.

## Retrieve a cart (GET)
To obtain the current cart opened by a customer in an ecommerce, the interface required a _customer_id_ and an _ecommerce_id_.

Example:

```http request
Request:

GET /v1/cart/1/2

Response:

{
    "ecommerce_id": 2,
    "customer_id": 4,
    "status": "checkout",
    "price": "3.286,58 €",
    "created_at": "2022-02-27 21:13:48",
    "item_list": [
        {
            "product_sku": "PRD1",
            "product_name": "Product 1",
            "file_type": "pdf",
            "quantity": 1,
            "price": "1,49 €",
            "delivery_date": "2022-03-01"
        },
        {
            "product_sku": "PRD2",
            "product_name": "Product 2",
            "file_type": "ai",
            "quantity": 500,
            "price": "618,75 €",
            "delivery_date": "2022-03-03"
        },
        {
            "product_sku": "PRD2",
            "product_name": "Product 2",
            "file_type": "psd",
            "quantity": 1000,
            "price": "1.377,00 €",
            "delivery_date": "2022-03-02"
        }
    ]
}
```

## Create a new cart (POST)
To create a new cart for a customer in an ecommerce, the interface required a _customer_id_ and an _ecommerce_id_.

Example:

```http request
Request:

POST /v1/cart/

Body:

{
    "ecommerce_id": 1,
    "customer_id": 2
}

Response:

[]
```

## Update a cart (PUT)
To update an opened cart by a customer in an ecommerce, the interface required a _customer_id_ and an _ecommerce_id_ and an items list.

Example:

```http request
Request:

PUT /v1/cart/

Body:

{
    "ecommerce_id": 2,
    "customer_id": 4,
    "item_list": [
        {
            "product_sku": "PRD1",
            "product_name": "Product 1",
            "file_type": "pdf",
            "quantity": 1,
            "delivery_date": "2022-03-01"
        },
         {
            "product_sku": "PRD2",
            "product_name": "Product 2",
            "file_type": "ai",
            "quantity": 500,
            "delivery_date": "2022-03-03"
        },
         {
            "product_sku": "PRD3",
            "product_name": "Product 3",
            "file_type": "psd",
            "quantity": 1000,
            "delivery_date": "2022-03-02"
        }
    ]
}

Response:

[]
```

## Delete a cart (DELETE)
To delete an opened cart, the interface required a _customer_id_ and an _ecommerce_id_.

Example:

```http request
Request:

DELETE /v1/cart/1/2

Response:

[]
```

## Checkout a cart (PATCH)
To execute the checkout action on a cart for a customer in an ecommerce, the interface required a _customer_id_ and an _ecommerce_id_.

Example:

```http request
Request:

PATCH /v1/cart/1/2

Response:

[]
```

# The project
The source code is hosted on [Github](https://github.com) and it can be cloned with the command:

```bash
git clone https://github.com/alessandro-andrea/pixartprinting-cart-api.git
```

Enter the cart-api directory and execute a `composer install` command to download the dependencies.

Then copy the `.env.example` file to `.env` file.
Update the `.env` file with the information about the database's connection.

Before to test the interface, execute the command to create database tables:

```bash
php artisan migrate
```

If you want, you can populate the database with some example data:

```bash
php artisan db:seed
```

# Testing
You can test the interface with an API Client, such as [Postman](https://www.postman.com).

