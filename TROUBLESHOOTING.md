# Troubleshooting: Custom Design Not Displaying Correctly on Server

## Quick Diagnosis Checklist

If the custom design looks fine locally (http://localhost:8000) but not on the server, follow these steps:

### 1. **Clear Browser Cache (Most Common Issue)**
   - **Chrome/Edge:** `Ctrl+Shift+Delete` (or `Cmd+Shift+Delete` on Mac)
   - **Firefox:** `Ctrl+Shift+Delete` (or `Cmd+Shift+Delete` on Mac)
   - **Safari:** Clear all history and website data
   - **Or:** Use hard refresh:
     - Chrome/Firefox/Edge: `Ctrl+F5` (or `Cmd+Shift+R` on Mac)
     - Safari: `Cmd+Option+R`

### 2. **Check Asset URLs Match**
   - Your `.env` file should have correct `APP_URL`
   - Current setting: `APP_URL=http://localhost`
   - On production server, update `.env`:
     ```
     APP_URL=https://yourdomain.com  (or http://yourdomain.com)
     ```

### 3. **Clear Laravel Cache on Server**
   ```bash
   php artisan cache:clear
   php artisan view:clear
   php artisan config:clear
   php artisan optimize:clear
   ```

### 4. **Verify Files Exist on Server**
   ```bash
   # SSH into your server and run:
   ls -la /path/to/public/css/custom-design.css
   ls -la /path/to/public/js/custom-interactions.js
   ```

### 5. **Check File Permissions**
   ```bash
   # Ensure public files are readable
   chmod 644 public/css/custom-design.css
   chmod 644 public/js/custom-interactions.js
   chmod 755 public/css
   chmod 755 public/js
   ```

### 6. **Check Web Server Configuration**
   
   **For Apache (.htaccess):**
   - Ensure `.htaccess` is present in `/public` directory
   - Ensure `mod_rewrite` is enabled: `a2enmod rewrite`
   - Restart Apache: `systemctl restart apache2`

   **For Nginx:**
   - Verify `public` folder is correctly pointed in nginx config
   - Verify symlink: `ls -la public` should show public folder

### 7. **Check if Files are Served (Local Test)**
   ```bash
   # Test CSS file
   curl -I http://localhost:8000/css/custom-design.css
   # Should return: HTTP/1.1 200 OK

   # Test JS file
   curl -I http://localhost:8000/js/custom-interactions.js
   # Should return: HTTP/1.1 200 OK
   ```

### 8. **Check Browser Console for Errors**
   1. Open page on server
   2. Press `F12` (Developer Tools)
   3. Go to "Console" tab
   4. Look for:
      - Red error messages about failed CSS/JS loading
      - 404 errors for asset URLs
      - CORS errors (if on different domain)

### 9. **Check Network Tab**
   1. Open Developer Tools (`F12`)
   2. Go to "Network" tab
   3. Reload page
   4. Look for `custom-design.css` and `custom-interactions.js`
   5. Click each and check:
      - Status: Should be `200`
      - Response: Should show CSS/JS content (not error page)

### 10. **If Using CDN or Reverse Proxy**
   - Make sure `APP_URL` in `.env` matches your actual domain
   - Clear CDN cache if using one
   - Bypass CDN temporarily to test: directly access IP or add `no-cache` header

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Page shows old Tailwind styling | Browser cache not cleared | Hard refresh (Ctrl+F5) + Clear cache |
| Assets return 404 errors | Files not on server | Run `git pull` and verify files exist |
| Assets load but styling not applied | Wrong `APP_URL` in `.env` | Update `.env` with correct domain |
| CSS/JS loads but scripts don't run | JavaScript errors in console | Check browser console for errors |
| Some sections styled, others not | Partial file upload | Run `git status` to verify all files committed |
| White page or no content | View cache corrupted | Run `php artisan view:clear` |

## File Structure Verification

Ensure all files are in correct locations:

```
laravel/
├── public/
│   ├── css/
│   │   └── custom-design.css       ✓ (21.8 KB)
│   └── js/
│       └── custom-interactions.js  ✓ (13.8 KB)
├── resources/
│   └── views/
│       └── welcome.blade.php       ✓ (Updated with custom classes)
└── .env                            ✓ (Check APP_URL)
```

## Testing Commands

```bash
# Test if CSS loads
curl -s http://localhost:8000/css/custom-design.css | wc -l
# Should return a number > 100

# Test if JS loads
curl -s http://localhost:8000/js/custom-interactions.js | wc -l
# Should return a number > 100

# Test if HTML has correct asset links
curl -s http://localhost:8000/ | grep "custom-design\|custom-interactions"
# Should return lines with asset URLs
```

## For Production Deployment

1. **Push to Git:**
   ```bash
   git status
   git add .
   git commit -m "Fix: ensure assets deployed"
   git push origin main
   ```

2. **On Server, Pull Changes:**
   ```bash
   cd /var/www/html/laravel  # your app path
   git pull origin main
   php artisan cache:clear
   php artisan view:clear
   sudo systemctl restart apache2  # or nginx
   ```

3. **Verify on Server:**
   - Visit your domain
   - Hard refresh browser (Ctrl+F5)
   - Check browser console (F12) for errors

## Additional Debugging

Enable debug mode temporarily (development only):
```env
APP_DEBUG=true
```

This will show detailed error messages if something breaks.

For persistent issues, check logs:
```bash
# Laravel log
tail -f storage/logs/laravel.log

# Apache log
tail -f /var/log/apache2/error.log

# Nginx log
tail -f /var/log/nginx/error.log
```

---

**Last Updated:** October 28, 2025
**Assets Deployed:** Commit 4593b56
