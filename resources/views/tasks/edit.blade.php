<x-app-layout>
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }
        input[type="date"], select {
            cursor: pointer;
        }
        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 6px 12px;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
            top: 0px !important;
        }
        input[type="text"],input[type="date"]{
            border-radius: var(--bs-border-radius) !important;
            border: var(--bs-border-width) solid var(--bs-border-color);
            padding: .375rem 2.25rem .375rem .75rem;
        }
        #task-suggestions{
            padding: .375rem 2.25rem .375rem .75rem;
        }
    </style>

    <div class="container mt-4">
        <h2 class="mb-4">Edit Task / Bug</h2>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>There were some errors with your input:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')

            {{-- Client and Owner --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="client" class="form-label">Client</label>
                    <select class="form-select select2" name="client" required>
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->name }}" {{ $client->name === $task->client ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <input type="hidden" name="owner" value="{{ Auth::user()->name }}">

                <div class="col">
                    <label for="owner" class="form-label">
                        Owner <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                </div>
            </div>

            {{-- Task / Bug Name --}}
            <div class="mb-3">
                <label for="task_bug_name" class="form-label">
                    Task / Bug Name <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="task_bug_name" id="task_bug_name" value="{{ old('task_bug_name', $task->task_bug_name)}}" autocomplete="off" required>
                <div id="task-suggestions" class="list-group position-absolute container" style="z-index: 1000;"></div>
            </div>

            {{-- Priority, Start Date, End Date --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                    <select name="priority" class="form-select" required>
                        @foreach(['Low', 'Medium', 'High'] as $priority)
                            <option value="{{ $priority }}" {{ $task->priority === $priority ? 'selected' : '' }}>{{ $priority }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="start_date" value="{{ old('start_date', $task->start_date) }}" required>
                </div>
                <div class="col">
                    <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="end_date" value="{{ old('end_date', $task->end_date) }}" required>
                </div>
            </div>

            {{-- All 4 dropdowns --}}
            @php
                $statusOptions = ['New', 'In-progress', 'Completed', 'On-hold', 'NA'];
            @endphp

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Development Status <span class="text-danger">*</span></label>
                    <select name="dev_status" class="form-select" required>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" {{ $task->dev_status === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Unit Testing Status <span class="text-danger">*</span></label>
                    <select name="unit_test_status" class="form-select" required>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" {{ $task->unit_test_status === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Staging Status <span class="text-danger">*</span></label>
                    <select name="staging_status" class="form-select" required>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" {{ $task->staging_status === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label class="form-label">Production Status <span class="text-danger">*</span></label>
                    <select name="prod_status" class="form-select" required>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" {{ $task->prod_status === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Comment Field --}}
            <div class="mb-3">
                <label for="comment" class="form-label">Comment</label>
                <!-- <textarea name="comment" class="form-control" rows="4">{{ old('comment', $task->comment) }}</textarea> -->
                <input id="comment" type="hidden" name="comment" value="{{ old('comment', $task->comment) }}">
                <trix-editor input="comment" class="trix-content"  style="height: 200px; overflow-y: auto;"></trix-editor>
            </div>

            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('click', () => {
                    input.showPicker?.();
                });
            });

            $('.select2').select2({
                placeholder: "Select a client",
                allowClear: true
            });

            $('#task_bug_name').on('keyup', function () {
                let query = $(this).val();

                if (query.length >= 3) {
                    $.ajax({
                        url: '{{ route("tasks.autosuggest") }}',
                        type: 'GET',
                        data: { query: query },
                        success: function (data) {
                            let suggestions = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    suggestions += `<a href="#" class="list-group-item list-group-item-action">${item}</a>`;
                                });
                            } else {
                                suggestions = '<div class="list-group-item disabled">No match found</div>';
                            }

                            $('#task-suggestions').html(suggestions).show();
                        }
                    });
                } else {
                    $('#task-suggestions').hide();
                }
            });

            $(document).on('click', '#task-suggestions a', function (e) {
                e.preventDefault();
                $('#task_bug_name').val($(this).text());
                $('#task-suggestions').hide();
            });

            $(document).on('click', function (event) {
                if (!$(event.target).closest('#task_bug_name, #task-suggestions').length) {
                    $('#task-suggestions').hide();
                }
            });
        });
    </script>
</x-app-layout>
