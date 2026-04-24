@extends('master')

@section('content')
<main id="main" class="main">

<div class="pagetitle">
  <h1>Sallary</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Home</a></li>
      <li class="breadcrumb-item">Paments</li>
      <li class="breadcrumb-item active">Sallary</li>
    </ol>
  </nav>
</div>
<section class="section">
            @if(session('success'))
              <div class="alert alert-success" role="alert">
                {{ session('success') }}
              </div>
            @endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-responsive-lg table-responsive-sm table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Basic Salary</th>
                                                <th scope="col">Bonus</th>
                                                <th scope="col">No of leaves</th>
                                                <th scope="col">Net Payable</th>
                                                <th scope="col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($salary as $salarry)
                                            <div class="employee-card">
                                                <tr>
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>{{ $salarry->employee->firstname ?? 'N/A' }}</td>
                                                    <td>{{ $salarry->gross_salary }}</td>
                                                    <td>{{ $salarry->bonus }}</td>
                                                    <td>{{ $salarry->total_leaves }}</td>
                                                    <td>{{ $salarry->payable_salary }}</td>
                                                    <td>
                                                        <a href="{{ route('salary.slip', ['id' => $salarry->id, 'employee_id' => $salarry->employee->id]) }}">
                                                            <i class="fa-regular fa-eye" style="color: green" aria-hidden="true"></i>
                                                        </a>
                                                </tr>
                                            </div>
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

