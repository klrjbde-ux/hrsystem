@extends('master')
@section('content')

<main id="main" class="main">
    <div class="container">

        <section class="section py-4">

            <div class="card">
                <div class="card-body">

                    <h5>Edit Policy</h5>

                    <form method="POST" action="{{ route('officepolicy.update', $officePolicy->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label>Title</label>
                            <input type="text" name="title" value="{{ $officePolicy->title }}" class="form-control">
                        </div>

                        <div class="mb-2">
                            <label>Description</label>
                            <textarea name="description" class="form-control">{{ $officePolicy->description }}</textarea>
                        </div>

                        <div class="mb-2">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="Active" {{ $officePolicy->status=='Active'?'selected':'' }}>Active</option>
                                <option value="Inactive" {{ $officePolicy->status=='Inactive'?'selected':'' }}>Inactive</option>
                            </select>
                        </div>

                        <button class="btn btn-primary mt-2">Update</button>

                    </form>

                </div>
            </div>

        </section>
    </div>
</main>

@endsection