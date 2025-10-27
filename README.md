# 🏆 Sport Academy MS - Management System

A comprehensive Sport Academy Management System built with Laravel 11, designed to streamline student enrollment, attendance tracking, payments, and academy operations.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat&logo=php&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green.svg)

## 📋 Table of Contents

- [Features](#features)
- [Screenshots](#screenshots)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Technologies](#technologies)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)

## ✨ Features

### Student Management
- ✅ Student registration with comprehensive profile information
- ✅ Jersey number and jersey name assignment
- ✅ Photo upload and management
- ✅ Parent/guardian information tracking
- ✅ Group and branch assignment
- ✅ Student status management (active/inactive)

### Attendance System
- ✅ Daily attendance tracking
- ✅ Attendance reports and analytics
- ✅ Historical attendance records
- ✅ Parent access to attendance data

### Payment & Subscription Management
- ✅ Subscription plan management
- ✅ Payment tracking and history
- ✅ Multiple payment methods support
- ✅ Payment receipts and invoices

### User Roles & Access Control
- 👨‍💼 **Admin** - Full system access
- 👨‍🏫 **Coach** - Student and attendance management
- 💼 **Accountant** - Financial operations and reports
- 👨‍👩‍👧‍👦 **Parent** - View child's information and progress

### Additional Features
- 📊 Dashboard with analytics and statistics
- 🏢 Multi-branch support
- 👥 Group/team management
- 📱 Responsive design for mobile and desktop
- 🔒 Secure authentication and authorization
- 📧 Email notifications
- 🎨 Modern UI with Tailwind CSS

## 🖼️ Screenshots

> Add screenshots of your application here

## 📋 Requirements

- PHP >= 8.4
- Composer
- MySQL >= 5.7 or MariaDB
- Node.js >= 18.x
- NPM or Yarn
- XAMPP/LAMPP or any PHP development environment

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/sport-academy-ms.git
cd sport-academy-ms/laravel
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
# or
yarn install
```

### 4. Environment Setup

```bash
# Copy the example env file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sport_academy_db
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Seed Database (Optional)

```bash
php artisan db:seed
```

### 8. Build Assets

```bash
npm run build
# For development with hot reload
npm run dev
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## ⚙️ Configuration

### Storage Link

Create symbolic link for file uploads:

```bash
php artisan storage:link
```

### Cache Configuration

```bash
# Clear all caches
php artisan optimize:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📖 Usage

### Default Login Credentials

After seeding the database, you can use these credentials:

**Admin:**
- Email: admin@sportacademyms.com
- Password: password

**Coach:**
- Email: coach@sportacademyms.com
- Password: password

**Accountant:**
- Email: accountant@sportacademyms.com
- Password: password

**Parent:**
- Email: parent@sportacademyms.com
- Password: password

> ⚠️ **Important:** Change these credentials in production!

## 🗄️ Database Schema

### Main Tables

- **users** - System users (admin, coaches, accountants, parents)
- **students** - Student profiles and information
- **branches** - Academy branches/locations
- **groups** - Training groups/teams
- **attendances** - Attendance records
- **subscriptions** - Subscription plans
- **payments** - Payment transactions

### Key Relationships

- Student → Parent (Many-to-One)
- Student → Branch (Many-to-One)
- Student → Group (Many-to-One)
- Student → Attendances (One-to-Many)
- Student → Subscriptions (Many-to-Many)
- Student → Payments (One-to-Many)

## 🛠️ Technologies

### Backend
- **Laravel 11** - PHP Framework
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Breeze** - Authentication Scaffolding

### Frontend
- **Blade Templates** - Server-side rendering
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Frontend build tool

### Additional Tools
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Composer** - Dependency management
- **NPM** - Package management

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Write meaningful commit messages
- Add tests for new features
- Update documentation as needed

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👨‍💻 Developer

**Gashumba**

📞 Contact: [0786 163 963](tel:0786163963)  
📧 Email: info@sportacademyms.com

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Icons from [Heroicons](https://heroicons.com)

---

<p align="center">Made with ❤️ by Gashumba | Sport Academy MS v1.0.0</p>

# sport_academy_management_system
