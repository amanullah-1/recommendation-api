## Submission Document for Recommendation API task for Limitless Technologies

### Project Overview

**Objective:** Develop a scalable and robust backend API for a personalized recommendation engine that enhances customer experience by analyzing user behavior, purchase history, and product attributes to suggest relevant products on product pages and during checkout.

### Technical Stack

- **Programming Language:** PHP
- **Framework:** Symfony Framework
- **Database:** MySQL (hosted on Google Cloud Platform)
- **Version Control System:** Git
- **API Documentation:** Swagger/OpenAPI

### Features Implemented

#### 1. User Management

- **User Registration and Login:**
  - Secure registration and login endpoints.
  - Passwords hashed using bcrypt.
  - Input validation and sanitation to prevent SQL injection and XSS vulnerabilities.
  - JWT-based authentication for session management.

#### 2. Product Management

- **Data Modeling:**
  - Robust data model for products with attributes: category, price, description, brand, size, and color.
  - Entity-Relationship Modeling (ERM) principles used for database design.

- **CRUD Operations:**
  - Create, Read, Update, and Delete operations for products.
  - Proper data validation implemented to ensure data integrity.

#### 3. Purchase History

- **Data Persistence:**
  - Efficient storage of user purchase data, including product information, timestamps, and quantities purchased.
  - Database normalization techniques applied to avoid data redundancy.

#### 4. Recommendation Engine

- **Algorithm Implementation:**
  - Designed and implemented a recommendation algorithm analyzing user purchase history, product attributes, and other factors.
  - Explored collaborative filtering and content-based filtering approaches.
  - Weighting factors considered to prioritize relevant recommendations.

#### 5. API Design & Scalability

- **API Structure:**
  - API structured for clarity and future enhancements.
  - Implemented RESTful principles with proper HTTP status codes.
  - Considered caching mechanisms and database optimization for scalability.

### Bonus Features

- **Unit Tests:**
  - Comprehensive unit tests covering core functionalities.
  - Tests written using PHPUnit.

- **Security Enhancements:**
  - Measures beyond basic authentication to prevent common vulnerabilities (e.g., CSRF protection, input validation).

- **Logging and Monitoring:**
  - Implemented logging for troubleshooting and performance analysis.
  - Integrated monitoring tools to ensure API performance.

- **Scalability Design:**
  - Designed with scalability in mind, using best practices and design patterns to support future growth.

### API Endpoints

#### User Management

- **Register User:** `POST /api/v1/auth/register
- **Login User:** `POST /api/v1/auth/login_check`

#### Product Management and Recommendation Engine

- **Create Product:** `POST /api/v1/products/create`
- **Get Products:** `GET /api/v1/products`
- **Get Product and Recommendation by ID:** `GET /api/v1/a-product-and-recommendation/{id}`
- **Update Product:** `PUT /api/v1/product/edit/{id}`
- **Delete Product:** `DELETE api/v1/product/delete/{id}`

#### Purchase Management

- **Create Purchase:** `POST 0/api/v1/purchases/create`
- **Get Purchases:** `GET //api/v1/purchases`
- **Get Purchase by ID:** `GET /api/v1/purchases/1`
- **Update Purchase:** `PUT /api/v1/purchases/{id}`
- **Delete Purchase:** `DELETE /api/v1/purchases/delete/{id}`


### Database Schema

#### Tables

- **User:**
  - `id` (INT, Primary Key)
  - `username` (VARCHAR)
  - `password` (VARCHAR)
  - `email` (VARCHAR)
  - `roles` (JSON)

- **Product:**
  - `id` (INT, Primary Key)
  - `name` (VARCHAR)
  - `description` (TEXT)
  - `price` (DECIMAL)
  - `brand` (VARCHAR, Nullable)
  - `size` (VARCHAR, Nullable)
  - `color` (VARCHAR, Nullable)
  - `category` (VARCHAR, Nullable)

- **Purchase:**
  - `id` (INT, Primary Key)
  - `user_id` (INT, Foreign Key)
  - `quantity` (INT)
  - `created_at` (DATETIME)
  - `updated_at` (DATETIME)

- **PurchaseItem:**
  - `purchase_id` (INT, Foreign Key)
  - `product_id` (INT, Foreign Key)
  - `quantity` (INT)

### Additional Libraries/Frameworks Used

- **JWT Authentication:** `lexik/jwt-authentication-bundle`
- **Doctrine Migrations:** `doctrine/migrations`
- **PHPUnit:** `phpunit/phpunit`

### GitHub Repository

[Link to GitHub Repository](#)

### Swaagger API Documentation

- A swagger api documentation is provided in a static html file in side the zipped directory.

### Approach and Thought Process

1. **Planning and Design:**
   - Analyzed the requirements and designed the database schema using ERM principles.
   - Structured the API endpoints to follow RESTful principles.

2. **Development:**
   - Implemented user authentication and product management features.
   - Developed the purchase history management system.
   - Designed and implemented the recommendation engine.
   - Added comprehensive unit tests to ensure functionality.

3. **Security:**
   - Ensured secure user authentication with JWT.
   - Implemented input validation and sanitation to prevent vulnerabilities.

4. **Scalability:**
   - Designed the API with scalability in mind, considering future growth and performance optimization.

### Conclusion

By following a structured approach and adhering to best practices, I successfully developed a robust and scalable backend API for the personalized recommendation engine. The API is secure, well-documented, and ready for future enhancements.


