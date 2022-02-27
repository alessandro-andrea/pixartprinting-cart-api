# Pixartprinting Code challenge: Cart API

## Intro

Cart API Code challenge: part of the interview process at Pixartprinting

_Dear candidate,_

_we are confident you are excited to take this challenge. As much as we are, when we will review your code._

## Forewords

_The aim is not to develop a full working project, production ready, rather to have a glance of your skills: how you approach and solve a problem._

You don't need to rush into delivering us a solution, but it can't also take forever.

Take the right time to evaluate this challenge: time is important but not the most...

You don't need to overdo or develop any possible aspect of the application, **focus on the most relevant aspects** and those requested by specification.

We will support you in accessing this repo and cloning it

**IMPORTANT**, as soon as you clone this repo:

- add your CONTRIBUTING.md file
- push it back to your working repository on your GitHub account
- right after this, before starting any development, please share back your working repo with us
- you will need CONTRIBUTING.md to input additional information about your development, all the info you consider relevant to share with us before we will discuss togheter your challenge solution

## Cart Challenge description

We are a "web to print" company and we ask you to create a REST API to implement the following business requirements, collected from our e-commerce Cart use case session:

- customers connect to our e-commerce
- choose a product and configure it
- select number of copies to print (quantity) along with a desired delivery date
- select and send us their artwork to print
- add the product to Cart
- checkout the Cart.

To have an idea on how we add products to cart, please have look at a live example: [Product Page](https://www.pixartprinting.co.uk/digital-litho-printing/printing-business-cards/)

### Cart creation

We create a cart by specifying :

`ecommerce_id` , `customer_id`, a `status`, `created_at`, `updated_at`

Cart `status` possible values are : `created`, `building` , `checkout`

### Add Items to Cart

We add items to Cart by providing `ecommerce_id`, `customer_id` and `item_list`

Each entity in `item_list` has the following details:

`product_sku`, `product_name`, `file_type`, `quantity`, `delivery_date`

For this challenge consider:

The `file_type` as a string value indicating the file's MIME type
The `delivery_date` is the choosen date for delivery by a customer
The `quantity` is the number of items for the same product

### View a Cart

The client should be able to query an API endpoint checking what's inside the Cart  

API will return the following details:
Cart `ecommerce_id`, `customer_id`, `created_at` the `status`, eventually a total `price`

The `item_list` with `product_sku`, `product_name`, `file_type`, `quantity`, `delivery_date`.
The calculated `price` for the item based on the policies below.

### Cart checkout

The customer will checkout the cart, the API will set the Cart `date_checkout`
and will calculate and set the final price for the cart

### Price policies calculation

- Let's assume all our products on our catalog have a same standard base price of 1,00€ per item
- When you buy more items of same `product_sku` :
  - no discount is applied for quantities until 100
  - a 5% discount is applied for quantities above 100
  - a 10% discount is applied for quantities above 250
  - a 15% discount is applied for quantities above 500
  - a 20% discount is applied for quantities above 1000
- When the choosen delivery date is (compared to Cart date checkout ):
  - within 24h price increments 30%
  - within 48h price increments 20%
  - within 72h price increments 10%
  - within 1 week price have no increment
- For PDF type formats the price is 15% more than the standard one.
- For PSD type formats (Adobe Photoshop) the price is 35% more than the standard one.
- For AI type formats (Adobe Illustrator) the price is 25% more than the standard one.

## What's desiderable from this project

- Technical stack: You can choose your favourite stack of technologies for this project
- Readability: Create clean code, following standards and good principles of code design. Comment code where's needed.
- Compliance: to the specs
- Testability: easy to understand and test
- Document: Providing a summary of the activity
- Feedback: pls leave us some comments on what you think about the test

_Have fun coding this challenge!_

Please let us know when you are done and have committed everything on your GitHub repo

Do not hesitate to contact us for any question about this challenge,
reaching out to your contact person in Pixartprinting!
