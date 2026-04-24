@extends('master')
@section('content')
<main id="main" class="main">

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Setting</li>
                <li class="breadcrumb-item active">Designation</li>
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
                        <!-- Table with stripped rows -->
                        <div class="text-right">
                            <a class="btn btn-primary" href="{{route('desigform')}}">Add Designation</a>
                        </div>

                        <table class="table gap">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($designations as $designation)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>{{ $designation->designation_name }}</td>
                                    <td>
                                        <form action="{{ url('designations/destroy', $designation->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this designation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>

            </div>
        </div>
    </section>



</main><!-- End #main -->

@endsection