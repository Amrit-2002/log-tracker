<x-app-layout>

    <!-- Custom Styling -->
    <style>
        .tabulator .tabulator-cell {
            vertical-align: middle !important;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: unset !important;
            word-break: break-word;
            padding: 8px 12px;
            font-size: 0.95rem;
        }
        .tabulator .tabulator-row:nth-child(even) {
            background-color:rgb(238, 239, 241);;
        }
        .tabulator .tabulator-header {
            background: #f3f4f6;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .tabulator .tabulator-footer {
            background: #e5e7eb;
            border-top: 1px solid #ddd;
        }
        input[type="date"],select {
            cursor: pointer;
        }
        input[type="text"],input[type="date"]{
            border-radius: var(--bs-border-radius) !important;
            border: var(--bs-border-width) solid var(--bs-border-color);
            padding: .375rem 2.25rem .375rem .75rem;
        }
        .tabulator .tabulator-footer .tabulator-page-size{
            padding: 2px 25px !important;
            border-radius: 7px !important;
        }
    </style>

    <div class="mx-4 mt-4">

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

        <!-- No Tasks Message -->
        @if($tasks->isEmpty())
            <div class="alert alert-info">
                No tasks available. Click <strong>"New Task"</strong> to add one.
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">📝 Task / Bug List </h2>

            <div class="d-flex gap-2">
                <a href="{{ route('tasks.create') }}" class="btn btn-success shadow-sm">
                    <i class="bi bi-plus-lg"></i> New Task
                </a>
                @if(!$tasks->isEmpty())
                    <a class="btn btn-outline-success">
                        <button id="download-xlsx">Download XLSX</button>
                    </a>
                @endif
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <!-- Filter By -->
                    <div class="col-md-2">
                        <label for="filter-field" class="form-label">Filter By</label>
                        <select id="filter-field" class="form-select">
                            <option></option>
                            <option value="client">Client</option>
                            <option value="task_bug_name">Task/Bug Name</option>
                            <option value="owner">Owner</option>
                            <option value="priority">Priority</option>
                            <option value="dev_status">Development Status</option>
                            <option value="unit_test_status">Unit Testing Status</option>
                            <option value="staging_status">Staging Status</option>
                            <option value="prod_status">Production Status</option>
                        </select>
                    </div>

                    <!-- Condition -->
                    <div class="col-md-1">
                        <label for="filter-type" class="form-label">Condition</label>
                        <select id="filter-type" class="form-select">
                            <option value="like">like</option>
                            <option value="=">=</option>
                            {{-- <option value="<"><</option>
                            <option value="<="><=</option>
                            <option value=">">></option>
                            <option value=">=">>=</option> --}}
                            <option value="!=">!=</option>
                        </select>
                    </div>

                    <!-- Value -->
                    <div class="col-md-4">
                        <label for="filter-value" class="form-label">Value</label>
                        <input id="filter-value" type="text" class="form-control" placeholder="Value to filter">
                    </div>

                    <!-- Date Range -->
                    <div class="col-md-2">
                        <label for="start-date" class="form-label">Start Date</label>
                        <input id="start-date" type="date" class="form-control">
                    </div>

                    <div class="col-md-2">
                        <label for="end-date" class="form-label">End Date</label>
                        <input id="end-date" type="date" class="form-control">
                    </div>

                    <!-- Clear Filter Button -->
                    <div class="col-md-1">
                        <label class="form-label invisible">Clear</label>
                        <button id="filter-clear" class="btn btn-secondary w-100">Clear</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="tabulator-container">
            <div id="task-table"></div>
        </div>
    </div>

    <!-- Tabulator CSS -->
    <link href="https://unpkg.com/tabulator-tables@5.5.0/dist/css/tabulator.min.css" rel="stylesheet">

    <!-- Tabulator XLSX Module -->
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

    <!-- Tabulator JS -->
    <script src="https://unpkg.com/tabulator-tables@5.5.0/dist/js/tabulator.min.js"></script>

</head>

    <script>
        const loggedInUserId = {{ auth()->id() }};

        var table;
        document.addEventListener("DOMContentLoaded", function () {

            // to set default date for current week
            const today = new Date();
            const day = today.getDay(); // Sunday - Saturday : 0 - 6

            // Calculate Monday
            const monday = new Date(today);
            monday.setDate(today.getDate() - ((day + 6) % 7));

            // Calculate Saturday
            const saturday = new Date(monday);
            saturday.setDate(monday.getDate() + 5);

            // Format to yyyy-mm-dd
            const formatDate = (date) => date.toISOString().split('T')[0];

            document.getElementById("start-date").value = formatDate(monday);
            document.getElementById("end-date").value = formatDate(saturday);


            table = new Tabulator("#task-table", {
                layout: "fitColumns",
                responsiveLayout: true,
                autoResize: true,
                ajaxURL: "{{ route('tasks.json') }}",
                pagination: true,
                pagination:true,
                paginationSize: 5,
                paginationSizeSelector:[10, 25, 50, 100, true],
                columns: [
                    { title: "Client", field: "client" },
                    { title: "Task/Bug Name", field: "task_bug_name"},
                    { title: "Owner", field: "owner" },
                    { title: "Priority", field: "priority" },
                    { title: "Start Date", field: "start_date" },
                    { title: "End Date", field: "end_date" },
                    { title: "Dev status", field: "dev_status" },
                    { title: "Unit Testing", field: "unit_test_status" },
                    { title: "Staging", field: "staging_status" },
                    { title: "Production", field: "prod_status" },
                    {
                        title: "Comment", field: "comment",
                        formatter: "html",
                        accessorDownload: function(cellValue, rowData, type){
                            // Strip HTML using browser parser
                            let div = document.createElement("div");
                            div.innerHTML = cellValue;
                            return div.textContent || div.innerText || "";
                        },
                        widthGrow: 2, headerSort: false, tooltip: true
                    },
                    {
                        title: "Actions", field: "id", hozAlign: "center",
                        formatter: function(cell) {
                            const id = cell.getValue();
                            const rowData = cell.getRow().getData();
                            if (rowData.user_id === loggedInUserId) {
                                return `
                                    <a href="/tasks/edit/${id}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger delete-btn" data-id="${id}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                `;
                            }
                        },
                        download: false, width: 120, headerSort: false
                    },
                ],
            });

            table.on("dataProcessed", function(){
                applyDateRangeFilter();
            });

            //trigger download of data.xlsx file
            document.getElementById("download-xlsx").addEventListener("click", function(){
                table.download("xlsx", "data.xlsx", {
                    sheetName:"My Data"
                });
            });

            var fieldEl = document.getElementById("filter-field");
            var typeEl = document.getElementById("filter-type");
            var valueEl = document.getElementById("filter-value");
            var startDateEl = document.getElementById("start-date");
            var endDateEl = document.getElementById("end-date");

            //Trigger setFilter function with correct parameters
            function updateFilter(){
                var filterVal = fieldEl.options[fieldEl.selectedIndex].value;
                var typeVal = typeEl.options[typeEl.selectedIndex].value;

                var filter = filterVal == "function" ? customFilter : filterVal;

                if(filterVal == "function" ){
                    typeEl.disabled = true;
                    valueEl.disabled = true;
                }else{
                    typeEl.disabled = false;
                    valueEl.disabled = false;
                }

                if(filterVal){
                    table.setFilter(filter,typeVal, valueEl.value);
                }
            }

            // Trigger setFilter function for start and end date
            function applyDateRangeFilter() {
                const start = document.getElementById("start-date").value;
                const end = document.getElementById("end-date").value;

                // Clear previous filters
                table.clearFilter(true);

                const filters = [];

                if (start) {
                    filters.push({ field: "start_date", type: ">=", value: start });
                }

                if (end) {
                    filters.push({ field: "end_date", type: "<=", value: end });
                }

                if (filters.length > 0) {
                    table.setFilter(filters);
                }
            }

            //Update filters on value change
            document.getElementById("filter-field").addEventListener("change", updateFilter);
            document.getElementById("filter-type").addEventListener("change", updateFilter);
            document.getElementById("filter-value").addEventListener("keyup", updateFilter);
            document.getElementById("start-date").addEventListener("change", applyDateRangeFilter);
            document.getElementById("end-date").addEventListener("change", applyDateRangeFilter);

            //Clear filters on "Clear Filters" button click
            document.getElementById("filter-clear").addEventListener("click", function(){
                fieldEl.value = "";
                typeEl.value = "=";
                valueEl.value = "";
                startDateEl.value = "";
                endDateEl.value = "";

                table.clearFilter();
            });


            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.addEventListener('click', () => {
                    input.showPicker?.();
                });
            });


        });

        document.addEventListener("click", function(e) {
            if (e.target.closest(".delete-btn")) {
                const btn = e.target.closest(".delete-btn");
                const taskId = btn.getAttribute("data-id");

                if (confirm("Are you sure you want to delete this task?")) {
                    fetch(`/tasks/delete/${taskId}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                            "Accept": "application/json"
                        },
                        credentials: "same-origin"
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert("Task deleted");
                            table.replaceData();
                        } else {
                            alert(data.message || "Failed to delete");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Something went wrong.");
                    });
                }
            }
        });
    </script>
</x-app-layout>
