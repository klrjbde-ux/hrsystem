@extends('master')
@section('content')
@section('people-management-active', 'active')
@section('people-management_addemp_active', 'active')

@section('add-employee-active', 'active')

<main id="main" class="main">
    <div class="container">

        <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="col-12 d-flex flex-column align-items-start justify-content-center">
                            <div class="card mb-6" style="width: 100%; max-width: 960px;">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Add Leave</h5>

                                        @if (session('success'))
                                        <div class="alert alert-success" role="alert" id="alert-success">

                                            {{ session('success') }}
                                        </div>
                                        @endif
                                        @if (session('danger'))
                                        <div class="alert alert-danger" role="alert" id="alert-success">
                                            {{ session('danger') }}
                                        </div>
                                        @endif
                                    </div>
                                    <form class="row g-3 needs-validation" novalidate action="{{ url('addleavedata') }}"
                                        method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" name="id" value="{{ $id }}"
                                            style="display: none">
                                        <div class="row col-12 gap">

                                            <div class="col-md-6">
                                                <label for="leave" class="form-label">Leave<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="Name" class="form-control" id="leave"
                                                    placeholder="Enter Leave" value="{{ $Name }}" required>
                                                <div class="invalid-feedback">The leave field is required</div>
                                                @error('Name')
                                                <div class="text-danger"> {{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="lastname" class="form-label">No of leaves<span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="count" class="form-control" id="lastname"
                                                    value="{{ $Count }}" placeholder="Enter total leaves"
                                                    required>
                                                <div class="invalid-feedback">The total number leave field is required
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row col-12 gap">

                                            <div class="col-md-6">
                                                <label for="name" class="form-label">Status<span
                                                        class="text-danger">*</span></label>
                                                <select class="form-select" name="status" id="status" required>
                                                    @if (isset($Status))
                                                    <option value="Active"
                                                        {{ $Status == 'Active' ? 'selected' : '' }}>Active</option>
                                                    <option value="Inactive"
                                                        {{ $Status == 'Inactive' ? 'selected' : '' }}>Inactive
                                                    </option>
                                                    @else
                                                    <!-- Default options if $Status is not set -->
                                                    <option value="Active">Active</option>
                                                    <option value="Inactive">Inactive</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3">
                                            @if ($id > 0)
                                            <button class="btn btn-primary" type="submit">Update Leave</button>
                                            @else
                                            <button class="btn btn-primary" type="submit">Add Leave</button>
                                            @endif
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
                                        </div>
                                </div>
                                </form>
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



@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById("startdate").setAttribute('min', today);
        document.getElementById("enddate").setAttribute('min', today);

        document.getElementById("startdate").addEventListener("change", function() {
            const startDate = this.value;
            const endDateInput = document.getElementById("enddate");
            endDateInput.setAttribute('min', startDate);
            if (endDateInput.value < startDate) {
                endDateInput.value = startDate;
            }
        });
    });


    setTimeout(function() {
        var message = document.getElementById('alert-success');
        if (message) {
            message.style.display = 'none';
        }
    }, 4000);
</script>
@endsection