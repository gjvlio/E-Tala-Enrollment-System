<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'School Enrollment System')) — Student Portal</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

    {{-- Top Navbar --}}
    <nav class="navbar navbar-expand-md navbar-dark bg-primary">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('student.showDashboard') }}">
                <i class="bi bi-mortarboard-fill" style="font-size:1.1rem;"></i>
                Student Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#studentNav"
                    aria-controls="studentNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="studentNav">
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
            <p class="sidebar-label">My Menu</p>
            <nav class="nav flex-column gap-1">
                <a href="{{ route('student.showDashboard') }}"
                   class="nav-link {{ request()->routeIs('student.showDashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i> Dashboard
                </a>
                <a href="{{ route('student.showEnrollForm') }}"
                   class="nav-link {{ request()->routeIs('student.showEnrollForm') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square"></i> Enrollment Form
                </a>
                <a href="{{ route('student.showEnrollStatus') }}"
                   class="nav-link {{ request()->routeIs('student.showEnrollStatus') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-check"></i> Enrollment Status
                </a>
                <a href="{{ route('student.showSection') }}"
                   class="nav-link {{ request()->routeIs('student.showSection') ? 'active' : '' }}">
                    <i class="bi bi-collection"></i> My Section
                </a>
                <a href="{{ route('student.showSubjects') }}"
                   class="nav-link {{ request()->routeIs('student.showSubjects') ? 'active' : '' }}">
                    <i class="bi bi-book-fill"></i> My Subjects
                </a>
                <a href="{{ route('student.showRecords') }}"
                   class="nav-link {{ request()->routeIs('student.showRecords') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line-fill"></i> My Records
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
