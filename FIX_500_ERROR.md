# 🔧 Fix 500 Internal Server Error on Namecheap

## Steps to Deploy and Fix the 500 Error

### 1. Pull Latest Code from GitHub

```bash
cd ~/sportacademyms
git pull origin main
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
npm install
npm run build
```

### 3. Set Correct Permissions

This is CRITICAL on shared hosting:

```bash
# Set directory permissions
chmod -R 755 ~/sportacademyms/storage
chmod -R 755 ~/sportacademyms/bootstrap/cache

# Set file permissions
find ~/sportacademyms/storage -type f -exec chmod 644 {} \;
find ~/sportacademyms/bootstrap/cache -type f -exec chmod 644 {} \;
```

### 4. Configure .env File

Make sure your `.env` file on Namecheap has:

```env
APP_NAME="Sport Academy MS"
APP_ENV=production
APP_KEY=base64:VDbCDE879cfiXCtVsyJkSRMu9fF7/iy4YeaC+iNsJ/k=
APP_DEBUG=false
APP_URL=https://www.sportacademyms.app.avanciafitness.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=avanvyov_sportacademy
DB_USERNAME=avanvyov_sportuser
DB_PASSWORD=your_database_password

SESSION_DRIVER=database
SESSION_LIFETIME=120

CACHE_STORE=database
QUEUE_CONNECTION=database

LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Important:** 
- Set `APP_DEBUG=false` in production
- Set `APP_ENV=production`
- Update database credentials to match your cPanel MySQL database

### 5. Run Migrations

```bash
cd ~/sportacademyms
php artisan migrate --force
```

### 6. Clear and Cache Configuration

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild caches for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Create Storage Link

```bash
php artisan storage:link
```

### 8. Set Up .htaccess in public_html

Your document root should point to `~/sportacademyms/public`. 

If using subdomain, create `.htaccess` in your document root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

OR create a symlink:

```bash
# If your document root is ~/public_html/sportacademyms.app.avanciafitness.com
rm -rf ~/public_html/sportacademyms.app.avanciafitness.com
ln -s ~/sportacademyms/public ~/public_html/sportacademyms.app.avanciafitness.com
```

### 9. Check Laravel's .htaccess

Make sure `~/sportacademyms/public/.htaccess` exists with:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 10. Check Error Logs

To find the actual error:

```bash
# Check Laravel logs
tail -50 ~/sportacademyms/storage/logs/laravel.log

# Check Apache/cPanel error logs
tail -50 ~/public_html/error_log
```

## Common 500 Error Causes and Solutions

### A. Missing APP_KEY
```bash
php artisan key:generate
```

### B. Wrong Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R $USER:$USER storage bootstrap/cache
```

### C. Database Connection Failed
- Check database credentials in `.env`
- Make sure database exists in cPanel → MySQL Databases
- Verify user has ALL PRIVILEGES on the database

### D. PHP Version Mismatch
Laravel 11 requires PHP 8.2+

Check PHP version:
```bash
php -v
```

If version is too old, change it in cPanel:
1. Go to cPanel → MultiPHP Manager
2. Select PHP 8.2 or 8.3
3. Apply

### E. Missing PHP Extensions
Required extensions:
- BCMath
- Ctype
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

Check in cPanel → MultiPHP INI Editor or contact support.

### F. Composer autoload not optimized
```bash
composer dump-autoload --optimize
```

### G. Route cache issue
```bash
php artisan route:clear
php artisan route:cache
```

## Quick Diagnostic Commands

Run these on your Namecheap server:

```bash
# 1. Check PHP version
php -v

# 2. Check if Laravel can run
php artisan --version

# 3. Check database connection
php artisan tinker
# Then type: DB::connection()->getPdo();
# Press Ctrl+D to exit

# 4. Check permissions
ls -la storage
ls -la bootstrap/cache

# 5. Check if .env exists
ls -la .env
```

## Complete Deployment Script

Save this as `deploy.sh` and run it:

```bash
#!/bin/bash
cd ~/sportacademyms

echo "🔄 Pulling latest code..."
git pull origin main

echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm install
npm run build

echo "🔧 Setting permissions..."
chmod -R 755 storage bootstrap/cache
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;

echo "🗄️ Running migrations..."
php artisan migrate --force

echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

echo "⚡ Optimizing..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔗 Creating storage link..."
php artisan storage:link

echo "✅ Deployment complete!"
```

Make it executable and run:
```bash
chmod +x deploy.sh
./deploy.sh
```

## Still Getting 500 Error?

1. **Enable debug mode temporarily** (in `.env`):
   ```env
   APP_DEBUG=true
   ```
   Reload the page to see the actual error, then set it back to `false`.

2. **Check the error log**:
   ```bash
   tail -100 ~/sportacademyms/storage/logs/laravel.log
   ```

3. **Contact me** with the error message:
   - **Developer:** Gashumba
   - **Phone:** 0786 163 963
   - **Email:** info@sportacademyms.com

## Security Reminder

⚠️ **Always set** `APP_DEBUG=false` in production after fixing the issue!

---

**Last Updated:** October 27, 2025
