# Todo API – Lumen 10

A lightweight RESTful API for a simple Todo application built with **Laravel Lumen 10**.  
Live demo: <https://upscalebe-production.up.railway.app/>

---

## ✨ Features
- **JWT authentication** (`tymon/jwt-auth`)
- CRUD for tasks with status filter (`pending | done`)
- “Quote of the Day” endpoint
- Auto‑generated OpenAPI docs via **swagger‑lume**
- MySQL (or any SQL DB supported by Eloquent)
- PSR‑12 code‑style, CI via GitHub Actions

---

## 🚀 Quick start

```bash
git clone <repo-url>
cd todo-backend
composer install

# copy and adjust env
cp .env.example .env

# generate JWT secret & app key
php artisan jwt:secret
php -r "echo substr(md5(uniqid()),0,32).PHP_EOL;" >> .env   # APP_KEY=…

# configure DB credentials in .env then run
php artisan migrate --seed

# (optional) generate Swagger docs
php artisan swagger-lume:generate

# serve locally
php -S 0.0.0.0:8000 -t public