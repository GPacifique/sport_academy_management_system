#!/bin/bash

# Sport Academy Management System - Build Script
# This script builds the Vite assets for production

echo "🏗️  Building Sport Academy Management System Assets..."

# Navigate to Laravel directory
cd "$(dirname "$0")" || exit 1

# Install/update dependencies if needed
if [ ! -d "node_modules" ] || [ "package.json" -nt "node_modules" ]; then
    echo "📦 Installing npm dependencies..."
    npm install
fi

# Build assets
echo "🎨 Building CSS and JavaScript assets..."
npm run build

echo "✅ Build completed successfully!"
echo ""
echo "📊 Build output:"
ls -la public/build/assets/

echo ""
echo "🚀 Your Sport Academy Management System is ready!"
echo "   The enhanced CSS and JavaScript modules are now optimized for production."