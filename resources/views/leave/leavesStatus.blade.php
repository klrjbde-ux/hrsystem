@extends('master')

@section('content')
<main id="main" class="main">
    <div class="pagetitle">
        <h1>Leaves Status</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Leaves</li>
                <li class="breadcrumb-item active">Leaves Status</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card-body">
                    <form method="GET" action="{{ route('LeavesStatus') }}">
                        <div class="row mb-3 g-2"> <!-- g-2 adds small gap between columns -->

                            <div class="col-md-2">
                                <input type="text" name="name" class="form-control" placeholder="Search Name" value="{{ request('name') }}">
                            </div>

                            <div class="col-md-2">
                                <input type="text" name="type" class="form-control" placeholder="Leave Type" value="{{ request('type') }}">
                            </div>

                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option {{ request('status')=='Approved'?'selected':'' }}>Approved</option>
                                    <option {{ request('status')=='Pending'?'selected':'' }}>Pending</option>
                                    <option {{ request('status')=='Declined'?'selected':'' }}>Declined</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <input type="date" name="start" class="form-control" value="{{ request('start') }}">
                            </div>

                            <div class="col-md-2">
                                <input type="date" name="end" class="form-control" value="{{ request('end') }}">
                            </div>

                            <div class="col-md-2 d-flex gap-2">
                                <button class="btn btn-primary w-100">Search</button>
                                <a href="{{ route('LeavesStatus') }}" class="btn btn-secondary w-100">Reset</a>
                            </div>

                        </div>
                    </form>
                    <table class="table  table-responsive-lg table-responsive-sm table-responsive-md">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Leave Type</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">No of Days</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leavereqest as $index => $leave)
                            <tr style="background-color: {{ $loop->iteration % 2 == 0 ? '#f2f2f2' : '#ffffff' }};">
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    @if($leave->Employee)
                                    <a href="{{ route('employeeleavehistroy.show', $leave->Employee->id) }}">
                                        {{ $leave->Employee->firstname }} {{ $leave->Employee->lastname }}
                                    </a>

                                    @else
                                    N/A @endif
                                </td>
                                <td>{{ $leave->TotalLeaves->Name ?? 'N/A' }}</td>
                                <td>{{ $leave->start_date }}</td>
                                <td>{{ $leave->end_date }}</td>
                                <td>{{ $leave->no_of_leaves }}</td>
                                <td>
                                    @if($leave->status == 'Approved')
                                    <span class="badge  rounded-pill bg-success text-center p-1 ">{{ $leave->status }}</span>
                                    @elseif($leave->status == 'Declined')
                                    <span class="badge  rounded-pill bg-danger text-center p-1 pr-2 pl-2">{{ $leave->status }}</span>
                                    @else
                                    <span class="badge rounded-pill text-bg-warning text-center p-1 pr-2 pl-2">{{ $leave->status }}</span>
                                    @endif
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
        </div>
    </section>
    </div>
</main>
@endsection