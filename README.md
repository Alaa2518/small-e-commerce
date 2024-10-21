# small-e-commerce
Build a simplified RESTful API for managing Products and  Orders  in a small e-commerce system


# API Documentation

## Authentication

### User Registration
- **Endpoint:** `POST /api/register`
- **Description:** Register a new user.
- **Request Body:**
    ```json
    {
        "name": "John Doe",
        "email": "johndoe@example.com",
        "password": "password123",
        "password_confirmation": "password123"
    }
    ```
- **Response:**
    - **201 Created**
    ```json
    {
        "message": "User registered successfully."
    }
    ```

### User Login
- **Endpoint:** `POST /api/login`
- **Description:** Log in an existing user.
- **Request Body:**
    ```json
    {
        "email": "johndoe@example.com",
        "password": "password123"
    }
    ```
- **Response:**
    - **200 OK**
    ```json
    {
        "token": "your_access_token",
        "token_type": "Bearer"
    }
    ```

### User Logout
- **Endpoint:** `POST /api/logout`
- **Description:** Log out the authenticated user.
- **Authorization:** Requires Bearer token.
- **Response:**
    - **200 OK**
    ```json
    {
        "message": "User logged out successfully."
    }
    ```

---

## Products

### Create a Product
- **Endpoint:** `POST /api/products`
- **Description:** Add a new product (authenticated users only).
- **Authorization:** Requires Bearer token.
- **Request Body:**
    ```json
    {
        "name": "Product Name",
        "description": "Product Description",
        "price": 99.99,
        "stock_quantity": 10
    }
    ```
- **Response:**
    - **201 Created**
    ```json
    {
        "id": 1,
        "name": "Product Name",
        "description": "Product Description",
        "price": 99.99,
        "stock_quantity": 10
    }
    ```

### Retrieve All Products
- **Endpoint:** `GET /api/products`
- **Description:** Get a list of all products with optional search and pagination.
- **Query Parameters:**
    - `search`: string (optional)
    - `min_price`: numeric (optional)
    - `max_price`: numeric (optional)
    - `per_page`: integer (optional, default: 10)
    - `page`: integer (optional, default: 1)
- **Response:**
    - **200 OK**
    ```json
    {
        "data": [
            {
                "id": 1,
                "name": "Product Name",
                "description": "Product Description",
                "price": 99.99,
                "stock_quantity": 10
            },
            ...
        ],
        "current_page": 1,
        "last_page": 2,
        "per_page": 10,
        "total": 15
    }
    ```

---

## Orders

### Create an Order
- **Endpoint:** `POST /api/orders`
- **Description:** Place a new order (authenticated users only).
- **Authorization:** Requires Bearer token.
- **Request Body:**
    ```json
    {
        "products": [
            {
                "id": 1,
                "quantity": 2
            },
            {
                "id": 2,
                "quantity": 1
            }
        ]
    }
    ```
- **Response:**
    - **201 Created**
    ```json
    {
        "id": 1,
        "user_id": 1,
        "total_amount": 299.97,
        "products": [
            {
                "id": 1,
                "quantity": 2
            },
            {
                "id": 2,
                "quantity": 1
            }
        ]
    }
    ```

### Retrieve All Orders
- **Endpoint:** `GET /api/orders`
- **Description:** Get a list of all orders placed by the authenticated user.
- **Authorization:** Requires Bearer token.
- **Query Parameters:**
    - `per_page`: integer (optional, default: 10)
    - `page`: integer (optional, default: 1)
- **Response:**
    - **200 OK**
    ```json
    {
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "total_amount": 299.97,
                "created_at": "2024-10-22T12:34:56Z",
                "updated_at": "2024-10-22T12:34:56Z"
            },
            ...
        ],
        "current_page": 1,
        "last_page": 2,
        "per_page": 10,
        "total": 15
    }
    ```

### Retrieve a Specific Order
- **Endpoint:** `GET /api/orders/{id}`
- **Description:** Get the details of a specific order.
- **Authorization:** Requires Bearer token.
- **Response:**
    - **200 OK**
    ```json
    {
        "id": 1,
        "user_id": 1,
        "total_amount": 299.97,
        "products": [
            {
                "id": 1,
                "quantity": 2
            },
            {
                "id": 2,
                "quantity": 1
            }
        ],
        "created_at": "2024-10-22T12:34:56Z",
        "updated_at": "2024-10-22T12:34:56Z"
    }
    ```

---

## Notes
- Ensure that all requests requiring authentication include a Bearer token in the `Authorization` header: `Authorization: Bearer your_access_token`.
- All prices should be in the correct format, and stock quantities should be integers.
- Responses may vary based on the application's implementation.
