<x-app-layout>
    <style>
        input[type="text"]{
            border-radius: var(--bs-border-radius) !important;
            border: var(--bs-border-width) solid var(--bs-border-color);
            padding: .375rem 2.25rem .375rem .75rem;
        }
        .input-group{
            width: 95%;
        }
    </style>

    <div class="container mt-4">

        <!-- Flash Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- error messages -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="fw-bold mb-3">Add New Client</h2>
        <form action="{{ route('clients.store') }}" method="POST" class="mb-4">
            @csrf
            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label for="name" class="col-form-label">Client Name:</label>
                </div>
                <div class="col-auto">
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>

        @if($clients->isEmpty())
            <div class="alert alert-info" role="alert">
                No clients found. Please add a new client.
            </div>
        @else
        <h3 class="fw-bold">Existing Clients</h3>
            <ul class="list-group">
                @foreach($clients as $client)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="flex-grow-1">
                            <!-- Normal display -->
                            <span id="client-name-{{ $client->id }}">{{ $client->name }}</span>

                            <!-- Inline edit form (hidden by default) -->
                            <form id="edit-form-{{ $client->id }}" class="d-none" action="{{ route('clients.update', $client->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ $client->name }}" required>
                                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="toggleEdit({{ $client->id }}, false)">Cancel</button>
                                </div>
                            </form>
                        </div>

                        @if($client->user_id === auth()->id())
                            <div>
                                <button class="btn btn-sm btn-outline-primary me-2" onclick="toggleEdit({{ $client->id }}, true)">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this client?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    <script>
        function toggleEdit(id, show) {
            const nameSpan = document.getElementById(`client-name-${id}`);
            const editForm = document.getElementById(`edit-form-${id}`);

            if (show) {
                nameSpan.classList.add('d-none');
                editForm.classList.remove('d-none');
            } else {
                nameSpan.classList.remove('d-none');
                editForm.classList.add('d-none');
            }
        }
    </script>

</x-app-layout>
