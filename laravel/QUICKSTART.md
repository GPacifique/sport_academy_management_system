# Quick Start Guide - Sport Academy MS

This guide will help you get Sport Academy MS up and running quickly.

## Prerequisites

Before you begin, ensure you have:

- ✅ PHP 8.4 or higher
- ✅ Composer
- ✅ MySQL 5.7+ or MariaDB
- ✅ Node.js 18+ and NPM
- ✅ Git
- ✅ XAMPP/LAMPP or similar PHP server

## Installation Steps

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/sport-academy-ms.git
cd sport-academy-ms/laravel
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Configure Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit your `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sport_academy_db
DB_USERNAME=root
DB_PASSWORD=your_password_here
```

Create the database:

```bash
mysql -u root -p
CREATE DATABASE sport_academy_db;
EXIT;
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Seed Database (Optional)

```bash
php artisan db:seed
```

This will create:
- Default admin user
- Sample branches
- Roles and permissions

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Build Frontend Assets

```bash
# For production
npm run build

# For development (with hot reload)
npm run dev
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit: `http://localhost:8000`

## Default Login Credentials

After seeding:

**Admin:**
- Email: `admin@sportacademyms.com`
- Password: `password`

**Coach:**
- Email: `coach@sportacademyms.com`
- Password: `password`

⚠️ **Change these credentials immediately in production!**

## Common Issues & Solutions

### Issue: "Class not found"

```bash
composer dump-autoload
php artisan optimize:clear
```

### Issue: Permission denied on storage

```bash
chmod -R 775 storage bootstrap/cache
```

### Issue: Database connection error

- Check your `.env` database credentials
- Ensure MySQL/MariaDB is running
- Verify the database exists

### Issue: NPM build errors

```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

## Production Deployment

For production, remember to:

1. Set `APP_ENV=production` in `.env`
2. Set `APP_DEBUG=false`
3. Use a strong `APP_KEY`
4. Configure proper database credentials
5. Set up email service (SMTP)
6. Run optimizations:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## Next Steps

- 📖 Read the full [README.md](README.md)
- 🤝 Check [CONTRIBUTING.md](CONTRIBUTING.md) to contribute
- 📝 See [CHANGELOG.md](CHANGELOG.md) for version history
- 📧 Contact: info@sportacademyms.com

---

**Developed by Gashumba** | 📞 0786 163 963
