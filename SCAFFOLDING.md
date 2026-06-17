# School Enrollment System — Scaffolding Guide

## Scope
- Online enrollment form
- Section/class assignment
- Subject enrollment per student
- Approval by registrar/admin
- Student records per semester

---

## 1. Database Migrations

### Modify existing
- [x] `users` — add `role` (enum: `student`, `registrar`, `admin`), `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`
- [x] `sections` — verify has: `name`, `course`, `max_slots`, `current_slots`, `school_year`, `semester`
- [x] `subjects` — verify has: `code`, `name`, `units`, `description`

### Review (columns should be complete)
- [x] `students`
- [x] `enrollments`
- [x] `enrollment_subjects`
- [x] `semester_records`

NOTE: to be finalized by kina

---

## 2. Auth & Roles

### Package (pick one)
- [ ] **Laravel Breeze** — lightweight, Blade-based, easiest to customize
- [ ] **Laravel Fortify** — headless, required for 2FA

### 2FA (requires Fortify)
- [ ] Enable `twoFactorAuthentication` in `config/fortify.php`
- [ ] Add 2FA columns to users migration (see above)
- [ ] Build `auth/two-factor-challenge.blade.php`
- [ ] Build 2FA enable/disable UI in user settings

### Roles
Simple approach — no extra package:
- [ ] `role` column on `users` (enum: `student`, `registrar`, `admin`)
- [ ] `php artisan make:middleware CheckRole`
- [ ] Register middleware in `bootstrap/app.php`

Complex approach — use Spatie:
```bash
composer require spatie/laravel-permission
```

---

## 3. Models

- [ ] `User` — add `role`, 2FA fields; relationship to `Student`
- [ ] `Student` — `belongsTo(User)`, `hasMany(Enrollment)`, `hasMany(SemesterRecord)`
- [ ] `Section` — `hasMany(Enrollment)`
- [ ] `Subject` — `belongsToMany` via `enrollment_subjects`
- [ ] `Enrollment` — `belongsTo(Student)`, `belongsTo(Section)`, `belongsToMany(Subject)`, `belongsTo(User, 'approved_by')`
- [ ] `EnrollmentSubject` — pivot with `grade`, `status`
- [ ] `SemesterRecord` — `belongsTo(Student)`

---

## 4. Controllers

### Auth
- [X] `Auth/TwoFactorController` (only if custom — Fortify handles it otherwise)

### Student
- [X] `Student/DashboardController`
- [X] `Student/EnrollmentController` — submit form, view status
- [X] `Student/SubjectController` — view enrolled subjects + grades
- [X] `Student/RecordController` — view semester history

### Registrar
- [X] `Registrar/DashboardController`
- [X] `Registrar/EnrollmentController` — list pending, approve/reject
- [X] `Registrar/StudentController` — view/manage student records
- [X] `Registrar/SectionController` — CRUD
- [X] `Registrar/SubjectController` — CRUD
- [X] `Registrar/SemesterRecordController` — manage grades/GPA

---

## 5. Routes

Public
  [X] GET  /login
  [X] POST /login
  [X] GET  /register
  [X] POST /register
  [X] GET  /two-factor-challenge
  [X] POST /two-factor-challenge

Student  (middleware: auth, role:student)
  [X] GET  /student/dashboard
  [X] GET  /student/enroll
  [X] POST /student/enroll
  [X] GET  /student/enrollment/status
  [X] GET  /student/subjects
  [X] GET  /student/records

Registrar  (middleware: auth, role:registrar)
  [X] GET  /registrar/dashboard
  [X] GET  /registrar/enrollments
  [X] GET  /registrar/enrollments/{id}
  [X] POST /registrar/enrollments/{id}/approve
  [X] POST /registrar/enrollments/{id}/reject
  [X] GET  /registrar/students
  [X] GET  /registrar/students/{id}
  [X] CRUD /registrar/sections
  [X] CRUD /registrar/subjects
  [X] GET  /registrar/records/{student}
  [X] PUT  /registrar/records/{student}

---

## 6. Blade Views

### Auth
- [X] `auth/login.blade.php`
- [X] `auth/register.blade.php`
- [X] `auth/two-factor-challenge.blade.php`

### Layouts
- [X] `layouts/app.blade.php` — shared shell
- [X] `layouts/student.blade.php` — student nav
- [X] `layouts/registrar.blade.php` — registrar nav

### Student pages
- [X] `student/dashboard.blade.php`
- [X] `student/enroll.blade.php` — enrollment form
- [X] `student/status.blade.php` — enrollment status
- [X] `student/subjects.blade.php` — subjects + grades
- [X] `student/records.blade.php` — semester history

### Registrar pages
- [X] `registrar/dashboard.blade.php`
- [X] `registrar/enrollments/index.blade.php` — pending queue
- [X] `registrar/enrollments/show.blade.php` — approve/reject
- [X] `registrar/students/index.blade.php`
- [X] `registrar/students/show.blade.php`
- [X] `registrar/sections/index.blade.php`
- [X] `registrar/sections/form.blade.php`
- [X] `registrar/subjects/index.blade.php`
- [X] `registrar/subjects/form.blade.php`
- [X] `registrar/records/show.blade.php`

---

## 7. Build Order

1. Finalize all migrations
2. Auth scaffolding (Breeze)
3. Roles middleware + `role` column
4. Models + relationships
5. Registrar CRUD (sections, subjects)
6. Student enrollment form + submission
7. Registrar approval flow
8. Grades / semester records
9. 2FA (add last, on top of working auth)

---

## Commands Reference

```bash
# Auth scaffolding
composer require laravel/breeze --dev
php artisan breeze:install blade

# Fortify (for 2FA)
composer require laravel/fortify
php artisan fortify:install

# Generate controllers
php artisan make:controller Student/DashboardController
php artisan make:controller Student/EnrollmentController
php artisan make:controller Registrar/EnrollmentController
php artisan make:controller Registrar/SectionController --resource --model=Section
php artisan make:controller Registrar/SubjectController --resource --model=Subject

# Generate models
php artisan make:model Student
php artisan make:model Enrollment
php artisan make:model Section
php artisan make:model Subject
php artisan make:model EnrollmentSubject
php artisan make:model SemesterRecord

# Middleware
php artisan make:middleware CheckRole

# Run migrations
php artisan migrate
php artisan migrate:fresh  # wipe and redo all
```
