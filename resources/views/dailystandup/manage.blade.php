@extends('master')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/dailystandup-manage.css') }}">
@endsection

@section('content')
<main id="main" class="main">
    <div class="container-fluid">
        <section class="section d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Daily Standup – Manage Meetings (Editable)</h5>
                                    <div class="d-inline-flex">
                                        <button id="addNewRowBtn" class="btn btn-success btn-sm btn-add-row d-flex align-items-center">
                                            Add Row
                                        </button>

                                        <a href="{{ route('dailystandup.index') }}"
                                            class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                                            Meeting List
                                        </a>
                                    </div>
                                </div>
                                <p class="text-muted small mb-3">
                                    Click any cell (Date, Employee, Status, Remarks) to edit. Change then click outside to save — a popup will confirm.<br>
                                    Click "Add New Row" button to add a new meeting directly in the table.
                                </p>

                                <div class="table-responsive">
                                    <table id="meetings-table"
                                           class="table table-striped"
                                           style="width:100%"
                                           data-ajax-url="{{ route('dailystandup.data') }}"
                                           data-store-url="{{ route('dailystandup.storeAjax') }}"
                                           data-update-url="{{ route('dailystandup.updateAjax') }}"
                                           data-delete-url="{{ route('dailystandup.deleteAjax') }}"
                                           data-employees-list="{{ json_encode($employees->map(fn($e) => ['id' => $e->id, 'name' => trim($e->firstname . ' ' . $e->lastname)])->values()) }}">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Emp Name</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

{{-- Delete confirm popup --}}
<div class="modal fade" id="deleteMeetingModal" tabindex="-1" aria-labelledby="deleteMeetingModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMeetingModalLabel">Delete Meeting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this meeting? This cannot be undone.</p>
                <input type="hidden" id="delete_meeting_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteMeetingConfirm">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // Define employees list as a global variable
        window.employeesList = @json($employees->map(fn($e) => [
            'id' => $e->id,
            'name' => trim($e->firstname . ' ' . $e->lastname)
        ])->values());
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/dailystandup-manage.js') }}"></script>
@endsection
