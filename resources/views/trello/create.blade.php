@extends('master')
@section('content')

<main id="main" class="main">
<div class="container-fluid">

<section class="section">
<div class="row justify-content-center">
<div class="col-12">

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
<div class="card-body">

<h5 class="card-title">Create Project Board</h5>

<form action="{{ route('trello.store') }}" method="POST">
@csrf

<div class="row g-3">

<div class="col-md-6">
<label class="form-label">Project Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="col-md-6">
<label class="form-label">Assign To</label>
<select name="assigned_to" class="form-control" required>
<option value="">Select Employee</option>
@foreach($employees as $employee)
<option value="{{ $employee->id }}">
{{ $employee->name }}
</option>
@endforeach
</select>
</div>

<div class="col-12 text-end">
<button type="submit" class="btn btn-primary">
Create Project
</button>
</div>

</div>

</form>

</div>
</div>

</div>
</div>
</section>

</div>
</main>

@endsection
