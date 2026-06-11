# School Enrollment System - Day 1 Setup (June 11)

Welcome to the School Enrollment System project. This document summarizes the Day 1 setup, directory structure, database configuration, and instructions to run the application locally.

---

## 1. Project Scope & Features
The School Enrollment System is designed to handle the following core operations:
- **Online Enrollment Form**: Allow students to register and submit enrollment requests.
- **Section & Class Assignment**: Admins can create sections/classes and assign students.
- **Subject Enrollment**: Student subject management per semester.
- **Approval Workflow**: Enrollment verification and approval by Registrars/Admins.
- **Student Records**: Persistent student records tracking performance and status per semester.

---

## 2. Day 1 Accomplishments (June 11)
- **Git Repo Initialized**: Created repository, set up `.gitignore` for Laravel, and made initial commits.
- **Laravel Project Setup**: Bootstrapped a fresh Laravel 11.x application.
- **Environment Configured**: Set up database configuration using MySQL and `school_enrollment_db`.
- **Database Schema Designed**: Created migration stubs containing fields and constraints for:
  - `users` (Added custom `role` column for role-based permissions)
  - `sections` (Fields: section name, grade level, advisor, max capacity)
  - `subjects` (Fields: subject code, name, description, credits)
  - `students` (Fields: user_id relation, student number, basic info, address, enrollment status)
  - `enrollments` (Fields: student relation, section relation, semester/year, approval logs)
  - `semester_records` (Fields: student relation, semester/year, GPA, status, remarks)
  - `enrollment_subjects` (Pivot table for subject enrollment per student with grade and status tracking)

---

## 3. Directory Structure
The initialized project follows the standard Laravel directory structure:
```text
School Enrollment System/
├── app/                      # Application core (Models, Controllers, Providers, etc.)
│   ├── Models/               # Eloquent Models (User)
│   └── Http/                 # HTTP layer (Controllers, Middleware, Requests)
├── bootstrap/                # Application bootstrap files
├── config/                   # Configuration files (app.php, database.php, etc.)
├── database/                 # Database migrations, factories, and seeders
│   └── migrations/           # Day 1 migration files
├── public/                   # Publicly accessible assets (index.php, CSS, JS)
├── resources/                # Front-end assets (Views/Blade templates, CSS/JS sources)
│   └── views/                # Blade views (welcome.blade.php)
├── routes/                   # Route definitions (web.php, console.php)
├── storage/                  # Application logs, file uploads, cache, and sessions
├── tests/                    # PHPUnit test cases
├── .env                      # Local environment settings (database connections)
├── .env.example              # Sample environment template file
├── .gitignore                # Version control exclusions
├── composer.json             # PHP dependencies configuration
├── package.json              # Node.js dependencies configuration
└── README_June11.md          # Day 1 documentation (this file)
```

---

## 4. Database Configuration
The local database configuration uses MySQL. The configuration details in `.env` are:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school_enrollment_db
DB_USERNAME=root
DB_PASSWORD=
```
Make sure you have a MySQL server running and create the database named `school_enrollment_db` before running migrations:
```sql
CREATE DATABASE school_enrollment_db;
```

---

## 5. Local Setup & Execution Instructions

Follow these steps to clone and run the project locally:

### Prerequisites
- PHP >= 8.2 (PHP 8.5.1 is verified on this machine)
- Composer >= 2.2 (Composer 2.9.7 is verified on this machine)
- MySQL Server (e.g. via XAMPP, Laragon, or standalone)
- Git (Git 2.54.0 is verified on this machine)

### Step-by-Step Installation
1. **Clone the repository**:
   ```bash
   git clone <repository-url> "School Enrollment System"
   cd "School Enrollment System"
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment File**:
   Duplicate `.env.example` as `.env` (already done for this directory):
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Set up the database**:
   - Start your MySQL server.
   - Create a database named `school_enrollment_db`.

6. **Run Database Migrations**:
   Once the database is created, run the migrations to create the tables:
   ```bash
   php artisan migrate
   ```

7. **Start Development Server**:
   Run the local development server:
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://127.0.0.1:8000`.

---

## 6. Git Repository Status
- **Initial Commit (with `.gitignore`)**: Done.
- **Project Setup & Migration Commit**: Done.
- **Working Tree**: Clean.
```bash
$ git status
On branch master
nothing to commit, working tree clean
```
