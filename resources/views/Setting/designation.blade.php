@extends('master')
@section('content')
<main id="main" class="main">
  <div class="container-fluid">
    <section class="section d-flex flex-column align-items-center justify-content-center py-4">
      @if(session('success'))
      <div class="alert alert-success" role="alert">
        {{ session('success') }}
      </div>
      @endif
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12">

            <div class="card mb-5" style="width: 100%;">
              <div class="card-body">

                <div class="row col-12 ">
                  <div class="col-9"></div>
                  <a class="btn mx-auto btn-primary w-100" href="{{route('leaveform')}}">Add leave</a>
                </div>
                <div class="table-responsive">
                  <table class="table table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <!-- <th scope="col">Action</th> -->
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
                            <button type="submit">Delete</button>
                          </form>
                        </td>
                      </tr>

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
  <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete <strong id="employeeName"></strong>?
        </div>
        <div class="modal-footer">
          <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger">Delete</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</main>
@endsection