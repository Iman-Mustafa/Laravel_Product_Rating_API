# Product Rating API Assessment

## Candidate Information
- **Name:** Iman Mustafa Hussein
- **Phone:** +255755109306 / +255673893640
- **Assessment:** Practical Interview Test (Laravel Backend Developer)

---

## Overview
A complete Laravel RESTful API implementation for managing products, user ratings, calculation metrics, and external hospital registration integration.

### Core Features Implemented:
1. **Task 1 & 2 (Models, Migrations & Relationships):**
   - `User`, `Product`, and `UserRating` models.
   - Foreign keys linking `users` and `products` to `user_ratings`.
   - Unique compound index on `[user_id, product_id]` preventing duplicate ratings.
   - Database seeder with dummy users and products.

2. **Task 3 & 5 (Controllers, Validation & Logic):**
   - **Rate & Update Product:** Validates rating (1 to 5) and creates or updates rating for existing user/product pair.
   - **Remove Rating:** Removes user's rating for a specific product.
   - **List Products:** Returns list of products with:
     - `ratings`: Calculated average rating of each product.
     - `user_rating`: Specific rating given by requesting user.
     - `time_passed`: Elapsed time in minutes since `rating_datetime`.
     - `active_time`: `'active'` if `time_passed > 30` minutes, else `'inactive'`.

3. **Task 4 (API Routes):** Clean endpoints defined in `routes/api.php`.

4. **Bonus Task:** Endpoint to register a new patient with Gpitg Hospital by forwarding payload to external endpoint (`http://41.188.172.204:3033/patient-registration`) and returning `Check_In_Date_And_Time`.

---

## Setup & Installation

```bash
# 1. Install dependencies
composer install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Run database migrations & seed dummy data
php artisan migrate --seed

# 4. Start local development server
php artisan serve
```

---

## API Documentation & Examples

### 1. List Products
- **Endpoint:** `GET /api/products?user_id=1`
- **Description:** Returns all products along with average rating, requesting user's rating, time passed, and active status.

**Sample Response:**
```json
{
  "status": "success",
  "data": [
    {
      "id": 1,
      "name": "Samsung Galaxy S24",
      "description": "Flagship smartphone with high resolution dynamic AMOLED display",
      "price": "TSH 700000",
      "ratings": 4.5,
      "user_rating": 5,
      "time_passed": "35 minutes",
      "active_time": "active"
    },
    {
      "id": 2,
      "name": "MacBook Air M2",
      "description": "Lightweight Apple laptop with M2 chip and long battery life",
      "price": "TSH 5000000",
      "ratings": 0,
      "user_rating": null,
      "time_passed": null,
      "active_time": "inactive"
    }
  ]
}
```

---

### 2. Rate Product (Create / Update)
- **Endpoint:** `POST /api/ratings`
- **Description:** Creates a new rating or updates an existing rating if user already rated the product. Rating must be between 1 and 5.

**Request Body:**
```json
{
  "user_id": 1,
  "product_id": 1,
  "rating": 5
}
```

**Sample Response:**
```json
{
  "status": "success",
  "message": "Product rated successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "product_id": 1,
    "rating": 5,
    "rating_datetime": "2026-08-29T11:20:00.000000Z",
    "created_at": "2026-08-29T11:20:00.000000Z",
    "updated_at": "2026-08-29T11:20:00.000000Z"
  }
}
```

---

### 3. Change Rating
- **Endpoint:** `POST /api/ratings/change`
- **Description:** Updates the existing rating value for the given user and product.

**Request Body:**
```json
{
  "user_id": 1,
  "product_id": 1,
  "rating": 4
}
```

**Sample Response:**
```json
{
  "status": "success",
  "message": "Product rated successfully",
  "data": {
    "id": 1,
    "user_id": 1,
    "product_id": 1,
    "rating": 4,
    "rating_datetime": "2026-08-29T11:25:00.000000Z",
    "created_at": "2026-08-29T11:20:00.000000Z",
    "updated_at": "2026-08-29T11:25:00.000000Z"
  }
}
```

---

### 4. Remove Rating
- **Endpoint:** `POST /api/ratings/remove`
- **Description:** Removes a user rating for a product.

**Request Body:**
```json
{
  "user_id": 1,
  "product_id": 1
}
```

**Sample Response:**
```json
{
  "status": "success",
  "message": "Rating removed successfully"
}
```

---

### 5. Bonus: Hospital Patient Registration
- **Endpoint:** `POST /api/patient-registration`
- **Description:** Registers a new patient with Gpitg Hospital via external service.

**Request Body:**
```json
{
  "Sponsor_ID": "1",
  "Patient_Name": "ngenzi ngenzi",
  "Date_Of_Birth": "2022-07-02",
  "Gender": "Male",
  "Visit_Type_ID": "1",
  "Type_Of_Check_In": "1",
  "branchId": "1",
  "Employee_ID": "46",
  "pf3": null,
  "Diceased": "no",
  "Referral_Status": null
}
```

**Sample Response:**
```json
{
  "message": "Patient registered successfully",
  "Check_In_Date_And_Time": "2026-08-29 11:20:00",
  "response_data": {
    "status": "success",
    "Check_In_Date_And_Time": "2026-08-29 11:20:00"
  }
}
```
