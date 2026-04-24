@extends('master')

@section('content')

<main id="main" class="main">

    ```
    <div class="pagetitle">
        <h1>Approve Leaves</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Leaves</li>
                <li class="breadcrumb-item active">Approve Leaves</li>
            </ol>
        </nav>
    </div>

    <section class="section">

        @if(session('success'))
        <div class="alert alert-success" id="alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if(session('danger'))
        <div class="alert alert-danger" id="alert-success">
            {{ session('danger') }}
        </div>
        @endif

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-body pt-3">

                        <!-- 🔎 Filters -->
                        <form method="GET" action="{{ route('ApproveLeaves') }}" class="mb-3">
                            <div class="row g-2 align-items-end">

                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Name</label>
                                    <input type="text" name="name"
                                        class="form-control form-control-sm"
                                        value="{{ request('name') }}">
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Type</label>
                                    <select name="type" class="form-select form-select-sm">
                                        <option value="">All</option>
                                        @foreach($types as $t)
                                        <option value="{{ $t->Name }}" {{ request('type')==$t->Name?'selected':'' }}>
                                            {{ $t->Name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Status</label>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">All</option>
                                        <option value="Pending" {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
                                        <option value="Approved" {{ request('status')=='Approved'?'selected':'' }}>Approved</option>
                                        <option value="Declined" {{ request('status')=='Declined'?'selected':'' }}>Declined</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small mb-1">Applied On</label>
                                    <input type="date" name="applied_on"
                                        class="form-control form-control-sm"
                                        value="{{ request('applied_on') }}">
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button class="btn btn-primary btn-sm w-100">Search</button>
                                    <a href="{{ route('ApproveLeaves') }}"
                                        class="btn btn-outline-secondary btn-sm w-100">
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>

                        <!-- ✅ Table -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Days</th>
                                        <th style="max-width:170px;">Reason</th>
                                        <th>Status</th>
                                        <th>Applied On</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($leavereqest as $leave)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            @if($leave->Employee)
                                            <a href="{{ route('employeeleavehistroy.show',$leave->Employee->id) }}">
                                                {{ $leave->Employee->firstname }} {{ $leave->Employee->lastname }}
                                            </a>
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td>
                                            {{ $leave->TotalLeaves->Name ?? 'N/A' }}
                                            @if($leave->paid=='No')
                                            <span class="text-danger fw-bold">Unpaid</span>
                                            @endif
                                        </td>

                                        <td>{{ $leave->start_date }}</td>
                                        <td>{{ $leave->end_date }}</td>
                                        <td>{{ $leave->no_of_leaves }}</td>

                                        <td style="word-wrap:break-word;max-width:170px;">
                                            {{ $leave->reason }}
                                        </td>

                                        <td>
                                            @if($leave->status=='Approved')
                                            <span class="badge bg-success">Approved</span>
                                            @elseif($leave->status=='Declined')
                                            <span class="badge bg-danger">Declined</span>
                                            @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        </td>

                                        <td>{{ $leave->created_at }}</td>

                                        <td>
                                            <div class="d-flex flex-column gap-1">

                                                @if($leave->status=='Pending')
                                                <div>
                                                    <form action="{{ route('approve',$leave->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-success btn-sm">Approve</button>
                                                    </form>

                                                    <form action="{{ route('denied',$leave->id) }}" method="POST" class="d-inline ms-1">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="btn btn-danger btn-sm">Deny</button>
                                                    </form>
                                                </div>
                                                @endif

                                                <a href="{{ route('aprovereqest',$leave->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    Update
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>
    ```

</main>
@endsection

@section('js')

<script>
    setTimeout(function() {
        let msg = document.getElementById('alert-success');
        if (msg) {
            msg.style.display = 'none'
        }
    }, 4000);
</script>

@endsection