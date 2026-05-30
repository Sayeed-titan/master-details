# Laravel Master-Details CRUD — Learning Repository

A hands-on learning repository for building **Master-Details CRUD** applications using **Laravel**.  
Each project inside this repo is a standalone Laravel app focused on a different real-world use case.

---

## What is Master-Details?

A **Master-Details** pattern is when one parent record (master) owns many child records (details).

**Example:**
- `Order` (master) → `Order Items` (details)
- `Invoice` (master) → `Invoice Lines` (details)
- `Student` (master) → `Enrolled Courses` (details)

---

## Projects

| # | Folder | Topic | Status |
|---|--------|-------|--------|
| 1 | `course/order-app` | Order & Order Items — Full CRUD with REST API + Blade Frontend | 🚧 In Progress |

> More projects will be added here as we progress through different use cases.

---

## Learning Phases (per project)

Each project follows the same structured learning phases:

- **Phase 1** — Project Setup, Database, Migrations, Models & Relationships
- **Phase 2** — Backend REST API (Controllers, Routes, API Resources)
- **Phase 3** — Frontend (Blade Views + Bootstrap 5)
- **Phase 4** — Connecting Frontend to API (Axios + Dynamic JS)
- **Phase 5** — Polish (Validation, Error Handling, Best Practices)

---

## Tech Stack

| Layer | Tool |
|-------|------|
| Backend | Laravel 13 (PHP 8.4) |
| Database | MySQL |
| API | RESTful JSON API |
| Frontend | Blade + Bootstrap 5 + Axios |

---

## How to Run a Project

```bash
# 1. Go into the project folder
cd course/order-app

# 2. Install dependencies
composer install

# 3. Copy env and set your DB credentials
cp .env.example .env
# Edit .env → set DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Generate app key
php artisan key:generate

# 5. Run migrations + seeders
php artisan migrate --seed

# 6. Start the server
php artisan serve
```

Then open `http://localhost:8000` in your browser.

---

## Repository Structure

```
master-details/
├── course/
│   └── order-app/        ← Project 1: Order Management
├── .gitignore
└── README.md
```

---

> Built for learning purposes — step by step, phase by phase.
