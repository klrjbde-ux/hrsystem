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
    @if (session('danger'))
    <div class="alert alert-danger" role="alert" id="success-message">
      {{ session('danger') }}
    </div>
    @endif

  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
          <div class="card-body">
            <div>
              <a class="btn btn-primary float-right mb-2" href="{{route('addsignature')}}">Add Signature</a>

            </div>

            <table class="table table-responsive-lg table-responsive-sm table-responsive-md table-responsive-xl table-responsive-xxl">
              <thead>
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Signature</th>
                    <th scope="col">Action</th>

                  </tr>
                </thead>
              <tbody>
                @foreach ($signature as $signatures)
                <div class="employee-card">
                  <tr>
                    <td>
                      {{ $loop->iteration }}
                    </td>
                    <td>
                      @if ($signatures->signature)
                      <img src="data:image/png;base64,{{ $signatures->signature }}"
                        alt="Signature" style="width:200px; height: 100px; border: 1px solid #000;">
                      @else
                      <p>No signature found.</p>
                      @endif


                    <td>
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $signatures->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                          Actions
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
                          aria-labelledby="dropdownMenuButton{{ $signatures->id}}">
                          @if(Auth::user()->isAllowed('web:Signature:delete'))
                          <li><a class="dropdown-item" href="{{ route('deletesignature', $signatures->id) }}">Delete</a></li>
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
  }, 4000);
</script>