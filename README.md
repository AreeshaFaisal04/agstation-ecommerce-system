# AGStation - Inventory & E-commerce Management System

AGStation is a PHP and MySQL-based web application that provides an inventory management system along with an e-commerce frontend and an admin dashboard.

The system allows management of products, orders, users, inventory, and related business operations through a structured admin panel, while also providing a user-facing interface for browsing and purchasing products.

---

## Features

### 🛒 Frontend (User Side)

* Homepage (`index.php`)
* Product listing and product details
* Add to cart and cart management
* Checkout system
* User registration and login
* Order details view
* Return request functionality
* Static pages:

  * About
  * Contact
  * Privacy Policy
  * Terms of Service

---

### User Panel

* User dashboard
* View orders
* Manage profile
* Submit and manage reviews

---

### Authentication System

* User registration (`register.php`)
* User login (`login.php`)
* Logout functionality

---

### Admin Panel

#### Dashboard

* Admin dashboard overview

#### Management Modules (CRUD)

* Brands
* Products
* Customers
* Users
* Roles
* Inventory
* Warehouse

#### Orders & Sales

* Orders management (view, update status, delete)
* Customer invoices
* Seller invoices

#### Business Operations

* Distributors management
* Shipments
* Shipments from suppliers
* Returns handling
* Payments tracking
* Expense management
* Third-party services

#### Content & Feedback

* Reviews management

---

## 🧱 Project Structure

agstation/
│
├── admin/                    # Admin dashboard modules
├── frontend/                 # User-facing pages
├── user/                     # User dashboard
├── auth/                     # Authentication system
├── config/                   # Database configuration
├── database/                 # SQL schema
├── includes/                 # Shared components (header, footer, etc.)
├── assets/                   # CSS, JS, images
├── uploads/                  # Uploaded product images
├── Script-32.sql             # Additional database script
└── README.md.txt             # Existing readme file

---

## 🛠️ Technologies Used

* PHP
* MySQL
* HTML
* CSS
* JavaScript

---

## ⚙️ Setup Instructions

1. Place the project folder in your local server directory:

   * XAMPP → `htdocs`
   * WAMP → `www`

2. Create a database in phpMyAdmin

3. Import one of the SQL files:

   * `database/agstation_schema.sql`
   * OR `Script-32.sql`

4. Configure database connection:

   * Open `config/db.php`
   * Update database credentials if required

5. Run the project in browser:

   Frontend:
   http://localhost/agstation/frontend/

   Admin Panel:
   http://localhost/agstation/admin/

---

##  Notes

* The project follows a modular structure with separate folders for admin, user, and frontend
* All CRUD operations are implemented through individual module folders
* Uploaded product images are stored in the `uploads/product_images` directory
* Shared UI components are located in the `includes` folder
