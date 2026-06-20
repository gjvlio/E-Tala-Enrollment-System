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

    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('student.showDashboard') }}">
                Student Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="studentNav">
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

        <aside class="bg-white border-end p-3" style="width: 220px; flex-shrink: 0;">
            <p class="text-uppercase text-muted small fw-bold mb-3 px-2">Navigation</p>
            <nav class="nav flex-column gap-1">
                <a href="{{ route('student.showDashboard') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('student.showDashboard') ? 'bg-primary text-white' : 'text-dark' }}">
                    Dashboard
                </a>
                <a href="{{ route('student.showEnrollForm') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('student.showEnrollForm') ? 'bg-primary text-white' : 'text-dark' }}">
                    Enrollment Form
                </a>
                <a href="{{ route('student.showEnrollStatus') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('student.showEnrollStatus') ? 'bg-primary text-white' : 'text-dark' }}">
                    Enrollment Status
                </a>
                <a href="{{ route('student.showSubjects') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('student.showSubjects') ? 'bg-primary text-white' : 'text-dark' }}">
                    My Subjects
                </a>
                <a href="{{ route('student.showRecords') }}"
                   class="nav-link rounded px-3 py-2 {{ request()->routeIs('student.showRecords') ? 'bg-primary text-white' : 'text-dark' }}">
                    My Records
                </a>
            </nav>
        </aside>

        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>

    </div>

</body>
</html>
