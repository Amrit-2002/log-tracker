<x-app-layout>
    <div class="mx-4 mt-4">

        <style>
            input[type="text"],input[type="date"]{
                border-radius: var(--bs-border-radius) !important;
                border: var(--bs-border-width) solid var(--bs-border-color);
                padding: .375rem 2.25rem .375rem .75rem;
            }
            input[type="date"],select {
                cursor: pointer;
            }
        </style>

        <!-- Title & New Task Button -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">📝 Task / Bug List</h2>
            <a href="{{ route('tasks.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-lg"></i> New Task
            </a>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- No Tasks Message -->
        @if($tasks->isEmpty())
            <div class="alert alert-info">
                No tasks available. Click <strong>"New Task"</strong> to add one.
            </div>
        @else

            <!-- Filter & Export -->
            <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end mb-3">
                <div class="col-md">
                    <input type="text" name="client" class="form-control" placeholder="Filter by Client" value="{{ request('client') }}">
                </div>
                <div class="col-md">
                    <input type="text" name="owner" class="form-control" placeholder="Filter by Owner" value="{{ request('owner') }}">
                </div>
                <div class="col-md">
                    <select name="priority" class="form-select">
                        <option value="">Priority</option>
                        @foreach(['Low', 'Medium', 'High'] as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                {{ $priority }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-funnel-fill"></i> Filter
                    </button>
                </div>
                <div class="col-md-auto">
                    <a href="{{ route('tasks.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Clear Filters
                    </a>
                </div>
                <div class="col-md-auto">
                    <button type="submit" class="btn btn-outline-success w-100" name="action" value="export">
                        <i class="bi bi-download"></i> Export CSV
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Task/Bug Name</th>
                            <th>Owner</th>
                            <th>Priority</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Dev Status</th>
                            <th>Unit Testing</th>
                            <th>Staging</th>
                            <th>Production</th>
                            <th>Comment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                            <tr>
                                <td>{{ $task->client }}</td>
                                <td class="text-start">{{ $task->task_bug_name }}</td>
                                <td>{{ $task->owner }}</td>
                                <td>
                                    <span class="badge 
                                        {{ $task->priority === 'High' ? 'bg-danger' : ($task->priority === 'Medium' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                        {{ $task->priority }}
                                    </span>
                                </td>
                                <td>{{ $task->start_date }}</td>
                                <td>{{ $task->end_date }}</td>
                                <td>{{ $task->dev_status }}</td>
                                <td>{{ $task->unit_test_status }}</td>
                                <td>{{ $task->staging_status }}</td>
                                <td>{{ $task->prod_status }}</td>
                                <td class="text-start">{!! $task->comment !!}</td>
                                <td>
                                    <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('click', () => {
                    input.showPicker?.();
                });
            });
        });
    </script>
</x-app-layout>









