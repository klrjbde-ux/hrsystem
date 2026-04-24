@extends('master')
@section('content')
@section('people-management-active','active');
<main id="main" class="main">

<div class="pagetitle">
                  @if (session('success'))
                  <div class="alert alert-success" role="alert" id="success-message">
                      {{ session('success') }}
                  </div>
                  @endif
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">

        <div class="card">
                <div class="card-body">
                  <div>
                    <a class="btn btn-primary float-right mb-2"  href="{{route('addleaveform')}}">Add leave</a>

                  </div>
            
                  <table class="table table-responsive-lg table-responsive-sm table-responsive-md table-responsive-xl table-responsive-xxl">                      <thead>
                    <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                          <th scope="col">Count</th>
                          <th scope="col">Status</th>
                          <th scope="col">Action</th>
                      
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($totalLeaves as $leave)
                        <div class="employee-card">
                          <tr>
                            <td>                            
                              {{ $loop->iteration }}
                            </td>
                            <td>{{ $leave->Name }}</td>
                            <td>{{ $leave->Count }}</td>
                            <td>{{ $leave->Status }}</td> 
                            <td>
                              <div class="dropdown" >
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $leave->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                  Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile" aria-labelledby="dropdownMenuButton{{ $leave->id }}">
                                @if(Auth::user()->isAllowed('web:Leave:edit'))
                                  <li><a class="dropdown-item" href="{{ route('leave.editleave', $leave->id) }}">Edit</a></li>
                                @endif
                                @if(Auth::user()->isAllowed('web:Leave:delete'))
                                <li><a class="dropdown-item" href="{{ route('leave.deleteleave', $leave->id) }}">Delete</a></li>
                              @endif
                                </ul>
                              </div>
                            </td>                    
                          </tr>
                        </div>
                        @endforeach
                       
                      </tbody>
                    </table>
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

<script>
  setTimeout(function() {
      var message = document.getElementById('success-message');
      if (message) {
          message.style.display = 'none';
      }
  },4000); 
</script>