-- ============================================
-- School Enrollment System
-- Database Schema
-- Updated to match Laravel migrations
-- ============================================

CREATE DATABASE IF NOT EXISTS school_enrollment_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE school_enrollment_db;

-- ============================================
-- 1. USERS
-- Base authentication table.
-- role determines access level.
-- ============================================
CREATE TABLE users (
  id                        BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                      VARCHAR(255) NOT NULL,
  email                     VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at         TIMESTAMP NULL,
  password                  VARCHAR(255) NOT NULL,
  role                      ENUM('student', 'registrar', 'admin') NOT NULL DEFAULT 'student',
  two_factor_secret         TEXT NULL,
  two_factor_recovery_codes TEXT NULL,
  two_factor_confirmed_at   TIMESTAMP NULL,
  remember_token            VARCHAR(100) NULL,
  created_at                TIMESTAMP NULL,
  updated_at                TIMESTAMP NULL
);

-- ============================================
-- 2. PASSWORD RESET TOKENS
-- Laravel default.
-- ============================================
CREATE TABLE password_reset_tokens (
  email      VARCHAR(255) PRIMARY KEY,
  token      VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NULL
);

-- ============================================
-- 3. SESSIONS
-- Laravel default.
-- ============================================
CREATE TABLE sessions (
  id            VARCHAR(255) PRIMARY KEY,
  user_id       BIGINT UNSIGNED NULL,
  ip_address    VARCHAR(45) NULL,
  user_agent    TEXT NULL,
  payload       LONGTEXT NOT NULL,
  last_activity INT NOT NULL,
  INDEX sessions_user_id_index (user_id),
  INDEX sessions_last_activity_index (last_activity)
);

-- ============================================
-- 4. SEMESTERS
-- Tracks each semester. Only one is_active = TRUE at a time.
-- ============================================
CREATE TABLE semesters (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_year VARCHAR(20) NOT NULL,
  semester    ENUM('1st', '2nd', 'summer') NOT NULL,
  is_active   TINYINT(1) NOT NULL DEFAULT 0,
  created_at  TIMESTAMP NULL,
  updated_at  TIMESTAMP NULL
);

-- ============================================
-- 5. REGISTRARS
-- Profile table for users with role = 'registrar'.
-- One user = one registrar.
-- ============================================
CREATE TABLE registrars (
  id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id    BIGINT UNSIGNED NOT NULL UNIQUE,
  first_name VARCHAR(100) NOT NULL,
  last_name  VARCHAR(100) NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- 6. STUDENTS
-- Profile table for users with role = 'student'.
-- One user = one student.
-- ============================================
CREATE TABLE students (
  id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id        BIGINT UNSIGNED NOT NULL UNIQUE,
  student_number VARCHAR(50) NOT NULL UNIQUE,
  first_name     VARCHAR(100) NOT NULL,
  last_name      VARCHAR(100) NOT NULL,
  phone          VARCHAR(20) NULL,
  birthdate      DATE NULL,
  address        TEXT NULL,
  created_at     TIMESTAMP NULL,
  updated_at     TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================
-- 7. SUBJECTS
-- Master list of subjects available for enrollment.
-- ============================================
CREATE TABLE subjects (
  id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  subject_code VARCHAR(50) NOT NULL UNIQUE,
  subject_name VARCHAR(150) NOT NULL,
  description  TEXT NULL,
  units        INT NOT NULL,
  created_at   TIMESTAMP NULL,
  updated_at   TIMESTAMP NULL
);

-- ============================================
-- 8. SECTIONS
-- A class group offered in a specific semester.
-- ============================================
CREATE TABLE sections (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  semester_id   BIGINT UNSIGNED NOT NULL,
  section_name  VARCHAR(50) NOT NULL,
  year_level    VARCHAR(20) NOT NULL,
  course        VARCHAR(255) NULL,
  advisor_name  VARCHAR(255) NULL,
  max_slots     INT NOT NULL DEFAULT 40,
  current_slots INT NOT NULL DEFAULT 0,
  created_at    TIMESTAMP NULL,
  updated_at    TIMESTAMP NULL,
  FOREIGN KEY (semester_id) REFERENCES semesters(id) ON DELETE RESTRICT
);

-- ============================================
-- 9. ENROLLMENTS
-- Core enrollment record per student per semester.
-- status defaults to 'pending' until registrar acts on it.
-- ============================================
CREATE TABLE enrollments (
  id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id  BIGINT UNSIGNED NOT NULL,
  semester_id BIGINT UNSIGNED NOT NULL,
  section_id  BIGINT UNSIGNED NOT NULL,
  status      ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  approved_by BIGINT UNSIGNED NULL,
  approved_at TIMESTAMP NULL,
  created_at  TIMESTAMP NULL,
  updated_at  TIMESTAMP NULL,
  FOREIGN KEY (student_id)  REFERENCES students(id)   ON DELETE RESTRICT,
  FOREIGN KEY (semester_id) REFERENCES semesters(id)  ON DELETE RESTRICT,
  FOREIGN KEY (section_id)  REFERENCES sections(id)   ON DELETE RESTRICT,
  FOREIGN KEY (approved_by) REFERENCES registrars(id) ON DELETE SET NULL
);

-- ============================================
-- 10. ENROLLMENT_SUBJECTS
-- Junction table: subjects a student enrolled in per enrollment.
-- ============================================
CREATE TABLE enrollment_subjects (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  enrollment_id BIGINT UNSIGNED NOT NULL,
  subject_id    BIGINT UNSIGNED NOT NULL,
  grade         DECIMAL(3, 2) NULL,
  status        ENUM('enrolled', 'passed', 'failed', 'dropped') NOT NULL DEFAULT 'enrolled',
  created_at    TIMESTAMP NULL,
  updated_at    TIMESTAMP NULL,
  UNIQUE KEY enrollment_subjects_unique (enrollment_id, subject_id),
  FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
  FOREIGN KEY (subject_id)    REFERENCES subjects(id)    ON DELETE RESTRICT
);

-- ============================================
-- 11. SEMESTER_RECORDS
-- GPA and status summary per student per semester.
-- ============================================
CREATE TABLE semester_records (
  id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  student_id    BIGINT UNSIGNED NOT NULL,
  academic_year VARCHAR(255) NOT NULL,
  semester      VARCHAR(255) NOT NULL,
  gpa           DECIMAL(3, 2) NULL,
  status        ENUM('active', 'completed', 'dropped') NOT NULL DEFAULT 'active',
  remarks       TEXT NULL,
  created_at    TIMESTAMP NULL,
  updated_at    TIMESTAMP NULL,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);
