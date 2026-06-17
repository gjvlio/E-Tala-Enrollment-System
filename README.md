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
| CSS | Tailwind CSS | v4 |
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

## Pending Tasks by Member

### Member A — Project Lead / Back-end Core
- [ ] Create `CheckRole` middleware (`php artisan make:middleware CheckRole`)
- [ ] Register `CheckRole` in `bootstrap/app.php`
- [ ] Define model relationships (User, Student, Enrollment, etc.)
- [ ] Set up deployment (ngrok for local sharing or InfinityFree)

### Member B — Database & Back-end Support
- [ ] Create seeders for all tables (`php artisan make:seeder`)
  - `SemesterSeeder` — create an active semester
  - `UserSeeder` — seed a registrar + student account
  - `SectionSeeder`, `SubjectSeeder`
- [ ] Implement `Registrar/SectionController` CRUD methods
- [ ] Implement `Registrar/SubjectController` CRUD methods
- [ ] Build registrar admin dashboard view

### Member C — Auth & Flow Logic
- [ ] Implement `Registrar/EnrollmentController` approve/reject logic
- [ ] Implement `Student/EnrollmentController` store + status logic
- [ ] Build 2FA flow (Fortify install + challenge view)
- [ ] UI polish on auth views (login, register)

### Member D — Front-end & Documentation
- [ ] Build enrollment form UI (`student/enroll.blade.php`)
- [ ] Build student records view (`student/records.blade.php`)
- [ ] Build registrar enrollment queue UI (`registrar/enrollments/index.blade.php`)
- [ ] Update README with final submission docs

---

## Requirements

- PHP 8.3+
- Composer
- MySQL 8.x
- Node.js 18+ and npm

---

## Local Setup

```bash
# 1. Clone the repo
git clone <repo-url>
cd SchoolEnrollmentSystem

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Copy environment file and configure
cp .env.example .env
php artisan key:generate

# 5. Create MySQL database
mysql -u root -p -e "CREATE DATABASE school_enrollment_db;"

# 6. Update DB credentials in .env
# DB_USERNAME=root
# DB_PASSWORD=yourpassword

# 7. Run migrations
php artisan migrate

# 8. Start the dev servers (run both in separate terminals)
php artisan serve
npm run dev
```

Or use the setup script (Windows):
```powershell
.\setup.ps1
```

---

## Sharing with Teammates (ngrok)

ngrok creates a public URL tunneling to your local server so teammates can test on their own devices.

### Install ngrok
1. Download from https://ngrok.com/download
2. Create free account at https://ngrok.com
3. Authenticate: `ngrok config add-authtoken <your-token>`

### Share your local server
```bash
# Make sure Laravel is running first
php artisan serve

# In a separate terminal
ngrok http 8000
```

Copy the `https://abc123.ngrok-free.app` URL and share with teammates.

### Allow ngrok host in .env
```env
APP_URL=https://abc123.ngrok-free.app
SESSION_DOMAIN=.ngrok-free.app
```

> ngrok free tier URL changes every restart. Re-share each session.

---

## Database

Database name: `school_enrollment_db`

| Table | Description |
|---|---|
| `users` | Auth accounts (student / registrar / admin) |
| `students` | Student profile linked to user |
| `registrars` | Registrar profile linked to user |
| `semesters` | Academic semester records |
| `sections` | Class sections per semester |
| `subjects` | Master subject list |
| `enrollments` | Student enrollment per semester |
| `enrollment_subjects` | Subjects per enrollment (pivot) |
| `semester_records` | GPA and status per semester |

---

## Project Structure

```
app/Http/Controllers/
  Auth/           — 2FA controller
  Student/        — student-facing controllers
  Registrar/      — registrar-facing controllers

resources/views/
  auth/           — login, register, 2FA
  layouts/        — shared layouts
  student/        — student pages
  registrar/      — registrar pages

routes/
  web.php         — main routes (student + registrar groups)
  auth.php        — Breeze auth routes (login, register, logout)

database/migrations/
  — all table migrations
```

---

## Running Tests

```bash
php artisan test
```
