# FinTrack API Documentation

## Overview

This document provides detailed API documentation for the FinTrack backend application. All endpoints return JSON responses and use standard HTTP status codes.

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
All endpoints except authentication require a Bearer token in the Authorization header:
```
Authorization: Bearer {your_token_here}
```

---

## 1. Authentication

### 1.1 Register User
Create a new user account.

**Endpoint:** `POST /register`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "email": "string (required, email, unique)",
  "password": "string (required, min:8)",
  "password_confirmation": "string (required, same:password)"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123def456..."
  }
}
```

### 1.2 Login User
Authenticate and get access token.

**Endpoint:** `POST /login`

**Request Body:**
```json
{
  "email": "string (required, email)",
  "password": "string (required)"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "token": "1|abc123def456..."
  }
}
```

### 1.3 Logout User
Invalidate the current access token.

**Endpoint:** `POST /logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "message": "Logged out successfully"
}
```

### 1.4 Get Authenticated User
Get current user information.

**Endpoint:** `GET /user`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "id": 1,
  "name": "John Doe",
  "email": "john@example.com",
  "email_verified_at": null,
  "created_at": "2024-01-01T00:00:00.000000Z",
  "updated_at": "2024-01-01T00:00:00.000000Z"
}
```

---

## 2. Dashboard

### 2.1 Get Dashboard Overview
Get comprehensive financial overview and statistics.

**Endpoint:** `GET /dashboard`

**Headers:**
```
Authorization: Bearer {token}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Dashboard data retrieved successfully",
  "data": {
    "total_income": 5000.00,
    "total_expenses": 3200.00,
    "total_investments": 15000.00,
    "total_debts": 8000.00,
    "total_assets": 25000.00,
    "net_worth": 25000.00,
    "monthly_budget_usage": 75.5,
    "recent_transactions": [...],
    "budget_summary": {...},
    "investment_performance": {...}
  }
}
```

---

## 3. Income Management

### 3.1 Income Sources

#### List Income Sources
**Endpoint:** `GET /income-sources`

**Response (200):**
```json
{
  "success": true,
  "message": "Income sources retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Primary Job",
      "description": "Main employment income",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Create Income Source
**Endpoint:** `POST /income-sources`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "description": "string (optional, max:1000)"
}
```

#### Get Income Source
**Endpoint:** `GET /income-sources/{id}`

#### Update Income Source
**Endpoint:** `PUT /income-sources/{id}`

**Request Body:**
```json
{
  "name": "string (optional, max:255)",
  "description": "string (optional, max:1000)"
}
```

#### Delete Income Source
**Endpoint:** `DELETE /income-sources/{id}`

### 3.2 Incomes

#### List Incomes
**Endpoint:** `GET /incomes`

**Query Parameters:**
- `start_date` (YYYY-MM-DD)
- `end_date` (YYYY-MM-DD)
- `income_source_id` (integer)

**Response (200):**
```json
{
  "success": true,
  "message": "Incomes retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "income_source_id": 1,
      "amount": 3000.00,
      "date": "2024-01-15",
      "description": "January salary",
      "income_source": {
        "id": 1,
        "name": "Primary Job"
      },
      "created_at": "2024-01-15T00:00:00.000000Z",
      "updated_at": "2024-01-15T00:00:00.000000Z"
    }
  ]
}
```

#### Create Income
**Endpoint:** `POST /incomes`

**Request Body:**
```json
{
  "income_source_id": "integer (required, exists:income_sources,id)",
  "amount": "numeric (required, min:0.01)",
  "date": "date (required)",
  "description": "string (optional, max:1000)"
}
```

#### Get Income
**Endpoint:** `GET /incomes/{id}`

#### Update Income
**Endpoint:** `PUT /incomes/{id}`

#### Delete Income
**Endpoint:** `DELETE /incomes/{id}`

---

## 4. Expense Management

### 4.1 List Expenses
**Endpoint:** `GET /expenses`

**Query Parameters:**
- `start_date` (YYYY-MM-DD)
- `end_date` (YYYY-MM-DD)
- `category` (string)

**Response (200):**
```json
{
  "success": true,
  "message": "Expenses retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "amount": 150.00,
      "category": "groceries",
      "date": "2024-01-10",
      "description": "Weekly grocery shopping",
      "created_at": "2024-01-10T00:00:00.000000Z",
      "updated_at": "2024-01-10T00:00:00.000000Z"
    }
  ]
}
```

### 4.2 Create Expense
**Endpoint:** `POST /expenses`

**Request Body:**
```json
{
  "amount": "numeric (required, min:0.01)",
  "category": "string (required, max:255)",
  "date": "date (required)",
  "description": "string (optional, max:1000)"
}
```

### 4.3 Get Expense
**Endpoint:** `GET /expenses/{id}`

### 4.4 Update Expense
**Endpoint:** `PUT /expenses/{id}`

### 4.5 Delete Expense
**Endpoint:** `DELETE /expenses/{id}`

---

## 5. Budget Management

### 5.1 Budgets

#### List Budgets
**Endpoint:** `GET /budgets`

**Response (200):**
```json
{
  "success": true,
  "message": "Budgets retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Monthly Budget - January 2024",
      "time_period": "monthly",
      "category_type": "expense",
      "status": "active",
      "description": "January household expenses",
      "total_planned_amount": 3000.00,
      "total_spent_amount": 1250.00,
      "usage_percentage": 41.67,
      "remaining_amount": 1750.00,
      "calculated_status": "on_track",
      "budget_items_count": 5,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-15T00:00:00.000000Z"
    }
  ]
}
```

#### Create Budget
**Endpoint:** `POST /budgets`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "time_period": "string (required, in:daily,weekly,monthly,yearly)",
  "category_type": "string (required, in:income,investment,expense,asset,debt)",
  "description": "string (optional, max:1000)",
  "category": "string (optional, max:255)",
  "monthly_limit": "numeric (optional, min:0)",
  "month": "integer (optional, 1-12)",
  "year": "integer (optional, min:2000)"
}
```

#### Get Budget with Items
**Endpoint:** `GET /budgets/{id}`

**Response (200):**
```json
{
  "success": true,
  "message": "Budget retrieved successfully",
  "data": {
    "id": 1,
    "name": "Monthly Budget - January 2024",
    "total_planned_amount": 3000.00,
    "total_spent_amount": 1250.00,
    "usage_percentage": 41.67,
    "remaining_amount": 1750.00,
    "calculated_status": "on_track",
    "budget_items": [
      {
        "id": 1,
        "budget_id": 1,
        "name": "Groceries",
        "planned_amount": 500.00,
        "spent_amount": 125.00,
        "category_type": "expense",
        "category": "groceries",
        "status": "active",
        "usage_percentage": 25.0,
        "remaining_amount": 375.00,
        "calculated_status": "on_track"
      }
    ]
  }
}
```

#### Update Budget
**Endpoint:** `PUT /budgets/{id}`

#### Delete Budget
**Endpoint:** `DELETE /budgets/{id}`

### 5.2 Budget Items

#### List All Budget Items
**Endpoint:** `GET /budget-items`

#### Get Budget Items for Specific Budget
**Endpoint:** `GET /budgets/{budgetId}/items`

**Response (200):**
```json
{
  "success": true,
  "message": "Budget items retrieved successfully",
  "data": {
    "budget": {
      "id": 1,
      "name": "Monthly Budget - January 2024",
      "total_planned_amount": 3000.00,
      "total_spent_amount": 1250.00
    },
    "budget_items": [
      {
        "id": 1,
        "budget_id": 1,
        "name": "Groceries",
        "planned_amount": 500.00,
        "spent_amount": 125.00,
        "category_type": "expense",
        "category": "groceries",
        "status": "active",
        "usage_percentage": 25.0,
        "remaining_amount": 375.00,
        "calculated_status": "on_track"
      }
    ],
    "summary": {
      "total_items": 5,
      "total_planned": 3000.00,
      "total_spent": 1250.00,
      "total_remaining": 1750.00
    }
  }
}
```

#### Create Budget Item
**Endpoint:** `POST /budget-items`

**Request Body:**
```json
{
  "budget_id": "integer (required, exists:budgets,id)",
  "name": "string (required, max:255)",
  "planned_amount": "numeric (required, min:0.01)",
  "spent_amount": "numeric (optional, min:0, default:0)",
  "category_type": "string (required, in:income,investment,expense,asset,debt)",
  "category": "string (optional, max:255)",
  "description": "string (optional, max:1000)"
}
```

#### Get Budget Item
**Endpoint:** `GET /budget-items/{id}`

#### Update Budget Item
**Endpoint:** `PUT /budget-items/{id}`

#### Update Spent Amount (Quick Update)
**Endpoint:** `PATCH /budget-items/{id}/spent-amount`

**Request Body:**
```json
{
  "spent_amount": "numeric (required, min:0)"
}
```

#### Delete Budget Item
**Endpoint:** `DELETE /budget-items/{id}`

#### Get Budget Items Summary by Category
**Endpoint:** `GET /budget-items-summary`

**Response (200):**
```json
{
  "success": true,
  "message": "Budget items summary retrieved successfully",
  "data": [
    {
      "category_type": "expense",
      "item_count": 5,
      "total_planned": 3000.00,
      "total_spent": 1250.00
    },
    {
      "category_type": "income",
      "item_count": 2,
      "total_planned": 5000.00,
      "total_spent": 4800.00
    }
  ]
}
```

---

## 6. Investment Management

### 6.1 List Investments
**Endpoint:** `GET /investments`

**Response (200):**
```json
{
  "success": true,
  "message": "Investments retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Tech Stock Portfolio",
      "type": "stocks",
      "amount_invested": 10000.00,
      "current_value": 12500.00,
      "date_invested": "2024-01-01",
      "description": "Technology sector investments",
      "gain_loss": 2500.00,
      "gain_loss_percentage": 25.0,
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-15T00:00:00.000000Z"
    }
  ]
}
```

### 6.2 Create Investment
**Endpoint:** `POST /investments`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "type": "string (required, in:stocks,bonds,mutual_funds,real_estate,crypto,other)",
  "amount_invested": "numeric (required, min:0.01)",
  "current_value": "numeric (required, min:0)",
  "date_invested": "date (required)",
  "description": "string (optional, max:1000)"
}
```

### 6.3 Get Investment
**Endpoint:** `GET /investments/{id}`

### 6.4 Update Investment
**Endpoint:** `PUT /investments/{id}`

### 6.5 Delete Investment
**Endpoint:** `DELETE /investments/{id}`

---

## 7. Debt Management

### 7.1 Debts

#### List Debts
**Endpoint:** `GET /debts`

**Response (200):**
```json
{
  "success": true,
  "message": "Debts retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Car Loan",
      "amount": 15000.00,
      "due_date": "2025-12-31",
      "status": "partial",
      "total_paid": 3000.00,
      "remaining_balance": 12000.00,
      "calculated_status": "partial",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-15T00:00:00.000000Z"
    }
  ]
}
```

#### Create Debt
**Endpoint:** `POST /debts`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "amount": "numeric (required, min:0.01)",
  "due_date": "date (required)",
  "status": "string (optional, in:paid,partial,unpaid, default:unpaid)"
}
```

#### Get Debt
**Endpoint:** `GET /debts/{id}`

#### Update Debt
**Endpoint:** `PUT /debts/{id}`

#### Delete Debt
**Endpoint:** `DELETE /debts/{id}`

### 7.2 Debt Payments

#### List All Debt Payments
**Endpoint:** `GET /debt-payments`

#### Get Debt Payments for Specific Debt
**Endpoint:** `GET /debts/{debtId}/payments`

**Response (200):**
```json
{
  "success": true,
  "message": "Debt payments retrieved successfully",
  "data": {
    "debt": {
      "id": 1,
      "name": "Car Loan",
      "amount": 15000.00,
      "total_paid": 3000.00,
      "remaining_balance": 12000.00
    },
    "payments": [
      {
        "id": 1,
        "debt_id": 1,
        "amount": 500.00,
        "payment_date": "2024-01-15",
        "payment_method": "bank_transfer",
        "notes": "Monthly payment",
        "created_at": "2024-01-15T00:00:00.000000Z"
      }
    ],
    "summary": {
      "total_debt": 15000.00,
      "total_paid": 3000.00,
      "remaining_balance": 12000.00,
      "payment_count": 6
    }
  }
}
```

#### Create Debt Payment
**Endpoint:** `POST /debt-payments`

**Request Body:**
```json
{
  "debt_id": "integer (required, exists:debts,id)",
  "amount": "numeric (required, min:0.01)",
  "payment_date": "date (required)",
  "payment_method": "string (optional, max:255)",
  "notes": "string (optional)"
}
```

#### Get Debt Payment
**Endpoint:** `GET /debt-payments/{id}`

#### Update Debt Payment
**Endpoint:** `PUT /debt-payments/{id}`

#### Delete Debt Payment
**Endpoint:** `DELETE /debt-payments/{id}`

#### Get Debt Payments Summary
**Endpoint:** `GET /debt-payments-summary`

**Response (200):**
```json
{
  "success": true,
  "message": "Payment summary retrieved successfully",
  "data": {
    "total_debts": 3,
    "total_debt_amount": 45000.00,
    "total_paid": 12000.00,
    "remaining_balance": 33000.00,
    "fully_paid_debts": 0,
    "partially_paid_debts": 2,
    "unpaid_debts": 1
  }
}
```

---

## 8. Asset Management

### 8.1 List Assets
**Endpoint:** `GET /assets`

**Response (200):**
```json
{
  "success": true,
  "message": "Assets retrieved successfully",
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "name": "Primary Residence",
      "type": "real_estate",
      "value": 250000.00,
      "acquisition_date": "2020-05-15",
      "description": "Family home",
      "created_at": "2024-01-01T00:00:00.000000Z",
      "updated_at": "2024-01-01T00:00:00.000000Z"
    }
  ]
}
```

### 8.2 Create Asset
**Endpoint:** `POST /assets`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "type": "string (required, in:real_estate,vehicle,jewelry,art,collectibles,other)",
  "value": "numeric (required, min:0.01)",
  "acquisition_date": "date (required)",
  "description": "string (optional, max:1000)"
}
```

### 8.3 Get Asset
**Endpoint:** `GET /assets/{id}`

### 8.4 Update Asset
**Endpoint:** `PUT /assets/{id}`

### 8.5 Delete Asset
**Endpoint:** `DELETE /assets/{id}`

---

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "amount": ["The amount must be at least 0.01."]
  }
}
```

### Not Found Error (404)
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### Unauthorized Error (401)
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### Forbidden Error (403)
```json
{
  "success": false,
  "message": "Access denied"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "Internal server error",
  "error": "Detailed error message"
}
```

---

## Rate Limiting

API endpoints are rate limited to prevent abuse. Default limits:
- 60 requests per minute for authenticated endpoints
- 10 requests per minute for authentication endpoints

---

## Data Types and Validation Rules

### Common Validation Rules
- `required`: Field must be present
- `string`: Must be a string
- `numeric`: Must be a number
- `integer`: Must be an integer
- `date`: Must be a valid date (YYYY-MM-DD)
- `email`: Must be a valid email address
- `min:value`: Minimum value/length
- `max:value`: Maximum value/length
- `exists:table,column`: Must exist in specified table/column
- `unique:table,column`: Must be unique in table
- `in:value1,value2`: Must be one of the specified values

### Date Format
All dates use ISO 8601 format: `YYYY-MM-DD`

### Decimal Precision
Monetary values use 2 decimal places (e.g., 123.45)

---

## Webhooks and Real-time Updates

Currently, the API does not support webhooks. For real-time updates, clients should poll relevant endpoints periodically or implement long-polling strategies.

---

## Versioning

The API uses URL versioning (`/api/v1/`). Future versions will be released under `/api/v2/`, etc., while maintaining backward compatibility where possible.