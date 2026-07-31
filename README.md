# 🛒 Multi-Vendor E-Commerce Backend

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)
[![Tests](https://img.shields.io/badge/Tests-Pest%2FPHPUnit-brightgreen?style=flat-square)](https://pestphp.com)
[![Static Analysis](https://img.shields.io/badge/PHPStan-Level%205-blueviolet?style=flat-square)](https://phpstan.org)

> A modern, domain-driven multi-vendor e-commerce platform backend built with Laravel 11, PHP 8.3, and Tailwind CSS.

---

## 📌 Overview

**Multi-Vendor E-Commerce Backend** provides an enterprise-grade framework for running multi-seller marketplaces. It addresses complex operational challenges—such as vendor commission splits, multi-warehouse shipping calculations, automated seller payouts, bulk catalog imports, and affiliate tracking—through a clean, domain-driven modular architecture.

---

## 📖 Table of Contents

- [Features](#-key-features)
- [Tech Stack](#-tech-stack)
- [Architecture & Folder Structure](#-architecture--folder-structure)
- [Installation](#-installation)
- [Usage & Commands](#-usage--commands)
- [Testing & Quality Assurance](#-testing--quality-assurance)
- [Contributing](#-contributing)
- [License](#-license)
- [Contact](#-author--contact)

---

## ✨ Key Features

### 🏢 Multi-Vendor Management
- **Seller Portal**: Vendor dashboard for inventory, store profile management, sales analytics, and payout requests.
- **Admin Control Panel**: Centralized management suite for vendor approvals, commission setup, global catalog controls, and audit reporting.

### 📦 Product & Catalog Management
- **Variant & Bundle System**: Configurable product attributes, variant inventory matrix, and bundle offers.
- **Bulk Product Upload**: Asynchronous processing for mass product ingestion with validation and image optimization.

### 💳 Order, Payment & Shipping
- **Multi-Vendor Order Processing**: Automated order splitting across vendors with independent order lifecycle tracking.
- **Payout & Commission Engine**: Calculation of vendor net earnings, platform commissions, refund adjustments, and automated payout verification.
- **Location-Based Shipping**: Dynamic shipping fee resolution by region/zone per vendor.

### 🎯 Marketing & Engagement
- **Affiliate System**: Referral tracking, affiliate link generation, commission logs, and performance metrics.
- **Promotions & Support**: Coupon engine, flash sale manager, and integrated customer support ticketing.

---

## 🛠 Tech Stack

- **Framework**: [Laravel 11.x](https://laravel.com)
- **Language**: [PHP 8.3+](https://php.net)
- **Database**: MySQL 8.0+ / PostgreSQL
- **Frontend / Styling**: Blade, Tailwind CSS, Lucide Icons, DataTables
- **Authentication**: Laravel Sanctum (API Tokens) & Session Guard
- **Testing**: Pest PHP & PHPUnit
- **Static Analysis**: PHPStan & Larastan
- **Package Manager**: Composer & npm

---

## 📂 Architecture & Folder Structure

The project follows a **Domain-Driven Design (DDD)** approach within the `app/Domain` layer:

```
ecommerce_multivendor_backend/
├── app/
│   ├── Domain/                 # Domain-driven business logic
│   │   ├── Affiliate/          # Referral & affiliate management
│   │   ├── Auth/               # Authentication & roles
│   │   ├── BulkUpload/         # Asynchronous mass import
│   │   ├── Bundle/             # Product bundle domain logic
│   │   ├── Media/              # File & image handling
│   │   ├── Order/              # Order lifecycle & tracking
│   │   ├── Payment/            # Gateway & payout processing
│   │   ├── Product/            # Catalog, variants & inventory
│   │   ├── Review/             # Product ratings & reviews
│   │   ├── Shipping/           # Rates, zones & tracking
│   │   ├── Support/            # Helpdesk & customer support
│   │   ├── Tax/                # Tax rule calculation engine
│   │   └── Vendor/             # Seller profile & shop settings
│   ├── Http/                   # Controllers, Requests & Middleware
│   └── Services/               # Shared cross-domain services
├── config/                     # Application configuration
├── database/
│   ├── factories/              # Database factories
│   ├── migrations/             # Database schema migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/                  # Blade templates (Admin, Seller, Web)
│   └── css/                    # Tailwind CSS assets
├── routes/                     # web.php, api.php, seller.php, admin.php
└── tests/                      # Pest & PHPUnit test suite
```

---

## 🚀 Installation

Follow these steps to set up the repository locally:

### 1. Prerequisites
- **PHP** `>= 8.3` with extensions (`pdo`, `mbstring`, `openssl`, `curl`, `gd` / `imagick`)
- **Composer** `>= 2.x`
- **Node.js** `>= 18.x` & **npm**
- **MySQL** `>= 8.0` or **PostgreSQL**

### 2. Clone the Repository
```bash
git clone https://github.com/Shraban-7/multivendor-ecommerce.git
cd multivendor-ecommerce
```

### 3. Install Dependencies
```bash
# Install PHP packages
composer install

# Install Node modules
npm install
```

### 4. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file with database and service credentials:
```env
APP_NAME="MultiVendor E-Commerce"
APP_ENV=local
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce_db
DB_USERNAME=root
DB_PASSWORD=secret
```

### 5. Run Database Migrations & Seeders
```bash
php artisan migrate --seed
```

### 6. Build Assets & Storage Link
```bash
php artisan storage:link
npm run build
```

---

## 💻 Usage & Commands

### Development Server
Run the local Laravel development server:
```bash
php artisan serve
```

### Concurrent Development (Vite Assets)
In a separate terminal, compile assets with hot reload:
```bash
npm run dev
```

### Artisan Helper Commands
```bash
# Clear application cache
php artisan cache:clear

# Process asynchronous queue jobs (bulk upload, emails)
php artisan queue:work
```

---

## 🧪 Testing & Quality Assurance

Maintain high code quality with static analysis and test execution:

```bash
# Run unit & integration tests via Pest
vendor/bin/pest

# Run static analysis check with PHPStan
vendor/bin/phpstan analyse

# Run code formatting check with Laravel Pint
vendor/bin/pint --test
```

---

## 🤝 Contributing

Contributions are welcome! To contribute:

1. Fork the project repository.
2. Create your feature branch (`git checkout -b feature/amazing-feature`).
3. Commit your changes with clear messages (`git commit -m 'feat: add amazing feature'`).
4. Push to the branch (`git push origin feature/amazing-feature`).
5. Open a Pull Request for review.

Please ensure all tests pass (`vendor/bin/pest`) and code passes static analysis (`vendor/bin/phpstan`) before submitting.

---

## 📄 License

This project is open-source software licensed under the [MIT License](LICENSE).

---

## ✉️ Author / Contact

- **Author**: Shraban
- **GitHub**: [@Shraban-7](https://github.com/Shraban-7)
- **Repository**: [Shraban-7/multivendor-ecommerce](https://github.com/Shraban-7/multivendor-ecommerce)
