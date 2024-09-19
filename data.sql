CREATE TABLE products (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(100) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);
CREATE TABLE clients (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15),
    address TEXT
);
CREATE TABLE sales (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    product_id INT(11) NOT NULL,
    client_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    sale_date DATE NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (client_id) REFERENCES clients(id)
);
CREATE TABLE purchases (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    supplier_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    purchase_date DATE NOT NULL,
    total_cost DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
CREATE TABLE invoices (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    client_id INT(11) NOT NULL,
    invoice_date DATE NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id)
);

CREATE TABLE invoice_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    invoice_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
CREATE TABLE ledger (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    description VARCHAR(255) NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
