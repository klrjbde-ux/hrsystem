@extends('master')
@section('content')

<main id="main" class="main">
    <div class="container">

        <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <div class="card mb-6" style="width: 100%; max-width: 960px;">
                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center fs-4">Add Office Policy</h5>
                                </div>

                                <form class="row g-3 needs-validation"
                                    action="{{ route('officepolicy.store') }}"
                                    method="POST"
                                    novalidate>
                                    @csrf

                                    {{-- Title --}}
                                    <div class="col-md-12">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text"
                                            name="title"
                                            class="form-control"
                                            value="{{ old('title') }}"
                                            required>
                                        @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Description --}}
                                    <div class="col-md-12">
                                        <label class="form-label">Description <span class="text-danger">*</span></label>
                                        <textarea name="description"
                                            class="form-control"
                                            rows="5"
                                            required>{{ old('description') }}</textarea>
                                        @error('description')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                    {{-- Submit --}}
                                    <div class="col-md-12 mt-4">
                                        <button class="btn btn-primary">
                                            Save Policy
                                        </button>
                                        <a href="{{ route('officepolicy.index') }}" class="btn btn-secondary">
                                            Back
                                        </a>
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