@extends('master')
@section('content')
@section('css')
@endsection
<main id="main" class="main">
    <div class="container-fluid">
        <section class="section d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Update Emp Meeting (Daily Standup)</h5>
                                @if (session('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('success') }}
                                </div>
                                @endif
                                @if (session('danger'))
                                <div class="alert alert-danger" role="alert">
                                    {{ session('danger') }}
                                </div>
                                @endif
                                <form action="{{ route('dailystandup.update', $meeting->id) }}" method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Date</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="date" id="date"
                                                    value="{{ old('date', $meeting->date ? $meeting->date->format('Y-m-d') : '') }}" required />
                                                <div class="input-group-text">
                                                    <i class="bi bi-calendar"></i>
                                                </div>
                                            </div>
                                            @error('date')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Emp Name</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="employee_id" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}" {{ old('employee_id', $meeting->employee_id) == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->firstname }} {{ $employee->lastname }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="status" required>
                                                <option value="">Select Status</option>
                                                <option value="present" {{ old('status', $meeting->status) == 'present' ? 'selected' : '' }}>Present</option>
                                                <option value="absent" {{ old('status', $meeting->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                            </select>
                                            @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Remarks</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="remarks" rows="3" placeholder="Remarks">{{ old('remarks', $meeting->remarks) }}</textarea>
                                            @error('remarks')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Update Meeting</button>
                                            <a href="{{ route('dailystandup.index') }}" class="btn btn-secondary">Back to List</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection