<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'School Enrollment System') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">

    <div class="d-flex flex-column min-vh-100">

        {{-- Hero header --}}
        <header class="text-center py-5">
            <i class="bi bi-mortarboard-fill text-primary" style="font-size: 3rem;"></i>
            <h1 class="fw-bold mt-3 mb-1">School Enrollment System</h1>
            <p class="text-muted">Senior High School — Online Enrollment Portal</p>
        </header>

        {{-- Role selection --}}
        <main class="flex-grow-1 d-flex align-items-center">
            <div class="container">
                <p class="text-center text-uppercase text-muted small fw-bold mb-4">Choose how you want to continue</p>

                <div class="row justify-content-center g-4">
                    {{-- Student --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 text-center">
                            <div class="card-body p-4">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-4 mb-3">
                                    <i class="bi bi-person-badge text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold">I'm a Student</h4>
                                <p class="text-muted small mb-4">
                                    Register, enroll for the active semester, and track your records.
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('register') }}" class="btn btn-primary">
                                        Register / Enroll
                                    </a>
                                    <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm">
                                        Already have an account? Log in
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Registrar --}}
                    <div class="col-md-5 col-lg-4">
                        <div class="card h-100 shadow-sm border-0 text-center">
                            <div class="card-body p-4">
                                <div class="rounded-circle bg-dark bg-opacity-10 d-inline-flex p-4 mb-3">
                                    <i class="bi bi-shield-lock text-dark" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold">I'm a Registrar</h4>
                                <p class="text-muted small mb-4">
                                    Manage semesters, sections, subjects, and review enrollments.
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('login') }}" class="btn btn-dark">
                                        Registrar Log in
                                    </a>
                                    <span class="text-muted small">Accounts are created by the school admin.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="text-center text-muted small py-4">
            &copy; {{ date('Y') }} School Enrollment System
        </footer>
    </div>

</body>
</html>
