# 🌍 Kenyan Inventory System

A sleek and secure web-based inventory management system built as a backend course project. Designed for managing products with Kenyan Shilling (KES) pricing, it features staff authentication, product CRUD operations, and image uploads, all wrapped in a modern, responsive UI.

---

## 📖 Project Overview

The **Kenyan Inventory System** is a PHP-based application for managing product inventory. It allows staff to register, log in, and perform operations like adding, editing, deleting, and viewing products. The system uses MySQL for data storage and Tailwind CSS for a polished, Kenyan-themed interface.

### ✨ Key Features
- 🔐 **Staff Authentication**: Secure login and registration with "Remember Me" functionality via cookies.
- 📦 **Product Management**: Create, read, update, and delete (

CRUD) products with details like name, description, price, quantity, and images.
- 🖼️ **Image Upload**: Upload product images (JPG, PNG, GIF, up to 5MB).
- 🎨 **Responsive Design**: Clean, modern UI with Kenyan-inspired colors using Tailwind CSS.
- 🗄️ **Database**: MySQL database named `product_management` for storing staff and product data.

---

## 🛠️ Prerequisites

To run this project, ensure you have:
- 🐘 **PHP** (7.4 or higher)
- 🗃️ **MySQL** (5.7 or higher)
- 🌐 **Web Server** (Apache or Nginx)
- 📦 **Composer** (optional, for dependency management)
- 🌍 **Web Browser** (Chrome, Firefox, etc.)

---

## 🚀 Setup Instructions

### 1. Clone the Project
Clone or download the project to your local machine or server.

```bash
git clone https://github.com/FaithMutua-code/Farm-Product-Management

```

### 2. Configure the Web Server
- Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP or `www` for WAMP).
- Ensure the `uploads/` directory is writable (set permissions to `0755` or `0777` on Linux).

### 3. Set Up the Database
1. Create a MySQL database named `product_management`.
2. Execute the following SQL to create the required tables:

#### 📋 Staff Table
```sql
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### 📋 Products Table
```sql
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### 4. Configure Database Connection
- Open `config.php` and verify the database credentials:
  ```php
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'product_management');
  ```
- Update `DB_USER` and `DB_PASS` if your MySQL setup uses different credentials.

### 5. Run the Application
- Start your web server and MySQL service (e.g., using XAMPP, WAMP, or a LAMP stack).
- Access the application at `http://localhost/<project-folder>/index.php`.
- Register a new staff account or log in to access the dashboard.

---

## 📂 Project Structure

- **`index.php`**: Landing page with login and registration links.
- **`login.php`**: Staff login with "Remember Me" functionality.
- **`register.php`**: Staff registration page.
- **`dashboard.php`**: Displays product list with options to add, edit, or delete.
- **`add_product.php`**: Form to add new products with image upload.
- **`edit_product.php`**: Form to edit existing products.
- **`delete_product.php`**: Handles product deletion.
- **`config.php`**: Database connection and session setup.
- **`logout.php`**: Clears session and cookies, redirects to login.
- **`uploads/`**: Directory for storing product images.

---

## 🗃️ Database Components

### Database Name: `product_management`

#### 1. Staff Table
- **Purpose**: Stores staff account details for authentication.
- **Columns**:
  - `id`: Auto-incrementing primary key (int).
  - `name`: Staff full name (varchar, 100).
  - `email`: Unique email for login (varchar, 100).
  - `password`: Hashed password (varchar, 255).
  - `created_at`: Account creation timestamp.
- **Constraints**:
  - Primary Key: `id`
  - Unique Key: `email`

#### 2. Products Table
- **Purpose**: Stores product details for inventory management.
- **Columns**:
  - `id`: Auto-incrementing primary key (int).
  - `name`: Product name (varchar, 100).
  - `description`: Optional product description (text).
  - `price`: Product price in KES (decimal, 10,2).
  - `quantity`: Stock quantity (int).
  - `staff_id`: Foreign key linking to staff (int).
  - `image_path`: Path to uploaded image (varchar, 255, nullable).
  - `created_at`: Product creation timestamp.
  - `updated_at`: Last update timestamp.
- **Constraints**:
  - Primary Key: `id`
  - Foreign Key: `staff_id` references `staff(id)`

---

## 🎮 Usage

1. **Register**: Navigate to `register.php` to create a staff account.
2. **Login**: Use `login.php` to sign in, with an option to save credentials for 30 days.
3. **Dashboard**: View all products and manage them (add, edit, delete).
4. **Add Product**: Use `add_product.php` to create a new product with optional image.
5. **Edit Product**: Update product details or replace images via `edit_product.php`.
6. **Delete Product**: Remove products with confirmation prompt via `delete_product.php`.

---

## 📝 Notes
- Ensure the `uploads/` directory is created and writable for image uploads.
- Passwords are securely hashed using PHP's `password_hash()`.
- The UI uses Tailwind CSS (via CDN) and Font Awesome for icons.
- Prices are displayed in Kenyan Shillings (KES) with proper formatting.

---

## 🛡️ Troubleshooting

- **Database Connection Error**: Check `config.php` credentials and ensure MySQL is running.
- **Image Upload Failure**: Verify `uploads/` directory permissions (create it if missing).
- **Session Issues**: Ensure browser cookies are enabled and the session save path is writable.
- **404 Errors**: Confirm all PHP files are in the correct directory and the web server is configured.

---

## 🌟 Future Improvements
- 🔑 Add password recovery for the "Forgot Password" link.
- 🔍 Implement product search and category filtering.
- 👥 Add user roles (admin vs. staff) for access control.
- 🖼️ Enhance image handling (e.g., file size validation, image resizing).

---

## 🎉 Happy Managing!
This project showcases a robust backend system for inventory management with a touch of Kenyan flair. For any issues or contributions, feel free to explore the code and make it your own!
