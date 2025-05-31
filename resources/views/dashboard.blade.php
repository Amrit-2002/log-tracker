<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


    <main class="flex-grow d-flex flex-column align-items-center justify-content-center px-4 mt-5">
        <div class="bg-white p-4 rounded-4 shadow w-100" style="max-width: 28rem; text-align: center; margin-bottom: 2rem;">
            <h2 class="h3 fw-bold mb-3">
                Welcome to <span class="text-danger">Log-Tracker</span>
            </h2>
            <p class="text-muted mb-4">
                Manage your daily tasks, bugs, and progress with ease.
            </p>

            <a href="{{ url('/tasks') }}" class="btn btn-danger px-4 py-2 shadow">
                Go to Task/Bug Details
            </a>
        </div>

        <section class="w-100" style="max-width: 72rem; padding: 0 1.5rem;">
            <h2 class="h3 fw-semibold text-center mb-5">Features</h2>
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="bg-white rounded shadow p-4">
                        <h3 class="h5 fw-semibold mb-2">Task Tracking</h3>
                        <p class="text-muted">Easily log, prioritize, and manage your daily tasks.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded shadow p-4">
                        <h3 class="h5 fw-semibold mb-2">Bug Management</h3>
                        <p class="text-muted">Track bugs and assign them with custom priorities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white rounded shadow p-4">
                        <h3 class="h5 fw-semibold mb-2">Excel Export</h3>
                        <p class="text-muted">Export your logs with a single click for reporting.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-app-layout>
