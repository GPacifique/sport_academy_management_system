# 🚀 Deploy to Namecheap Server Instructions

## ✅ MySQL Error Fixed

The `Specified key was too long` error has been fixed by setting the default string length to 191 in `AppServiceProvider.php`.

## 📋 Steps to Deploy on Namecheap Server

### 1. Pull Latest Changes from GitHub

Since you're on the Namecheap server at `sportacademyms` directory:

```bash
cd ~/sportacademyms
git pull origin main
```

If you get authentication errors, follow Step 2 below.

### 2. Setup GitHub Authentication on Namecheap Server

#### Option A: Using SSH Key (Recommended)

```bash
# Generate SSH key on Namecheap server
ssh-keygen -t ed25519 -C "info@sportacademyms.com" -f ~/.ssh/id_ed25519_github -N ""

# Display the public key
cat ~/.ssh/id_ed25519_github.pub
```

**Copy the output** and add it to GitHub:
1. Go to https://github.com/settings/keys
2. Click "New SSH key"
3. Title: `Namecheap Server - Sport Academy MS`
4. Paste the key
5. Click "Add SSH key"

Then configure Git to use this key:

```bash
# Add to SSH config
cat >> ~/.ssh/config << 'EOF'
Host github.com
    HostName github.com
    User git
    IdentityFile ~/.ssh/id_ed25519_github
    IdentitiesOnly yes
EOF

# Set correct permissions
chmod 600 ~/.ssh/config

# Test connection
ssh -T git@github.com

# Change remote to SSH
cd ~/sportacademyms
git remote set-url origin git@github.com:GPacifique/sport_academy_management_system.git
```

#### Option B: Using Personal Access Token

1. Create token at: https://github.com/settings/tokens
2. Generate new token (classic)
3. Name: `Namecheap Server`
4. Scopes: Select `repo`
5. Generate and copy the token

Then update the remote URL:

```bash
cd ~/sportacademyms
git remote set-url origin https://GPacifique:YOUR_TOKEN_HERE@github.com/GPacifique/sport_academy_management_system.git
```

### 3. Pull Latest Code

```bash
cd ~/sportacademyms
git pull origin main
```

### 4. Run Database Migrations

The MySQL error has been fixed. Now run:

```bash
cd ~/sportacademyms
php artisan migrate
```

When prompted "Are you sure you want to run this command?", select **Yes**.

### 5. Clear Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 6. Set Correct Permissions

```bash
chmod -R 755 ~/sportacademyms/storage
chmod -R 755 ~/sportacademyms/bootstrap/cache
```

### 7. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🔧 Environment Configuration

Make sure your `.env` file on Namecheap has the correct database settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

## 📝 Database Configuration Notes

The fix applied (`Schema::defaultStringLength(191)`) ensures compatibility with:
- MySQL 5.6+
- MariaDB 10.1+
- Older MySQL versions with utf8mb4

This sets the maximum string length for indexed columns to 191 characters instead of 255, which prevents the "key too long" error.

## 🆘 Troubleshooting

### If migrations still fail:

1. **Check MySQL version:**
   ```bash
   mysql --version
   ```

2. **Check database charset:**
   ```sql
   SHOW VARIABLES LIKE 'character_set%';
   ```

3. **Drop and recreate database** (⚠️ Only if fresh install):
   ```bash
   mysql -u your_user -p
   ```
   ```sql
   DROP DATABASE IF EXISTS your_database_name;
   CREATE DATABASE your_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   EXIT;
   ```

4. **Run migrations again:**
   ```bash
   php artisan migrate:fresh
   ```

### If you see "Access denied for user":

Check your database credentials in `.env` match what's in cPanel → MySQL Databases.

## 📞 Contact

**Developer:** Gashumba  
**Phone:** 0786 163 963  
**Email:** info@sportacademyms.com

---

**Last Updated:** October 27, 2025  
**Current Version:** v1.0.0 with comprehensive student fields
