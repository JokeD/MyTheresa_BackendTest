## Requirements

### What we expect ###

Code structure/architecture must fit this use case, as simple or as complex needed to complete what
is asked for.

Tests are a must. Code must be testable without requiring networking or the filesystem. Tests should
be runnable with 1 command.

The project must be runnable with 1 simple command from any machine.
Explanations on decisions taken

### Given list of products

```
{
    "products": [
        {
        "sku": "000001",
        "name": "BV Lean leather ankle boots",
        "category": "boots",
        "price": 89000
        },
        {
        "sku": "000002",
        "name": "BV Lean leather ankle boots",
        "category": "boots",
        "price": 99000
        },
        {
        "sku": "000003",
        "name": "Ashlington leather ankle boots",
        "category": "boots",
        "price": 71000
        },
        {
        "sku": "000004",
        "name": "Naima embellished suede sandals",
        "category": "sandals",
        "price": 79500
        },
        {
        "sku": "000005",
        "name": "Nathane leather sneakers",
        "category": "sneakers",
        "price": 59000
        }
    ]
}
```

You must take into account that this list could grow to have 20.000 products.

The prices are integers for example, 100.00€ would be 10000.

You can store the products as you see fit (json file, in memory, rdbms of choice)

### Given that:

Products in the boots category have a 30% discount.

The product with sku = 000003 has a 15% discount.

When multiple discounts collide, the biggest discount must be applied.

Provide a single endpoint GET /products

Can be filtered by category as a query string parameter
(optional) 

Can be filtered by priceLessThan as a query string parameter, this filter applies before
discounts are applied and will show products with prices lesser than or equal the value provided.


Returns a list of Product with the given discounts applied when necessary
Must return at most 5 elements. (The order does not matter)

### Product model

price.currency is always EUR

When a product does not have a discount, price.final and price.original should be the same number
and discount_percentage should be null.

When a product has a discount price.original is the original price, price.final is the amount with the
discount applied and discount_percentage represents the applied discount with the % sign.
Example product with a discount of 30% applied:
```
{
    "sku": "000001",
    "name": "BV Lean leather ankle boots",
    "category": "boots",
    "price": {
    "original": 89000,
    "final": 62300,
    "discount_percentage": "30%",
    "currency": "EUR"
    }
}
```
Example product without a discount
```    
{
    "sku": "000001",
    "name": "BV Lean leather ankle boots",
    "category": "boots",
    "price": {
    "original": 89000,
    "final": 89000,
    "discount_percentage": null,
    "currency": "EUR"
    }
}
```

## Explanation

Test was made with symfony fmk and trying a DDD approach.

Requirements to local environment: Docker , Docker Compose.
The persistence infrastructure is mysql.

All the infra (Docker image etc..) is inside the **ops** folder.

The system can handle other types of discounts if is it necessary such as fixed amount
(example fixed 20E) or based in any case of product condition.

Just to extend from Discountable Interface.

Hex. Architecture implemented by UseCases Repository Interface

Command bus and doctrine middleware configured in order to improve performance
wrapping transactions. 

If the application grows and more UseCases are
chained, that guaranties for data consistency because it will internally rollback,
plus de database connection will be opened once and not in every **flush** in repositories.

In order to scale at 20.000  products I will suggest to use ElasticSearch.

The insertion off products can be improved doing an async job queue's.

### Installation

Browse to folder :  **ops/docker/**
and then : 

```bash
./init
```

Then the dev server will be availabe to receive request's to ***localhost:8001/products***

(Optionally) Manually steps can be called :


```bash
./build && ./up && ./composer install ...
```

(Optionally) Manually create the database schema :

```bash
./console doctrine:schema:create
./console doctrine:schema:create --env=test
```

### Tests

Run integration and units tests

```bash
./tests
```

### REST - Usage

A Postman Collection included at project root level : MyTheresa.postman_collection.json
