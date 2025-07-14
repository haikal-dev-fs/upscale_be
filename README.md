# Todo Backend – Lumen 🏗️


A **JWT‑secured REST API** for a full‑stack *To‑Do* application. Built with **Laravel Lumen 11**, documented via **Swagger‑Lume 11**, and continuously deployed to **Railway**.

> ℹ️ **Frontend out of scope** – repository only covers backend service.
                                                                            
## ✨ Features

| Area | Detail |
|------|--------|
| **Auth** | JWT (access + refresh) via *tymon/jwt‑auth* |
| **CRUD** | `/tasks` endpoints with status filter |
| **Docs** | Swagger UI served at `/api/documentation` |
| **Testing** | PHPUnit feature & unit tests (coverage report) |
| **CI/CD** | GitHub Actions → Railway deploy on every push `main` |
| **DB** | MySQL 8 (local Docker & Railway add‑on) |

---
## 🛠 Stack & Requirements

* PHP ≥ 8.2 & Composer 2.7  
* MySQL 8 (or MariaDB) – Docker recommended  
* **Optional **: Node ≥ 18 if you run client tools (not required here)

```bash
# clone
git clone https://github.com/haikal-dev-fs/todo-backend.git
cd todo-backend

# install dependencies
composer install
```

---
## 🔧 Environment Variables

Copy `.env.example` to `.env` and fill values:

| Key | Description |
|-----|-------------|
| `APP_KEY` | Generate via `php -r "echo substr(bin2hex(random_bytes(16)),0,32);"` |
| `JWT_SECRET` | `php artisan jwt:secret` |
| `DB_*` | Connection values (host, user, pass, port, database) |
| `L5_SWAGGER_CONST_HOST` | Base URL (local `http://localhost:3200` / production URL) |

---
## 🚀 Running Locally

```bash
# start MySQL (Docker)
docker run --name todo-db -e MYSQL_ROOT_PASSWORD=root         -e MYSQL_DATABASE=todo -p 3307:3306 -d mysql:8

# migrate & seed
php artisan migrate --seed

# start dev server
php -S 0.0.0.0:3200 -t public
```

Visit **https://upscalebe-production.up.railway.app/api/documentation** for Swagger UI.

---
## 🧪 Tests

```bash
vendor/bin/phpunit
```

Coverage report generated to `coverage.xml` (consumed by CI).

---
## ⚙️ CI Pipeline (GitHub Actions)

Workflow **ci.yml** performs:

1. Checkout & install PHP 8.2.
2. Spin‑up MySQL service.  
3. Run `php artisan migrate --force` & PHPUnit.  
4. Regenerate Swagger docs and upload as artifact.  
5. **On main branch** – Railway picks new commit and redeploys.

---
## ☁️ Deployment – Railway

1. Create project → “Deploy from GitHub” → select this repo.  
2. Add **MySQL add‑on**.  
3. Fill *Variables* from `.env`.  
4. Build command: `composer install --no-dev --optimize-autoloader`  
5. Start command: `php -S 0.0.0.0:${PORT} -t public`  
6. Deploy hook: `php artisan migrate --force && php artisan swagger-lume:generate`

After deploy: `https://<your‑railway‑subdomain>.up.railway.app/api/documentation`

---
## 🗺 Project Structure

```text
├─ app/                # Controllers, Models, Services
├─ bootstrap/          # Lumen bootstrap
├─ config/             # JWT, swagger-lume, auth, etc.
├─ database/
│  ├─ migrations/
│  └─ seeders/
├─ public/             # index.php & swagger-ui-assets
├─ tests/              # PHPUnit tests
├─ docs/               # Architecture diagrams & ADRs
└─ .github/workflows/  # CI pipeline
```

---
## 📝 License

This project is licensed under the MIT License – see the [LICENSE](LICENSE) file for details.
