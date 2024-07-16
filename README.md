# Invoice Management System

## Screenshots

Here are some screenshots from the Invoice Management System:

![Screenshot 1](https://github.com/haweil/Invoice-Management-System/blob/main/images/1.jpg)
![Screenshot 2](https://github.com/haweil/Invoice-Management-System/blob/main/images/2.jpg)
![Screenshot 3](https://github.com/haweil/Invoice-Management-System/blob/main/images/3.jpg)
![Screenshot 4](https://github.com/haweil/Invoice-Management-System/blob/main/images/4.jpg)
![Screenshot 5](https://github.com/haweil/Invoice-Management-System/blob/main/images/5.jpg)
![Screenshot 6](https://github.com/haweil/Invoice-Management-System/blob/main/images/6.jpg)
![Screenshot 7](https://github.com/haweil/Invoice-Management-System/blob/main/images/7.jpg)
![Screenshot 8](https://github.com/haweil/Invoice-Management-System/blob/main/images/8.jpg)
![Screenshot 9](https://github.com/haweil/Invoice-Management-System/blob/main/images/9.jpg)
![Screenshot 10](https://github.com/haweil/Invoice-Management-System/blob/main/images/10.jpg)
![Screenshot 11](https://github.com/haweil/Invoice-Management-System/blob/main/images/11.jpg)

## Description

An invoice management system designed to streamline and simplify the invoicing process for businesses. This system allows businesses to send professional invoices, manage payments, and handle accounting tasks efficiently.

## Features

Designed and developed a comprehensive invoice management system using Laravel. Implemented features for invoice creation, editing, and deletion, with custom views and routes to streamline management tasks. Utilized Laravel's database migrations and seeders for efficient database creation and population. Enhanced the system with professional invoice templates, print/export options (Excel/PDF), dynamic payment status tracking, and a robust messaging system. Integrated Spatie Laravel-permission for role management, middleware for security, and a dynamic dashboard with detailed reporting and file upload capabilities. Added custom branding and real-time notifications, ensuring a polished user experience.

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/haweil/invoice-management-system.git
2. Go into the project root directory
    ```sh
    cd  Invoices_management
    ```
1. Copy .env.example file to .env file
    ```sh
    cp .env.example .env
    ```
1. Create database `invoice-management-system` (you can change database name)

1. Install PHP dependencies 
    ```sh
    composer install
    ```

1. Generate key 
    ```sh
    php artisan key:generate
    ```


1. Run migration
    ```
    php artisan migrate
    ```
    
1. Run seeder
    ```
    php artisan db:seed
    ```
      this command will create  user (owner):
     > email: haweil@admin.com , password: 12345678

1. Run server 

   
    ```sh
    php artisan serve
    ```  
Visit localhost:8000 in your favorite browser.
