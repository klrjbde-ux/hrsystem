@extends('master')
@section('css')
<style>
    #sidebar {
        width: 70px;
        height: 100vh;
        overflow-y: auto;
        /* ✅ enable vertical scroll */
        overflow-x: hidden;
        /* ✅ prevent horizontal scroll */
        transition: width 0.3s ease;
    }

    /* Smooth scrollbar (optional but clean UI) */
    #sidebar::-webkit-scrollbar {
        width: 5px;
    }

    #sidebar::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }

    #sidebar .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Hide text when collapsed */
    #sidebar .nav-link span,
    #sidebar .nav-content a span {
        opacity: 0;
        visibility: hidden;
        transition: 0.2s;
    }

    /* Center icons */
    #sidebar .nav-link i {
        font-size: 18px;
        min-width: 25px;
        text-align: center;
    }

    #sidebar:hover {
        width: 250px;
    }

    /* Show text on hover */
    #sidebar:hover .nav-link span,
    #sidebar:hover .nav-content a span {
        opacity: 1;
        visibility: visible;
    }

    /* Chevron fix */
    #sidebar .bi-chevron-down.ms-auto {
        opacity: 0;
    }

    #sidebar:hover .bi-chevron-down.ms-auto {
        opacity: 1;
    }


    #main {
        transition: margin-left 0.3s ease;
    }

    #sidebar .nav-content {
        padding-left: 10px;
    }
</style>
@endsection
@section('content')
<main id="main" class="main">
    <div class="container">
        <h3 class="mb-4 shadow-sm p-3 rounded bg-white text-center">{{ $task->title }} - Task Details</h3>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="card-title">{{ $task->title }}</h5>
                                <p class="card-text">{{ $task->description }}</p>
                                <p class="card-text"><strong>Created on:</strong> {{ $task->created_at->format('Y-m-d') }}</p>
                                <p class="card-text"><strong>Due Date:</strong> {{ $task->due_date }}</p>
                                <p class="card-text"><strong>Priority:</strong> <span
                                        class="badge {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">{{ ucfirst($task->priority) }}</span>
                                </p>
                                <p class="card-text"><strong>Status:</strong>
                                    @if ($task->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                    @elseif($task->status == 'to_do')
                                    <span class="badge bg-primary">To Do</span>
                                    @elseif($task->status == 'in_progress')
                                    <span class="badge bg-warning">In Progress</span>
                                    @elseif($task->status == 'qa')
                                    <span class="badge bg-info">QA</span>
                                    @elseif($task->status == 'qa_passed')
                                    <span class="badge bg-success">QA Passed</span>
                                    @elseif($task->status == 'qa_failed')
                                    <span class="badge bg-danger">QA Failed</span>
                                    @endif
                                </p>

                                <p class="card-text"><strong>Assign To:</strong> {{ $task->user->name }}</p>
                                @hasanyrole('admin|hr_manager')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editTaskModal"> <i class="bi bi-pencil-square"></i> </button>
                                @endhasanyrole
                                <a href="{{ route('projects.tasks.index', $task->project->id) }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-90deg-left"></i> </a>
                            </div>

                            @if(auth()->id() == $task->user_id || auth()->user()->hasRole('admin'))

                            @endif

                            <div class="col-md-6 border-start">
                                <h5>Flow Time (by Column)</h5>
                                <table class="table table-sm">
                                    <tbody>
                                        <tr>
                                            <th style="width: 160px;">To Do</th>
                                            <td>{{ $durations['to_do'] ?? '00:00:00' }}</td>
                                        </tr>
                                        <tr>
                                            <th>In Progress</th>
                                            <td>{{ $durations['in_progress'] ?? '00:00:00' }}</td>
                                        </tr>
                                        <tr>
                                            <th>QA</th>
                                            <td>{{ $durations['qa'] ?? '00:00:00' }}</td>
                                        </tr>
                                        <tr>
                                            <th>QA Passed</th>
                                            <td>{{ $durations['qa_passed'] ?? '00:00:00' }}</td>
                                        </tr>
                                        <tr>
                                            <th>QA Failed</th>
                                            <td>{{ $durations['qa_failed'] ?? '00:00:00' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Completed</th>
                                            <td>{{ $durations['completed'] ?? '00:00:00' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="small text-muted">
                                    Developer time (In Progress): <strong>{{ $summary['developer_time'] ?? '00:00:00' }}</strong><br>
                                    QA time (QA): <strong>{{ $summary['qa_time'] ?? '00:00:00' }}</strong><br>
                                    QA failed cycles: <strong>{{ $summary['cycles'] ?? 0 }}</strong>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Checklist Modal -->
        <div class="modal fade" id="addChecklistModal" tabindex="-1" aria-labelledby="addChecklistModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="add-checklist-form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addChecklistModalLabel">Add Checklist Item</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="checklist-name" class="form-label">Item Name</label>
                                <input type="text" name="name" id="checklist-name" class="form-control" required>
                                <div class="invalid-feedback" id="checklist-name-error"></div>
                            </div>
                            <input type="hidden" name="task_id" id="task_id" value="{{ $task->id }}">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" aria-labelledby="editTaskModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ $task->title }}" required>
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control">{{ $task->description }}</textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" name="due_date" id="due_date" class="form-control"
                                    value="{{ $task->due_date }}">
                                @error('due_date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select name="priority" id="priority" class="form-select" required>
                                    <option value="low" {{ $task->priority == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ $task->priority == 'medium' ? 'selected' : '' }}>Medium
                                    </option>
                                    <option value="high" {{ $task->priority == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                                @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Assign Member</label>
                                <select name="user_id" id="user_id" class="form-select" required>
                                    @foreach($task->project->users as $user)
                                    <option value="{{ $user->id }}" {{ $task->user_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        let timer;
        let seconds = 0;
        let isRunning = false;

        function formatTime(sec) {
            let hours = Math.floor(sec / 3600);
            let minutes = Math.floor((sec % 3600) / 60);
            let seconds = sec % 60;

            return `${hours.toString().padStart(2,'0')}:${minutes.toString().padStart(2,'0')}:${seconds.toString().padStart(2,'0')}`;
        }

        function updateTimeDisplay() {
            document.getElementById('time-display').innerText = formatTime(seconds);
        }

        document.getElementById('start-btn').addEventListener('click', () => {
            if (!isRunning) {
                isRunning = true;
                timer = setInterval(() => {
                    seconds++;
                    updateTimeDisplay();
                }, 1000);
            }
        });

        document.getElementById('pause-btn').addEventListener('click', () => {
            if (isRunning) {
                isRunning = false;
                clearInterval(timer);
            }
        });

        document.getElementById('reset-btn').addEventListener('click', () => {
            isRunning = false;
            clearInterval(timer);
            seconds = 0;
            updateTimeDisplay();
        });

        updateTimeDisplay();



        /* checklist toggle */

        function toggleChecklistItem(itemId) {
            fetch(`/checklist-items/${itemId}/update-status`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const checkbox = document.getElementById(`checklist-item-checkbox-${itemId}`);
                        const label = checkbox.closest('.form-check').querySelector('.form-check-label');

                        label.classList.toggle('text-decoration-line-through', checkbox.checked);
                    }
                })
                .catch(err => console.error(err));
        }

        document.querySelectorAll('[id^="edit-checklist-form-"]').forEach(form => {
            form.addEventListener("submit", function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const action = this.action;
                const itemId = this.id.split('-').pop();

                fetch(action, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {

                        if (!data.success) {
                            alert("Update failed");
                            return;
                        }

                        //  Update UI without reload
                        const label = document.querySelector(`#checklist-item-${itemId} .form-check-label`);
                        label.innerText = formData.get('name');

                        // Close modal
                        const modalEl = document.getElementById(`editChecklistModal-${itemId}`);
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                    })
                    .catch(err => console.error(err));
            });
        });

        /* delete */

        function deleteChecklistItem(itemId) {
            const form = document.getElementById(`delete-checklist-form-${itemId}`);
            const formData = new FormData(form);

            fetch(form.action, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`checklist-item-${itemId}`).remove();
                    }
                })
                .catch(err => console.error(err));
        }



        /* add checklist item */

        document.getElementById("add-checklist-form").addEventListener("submit", function(e) {

            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch("{{ route('checklist-items.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                })

                .then(res => res.json())

                .then(item => {

                    if (!item.success) {
                        alert("Item not added");
                        return;
                    }

                    const li = document.createElement("li");

                    li.className = "list-group-item d-flex justify-content-between align-items-center";

                    li.id = `checklist-item-${item.id}`;

                    li.innerHTML = `

        <div class="form-check">
            <input class="form-check-input" type="checkbox"
                id="checklist-item-checkbox-${item.id}"
                onchange="toggleChecklistItem(${item.id})">

            <label class="form-check-label">${item.name}</label>
        </div>

        <div>

            <button class="btn btn-primary btn-sm">
                <i class="bi bi-pencil-square"></i>
            </button>

            <button class="btn btn-danger btn-sm"
                onclick="deleteChecklistItem(${item.id})">
                <i class="bi bi-trash"></i>
            </button>

        </div>

        <form id="delete-checklist-form-${item.id}"
            action="/checklist-items/${item.id}"
            method="POST" style="display:none">

            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">

        </form>
        `;

                    document.getElementById("checklist-items").appendChild(li);

                    form.reset();

                    const modal = bootstrap.Modal.getInstance(document.getElementById("addChecklistModal"));
                    modal.hide();

                })

                .catch(err => {
                    console.log(err);
                });

        });
    </script>
</main>
@endsection