@extends('master')
@section('content')

<main id="main" class="main">
    <div class="container">

        <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="card mb-6" style="width: 100%; max-width: 960px;">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Edit Policy</h5>
                                    @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                    @endif
                                </div>

                                <form class="row g-3" method="POST" action="{{ route('officepolicy.update', $officePolicy->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="col-md-12">
                                        <label for="title" class="form-label">Title</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                            value="{{ $officePolicy->title }}" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required>{{ $officePolicy->description }}</textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status" class="form-label">Status</label>
                                        <select name="status" id="status" class="form-select" required>
                                            <option value="Active" {{ $officePolicy->status == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ $officePolicy->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>

                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary">Update Policy</button>
                                        <a href="{{ route('officepolicy.index') }}" class="btn btn-secondary">Cancel</a>
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