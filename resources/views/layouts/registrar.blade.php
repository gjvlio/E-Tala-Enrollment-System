<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School Enrollment System')) — Registrar</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('registrar.showDashboard') }}">
                <i class="bi bi-shield-lock-fill" style="font-size:1.1rem;"></i>
                Registrar Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#registrarNav"
                    aria-controls="registrarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="registrarNav">
                <ul class="navbar-nav ms-auto align-items-md-center gap-1">
                    <li class="nav-item">
                        <span class="nav-link text-white-50 d-flex align-items-center gap-1">
                            <i class="bi bi-person-circle"></i>
                            {{ Auth::user()?->name ?? 'Guest' }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">
                            <i class="bi bi-gear me-1"></i> Profile
                        </a>
                    </li>
                    <li class="nav-item ms-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">

        {{-- Sidebar --}}
        <aside class="sidebar d-none d-md-block">
            <p class="sidebar-label">Navigation</p>
            <nav class="nav flex-column gap-1">
                <a href="{{ route('registrar.showDashboard') }}"
                   class="nav-link {{ request()->routeIs('registrar.showDashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="{{ route('registrar.semester.index') }}"
                   class="nav-link {{ request()->routeIs('registrar.semester.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar3"></i> Semesters
                </a>
                <a href="{{ route('registrar.showEnrollments') }}"
                   class="nav-link {{ request()->routeIs('registrar.showEnrollments') || request()->routeIs('registrar.showEnrollment') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-check"></i> Enrollments
                </a>
                <a href="{{ route('registrar.showStudents') }}"
                   class="nav-link {{ request()->routeIs('registrar.showStudents') || request()->routeIs('registrar.showStudent') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Students
                </a>
                <a href="{{ route('registrar.sections.showSections') }}"
                   class="nav-link {{ request()->routeIs('registrar.sections.*') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i> Sections
                </a>
                <a href="{{ route('registrar.subjects.showSubjects') }}"
                   class="nav-link {{ request()->routeIs('registrar.subjects.*') ? 'active' : '' }}">
                    <i class="bi bi-book-fill"></i> Subjects
                </a>
            </nav>
        </aside>

        {{-- Main content --}}
        <div class="flex-grow-1 p-3 p-md-4" style="min-height: calc(100vh - 56px); overflow-x: auto;">
            @yield('content')
        </div>

    </div>

</body>
</html>
