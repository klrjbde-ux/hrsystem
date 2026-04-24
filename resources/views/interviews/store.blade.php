@extends('master')
@section('content')

<main class="main">
    <div class="container text-center mt-5">

        <div class="alert alert-success">
            <h4>Candidate Added Successfully!</h4>
            <p>The candidate information has been stored in the system.</p>
        </div>

        <div class="d-flex justify-content-center gap-2 mt-3">
            <a href="{{ route('interviews.index') }}" class="btn btn-primary">Back to List</a>
            <a href="{{ route('interviews.create') }}" class="btn btn-success">Add Another Candidate</a>
        </div>

    </div>
</main>

@endsection