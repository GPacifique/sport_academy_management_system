#!/bin/bash
# Asset Verification Script for Sport Club MS
# This script helps verify that all custom design assets are deployed correctly

echo "================================================"
echo "Sport Club MS - Asset Verification Script"
echo "================================================"
echo ""

# Color codes
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if files exist
echo "1. Checking file existence..."
echo ""

if [ -f "public/css/custom-design.css" ]; then
    SIZE=$(du -h public/css/custom-design.css | cut -f1)
    echo -e "${GREEN}✓${NC} CSS file exists (Size: $SIZE)"
else
    echo -e "${RED}✗${NC} CSS file NOT found!"
fi

if [ -f "public/js/custom-interactions.js" ]; then
    SIZE=$(du -h public/js/custom-interactions.js | cut -f1)
    echo -e "${GREEN}✓${NC} JS file exists (Size: $SIZE)"
else
    echo -e "${RED}✗${NC} JS file NOT found!"
fi

if [ -f "resources/views/welcome.blade.php" ]; then
    echo -e "${GREEN}✓${NC} Welcome view exists"
else
    echo -e "${RED}✗${NC} Welcome view NOT found!"
fi

echo ""
echo "2. Checking file contents..."
echo ""

# Check if CSS has custom classes
if grep -q "\.navbar" public/css/custom-design.css 2>/dev/null; then
    echo -e "${GREEN}✓${NC} CSS contains custom navbar styling"
else
    echo -e "${RED}✗${NC} CSS missing navbar styling"
fi

if grep -q "\.hero" public/css/custom-design.css 2>/dev/null; then
    echo -e "${GREEN}✓${NC} CSS contains hero section styling"
else
    echo -e "${RED}✗${NC} CSS missing hero styling"
fi

# Check if JS has custom functions
if grep -q "initNavigation" public/js/custom-interactions.js 2>/dev/null; then
    echo -e "${GREEN}✓${NC} JS contains navigation initialization"
else
    echo -e "${RED}✗${NC} JS missing navigation initialization"
fi

if grep -q "initScrollAnimations" public/js/custom-interactions.js 2>/dev/null; then
    echo -e "${GREEN}✓${NC} JS contains scroll animations"
else
    echo -e "${RED}✗${NC} JS missing scroll animations"
fi

# Check if welcome.blade.php references custom assets
echo ""
echo "3. Checking welcome.blade.php references..."
echo ""

if grep -q "custom-design.css" resources/views/welcome.blade.php 2>/dev/null; then
    echo -e "${GREEN}✓${NC} Welcome.blade.php links custom CSS"
else
    echo -e "${RED}✗${NC} Welcome.blade.php doesn't link custom CSS"
fi

if grep -q "custom-interactions.js" resources/views/welcome.blade.php 2>/dev/null; then
    echo -e "${GREEN}✓${NC} Welcome.blade.php links custom JS"
else
    echo -e "${RED}✗${NC} Welcome.blade.php doesn't link custom JS"
fi

if grep -q "\.navbar" resources/views/welcome.blade.php 2>/dev/null; then
    echo -e "${GREEN}✓${NC} Welcome.blade.php uses custom navbar class"
else
    echo -e "${RED}✗${NC} Welcome.blade.php doesn't use custom navbar"
fi

if grep -q "\.hero" resources/views/welcome.blade.php 2>/dev/null; then
    echo -e "${GREEN}✓${NC} Welcome.blade.php uses custom hero class"
else
    echo -e "${RED}✗${NC} Welcome.blade.php doesn't use custom hero"
fi

echo ""
echo "4. File permissions..."
echo ""

PERM=$(stat -c "%a" public/css/custom-design.css 2>/dev/null || stat -f "%OLp" public/css/custom-design.css 2>/dev/null | cut -c 3-)
echo "CSS file permissions: $PERM"

PERM=$(stat -c "%a" public/js/custom-interactions.js 2>/dev/null || stat -f "%OLp" public/js/custom-interactions.js 2>/dev/null | cut -c 3-)
echo "JS file permissions: $PERM"

echo ""
echo "5. .env configuration..."
echo ""

APP_URL=$(grep "^APP_URL" .env | cut -d'=' -f2)
echo "APP_URL: $APP_URL"

APP_ENV=$(grep "^APP_ENV" .env | cut -d'=' -f2)
echo "APP_ENV: $APP_ENV"

echo ""
echo "================================================"
echo "Verification Complete!"
echo "================================================"
echo ""
echo "If all checks passed (✓), your assets should display correctly."
echo "If you see any ✗ marks:"
echo "  1. Run 'git pull origin main' to get latest files"
echo "  2. Run 'php artisan cache:clear && php artisan view:clear'"
echo "  3. Hard refresh browser (Ctrl+F5 / Cmd+Shift+R)"
echo ""
