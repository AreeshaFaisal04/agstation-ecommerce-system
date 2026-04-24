-- Table: Roles
CREATE TABLE Roles (
    role_id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: Users
CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    role_id INT NOT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME,
    FOREIGN KEY (role_id) REFERENCES Roles(role_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Table: WebsiteCustomers
CREATE TABLE WebsiteCustomers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    shipping_address TEXT,
    billing_address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table: Brands
CREATE TABLE Brands (
    brand_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    contact_email VARCHAR(255),
    phone_number VARCHAR(50),
    website_url VARCHAR(255),
    authorized_reseller BOOLEAN DEFAULT FALSE,
    contract_document_url TEXT,
    category VARCHAR(255),
    status ENUM('Active', 'Pending', 'Blacklisted') DEFAULT 'Pending',
    notes TEXT
);

-- Table: Distributors
CREATE TABLE Distributors (
    distributor_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255),
    email VARCHAR(255),
    phone_number VARCHAR(50),
    address TEXT,
    country VARCHAR(100),
    website_url VARCHAR(255),
    business_license_no VARCHAR(100),
    status ENUM('Active', 'Inactive', 'Blacklisted') DEFAULT 'Active',
    notes TEXT
);

-- Table: Products
CREATE TABLE Products (
    asin VARCHAR(20) PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    upc VARCHAR(100),
    brand_id INT,
    distributor_id INT,
    category VARCHAR(100),
    cost_price DECIMAL(10,2),
    retail_price DECIMAL(10,2),
    description TEXT,
    image_url TEXT,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (brand_id) REFERENCES Brands(brand_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (distributor_id) REFERENCES Distributors(distributor_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: WebsiteOrders
CREATE TABLE WebsiteOrders (
    order_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    order_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    total_amount DECIMAL(10,2),
    payment_status ENUM('Paid', 'Unpaid', 'Refunded') DEFAULT 'Unpaid',
    FOREIGN KEY (customer_id) REFERENCES WebsiteCustomers(customer_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- NEW: Table OrderItems to track products in each order
CREATE TABLE OrderItems (
    item_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id VARCHAR(20) NOT NULL,
    quantity INT NOT NULL,
    price_per_unit DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES WebsiteOrders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(asin)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: Payments
CREATE TABLE Payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    method ENUM('Card', 'PayPal') NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Paid', 'Failed', 'Refunded') DEFAULT 'Paid',
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES WebsiteOrders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: CustomerShipments
CREATE TABLE CustomerShipments (
    shipment_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    carrier VARCHAR(255),
    tracking_number VARCHAR(255),
    status ENUM('Shipped', 'In Transit', 'Delivered') DEFAULT 'Shipped',
    FOREIGN KEY (order_id) REFERENCES WebsiteOrders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: Returns
CREATE TABLE Returns (
    return_id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    product_id VARCHAR(20),
    return_date DATETIME,
    reason TEXT,
    status ENUM('Requested', 'Approved', 'Rejected', 'Refunded') DEFAULT 'Requested',
    resolution ENUM('Refund', 'Replacement', 'Store Credit'),
    refund_amount DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES WebsiteOrders(order_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(asin)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: CustomerInvoices
CREATE TABLE CustomerInvoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    issue_date DATETIME,
    due_date DATETIME,
    total_amount DECIMAL(10,2),
    status ENUM('Paid', 'Unpaid', 'Overdue') DEFAULT 'Unpaid',
    items TEXT,
    FOREIGN KEY (customer_id) REFERENCES WebsiteCustomers(customer_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: Warehouse
CREATE TABLE Warehouse (
    warehouse_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    type ENUM('Amazon FBA', 'Prep Center'),
    address TEXT,
    contact_person VARCHAR(255),
    phone VARCHAR(50)
);

-- Table: ShipmentsFromSuppliers
CREATE TABLE ShipmentsFromSuppliers (
    shipment_id INT PRIMARY KEY AUTO_INCREMENT,
    shipment_cost DECIMAL(10,2),
    distributor_id INT,
    brand_id INT,
    products_sent TEXT,
    shipment_date DATETIME,
    warehouse_id INT,
    tracking_number VARCHAR(255),
    status ENUM('Pending', 'Received', 'Delayed') DEFAULT 'Pending',
    FOREIGN KEY (distributor_id) REFERENCES Distributors(distributor_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES Brands(brand_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES Warehouse(warehouse_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: ThirdPartyServices
CREATE TABLE ThirdPartyServices (
    service_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    type ENUM('Repricing', 'Analytics', 'Freight', 'Automation', 'Prep Center'),
    account_id INT,
    warehouse_id INT,
    contact_email VARCHAR(255),
    api_key TEXT,
    base_url VARCHAR(255),
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    subscription_cost DECIMAL(10,2),
    billing_cycle ENUM('Monthly', 'Annually', 'One-Time'),
    integration_date DATETIME,
    last_synced DATETIME,
    notes TEXT,
    FOREIGN KEY (warehouse_id) REFERENCES Warehouse(warehouse_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- NEW: Table for outgoing payments (to fix circular reference)
CREATE TABLE SupplierPayments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT,
    amount DECIMAL(10,2) NOT NULL,
    method ENUM('Bank Transfer', 'Credit Card', 'PayPal', 'Check') NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Processed', 'Failed') DEFAULT 'Pending'
);

-- Table: SellerInvoices (fixed to remove circular reference)
CREATE TABLE SellerInvoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    payment_id INT,
    brand_id INT,
    distributor_id INT,
    service_id INT,
    issue_date DATETIME,
    due_date DATETIME,
    total_amount DECIMAL(10,2),
    status ENUM('Paid', 'Unpaid', 'Overdue') DEFAULT 'Unpaid',
    items TEXT,
    FOREIGN KEY (payment_id) REFERENCES SupplierPayments(payment_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (brand_id) REFERENCES Brands(brand_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (distributor_id) REFERENCES Distributors(distributor_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (service_id) REFERENCES ThirdPartyServices(service_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: Expenses (fixed to remove circular reference)
CREATE TABLE Expenses (
    expense_id INT PRIMARY KEY AUTO_INCREMENT,
    type VARCHAR(100),
    amount DECIMAL(10,2),
    paid_to VARCHAR(255),
    date DATE,
    notes TEXT,
    service_id INT,
    invoice_id INT,
    FOREIGN KEY (service_id) REFERENCES ThirdPartyServices(service_id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY (invoice_id) REFERENCES SellerInvoices(invoice_id)
        ON DELETE SET NULL ON UPDATE CASCADE
);

-- Table: Inventory
CREATE TABLE Inventory (
    inventory_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id VARCHAR(20) NOT NULL,
    warehouse_id INT NOT NULL,
    quantity INT DEFAULT 0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES Products(asin)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (warehouse_id) REFERENCES Warehouse(warehouse_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table: ProductReviews
CREATE TABLE ProductReviews (
    review_id INT PRIMARY KEY AUTO_INCREMENT,
    product_id VARCHAR(20) NOT NULL,
    source ENUM('Amazon', 'Website'),
    rating INT CHECK (rating BETWEEN 1 AND 5),
    review_text TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES Products(asin)
        ON DELETE CASCADE ON UPDATE CASCADE
);
