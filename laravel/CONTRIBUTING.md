# Contributing to Sport Academy MS

Thank you for considering contributing to Sport Academy MS! We welcome contributions from the community.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- A clear title and description
- Steps to reproduce the issue
- Expected vs actual behavior
- Screenshots (if applicable)
- Your environment details (PHP version, OS, etc.)

### Suggesting Features

We love new ideas! Please create an issue with:
- A clear description of the feature
- The problem it solves
- Possible implementation approach
- Any mockups or examples

### Pull Requests

1. **Fork the repository** and create your branch from `main`
2. **Make your changes** following our coding standards
3. **Test your changes** thoroughly
4. **Update documentation** if needed
5. **Commit your changes** with clear messages
6. **Push to your fork** and submit a pull request

### Coding Standards

- Follow **PSR-12** coding standards
- Use **meaningful variable and function names**
- Add **comments** for complex logic
- Write **tests** for new features
- Keep **commits atomic** and well-described

### Code Style

Run Laravel Pint before committing:

```bash
./vendor/bin/pint
```

### Testing

Run tests to ensure nothing breaks:

```bash
php artisan test
```

### Commit Messages

Use clear, descriptive commit messages:

```
feat: add jersey number field to student registration
fix: correct attendance calculation logic
docs: update installation instructions
style: format code with Pint
refactor: simplify payment processing
test: add tests for student controller
```

## Development Setup

1. Clone your fork
2. Install dependencies: `composer install && npm install`
3. Copy `.env.example` to `.env`
4. Generate key: `php artisan key:generate`
5. Run migrations: `php artisan migrate`
6. Start server: `php artisan serve`

## Questions?

Feel free to reach out:
- 📞 Phone: 0786 163 963
- 📧 Email: info@sportacademyms.com

Thank you for contributing! 🎉
