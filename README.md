AGSTATION - User and Admin Management System
============================================

Overview:
---------
AGSTATION is a web-based platform designed to manage agricultural products, users, orders, reviews, and other functionalities for both customers and administrators. The system includes role-based access control, allowing administrators to manage the platform and customers to interact with the system seamlessly.

Features:
---------
1. **User Roles**:
   - **Admin**: Manage users, products, orders, warehouses, shipments, and third-party services.
   - **Customer**: View and manage their profile, orders, and reviews.

2. **Key Functionalities**:
   - User authentication (login, logout, registration).
   - Role-based access control for secure access.
   - Product management and customer reviews.
   - Order management for customers.
   - Admin dashboard for managing users, warehouses, and shipments.

3. **Responsive Design**:
   - Fully responsive layout using Bootstrap for a seamless experience on all devices.

4. **Security**:
   - Secure database interactions using prepared statements (PDO).
   - XSS protection using `htmlspecialchars()` for dynamic content.
   - Role-based access control to restrict unauthorized access.

Setup Instructions:
-------------------
1. **Prerequisites**:
   - PHP 7.4 or higher.
   - MySQL database.
   - A web server (e.g., Apache or Nginx).
   - Composer (optional, for dependency management).

2. **Installation**:
   - Clone or download the project files into your web server's root directory.
   - Import the `database.sql` file into your MySQL database to set up the required tables.
   - Update the database configuration in `config/db.php` with your database credentials.

3. **File Structure**:
   - `frontend/`: Contains user-facing pages (e.g., `index.php`, `products.php`, `orders.php`, `profile.php`).
   - `admin/`: Contains admin-facing pages (e.g., `dashboard.php`, `manage_users.php`, `warehouse.php`).
   - `includes/`: Contains reusable components (e.g., `header.php`, `footer.php`, `session.php`, `sidebar.php`).
   - `assets/`: Contains CSS, JavaScript, and image files.
   - `config/`: Contains configuration files (e.g., `db.php` for database connection).

4. **Accessing the System**:
   - **Admin Login**: Use the admin credentials set up in the database.
   - **Customer Registration**: Customers can register via the `register_customer.php` page.

5. **Running the Project**:
   - Start your web server and navigate to the project URL (e.g., `http://localhost/agstation`).

Database Structure:
-------------------
1. **`Users` Table**:
   - Stores user information (e.g., `user_id`, `name`, `email`, `password_hash`, `role_id`).

2. **`WebsiteCustomers` Table**:
   - Stores customer-specific information (e.g., `customer_id`, `user_id`, `email`).

3. **`WebsiteOrders` Table**:
   - Stores order details (e.g., `order_id`, `customer_id`, `total_amount`, `order_date`, `status`).

4. **`ProductReviews` Table**:
   - Stores product reviews (e.g., `review_id`, `product_id`, `customer_id`, `rating`, `review_text`, `created_at`).

5. **`Products` Table**:
   - Stores product details (e.g., `asin`, `name`, `description`, `price`, `image_url`).

6. **Additional Tables**:
   - Tables for admin functionalities like `Warehouse`, `Shipments`, and `ThirdPartyServices`.

Key Pages:
----------
1. **Frontend Pages**:
   - `index.php`: Homepage for customers.
   - `products.php`: Displays available products.
   - `orders.php`: Displays customer orders.
   - `profile.php`: Allows customers to update their profile.
   - `reviews.php`: Displays customer reviews.

2. **Admin Pages**:
   - `dashboard.php`: Admin dashboard for managing the platform.
   - `manage_users.php`: Manage user accounts.
   - `warehouse.php`: Manage warehouse details.
   - `shipments.php`: Manage shipment details.

3. **Authentication Pages**:
   - `login.php`: Login page for users.
   - `register_customer.php`: Registration page for customers.
   - `logout.php`: Logout functionality.

