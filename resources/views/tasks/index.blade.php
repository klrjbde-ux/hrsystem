@extends('master')
@section('css')
<style>
    #sidebar {
        width: 70px;
        transition: width 0.3s ease;
        overflow: hidden;
        height: 100vh;
    }

    #sidebar:hover {
        width: 300px;
        overflow-y: auto;
    }

    #main,
    #footer {
        margin-left: 0px;
    }

    #sidebar .sidebar-nav .nav-link span {
        display: none;
        white-space: nowrap;
    }

    #sidebar .nav-content a span {
        display: none;
    }

    #sidebar:hover {
        width: 300px;
    }

    .sidebar:hover~#main,
    .sidebar:hover~#footer {
        margin-left: 300px;
    }

    #sidebar:hover .sidebar-nav .nav-link span,
    #sidebar:hover .nav-content a span {
        display: inline;
    }

    #sidebar .bi-chevron-down.ms-auto {
        display: none;
    }

    #sidebar:hover .bi-chevron-down.ms-auto {
        display: inline-block;
    }

    .chat-message {
        max-width: max-content;
        padding: 10px 14px;
        border-radius: 15px;
        margin-bottom: 8px;
        display: inline-block;
        position: relative;
    }

    .comment-text-content {
        display: inline;
        font-size: 14px;
        line-height: 1.4;
    }

    .edited-badge {
        font-size: 11px;
        opacity: 0.7;
        vertical-align: middle;
    }

    .history-container {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .chat-left {
        background: #f1f1f1;
        text-align: left;

        font-weight: 400;
    }

    .chat-right {
        background: #34eb71;
        color: white;
        font-weight: 500;
        margin-left: auto;
        text-align: right;
    }

    .chat-wrapper {
        display: flex;
        flex-direction: column;
    }

    /* Action buttons */
    .comment-actions {
        position: absolute;
        top: -8px;
        right: -8px;
        display: none;
    }

    .chat-message:hover .comment-actions {
        display: flex;
        gap: 5px;
    }

    .comment-actions button {
        border: none;
        padding: 4px 6px;
        border-radius: 6px;
        font-size: 12px;
    }

    .edit-btn {
        background: #ffc107;
        color: black;
    }

    .delete-btn {
        background: #dc3545;
        color: white;
    }

    .edit-btn:hover {
        background: #e0a800;
    }

    .delete-btn:hover {
        background: #bb2d3b;
    }

    #commentsBody {
        max-height: 60vh;
        /* adjust as needed */
        overflow-y: auto;
        padding-right: 10px;
    }
</style>
@endsection
@section('content')
<style>
    .kanban-column {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        height: 80vh;
        display: flex;
        flex-direction: column;
    }

    .kanban-list {
        flex: 1;
        overflow-y: auto;
        background-color: #e9ecef;
        border-radius: 5px;
        padding: 10px;
    }

    .kanban-item {
        cursor: move;
    }

    .kanban-item.invisible {
        opacity: 0.4;
    }
</style>
<main id="main" class="main">
    <div class="container">
        <div class="bg-white align-items-center mb-4 shadow-sm p-3 rounded">
            <h3 class="text-center">{{ $project->name }} - Tasks</h3>
        </div>
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body d-flex flex-wrap align-items-center gap-3">

                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-funnel fs-5 text-primary"></i>
                    <strong>Filter Tasks</strong>
                </div>

                {{-- User Filter --}}
                <select id="userFilter" class="form-select form-select-sm" style="width: 180px;">
                    <option value="">All Members</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                {{-- Priority Filter --}}
                <select id="priorityFilter" class="form-select form-select-sm" style="width: 180px;">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

            </div>
        </div>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="row">

            {{-- TO DO --}}
            <div class="col-md-3">
                <div class="kanban-column">
                    <div
                        class="d-flex justify-content-between shadow-sm align-items-center bg-primary px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">To Do</h4>
                        @hasanyrole('admin|hr_manager')
                        <button type="button" class="btn btn-light" data-bs-toggle="modal"
                            data-bs-target="#createTaskModal" data-status="to_do" style="padding: 6px 12px;">
                            +
                        </button>
                        @endhasanyrole
                    </div>

                    <div class="kanban-list" id="to_do">
                        @foreach ($tasks['to_do'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" data-user="{{ $task->user_id }}"
                            data-priority="{{ $task->priority }}"
                            data-department="{{ strtolower($task->user->employee->department) }}"
                            draggable="{{ (auth()->id() == $task->user_id || auth()->user()->hasAnyRole('sqa|qa')) ? 'true' : 'false' }}">
                            <div class="card-body">

                                <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px; "
                                        class="badge text-white {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </h5>

                                <p class="mb-2 text-muted">
                                    <strong>Assigned To :</strong> {{ $task->user->name }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Description :</strong> {{ $task->description }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Department :</strong> {{ $task->user->employee->department }}
                                </p>

                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-dark btn-sm commentBtn" data-id="{{ $task->id }}"
                                    data-bs-toggle="modal" data-bs-target="#commentsModal">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button
                                    class="btn btn-secondary btn-sm trackHistoryBtn"
                                    data-id="{{ $task->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#historyModal">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @hasanyrole('admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- IN PROGRESS --}}
            <div class="col-md-3">
                <div class="kanban-column">
                    <div
                        class="d-flex justify-content-between shadow-sm align-items-center bg-warning px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">In Progress</h4>
                    </div>

                    <div class="kanban-list" id="in_progress">
                        @foreach ($tasks['in_progress'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" data-user="{{ $task->user_id }}"
                            data-priority="{{ $task->priority }}"
                            data-department="{{ strtolower($task->user->employee->department) }}"
                            draggable="{{ (auth()->id() == $task->user_id || auth()->user()->hasAnyRole('sqa|qa')) ? 'true' : 'false' }}">
                            <div class="card-body">

                                <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px; "
                                        class="badge text-white {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </h5>

                                <p class="mb-2 text-muted">
                                    <strong>Assigned To :</strong> {{ $task->user->name }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Description :</strong> {{ $task->description }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Department :</strong> {{ $task->user->employee->department }}
                                </p>

                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-dark btn-sm commentBtn" data-id="{{ $task->id }}"
                                    data-bs-toggle="modal" data-bs-target="#commentsModal">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button
                                    class="btn btn-secondary btn-sm trackHistoryBtn"
                                    data-id="{{ $task->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#historyModal">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @hasanyrole('admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- COMPLETED --}}
            <div class="col-md-3">
                <div class="kanban-column">
                    <div
                        class="d-flex justify-content-between shadow-sm align-items-center bg-success px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">Completed</h4>
                    </div>

                    <div class="kanban-list" id="completed">
                        @foreach ($tasks['completed'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" data-user="{{ $task->user_id }}"
                            data-priority="{{ $task->priority }}"
                            data-department="{{ strtolower($task->user->employee->department) }}"
                            draggable="{{ (auth()->id() == $task->user_id || auth()->user()->hasAnyRole('sqa|qa')) ? 'true' : 'false' }}">
                            <div class="card-body">

                                <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px; "
                                        class="badge text-white {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </h5>

                                <p class="mb-2 text-muted">
                                    <strong>Assigned To :</strong> {{ $task->user->name }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Description :</strong> {{ $task->description }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Department :</strong> {{ $task->user->employee->department }}
                                </p>

                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-dark btn-sm commentBtn" data-id="{{ $task->id }}"
                                    data-bs-toggle="modal" data-bs-target="#commentsModal">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button
                                    class="btn btn-secondary btn-sm trackHistoryBtn"
                                    data-id="{{ $task->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#historyModal">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @hasanyrole('admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- QA --}}
            <div class="col-md-3">
                <div class="kanban-column">
                    <div
                        class="d-flex justify-content-between shadow-sm align-items-center bg-info px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">QA</h4>
                    </div>

                    <div class="kanban-list" id="qa">
                        @foreach ($tasks['qa'] ?? [] as $task)
                        <div class="card mb-3 kanban-item" data-id="{{ $task->id }}" data-user="{{ $task->user_id }}"
                            data-priority="{{ $task->priority }}"
                            data-department="{{ strtolower($task->user->employee->department) }}"
                            draggable="{{ (auth()->id() == $task->user_id || auth()->user()->hasAnyRole('sqa|qa')) ? 'true' : 'false' }}">
                            <div class="card-body">

                                <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px; "
                                        class="badge text-white {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </h5>

                                <p class="mb-2 text-muted">
                                    <strong>Assigned To :</strong> {{ $task->user->name }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Description :</strong> {{ $task->description }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Department :</strong> {{ $task->user->employee->department }}
                                </p>

                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-dark btn-sm commentBtn" data-id="{{ $task->id }}"
                                    data-bs-toggle="modal" data-bs-target="#commentsModal">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button
                                    class="btn btn-secondary btn-sm trackHistoryBtn"
                                    data-id="{{ $task->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#historyModal">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @hasanyrole('admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- QA Passed --}}
            <div class="col-md-3">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between shadow-sm align-items-center bg-success px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">QA Passed</h4>
                    </div>

                    <div class="kanban-list" id="qa_passed">
                        @foreach ($tasks['qa_passed'] ?? [] as $task)
                        <div class="card mb-3 kanban-item"
                            data-id="{{ $task->id }}"
                            data-user="{{ $task->user_id }}"
                            data-priority="{{ $task->priority }}"
                            data-department="{{ strtolower($task->user->employee->department) }}"
                            draggable="{{ (auth()->id() == $task->user_id || auth()->user()->hasAnyRole('sqa|qa')) ? 'true' : 'false' }}">

                            <div class="card-body">
                                <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px; "
                                        class="badge text-white {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </h5>

                                <p class="mb-2 text-muted">
                                    <strong>Assigned To :</strong> {{ $task->user->name }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Description :</strong> {{ $task->description }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Department :</strong> {{ $task->user->employee->department }}
                                </p>

                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-dark btn-sm commentBtn" data-id="{{ $task->id }}"
                                    data-bs-toggle="modal" data-bs-target="#commentsModal">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button
                                    class="btn btn-secondary btn-sm trackHistoryBtn"
                                    data-id="{{ $task->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#historyModal">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @hasanyrole('admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- QA Failed --}}
            <div class="col-md-3">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between shadow-sm align-items-center bg-danger px-3 py-2 rounded-top">
                        <h4 class="text-white fw-bolder m-0">QA Failed</h4>
                    </div>

                    <div class="kanban-list" id="qa_failed">
                        @foreach ($tasks['qa_failed'] ?? [] as $task)
                        <div class="card mb-3 kanban-item"
                            data-id="{{ $task->id }}"
                            data-user="{{ $task->user_id }}"
                            data-priority="{{ $task->priority }}"
                            data-department="{{ strtolower($task->user->employee->department) }}"
                            draggable="{{ (auth()->id() == $task->user_id || auth()->user()->hasAnyRole('sqa|qa')) ? 'true' : 'false' }}">

                            <div class="card-body">
                                <h5 class="card-title">{{ $task->title }}
                                    <span style="font-size: 12px; "
                                        class="badge text-white {{ $task->priority == 'low' ? 'bg-success' : ($task->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </h5>

                                <p class="mb-2 text-muted">
                                    <strong>Assigned To :</strong> {{ $task->user->name }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Description :</strong> {{ $task->description }}
                                </p>

                                <p class="mb-2 text-muted">
                                    <strong>Department :</strong> {{ $task->user->employee->department }}
                                </p>

                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-danger btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button class="btn btn-dark btn-sm commentBtn" data-id="{{ $task->id }}"
                                    data-bs-toggle="modal" data-bs-target="#commentsModal">
                                    <i class="bi bi-chat-dots"></i>
                                </button>
                                <button
                                    class="btn btn-secondary btn-sm trackHistoryBtn"
                                    data-id="{{ $task->id }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#historyModal">
                                    <i class="bi bi-clock-history"></i>
                                </button>
                                @hasanyrole('admin')
                                <form action="{{ route('tasks.destroy', $task->id) }}"
                                    method="POST"
                                    class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')

                                    <button type="button" class="btn btn-danger btn-sm delete-btn">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endhasanyrole
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- CREATE TASK MODAL --}}
            <div class="modal fade" id="createTaskModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('projects.tasks.store', $project->id) }}" method="POST">
                            @csrf

                            <div class="modal-header">
                                <h5 class="modal-title">Create Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Priority</label>
                                    <select name="priority" class="form-select" required>
                                        <option value="high">High</option>

                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Assign To</label>
                                    <select name="user_id" class="form-select">
                                        <!-- <option value="{{ auth()->user()->id }}">Self</option> -->
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="status" id="task_status">

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create Task</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            {{-- COMMENTS MODAL --}}
            <div class="modal fade" id="commentsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Task Comments</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body" id="commentsBody">
                            {{-- Comments will load here dynamically via JS --}}
                        </div>

                        <div class="modal-footer flex-column">

                            <div id="replyPreview" class="w-100 mb-2"></div>

                            <div class="w-100">



                                <!-- Comment + Send -->
                                <div id="imagePreviewContainer" class="d-flex gap-2 flex-wrap mb-2"></div>

                                <div class="d-flex w-100 align-items-center gap-2">

                                    <!-- Attachment -->
                                    <label class="btn btn-light mb-0" style="margin:auto;">
                                        <i class="bi bi-paperclip"></i>
                                        <input type="file" id="commentAttachment" multiple accept="image/png,image/jpeg" hidden>
                                    </label>

                                    <small class="text-muted">Only JPG, PNG</small>

                                    <input type="text" id="newComment" class="form-control" placeholder="Write a comment...">

                                    <button type="button" id="sendComment" class="btn btn-primary">Send</button>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="imageViewModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-3">

                        <div id="imageContainer" style="max-height:70vh;overflow-y:auto;"></div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="confirmDeleteImageModal" tabindex="-1" style="z-index:999999;">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4 text-center">

                        <div class="mb-3">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size:40px;"></i>
                        </div>

                        <h5>Delete Image?</h5>
                        <p class="text-muted small">This action cannot be undone</p>

                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button id="confirmDeleteImageBtn" class="btn btn-danger">Delete</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </section>

    </div>
</main>
{{-- HISTORY MODAL --}}
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Task History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="historyBody">
                {{-- History will load dynamically via JS --}}
                Loading...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const historyButtons = document.querySelectorAll('.trackHistoryBtn');
        const historyBody = document.getElementById('historyBody');

        historyButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const taskId = btn.getAttribute('data-id');
                loadHistory(taskId);
            });
        });

        function loadHistory(taskId) {
            historyBody.innerHTML = 'Loading...';

            fetch(`/tasks/${taskId}/history`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        historyBody.innerHTML = '<p>No history yet.</p>';
                        return;
                    }

                    let html = `<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Previous Status</th>
                            <th>New Status</th>
                            <th>Assigned To</th>
                            <th>Changed By</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>`;

                    data.forEach((item, index) => {
                        html += `<tr>
                        <td>${index + 1}</td>
                        <td>${formatStatus(item.old_status)}</td>
<td>${formatStatus(item.new_status)}</td>
                        <td>${item.assigned_to}</td>
                        <td>${item.changed_by}</td>
                        <td>${item.created_at}</td>
                    </tr>`;
                    });

                    html += `</tbody></table>`;
                    historyBody.innerHTML = html;
                })
                .catch(err => {
                    historyBody.innerHTML = '<p class="text-danger">Failed to load history.</p>';
                    console.error(err);
                });
        }

    });

    function formatStatus(text) {
        if (!text) return '';

        return text
            .toLowerCase()
            .replace(/_/g, ' ') // convert _ to space
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }


    document.addEventListener('DOMContentLoaded', (event) => {
        const kanbanItems = document.querySelectorAll('.kanban-item');
        const kanbanLists = document.querySelectorAll('.kanban-list');
        const createTaskModal = document.getElementById('createTaskModal');
        const taskStatusInput = document.getElementById('task_status');
        const showToast = (message, icon = 'warning') => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 2200,
                timerProgressBar: true
            });
        };

        if (createTaskModal) {
            createTaskModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const status = button.getAttribute('data-status');
                taskStatusInput.value = status;
            });
        }

        kanbanItems.forEach(item => {
            item.addEventListener('dragstart', handleDragStart);
            item.addEventListener('dragend', handleDragEnd);
        });

        kanbanLists.forEach(list => {
            list.addEventListener('dragover', handleDragOver);
            list.addEventListener('drop', handleDrop);
        });

        function handleDragStart(e) {
            e.dataTransfer.setData('text/plain', e.target.dataset.id);
            setTimeout(() => {
                e.target.classList.add('invisible');
            }, 0);
        }

        function handleDragEnd(e) {
            e.target.classList.remove('invisible');
        }

        function handleDragOver(e) {
            e.preventDefault();
        }

        function handleDrop(e) {
            e.preventDefault();

            const id = e.dataTransfer.getData('text');
            const draggableElement = document.querySelector(`.kanban-item[data-id='${id}']`);
            const dropzone = e.target.closest('.kanban-list');

            if (!dropzone) return;

            const newStatus = dropzone.id;
            const assignedUserId = draggableElement.getAttribute('data-user');
            const currentUserId = "{{ auth()->id() }}";
            const currentUserIsSQA = @json(auth()->user()->hasAnyRole('sqa|qa'));
            const currentColumn = draggableElement.parentElement.id;

            // ----------------------------
            // Define allowed moves visually
            // ----------------------------
            const allowedMoves = {
                to_do: ['in_progress'],
                in_progress: ['completed', 'qa'],
                completed: ['in_progress', 'qa'],
                qa: ['completed', 'in_progress'],
                qa_passed: [],
                qa_failed: ['in_progress'],
            };

            // SQA overrides
            if (currentUserIsSQA) {
                allowedMoves['to_do'] = [];
                allowedMoves['in_progress'] = [];
                allowedMoves['completed'] = [];
                allowedMoves['qa'] = ['qa_passed', 'qa_failed'];
                allowedMoves['qa_passed'] = ['qa', 'qa_failed'];
                allowedMoves['qa_failed'] = ['qa', 'qa_passed'];
            }

            // ----------------------------
            // Permission check
            // ----------------------------
            const isAssignedUser = assignedUserId == currentUserId;
            const isSQA = currentUserIsSQA;

            // Normal users can move their own tasksd
            if (!isAssignedUser && !isSQA) {
                showToast("Only assigned user or SQA can move this task.");
                return;
            }

            // Only SQA can move tasks out of QAd
            if (currentColumn === 'qa' && !isSQA) {
                showToast("Only SQA can move tasks from QA.");
                return;
            }
            // Check if the move is allowed
            if (!allowedMoves[currentColumn] || !allowedMoves[currentColumn].includes(newStatus)) {
                showToast("You are not allowed to move this task here.");
                return;
            }
            // Move the card visually
            draggableElement.parentNode.removeChild(draggableElement);
            dropzone.appendChild(draggableElement);

            // Update status in DB
            updateTaskStatus(id, newStatus);
        }

        function updateTaskStatus(id, status) {
            fetch(`/tasks/${id}/update-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: status
                    })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Failed to update task status');
                    return response.json();
                })
                .then(data => console.log('Task status updated:', data))
                .catch(error => console.error('Error:', error));
        }

        // -----------------------------
        // COMMENTS MODAL FUNCTIONALITY
        // -----------------------------
        const commentButtons = document.querySelectorAll('.commentBtn');
        const commentsBody = document.getElementById('commentsBody');
        const sendCommentBtn = document.getElementById('sendComment');
        const newCommentInput = document.getElementById('newComment');
        let currentTaskId = null;

        // Only attach if elements exist
        if (commentButtons.length && commentsBody && sendCommentBtn && newCommentInput) {

            commentButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    currentTaskId = btn.getAttribute('data-id');
                    newCommentInput.value = '';
                    loadComments(currentTaskId);
                });
            });

            sendCommentBtn.addEventListener('click', () => {

                const commentText = newCommentInput.value.trim();
                const files = attachmentInput.files ? Array.from(attachmentInput.files) : [];

                if (!commentText && files.length === 0) {
                    alert("Write something or attach image");
                    return;
                }

                const formData = new FormData();
                formData.append('comment', commentText);

                if (typeof replyToCommentId !== "undefined" && replyToCommentId) {
                    formData.append('parent_comment_id', replyToCommentId);
                }

                files.forEach(file => {
                    formData.append('attachments[]', file);
                });

                fetch(`/tasks/${currentTaskId}/comments`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {

                        newCommentInput.value = '';
                        attachmentInput.value = '';
                        document.getElementById('imagePreviewContainer').innerHTML = '';
                        document.getElementById('replyPreview').innerHTML = '';
                        replyToCommentId = null;

                        loadComments(currentTaskId);
                    })
                    .catch(err => {
                        console.error(err);
                        alert("Failed to send comment");
                    });
            });

            function loadComments(taskId) {

                commentsBody.innerHTML = 'Loading...';

                fetch(`/tasks/${taskId}/comments`)
                    .then(res => res.json())
                    .then(data => {

                        console.log('API Response:', data); // 👈 debug

                        if (!data.success || !data.comments || data.comments.length === 0) {
                            commentsBody.innerHTML = '<p>No comments yet.</p>';
                            return;
                        }

                        const comments = data.comments;

                        commentsBody.innerHTML = '';

                        commentsBody.innerHTML = '';
                        const currentUserId = "{{ auth()->id() }}";

                        comments.reverse().forEach(comment => {

                            const wrapper = document.createElement('div');
                            wrapper.classList.add('chat-wrapper');

                            const commentDiv = document.createElement('div');

                            // Reply preview
                            let replyHtml = '';

                            if (comment.parent_comment) {
                                replyHtml = `
                        <div class="small text-danger fw-bold border-start ps-2 mb-1">
                            Reply: ${comment.parent_comment}
                        </div>
                                `;
                            }
                            let imageHtml = '';

                            if (comment.attachment && comment.attachment.length > 0) {
                                const imgCount = comment.attachment.length;

                                imageHtml = `
    <button class="btn btn-sm btn-light viewAttachment "
        data-images='${encodeURIComponent(JSON.stringify(comment.attachment))}'
        data-comment-id="${comment.id}">
        <i class="bi bi-eye"></i> ${imgCount}
    </button>
`;
                            }
                            let actionButtons = `
                                <div class="comment-actions">

                                 <button class="replyComment btn btn-sm btn-info" data-id="${comment.id}" data-user="${comment.user_name}"data-text="${encodeURIComponent(comment.comment || '')}"><i class="bi bi-reply"></i></button>`;

                            if (comment.user_id == currentUserId) {
                                actionButtons += `<button class="editComment edit-btn" data-id="${comment.id}"><i class="bi bi-pencil"></i></button>

                                 <button class="deleteComment delete-btn"data-id="${comment.id}"><i class="bi bi-trash"></i></button>`;
                            }
                            actionButtons += `</div>`;

                            // Right side (current user)
                            // Inside loadComments(), where you create commentDiv.innerHTML
                            commentDiv.classList.add('chat-message', comment.user_id == currentUserId ? 'chat-right' : 'chat-left');
                            commentDiv.innerHTML = `<small>${comment.user_name}</small>${replyHtml}${comment.comment ? `<div class="commentText mt-1">${comment.comment}</div>` : ''}${imageHtml}${comment.is_edited ? `<button class="btn btn-sm btn-link p-0 edited-comment" data-comment-id="${comment.id}">(edited)</button>` : ''}${actionButtons}`;
                            wrapper.appendChild(commentDiv);
                            commentsBody.appendChild(wrapper);

                        });
                        const tooltipTriggerList = [].slice.call(document.querySelectorAll('.edited-comment'));
                        tooltipTriggerList.map(function(tooltipTriggerEl) {
                            return new bootstrap.Tooltip(tooltipTriggerEl);
                        });

                    })
                    .catch(err => {

                        commentsBody.innerHTML = '<p class="text-danger">Failed to load comments.</p>';
                        console.error(err);

                    });

            }

        }
        // EDIT COMMENT
        // EDIT COMMENT
        document.getElementById('confirmDeleteImageBtn').addEventListener('click', function() {

            fetch(`/comments/${deleteImageData.commentId}/delete-image`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        index: deleteImageData.index
                    })
                })
                .then(res => res.json())
                .then(() => {

                    bootstrap.Modal.getInstance(
                        document.getElementById('confirmDeleteImageModal')
                    ).hide();

                    if (deleteImageData.card) {
                        deleteImageData.card.remove();
                    }

                    // ✅ CHECK IF NO IMAGE LEFT → CLEAN UI (FIX LINE ISSUE ALSO)
                    const container = document.getElementById('imageContainer');

                    if (container.children.length === 0) {
                        container.innerHTML = '<p class="text-center text-muted">No images</p>';
                    }

                    // ALSO refresh comments silently (optional but safe)
                    loadComments(currentTaskId);
                });
        });
        document.addEventListener('click', function(e) {



            const deleteBtn = e.target.closest('.deleteImageBtn');
            if (deleteBtn) {

                deleteImageData = {
                    index: deleteBtn.dataset.index,
                    commentId: deleteBtn.dataset.commentId,
                    card: deleteBtn.closest('.card')
                };

                new bootstrap.Modal(document.getElementById('confirmDeleteImageModal')).show();
            }

            const viewBtn = e.target.closest('.viewAttachment');
            if (viewBtn) {

                const images = JSON.parse(decodeURIComponent(viewBtn.dataset.images));
                const commentId = viewBtn.dataset.commentId;

                const container = document.getElementById('imageContainer');
                container.innerHTML = '';

                // ✅ ADD THIS BLOCK HERE
                if (!images || images.length === 0) {
                    container.innerHTML = '<p class="text-center text-muted">No images</p>';
                    new bootstrap.Modal(document.getElementById('imageViewModal')).show();
                    return;
                }

                images.forEach((img, index) => {

                    container.innerHTML += `
  <div class="card p-2 mb-3" style="min-width:200px;position:relative;border:none;">
        <img src="${img}" style="width:100%;max-height:400px;object-fit:contain;border-radius:8px;">
        
      <button class="btn btn-danger deleteImageBtn"
    data-index="${index}"
    data-comment-id="${commentId}"
    style="
        position:absolute;
        bottom:-13px;
        left:50%;
        transform:translateX(-50%);
        padding:6px 14px;
        font-size:12px;
        font-weight:600;
        border-radius:8px;
        display:flex;
        align-items:center;
        gap:6px;
    ">
    <i class="bi bi-trash"></i> Delete
</button>
    </div>
`;
                });

                new bootstrap.Modal(document.getElementById('imageViewModal')).show();
            }

            const editBtn = e.target.closest('.editComment');
            if (!editBtn) return;

            const commentDiv = editBtn.closest('.chat-message');
            const commentTextSpan = commentDiv.querySelector('.commentText');
            const commentId = editBtn.dataset.id;

            // Prevent multiple edits at once
            if (commentDiv.querySelector('input')) return;

            const oldText = commentTextSpan.textContent;

            // Replace span with input
            const input = document.createElement('input');
            input.type = 'text';
            input.value = oldText;
            input.classList.add('form-control', 'form-control-sm', 'mb-1');

            commentDiv.insertBefore(input, commentTextSpan);
            commentTextSpan.style.display = 'none';
            input.focus();

            // Add Save button
            const saveBtn = document.createElement('button');
            saveBtn.textContent = 'Save';
            saveBtn.classList.add('btn', 'btn-sm', 'btn-success', 'me-1');

            // Add Cancel button
            const cancelBtn = document.createElement('button');
            cancelBtn.textContent = 'Cancel';
            cancelBtn.classList.add('btn', 'btn-sm', 'btn-secondary');


            const editAttachmentInput = document.createElement('input');
            editAttachmentInput.type = 'file';
            editAttachmentInput.multiple = true;
            editAttachmentInput.accept = "image/png,image/jpeg";
            editAttachmentInput.style.display = 'none';

            const attachLabel = document.createElement('label');
            attachLabel.classList.add('btn', 'btn-sm', 'btn-light', 'me-1');
            attachLabel.innerHTML = `<i class="bi bi-paperclip" style="margin:0;"></i>`;
            attachLabel.appendChild(editAttachmentInput);

            const actionsContainer = document.createElement('div');
            actionsContainer.classList.add('mt-1');

            actionsContainer.appendChild(attachLabel);
            actionsContainer.appendChild(saveBtn);
            actionsContainer.appendChild(cancelBtn);

            actionsContainer.style.display = 'flex';
            actionsContainer.style.alignItems = 'center';
            actionsContainer.style.gap = '6px';
            actionsContainer.style.width = '100%';

            commentDiv.appendChild(actionsContainer);

            // Save edited comment
            saveBtn.addEventListener('click', () => {

                const newText = input.value.trim();
                if (!newText) return;

                const formData = new FormData();
                formData.append('comment', newText);

                // Attach new images if any
                Array.from(editAttachmentInput.files).forEach(file => {
                    formData.append('attachments[]', file);
                });

                fetch(`/comments/${commentId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(res => {
                        console.log('Response object:', res); // 👈 raw response
                        console.log('Status:', res.status); // 👈 status code
                        return res.json();
                    })
                    .then(data => {
                        console.log('Response data:', data); // 👈 actual backend response

                        if (data.success) {
                            console.log('Comment updated successfully ✅');
                            loadComments(currentTaskId);
                        } else {
                            console.warn('Backend returned failure:', data);
                            alert('Failed to save comment.');
                        }
                    })
                    .catch(err => {
                        console.error('Fetch error:', err); // 👈 network / JS error
                        alert('Error saving comment.');
                    });

            });
            // Cancel editing
            cancelBtn.addEventListener('click', () => {
                commentTextSpan.style.display = 'inline';
                input.remove();
                actionsContainer.remove();
            });

        });
        // DELETE COMMENT
        document.addEventListener('click', function(e) {

            const deleteBtn = e.target.closest('.deleteComment');
            if (!deleteBtn) return;

            const commentId = deleteBtn.dataset.id;

            if (!confirm("Delete this comment?")) return;

            fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    loadComments(currentTaskId);
                });

        });
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.show-history');
            if (!btn) return;

            const commentId = btn.dataset.commentId;

            fetch(`/comments/${commentId}/history`)
                .then(res => res.json())
                .then(data => {
                    if (!data.history || data.history.length === 0) {
                        alert('No previous versions found.');
                        return;
                    }



                    // Show history in a modal or alert
                    let historyHtml = data.history.map((h, i) => `<b>Version ${i + 1}:</b> ${h}`).join('<br><hr>');

                    // Use Bootstrap modal dynamically
                    const historyModalHtml = `
                <div class="modal fade" id="historyModal" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Comment History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">${historyHtml}</div>
                    </div>
                  </div>
                </div> `;

                    // Append modal to body
                    let tempDiv = document.createElement('div');
                    tempDiv.innerHTML = historyModalHtml;
                    document.body.appendChild(tempDiv);

                    // Show modal
                    const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
                    historyModal.show();

                    // Remove modal from DOM after hide
                    document.getElementById('historyModal').addEventListener('hidden.bs.modal', function() {
                        tempDiv.remove();
                    });
                })
                .catch(err => console.error(err));
        });
        // -----------------------------
        // REPLY COMMENT
        // -----------------------------

        let replyToCommentId = null;

        document.addEventListener('click', function(e) {

            // ✅ REMOVE PREVIEW IMAGE (FIXED)
            const removePreview = e.target.closest('.removePreviewImg');
            if (removePreview) {
                const index = parseInt(removePreview.dataset.index);

                let files = Array.from(attachmentInput.files);

                files.splice(index, 1);

                const dt = new DataTransfer();
                files.forEach(file => dt.items.add(file));

                attachmentInput.files = dt.files;

                // re-render preview
                document.getElementById('imagePreviewContainer').innerHTML = '';

                Array.from(dt.files).forEach((file, i) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.style.position = 'relative';

                        div.innerHTML = `
                <img src="${e.target.result}" style="width:70px;height:70px;object-fit:cover;border-radius:8px;">
                <button class="removePreviewImg btn btn-danger btn-sm"
                    data-index="${i}"
                    style="position:absolute;top:-8px;right:-8px;border-radius:50%;">×</button>
            `;
                        document.getElementById('imagePreviewContainer').appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });

                return;
            }
            const replyBtn = e.target.closest('.replyComment');
            if (!replyBtn) return;

            replyToCommentId = replyBtn.dataset.id;

            const user = replyBtn.dataset.user;
            const text = decodeURIComponent(replyBtn.dataset.text);

            const replyPreview = document.getElementById('replyPreview');

            replyPreview.innerHTML = `<div class="alert alert-light border p-2">Replying to <b>${user}</b>: ${text}
            <button class="btn btn-sm btn-danger float-end" id="cancelReply
        ">x</button>
             </div> `;
        });

        document.addEventListener('click', function(e) {

            if (e.target.id === 'cancelReply') {
                replyToCommentId = null;
                document.getElementById('replyPreview').innerHTML = '';
            }

        });

        document.addEventListener('click', function(e) {
            const editedBtn = e.target.closest('.edited-comment, .show-history');
            if (!editedBtn) return;

            const commentId = editedBtn.dataset.commentId;

            fetch(`/comments/${commentId}/history`)
                .then(res => res.json())
                .then(data => {
                    if (!data.history || data.history.length === 0) {
                        alert('No previous versions found.');
                        return;
                    }

                    // Current comment div
                    const commentDiv = editedBtn.closest('.chat-message');

                    // Remove existing history if already open (toggle)
                    const existingHistory = commentDiv.querySelector('.history-container');
                    if (existingHistory) {
                        existingHistory.remove();
                        return;
                    }

                    const historyContainer = document.createElement('div');
                    historyContainer.classList.add('history-container', 'border-start', 'ps-2', 'mt-2', 'small', 'text-muted');

                    data.history.forEach((h, i) => {
                        const versionDiv = document.createElement('div');
                        versionDiv.innerHTML = `<b>Version ${i + 1}:</b> ${h}`;
                        historyContainer.appendChild(versionDiv);
                    });

                    commentDiv.appendChild(historyContainer);
                })
                .catch(err => console.error(err));
        });

        const userFilter = document.getElementById('userFilter');
        const priorityFilter = document.getElementById('priorityFilter');

        function applyFilters() {
            const selectedUser = userFilter.value;
            const selectedPriority = priorityFilter.value;

            const allTasks = document.querySelectorAll('.kanban-item');

            allTasks.forEach(task => {
                const taskUser = task.getAttribute('data-user');
                const taskPriority = task.getAttribute('data-priority');

                let show = true;

                // User filter
                if (selectedUser && taskUser !== selectedUser) {
                    show = false;
                }

                // Priority filter
                if (selectedPriority && taskPriority !== selectedPriority) {
                    show = false;
                }

                task.style.display = show ? 'block' : 'none';
            });
        }

        // Event listeners
        if (userFilter) {
            userFilter.addEventListener('change', applyFilters);
        }

        if (priorityFilter) {
            priorityFilter.addEventListener('change', applyFilters);
        }
        const attachmentInput = document.getElementById('commentAttachment');
        let deleteImageData = {};
        // PREVIEW IMAGES
        attachmentInput.addEventListener('change', function() {

            const preview = document.getElementById('imagePreviewContainer');
            preview.innerHTML = '';

            Array.from(this.files).forEach((file, index) => {

                const reader = new FileReader();

                reader.onload = function(e) {

                    const div = document.createElement('div');
                    div.style.position = 'relative';

                    div.innerHTML = `
                <img src="${e.target.result}" style="width:70px;height:70px;object-fit:cover;border-radius:8px;">
                <button class="removePreviewImg btn btn-danger btn-sm"
                    data-index="${index}"
                    style="position:absolute;top:-8px;right:-8px;border-radius:50%;">×</button>
            `;

                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            });
        });
        const historyButtons = document.querySelectorAll('.trackHistoryBtn');
        const historyBody = document.getElementById('historyBody');

        historyButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const taskId = btn.getAttribute('data-id');
                loadHistory(taskId);
            });
        });

        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-btn');
            if (!deleteBtn) return;

            const form = deleteBtn.closest('.delete-form');

            Swal.fire({
                title: 'Are you sure?',
                text: "This task will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });



    });
</script>