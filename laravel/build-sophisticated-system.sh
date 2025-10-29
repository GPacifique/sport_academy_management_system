#!/bin/bash

# Sport Academy Management System - Build Script
# Builds all assets and ensures proper integration

echo "🏈 Building Sport Academy Management System..."

# Navigate to Laravel directory
cd "$(dirname "$0")"

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Not in Laravel project directory"
    exit 1
fi

# Install/Update NPM dependencies
echo "📦 Installing NPM dependencies..."
npm install

# Install/Update Composer dependencies
echo "🎼 Installing Composer dependencies..."
composer install --optimize-autoloader

# Clear Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate application key if not exists
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Compile assets
echo "🎨 Compiling assets..."
npm run build

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
echo "🔒 Setting permissions..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Create symbolic link for storage
echo "🔗 Creating storage link..."
php artisan storage:link

# Verify installation
echo "✅ Verifying installation..."

# Check if sophisticated JS modules exist
js_modules=(
    "resources/js/modules/Core.js"
    "resources/js/modules/Dashboard.js"
    "resources/js/modules/Modal.js"
    "resources/js/modules/Form.js"
    "resources/js/modules/Navigation.js"
    "resources/js/modules/Theme.js"
    "resources/js/modules/Integration.js"
)

for module in "${js_modules[@]}"; do
    if [ -f "$module" ]; then
        echo "✅ $module - Found"
    else
        echo "❌ $module - Missing"
    fi
done

# Check if CSS exists
if [ -f "resources/css/advanced.css" ]; then
    echo "✅ Advanced CSS - Found"
else
    echo "❌ Advanced CSS - Missing"
fi

# Check if compiled assets exist
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Build manifest - Found"
else
    echo "❌ Build manifest - Missing"
fi

echo ""
echo "🎉 Build completed successfully!"
echo ""
echo "Sophisticated JavaScript Enhancement Features:"
echo "• Core Framework with Event Bus"
echo "• Interactive Dashboard Components"
echo "• Advanced Modal & Notification System"
echo "• Enhanced Form Validation & UX"
echo "• Sophisticated Navigation System"
echo "• Comprehensive Theme Management"
echo "• Laravel Integration Layer"
echo "• Advanced CSS Architecture"
echo ""
echo "📖 Documentation: SOPHISTICATED_JS_DOCUMENTATION.md"
echo ""
echo "🚀 To start development server:"
echo "   npm run dev"
echo ""
echo "🏃 To start Laravel server:"
echo "   php artisan serve"
echo ""
echo "Happy coding! 🎯"