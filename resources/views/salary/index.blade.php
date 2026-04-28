@extends('master')

@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Salary</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Payments</li>
                <li class="breadcrumb-item active">Salary</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        @if(session('success'))
        <div class="alert alert-success" role="alert" id="alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(session('danger'))
        <div class="alert alert-danger" role="alert" id="alert-danger">
            {{ session('danger') }}
        </div>
        @endif

        <!-- 🔎 Search Filters -->
        <!-- 🔎 Search Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('salary.index') }}">
                    <div class="row g-2 align-items-end">

                        <!-- Name -->
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ request('name') }}">
                        </div>

                        <!-- Date -->
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Date</label>
                            <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                        </div>

                        <!-- Salary Range -->
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Salary Range</label>
                            <select name="salary_range" class="form-select form-select-sm">
                                <option value="">All</option>
                                <option value="30000-40000" {{ request('salary_range')=='30000-40000'?'selected':'' }}>30,000 - 40,000</option>
                                <option value="40001-50000" {{ request('salary_range')=='40001-50000'?'selected':'' }}>40,001 - 50,000</option>
                                <option value="50001-60000" {{ request('salary_range')=='50001-60000'?'selected':'' }}>50,001 - 60,000</option>
                                <option value="60001-70000" {{ request('salary_range')=='60001-70000'?'selected':'' }}>60,001 - 70,000</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="col-md-3 d-flex gap-2">
                            <button class="btn btn-primary btn-sm w-100">Search</button>
                            <a href="{{ route('salary.index') }}" class="btn btn-outline-secondary btn-sm w-100">Reset</a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        <!-- Salary Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-size: 15px">Date</th>
                                        <th style="font-size: 15px">Name</th>
                                        <th style="font-size: 15px">Basic Salary</th>
                                        <th style="font-size: 15px">Bonus</th>
                                        <th style="font-size: 15px">Unpaid Leaves</th>
                                        <th style="font-size: 15px">Deduction</th>
                                        <th style="font-size: 15px">Net Payable</th>
                                        <th style="font-size: 15px">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salary as $salarry)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</th>
                                        <td>{{ \Carbon\Carbon::parse($salarry->created_at)->format('F j, Y') }}</td>
                                        <td>{{ $salarry->employee->firstname ?? 'N/A' }}</td>
                                        <td>{{ $salarry->gross_salary }}</td>
                                        <td>{{ $salarry->bonus }}</td>
                                        <td>{{ $salarry->total_leaves }}</td>
                                        <td>{{ $salarry->deduction }}</td>
                                        <td>{{ $salarry->payable_salary }}</td>
                                        <td>
                                            <a href="{{ route('bonus', $salarry->id) }}"><i class="fa fa-plus-square" style="color: green"></i></a>
                                            <a href="{{ route('salary.slip', ['id' => $salarry->id, 'employee_id' => $salarry->employee->id]) }}" title="View Salary Slip">
                                                <i class="fa fa-eye" style="color: green;"></i>
                                            </a>
                                            <a href="{{ route('deduction', $salarry->id) }}"><i class="fa fa-minus-square" style="color: red;"></i></a>
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
</main>
@endsection
@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert');
        
        alerts.forEach(alert => {
            alert.style.transition = "opacity 0.5s ease";
            alert.style.opacity = "0";

            setTimeout(() => {
                alert.remove();
            }, 500); // remove after fade
        });

    }, 4000); // ⏱️ 4 seconds (you can change 3000–5000)
});
</script>
@endsection