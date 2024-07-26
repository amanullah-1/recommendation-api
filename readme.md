openapi: 3.0.3
info:
  title: Recommendation Engine API
  description: >-
    # 🚀 Get started here


    This template guides you through CRUD operations (GET, POST, PUT, DELETE),
    variables, and tests.


    ## 🔖 **How to use this template**


    #### **Step 1: Send requests**


    RESTful APIs allow you to perform CRUD operations using the POST, GET, PUT,
    and DELETE HTTP methods.


    This collection contains each of these
    [request](https://learning.postman.com/docs/sending-requests/requests/)
    types. Open each request and click "Send" to see what happens.


    #### **Step 2: View responses**


    Observe the response tab for status code (200 OK), response time, and size.


    #### **Step 3: Send new Body data**


    Update or add new data in "Body" in the POST request. Typically, Body data
    is also used in PUT request.


    ```

    {
        "name": "Add your name in the body"
    }

     ```

    #### **Step 4: Update the variable**


    Variables enable you to store and reuse values in Postman. We have created a
    [variable](https://learning.postman.com/docs/sending-requests/variables/)
    called `base_url` with the sample request
    [https://postman-api-learner.glitch.me](https://postman-api-learner.glitch.me).
    Replace it with your API endpoint to customize this collection.


    #### **Step 5: Add tests in the "Scripts" tab**


    Adding tests to your requests can help you confirm that your API is working
    as expected. You can write test scripts in JavaScript and view the output in
    the "Test Results" tab.


    <img
    src="https://content.pstmn.io/fa30ea0a-373d-4545-a668-e7b283cca343/aW1hZ2UucG5n"
    alt="" height="1530" width="2162">


    ## 💪 Pro tips


    - Use folders to group related requests and organize the collection.
        
    - Add more
    [scripts](https://learning.postman.com/docs/writing-scripts/intro-to-scripts/)
    to verify if the API works as expected and execute workflows.
        

    ## 💡Related templates


    [API testing
    basics](https://go.postman.co/redirect/workspace?type=personal&collectionTemplateId=e9a37a28-055b-49cd-8c7e-97494a21eb54&sourceTemplateId=ddb19591-3097-41cf-82af-c84273e56719)  

    [API
    documentation](https://go.postman.co/redirect/workspace?type=personal&collectionTemplateId=e9c28f47-1253-44af-a2f3-20dce4da1f18&sourceTemplateId=ddb19591-3097-41cf-82af-c84273e56719)  

    [Authorization
    methods](https://go.postman.co/redirect/workspace?type=personal&collectionTemplateId=31a9a6ed-4cdf-4ced-984c-d12c9aec1c27&sourceTemplateId=ddb19591-3097-41cf-82af-c84273e56719)
  version: 1.0.0
  contact: {}
servers:
  - url: http://localhost
paths:
  /api/v1/auth/register:
    post:
      tags:
        - UserAuth
      summary: register
      description: >-
        This is a POST request, submitting data to an API via the request body.
        This request submits JSON data, and the data is reflected in the
        response.


        A successful POST request typically returns a `200 OK` or `201 Created`
        response code.
      operationId: register
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                email:
                  type: string
                  example: aman1@aman.com
                firstName:
                  type: string
                  example: aman
                lastName:
                  type: string
                  example: ullah
                password:
                  type: string
                  example: '12345'
            examples:
              register:
                value:
                  email: aman1@aman.com
                  firstName: aman
                  lastName: ullah
                  password: '12345'
      responses:
        '200':
          description: ''
  /api/v1/auth/login_check:
    post:
      tags:
        - UserAuth
      summary: login
      description: >-
        This is a POST request, submitting data to an API via the request body.
        This request submits JSON data, and the data is reflected in the
        response.


        A successful POST request typically returns a `200 OK` or `201 Created`
        response code.
      operationId: login
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                password:
                  type: string
                  example: '12345'
                username:
                  type: string
                  example: aman@aman.com
            examples:
              login:
                value:
                  password: '12345'
                  username: aman@aman.com
      responses:
        '200':
          description: ''
  /api/v1/products:
    get:
      tags:
        - Products
      summary: get products
      description: get products
      operationId: getProducts
      parameters:
        - name: page
          in: query
          schema:
            type: string
            example: '1'
        - name: limit
          in: query
          schema:
            type: string
            example: '30'
      responses:
        '200':
          description: ''
  /api/v1/a-product-and-recommendation/4:
    get:
      tags:
        - Products
      summary: a product n recommendations
      description: a product n recommendations
      operationId: aProductNRecommendations
      responses:
        '200':
          description: ''
  /api/v1/products/create:
    post:
      tags:
        - Products
      summary: add product
      description: add product
      operationId: addProduct
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                brand:
                  type: string
                  example: awesome brand
                category:
                  type: string
                  example: category XYZ
                color:
                  type: string
                  example: resd
                description:
                  type: string
                  example: a awesome product description
                name:
                  type: string
                  example: a product tse
                price:
                  type: string
                  example: '12.79'
                size:
                  type: string
                  example: xyz
            examples:
              add product:
                value:
                  brand: awesome brand
                  category: category XYZ
                  color: resd
                  description: a awesome product description
                  name: a product tse
                  price: '12.79'
                  size: xyz
      responses:
        '200':
          description: ''
  /api/v1/product/edit/201:
    put:
      tags:
        - Products
      summary: edit product
      description: edit product
      operationId: editProduct
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                brand:
                  type: string
                  example: awesome brand
                category:
                  type: string
                  example: category XYZ 2
                color:
                  type: string
                  example: resd
                description:
                  type: string
                  example: a awesome product description 2
                name:
                  type: string
                  example: a product 2
                price:
                  type: string
                  example: '12.9'
                size:
                  type: string
                  example: xyz
            examples:
              edit product:
                value:
                  brand: awesome brand
                  category: category XYZ 2
                  color: resd
                  description: a awesome product description 2
                  name: a product 2
                  price: '12.9'
                  size: xyz
      responses:
        '200':
          description: ''
  /api/v1/product/delete/201:
    delete:
      tags:
        - Products
      summary: delete
      description: delete
      operationId: delete
      responses:
        '200':
          description: ''
  /api/v1/purchases/3:
    get:
      tags:
        - Purchase
      summary: get pruchase list
      description: get pruchase list
      operationId: getPruchaseList
      responses:
        '200':
          description: ''
  /api/v1/purchases/create:
    post:
      tags:
        - Purchase
      summary: add purchase record
      description: add purchase record
      operationId: addPurchaseRecord
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                items:
                  type: array
                  items:
                    type: object
                    properties:
                      productId:
                        type: number
                        example: 4
                      quantity:
                        type: number
                        example: 2
                  example:
                    - productId: 4
                      quantity: 2
                    - productId: 5
                      quantity: 4
                    - productId: 7
                      quantity: 2
            examples:
              add purchase record:
                value:
                  items:
                    - productId: 4
                      quantity: 2
                    - productId: 5
                      quantity: 4
                    - productId: 7
                      quantity: 2
      responses:
        '200':
          description: ''
  /api/v1/purchases/1:
    put:
      tags:
        - Purchase
      summary: edit a purchaserecord
      description: edit a purchaserecord
      operationId: editAPurchaserecord
      requestBody:
        content:
          application/json:
            schema:
              type: object
              properties:
                items:
                  type: array
                  items:
                    type: object
                    properties:
                      productId:
                        type: number
                        example: 1
                      quantity:
                        type: number
                        example: 4
                  example:
                    - productId: 1
                      quantity: 4
                    - productId: 2
                      quantity: 5
            examples:
              edit a purchaserecord:
                value:
                  items:
                    - productId: 1
                      quantity: 4
                    - productId: 2
                      quantity: 5
      responses:
        '200':
          description: ''
  /api/v1/purchases/delete/{id}:
    delete:
      tags:
        - Purchase
      summary: delete
      description: delete
      operationId: delete1
      responses:
        '200':
          description: ''
    parameters:
      - name: id
        in: path
        required: true
        schema:
          type: string
tags:
  - name: UserAuth
  - name: Products
  - name: Purchase
