# School Enrollment System

A web-based school enrollment system built with Laravel 13.

---

## Tech Stack

| Layer | Tech | Version |
|---|---|---|
| Backend | Laravel | 13 |
| Language | PHP | ^8.3 |
| Database | MySQL | 8.x |
| Frontend | Blade Templates | — |
| CSS | Bootstrap | 5.3 |
| Icons | Bootstrap Icons | — |
| Build Tool | Vite | 8 |
| Auth | Laravel Breeze | — |
| Testing | PHPUnit | 12 |

---

## Group Members & Responsibilities

| Member | Role | Primary Deliverables |
|---|---|---|
| **Member A** | Project Lead / Back-end Core | Scaffolding, subject enrollment, middleware, deployment |
| **Member B** | Database & Back-end Support | ERD, migrations, seeders, section CRUD, admin dashboard |
| **Member C** | Auth & Flow Logic | Breeze auth, approval/rejection flow, UI polish |
| **Member D** | Front-end & Documentation | Enrollment form, student records view, README & submission docs |

---

## Requirements

- PHP 8.3+
- Composer
- MySQL 8.x
- Node.js 18+ and npm

---

## Local Setup

> On Windows, use PowerShell.

```powershell
# 1. Clone the repo
git clone https://github.com/JJEEYYSSEE/Webdev_SchoolEnrollmentSystem.git
cd Webdev_SchoolEnrollmentSystem

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies (only needed if you change frontend assets)
npm install

# 4. Copy environment file
copy .env.example .env
php artisan key:generate

# 5. Create MySQL database
# CREATE DATABASE school_enrollment_db;

# 6. Set DB credentials in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=school_enrollment_db
# DB_USERNAME=root
# DB_PASSWORD=yourpassword

# 7. Run migrations
php artisan migrate

# 8. Start the server
php artisan serve
```

App runs at: `http://localhost:8000`

> `public/build/` is committed — teammates do not need to run `npm run dev`.

---

## Project Structure

```
app/Http/Controllers/
  Auth/           — 2FA controller
  Student/        — student-facing controllers
  Registrar/      — registrar-facing controllers

app/Http/Middleware/
  CheckRole.php   — role-based access control

resources/
  sass/           — Bootstrap SCSS entry point + variable overrides
  js/             — Bootstrap JS bootstrap
  views/
    auth/         — login, register, password reset
    layouts/      — app, guest, student, registrar base layouts
    student/      — student portal pages
    registrar/    — registrar portal pages
    profile/      — profile edit pages

routes/
  web.php         — student + registrar route groups
  auth.php        — Breeze auth routes

database/migrations/
  — 9 domain tables + 3 Laravel defaults
```

---

## Routes

| Prefix | Middleware | Description |
|---|---|---|
| `/` | — | Landing page |
| `/login`, `/register` | guest | Auth (Breeze) |
| `/student/*` | auth, role:student | Student portal |
| `/registrar/*` | auth, role:registrar | Registrar portal |
| `/profile` | auth | Profile edit |

---

## Database Tables

| Table | Description |
|---|---|
| `users` | Auth accounts — role: student / registrar / admin |
| `students` | Student profile linked to user account |
| `registrars` | Registrar profile linked to user account |
| `semesters` | Academic semesters — one marked `is_active` at a time |
| `sections` | Class sections per semester |
| `subjects` | Master subject list |
| `enrollments` | Student enrollment per semester — pending / approved / rejected |
| `enrollment_subjects` | Subjects in an enrollment (pivot) with grade and status |
| `semester_records` | GPA and completion status per student per semester |

---

## Verify Setup

```powershell
# All routes registered
php artisan route:list

# Student routes only
php artisan route:list | Select-String "student"

# Registrar routes only
php artisan route:list | Select-String "registrar"

# Migrations ran clean
php artisan migrate:status

# Run tests
php artisan test
```

---

## Sharing with Teammates (ngrok)

```powershell
# Terminal 1
php artisan serve

# Terminal 2
ngrok http 8000
```

Copy the `https://abc123.ngrok-free.app` URL and share with teammates. Add to `.env`:

```env
APP_URL=https://abc123.ngrok-free.app
SESSION_DOMAIN=.ngrok-free.app
```

> Free tier URL changes every restart. Re-share each session.
