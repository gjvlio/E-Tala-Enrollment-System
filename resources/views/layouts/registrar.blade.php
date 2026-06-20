<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School Enrollment System'))</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('registrar.showDashboard') }}">
                Registrar Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#registrarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="registrarNav">
                <ul class="navbar-nav ms-auto align-items-md-center gap-2">
                    <li class="nav-item">
                        <span class="nav-link text-white">{{ Auth::user()?->name ?? 'Guest' }}</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex" style="min-height: calc(100vh - 56px);">

        <aside class="bg-white border-end p-3" style="width: 210px; flex-shrink: 0;">
            <p class="text-uppercase text-muted small fw-bold mb-3 px-2">Navigation</p>
            <nav class="nav flex-column gap-1">
                <a href="{{ route('registrar.showDashboard') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('registrar.showDashboard') ? 'bg-dark text-white' : 'text-dark' }}">
                    Dashboard
                </a>
                <a href="{{ route('registrar.semester.index') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('registrar.semester.*') ? 'bg-dark text-white' : 'text-dark' }}">
                    Semesters
                </a>
                <a href="{{ route('registrar.showEnrollments') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('registrar.showEnrollments') || request()->routeIs('registrar.showEnrollment') ? 'bg-dark text-white' : 'text-dark' }}">
                    Enrollments
                </a>
                <a href="{{ route('registrar.showStudents') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('registrar.showStudents') || request()->routeIs('registrar.showStudent') ? 'bg-dark text-white' : 'text-dark' }}">
                    Students
                </a>
                <a href="{{ route('registrar.sections.showSections') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('registrar.sections.*') ? 'bg-dark text-white' : 'text-dark' }}">
                    Sections
                </a>
                <a href="{{ route('registrar.subjects.showSubjects') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('registrar.subjects.*') ? 'bg-dark text-white' : 'text-dark' }}">
                    Subjects
                </a>
            </nav>
        </aside>

        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>

    </div>

</body>
</html>
