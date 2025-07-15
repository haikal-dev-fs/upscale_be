# Todo API – Lumen 10

A lightweight RESTful API for a simple Todo application built with **Laravel Lumen 10**.  
Live demo: <https://upscalebe-production.up.railway.app/>

---

## 1️⃣ Instruksi Setup (Project)

```bash
git clone <repo-backend-url>
cd todo-backend
composer install          # pasang dependency PHP

cp .env.example .env      # salin konfigurasi
php artisan jwt:secret    # generate JWT_SECRET
php -r "echo substr(md5(uniqid()),0,32).PHP_EOL;" >> .env   # APP_KEY=…

# sesuaikan kredensial DB di .env
php artisan migrate --seed   # jalankan migrasi & seeder

# (opsional) generate dokumentasi Swagger
php artisan swagger-lume:generate

# serve lokal
php -S 0.0.0.0:8000 -t public
```

---

## 2️⃣ Struktur Proyek (Singkat)

```
todo-backend/
├─ app/               # Controller, Model, Provider
├─ bootstrap/
├─ config/
├─ database/
│   ├─ migrations/
│   └─ seeders/
├─ routes/
│   └─ web.php        # Definisi endpoint REST
├─ tests/             # PHPUnit
└─ public/            # index.php (front‑controller)
```

---

## 3️⃣ Teknologi yang Digunakan

| Lapisan | Teknologi / Paket | Keterangan Singkat |
|---------|-------------------|--------------------|
| Framework | **Laravel Lumen 10** | Micro‑framework PHP |
| Bahasa | **PHP 8.2** |  |
| DB & ORM | **MySQL** (∵ Eloquent) | bebas ganti PostgreSQL / SQLite |
| Auth | `tymon/jwt-auth` | JWT Bearer Token |
| Dokumentasi | `swagger‑lume` (OpenAPI 3) | Swagger UI di `/api/documentation` |
| Testing | **PHPUnit** | unit & feature tests |
| CI/CD | **GitHub Actions** | lint + test otomatis |
