<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log-Tracker</title>

    <!-- Bootstrap 5 CSS & Figtree font -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100 text-dark">

    <!-- Header -->
    <header class="bg-white shadow py-3 px-4 d-flex justify-content-between align-items-center">
        <h1 class="h5 fw-bold text-danger mb-0">Log-Tracker</h1>
        <nav class="d-flex gap-3">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-dark fw-semibold text-decoration-none">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-dark fw-semibold text-decoration-none">Login</a>
                <a href="{{ route('register') }}" class="text-dark fw-semibold text-decoration-none">Register</a>
            @endauth
        </nav>
    </header>

    <!-- Main Content -->
    <main class="flex-grow-1 d-flex flex-column align-items-center justify-content-center px-3">

        <!-- Center Card -->
        <div class="bg-white p-4 rounded-4 shadow w-100 mb-5 text-center" style="max-width: 28rem;">
            <h2 class="h3 fw-bold mb-3">Welcome to <span class="text-danger">Log-Tracker</span></h2>
            <p class="text-muted mb-4">Manage your daily tasks, bugs, and progress with ease.</p>

            @auth
                <a href="{{ url('/dashboard') }}" class="btn btn-danger px-4 py-2 shadow-sm">
                    Go to Dashboard
                </a>
            @else
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('login') }}" class="btn btn-dark px-4 py-2 shadow-sm">
                        Log In
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-danger px-4 py-2 shadow-sm">
                        Register
                    </a>
                </div>
            @endauth
        </div>

        <!-- Features Section -->
        <section class="w-100" style="max-width: 72rem;">
            <h2 class="h3 fw-semibold text-center mb-5">Features</h2>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="bg-white rounded shadow p-4 h-100">
                        <h3 class="h5 fw-semibold mb-2">Task Tracking</h3>
                        <p class="text-muted">Easily log, prioritize, and manage your daily tasks.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded shadow p-4 h-100">
                        <h3 class="h5 fw-semibold mb-2">Bug Management</h3>
                        <p class="text-muted">Track bugs and assign them with custom priorities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded shadow p-4 h-100">
                        <h3 class="h5 fw-semibold mb-2">Excel Export</h3>
                        <p class="text-muted">Export your logs with a single click for reporting.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-white border-top py-3 text-center text-muted small mt-auto">
        &copy; {{ date('Y') }} Log-Tracker. All rights reserved.
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
