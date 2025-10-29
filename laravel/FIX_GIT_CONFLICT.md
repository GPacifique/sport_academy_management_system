# 🔧 Fix Git Merge Conflict on Namecheap Server

## Problem
Your local changes conflict with the updates from GitHub.

## Quick Fix - Run These Commands:

```bash
cd ~/sportacademyms

# Option 1: Stash local changes and pull (RECOMMENDED)
git stash
git pull origin main
git stash pop

# If stash pop causes conflicts, just use the new version:
git checkout --theirs resources/views/layouts/app.blade.php
git add resources/views/layouts/app.blade.php
git stash drop
```

## OR Option 2: Backup and Force Pull

```bash
cd ~/sportacademyms

# Backup your local changes
cp resources/views/layouts/app.blade.php resources/views/layouts/app.blade.php.backup

# Discard local changes and pull
git reset --hard HEAD
git pull origin main

# Compare if needed
diff resources/views/layouts/app.blade.php.backup resources/views/layouts/app.blade.php
```

## After Git Pull Succeeds:

```bash
# Now run the deploy script
chmod +x deploy.sh
./deploy.sh
```

## OR Manual Deployment (if deploy.sh doesn't exist yet):

```bash
cd ~/sportacademyms

# 1. Pull code
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Set permissions
chmod -R 755 storage bootstrap/cache
chmod 644 public/css/inline-styles.css

# 4. Run migrations
php artisan migrate --force

# 5. Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 6. Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Storage link
php artisan storage:link
```

## Verify CSS File Exists:

```bash
ls -la ~/sportacademyms/public/css/inline-styles.css
```

Should show: `-rw-r--r-- ... inline-styles.css`

If file doesn't exist after pull, something went wrong. Check:

```bash
git status
git log --oneline -5
```

## Test the Site:

Visit: https://www.sportacademyms.app.avanciafitness.com

Should now show styled pages!

## If Still No Styles:

1. **Hard refresh browser**: Ctrl+Shift+R (Windows/Linux) or Cmd+Shift+R (Mac)

2. **Check file loaded**:
   - Open browser DevTools (F12)
   - Go to Network tab
   - Refresh page
   - Look for `inline-styles.css` - should be 200 OK

3. **Check .htaccess** in public directory exists

4. **Verify document root** points to `public` folder

---

**Need Help?**  
📞 Gashumba | 0786 163 963 | info@sportacademyms.com
