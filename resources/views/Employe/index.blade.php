@extends('master')
@section('people-management-active', 'active')

@section('content')
				<main id="main" class="main">

								<!-- Page Title -->
								<div class="pagetitle mb-4">
												<h1>Employees</h1>
												<nav>
																<ol class="breadcrumb">
																				<li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
																				<li class="breadcrumb-item">People Management</li>
																				<li class="breadcrumb-item active">Employees</li>
																</ol>
												</nav>
								</div>

								<section class="section">
												<div class="card shadow-sm p-3">

																<!-- Add Employee Button -->
																<div class="d-flex justify-content-end mb-3">
																				<a href="{{ route('addemployee') }}" class="btn btn-primary btn-sm">
																								<i class="bi bi-person-plus me-1"></i> Add Employee
																				</a>
																</div>

																<!-- Search & Filter Form -->
																<form method="GET" action="{{ route('employees.index') }}" class="row g-2 align-items-center mb-4">

																				<div class="col-12 col-md-2">
																								<input type="text" name="search" value="{{ request('search') }}"
																												class="form-control form-control-sm" placeholder="Search by name, email, contact">
																				</div>

																				<div class="col-6 col-md-2">
																								<select name="emp_type" class="form-select form-select-sm">
																												<option value="">All Types</option>
																												@foreach ($types as $type)
																																<option value="{{ $type->type }}"
																																				{{ request('emp_type') == $type->type ? 'selected' : '' }}>
																																				{{ $type->type }}
																																</option>
																												@endforeach
																								</select>
																				</div>

																				<div class="col-6 col-md-2">
																								<select name="emp_status" class="form-select form-select-sm">
																												<option value="">All Status</option>
																												<option value="Probation" {{ request('emp_status') == 'Probation' ? 'selected' : '' }}>Probation
																												</option>
																												<option value="Permanent" {{ request('emp_status') == 'Permanent' ? 'selected' : '' }}>Permanent
																												</option>
																												<option value="Contractual" {{ request('emp_status') == 'Contractual' ? 'selected' : '' }}>
																																Contractual</option>
																								</select>
																				</div>

																				<div class="col-6 col-md-2">
																								<select name="designation" class="form-select form-select-sm">
																												<option value="">All Designations</option>
																												@foreach ($designations as $designation)
																																<option value="{{ $designation->designation_name }}"
																																				{{ request('designation') == $designation->designation_name ? 'selected' : '' }}>
																																				{{ $designation->designation_name }}
																																</option>
																												@endforeach
																								</select>
																				</div>

																				<div class="col-6 col-md-2">
																								<select name="department" class="form-select form-select-sm">
																												<option value="">All Departments</option>
																												@foreach ($departments as $department)
																																<option value="{{ $department->department_name }}"
																																				{{ request('department') == $department->department_name ? 'selected' : '' }}>
																																				{{ $department->department_name }}
																																</option>
																												@endforeach
																								</select>
																				</div>

																				<div class="col-12 col-md-2 d-flex gap-1">
																								<button type="submit" class="btn btn-primary btn-sm flex-fill">
																												<i class="bi bi-search"></i>
																								</button>
																								<a href="{{ route('employees.index') }}" class="btn btn-secondary btn-sm flex-fill">
																												<i class="bi bi-arrow-counterclockwise"></i>
																								</a>
																				</div>

																</form>

																<!-- Employee Table -->
																<div class="table-responsive">
																				<table class="table table-hover align-middle text-center mb-0">
																								<thead class="table-light">
																												<tr>
																																<th>#</th>
																																<th class="text-start">Name</th>
																																<th>Email</th>
																																<th>Type</th>
																																<th>Status</th>
																																<th>Actions</th>
																												</tr>
																								</thead>
																								<tbody>
																												@forelse ($employees as $employee)
																																<tr>
																																				<td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}
																																				</td>
																																				<td class="text-start d-flex align-items-center">
																																								<img src="{{ $employee->image ? asset('storage/assets/profile_images/' . $employee->image) : asset('storage/assets/profile_images/default.png') }}"
																																												width="35" height="35" class="rounded-circle me-2" alt="Profile Image">
																																								{{ $employee->firstname }} {{ $employee->lastname }}
																																				</td>
																																				<td>{{ $employee->personal_email }}</td>
																																				<td>{{ $employee->emp_type ?? 'NA' }}</td>
																																				<td>{{ $employee->emp_status ?? 'NA' }}</td>
																																				<td>
																																								<div class="dropdown">
																																												<button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
																																																data-bs-toggle="dropdown">
																																																Actions
																																												</button>
																																												<ul class="dropdown-menu dropdown-menu-end">
																																																@if (Auth::user()->isAllowed('web:Employee:show'))
																																																				<li><a class="dropdown-item"
																																																												href="{{ route('employees.show', $employee->id) }}">Details</a>
																																																				</li>
																																																@endif
																																																@if (Auth::user()->isAllowed('web:Employee:edit'))
																																																				<li><a class="dropdown-item"
																																																												href="{{ route('employee.edit', $employee->id) }}">Edit</a>
																																																				</li>
																																																@endif
																																																@if (Auth::user()->isAllowed('web:Employee:delete'))
																																																				<li>
																																																								<form method="POST"
																																																												action="{{ route('employee.delete', $employee->id) }}">
																																																												@csrf
																																																												@method('GET')
																																																												<button type="submit"
																																																																class="dropdown-item text-danger">Delete</button>
																																																								</form>
																																																				</li>
																																																@endif
																																												</ul>
																																								</div>
																																				</td>
																																</tr>
																												@empty
																																<tr>
																																				<td colspan="6" class="text-center py-3">No employees found</td>
																																</tr>
																												@endforelse
																								</tbody>
																				</table>
																</div>

																<!-- Pagination -->
																<div class="mt-3 d-flex justify-content-end">
																				{{ $employees->withQueryString()->links() }}
																</div>

												</div>
								</section>

				</main>

				<!-- Optional CSS -->
				<style>
								.table-hover tbody tr td,
								.table-hover thead th {
												border: none !important;
								}

								/* Smaller search/filter inputs and buttons */
								form.row.g-2 .form-control-sm,
								form.row.g-2 .form-select-sm {
												height: 36px;
												font-size: 0.85rem;
								}

								.btn-sm {
												font-size: 0.85rem;
								}

								@media (max-width: 575px) {
												form.row.g-2 .d-flex {
																flex-direction: row;
																gap: 0.5rem;
												}
								}
				</style>

@endsection
