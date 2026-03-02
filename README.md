# Rumah Pintar

Rumah Pintar adalah platform digital untuk yayasan sosial berbasis pendidikan yang mengintegrasikan sistem donasi, relawan, penjualan produk sosial, serta dashboard manajemen dalam satu ekosistem terpusat.

Project ini dikembangkan menggunakan PHP Native dengan pendekatan modular dan scalable architecture untuk mendukung pertumbuhan jangka panjang.

---

## Core Features

### Donation System
- Donasi Uang (upload bukti transfer)
- Donasi Barang
- Verifikasi & approval admin
- Status tracking
- Rekening & bank dinamis melalui System Settings

### Product & Order System
- CRUD Produk
- Stok otomatis berkurang saat checkout
- Cart system
- Checkout dengan integrasi OpenStreetMap (Leaflet)
- Order status management (pending, paid, shipped, completed)
- Revenue tracking & analytics

### Volunteer Management
- Online registration
- Admin approval system
- Email notification (PHPMailer)
- PDF certificate generation (FPDF)
- Export volunteer data

### CMS & Content Management
- Artikel dengan thumbnail
- Draft / Publish system
- WYSIWYG editor (TinyMCE)
- Search & pagination
- Bulk action management
- Modern admin interface

### Admin Dashboard
- Revenue analytics
- Order statistics
- Donation summary
- Volunteer metrics
- AJAX-based dynamic charts (Chart.js)
- Monthly comparison feature

### System Settings
- Site name & description
- Logo management
- WhatsApp & email configuration
- Bank account settings
- Minimum donation configuration
- Maintenance mode toggle

---

## Project Structure

```
rumahpintar/
│
├── admin/                # Admin dashboard & CMS
│   ├── dashboard.php
│   ├── list_artikel.php
│   ├── list_produk.php
│   ├── list_orders.php
│   ├── list_volunteer.php
│   └── settings.php
│
├── services/             # Service layer (e.g. WhatsAppService)
├── includes/             # Shared components (header, footer)
├── assets/               # CSS & JS files
├── uploads/              # Uploaded files
├── koneksi.php           # Database connection
└── index.php             # Landing page
```

---

## Tech Stack

- PHP 8+
- MySQL / MariaDB
- Bootstrap 5
- Chart.js
- TinyMCE
- PHPMailer
- FPDF
- Leaflet (OpenStreetMap)

---

## Security Implementation

- Prepared Statements (SQL Injection prevention)
- CSRF Protection
- Session Hardening
- File upload validation
- Role-based admin access
- Secure authentication with password hashing
- Activity logging (planned extension)

---

## Installation Guide

1. Clone repository

```bash
git clone https://github.com/your-username/rumahpintar.git
```

2. Import database file into MySQL
3. Configure database connection in `koneksi.php`
4. Create initial record in `settings` table
5. Run via local server (XAMPP / Laragon)

---

## Roadmap

- WhatsApp automation service
- Multi-bank & QRIS integration
- Campaign-based donation system
- Financial transparency report page
- Payment gateway integration (Midtrans / Xendit)
- Role-based admin management
- REST API endpoints
- Performance optimization & caching

---

## Contribution

Pull requests are welcome for improvements, optimizations, security enhancements, and feature development.

If you would like to contribute:

1. Fork this repository
2. Create a new branch
3. Commit your changes
4. Submit a pull request

---

## License

This project is open-source and intended for social and educational purposes.

---

## About

Rumah Pintar is built to empower education and community development through technology-driven transparency, digital donation systems, and structured volunteer management.

Together, we build impact through technology.
