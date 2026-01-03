# FinTrack Backend API

A comprehensive financial tracking API built with Laravel that helps users manage their personal finances including income, expenses, budgets, investments, debts, and assets.

## 🚀 Features

### Core Financial Management
- **Income Tracking**: Track multiple income sources and amounts
- **Expense Management**: Categorize and monitor spending patterns
- **Budget Planning**: Create detailed budgets with individual budget items
- **Investment Portfolio**: Track investment performance and growth
- **Debt Management**: Monitor debts with payment tracking
- **Asset Management**: Keep track of personal assets

### Advanced Features
- **Real-time Calculations**: Automatic percentage calculations and status tracking
- **Hierarchical Budgeting**: Budgets contain multiple detailed budget items
- **Payment Tracking**: Track individual debt payments with history
- **Category Organization**: Organize finances by income, expense, investment, asset, and debt categories
- **Dashboard Analytics**: Comprehensive financial overview and insights

## 🛠 Tech Stack

- **Framework**: Laravel 11.x
- **Database**: MySQL/PostgreSQL
- **Authentication**: Laravel Sanctum
- **API**: RESTful API with JSON responses
- **Validation**: Laravel Request Validation
- **Migrations**: Database migrations for schema management

## 📋 Prerequisites

- PHP 8.1 or higher
- Composer
- MySQL/PostgreSQL database
- Laravel CLI

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd fintrack-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Update your `.env` file with database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fintrack
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Run Migrations**
   ```bash
   php artisan migrate
   ```

6. **Start the Server**
   ```bash
   php artisan serve
   ```

## 📊 Database Schema

### Core Tables

#### Users
- `id`, `name`, `email`, `password`, `timestamps`

#### Income Sources
- `id`, `user_id`, `name`, `description`, `timestamps`

#### Incomes
- `id`, `user_id`, `income_source_id`, `amount`, `date`, `description`, `timestamps`

#### Expenses
- `id`, `user_id`, `amount`, `category`, `date`, `description`, `timestamps`

#### Budgets
- `id`, `user_id`, `name`, `time_period`, `category_type`, `status`, `description`, `timestamps`

#### Budget Items
- `id`, `budget_id`, `user_id`, `name`, `planned_amount`, `spent_amount`, `category_type`, `category`, `description`, `status`, `timestamps`

#### Investments
- `id`, `user_id`, `name`, `type`, `amount_invested`, `current_value`, `date_invested`, `description`, `timestamps`

#### Debts
- `id`, `user_id`, `name`, `amount`, `due_date`, `status`, `timestamps`

#### Debt Payments
- `id`, `debt_id`, `user_id`, `amount`, `payment_date`, `payment_method`, `notes`, `timestamps`

#### Assets
- `id`, `user_id`, `name`, `type`, `value`, `acquisition_date`, `description`, `timestamps`

## 🔐 Authentication

The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header for protected routes.

### Authentication Endpoints

#### Register
```http
POST /api/v1/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

#### Login
```http
POST /api/v1/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

#### Logout
```http
POST /api/v1/logout
Authorization: Bearer {token}
```

## 📚 API Endpoints

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication Required
All endpoints except registration and login require authentication.

---

## 💰 Income Management

### Income Sources
```http
GET    /income-sources          # List income sources
POST   /income-sources          # Create income source
GET    /income-sources/{id}     # Get specific income source
PUT    /income-sources/{id}     # Update income source
DELETE /income-sources/{id}     # Delete income source
```

### Incomes
```http
GET    /incomes                 # List incomes
POST   /incomes                 # Create income
GET    /incomes/{id}            # Get specific income
PUT    /incomes/{id}            # Update income
DELETE /incomes/{id}            # Delete income
```

---

## 💸 Expense Management

```http
GET    /expenses                # List expenses
POST   /expenses                # Create expense
GET    /expenses/{id}           # Get specific expense
PUT    /expenses/{id}           # Update expense
DELETE /expenses/{id}           # Delete expense
```

---

## 📊 Budget Management

### Budgets
```http
GET    /budgets                 # List budgets with items
POST   /budgets                 # Create budget
GET    /budgets/{id}            # Get budget with all items
PUT    /budgets/{id}            # Update budget
DELETE /budgets/{id}            # Delete budget
```

### Budget Items
```http
GET    /budget-items                                    # List all budget items
POST   /budget-items                                    # Create budget item
GET    /budget-items/{id}                               # Get specific budget item
PUT    /budget-items/{id}                               # Update budget item
DELETE /budget-items/{id}                               # Delete budget item
GET    /budgets/{budgetId}/items                        # Get items for specific budget
PATCH  /budget-items/{id}/spent-amount                  # Update spent amount
GET    /budget-items-summary                            # Summary by category
```

---

## 📈 Investment Management

```http
GET    /investments             # List investments
POST   /investments             # Create investment
GET    /investments/{id}        # Get specific investment
PUT    /investments/{id}        # Update investment
DELETE /investments/{id}        # Delete investment
```

---

## 🏦 Debt Management

### Debts
```http
GET    /debts                   # List debts
POST   /debts                   # Create debt
GET    /debts/{id}              # Get specific debt
PUT    /debts/{id}              # Update debt
DELETE /debts/{id}              # Delete debt
```

### Debt Payments
```http
GET    /debt-payments                           # List all debt payments
POST   /debt-payments                           # Create debt payment
GET    /debt-payments/{id}                      # Get specific payment
PUT    /debt-payments/{id}                      # Update payment
DELETE /debt-payments/{id}                      # Delete payment
GET    /debts/{debtId}/payments                 # Get payments for debt
GET    /debt-payments-summary                   # Payment summary
```

---

## 🏠 Asset Management

```http
GET    /assets                  # List assets
POST   /assets                  # Create asset
GET    /assets/{id}             # Get specific asset
PUT    /assets/{id}             # Update asset
DELETE /assets/{id}             # Delete asset
```

---

## 📊 Dashboard

```http
GET    /dashboard               # Get financial overview
GET    /user                    # Get authenticated user info
```

## 📝 API Response Format

All API responses follow a consistent format:

### Success Response
```json
{
  "success": true,
  "message": "Operation completed successfully",
  "data": {
    // Response data
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "error": "Detailed error information"
}
```

### Validation Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

## 🔍 Example Usage

### Create a Budget with Items

1. **Create Budget**
```http
POST /api/v1/budgets
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Monthly Budget - January 2024",
  "time_period": "monthly",
  "category_type": "expense",
  "description": "January household expenses"
}
```

2. **Add Budget Items**
```http
POST /api/v1/budget-items
Authorization: Bearer {token}
Content-Type: application/json

{
  "budget_id": 1,
  "name": "Groceries",
  "planned_amount": 500.00,
  "category_type": "expense",
  "category": "groceries",
  "description": "Weekly grocery shopping"
}
```

3. **Track Spending**
```http
PATCH /api/v1/budget-items/1/spent-amount
Authorization: Bearer {token}
Content-Type: application/json

{
  "spent_amount": 125.50
}
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 📦 Deployment

1. Set up your production environment
2. Configure environment variables
3. Run migrations: `php artisan migrate`
4. Set up web server (Apache/Nginx) to serve Laravel
5. Configure SSL certificate
6. Set up queue workers if using background jobs

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🆘 Support

For support, email support@fintrack.com or create an issue in the repository.

---

**FinTrack Backend API** - Take control of your personal finances with comprehensive tracking and insights.
