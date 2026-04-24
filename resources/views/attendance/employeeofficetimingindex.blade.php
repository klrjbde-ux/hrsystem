@extends('master')
@section('content')
@section('people-management-active','active');
<main id="main" class="main">

  <div class="pagetitle">
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

  <section class="section">
    <div class="row">
      <div class="col-lg-12">

        <div class="card">
                <div class="card-body">
                  <div>
                    <a class="btn btn-primary float-right mb-2"  href="{{route('officetiming')}}">Add Office Timing</a>

                  </div>
            
                  <table class="table table-responsive-lg table-responsive-sm table-responsive-md table-responsive-xl table-responsive-xxl">                      <thead>
                    <thead>
                        <tr>
                          <th scope="col">#</th>
                          
                          <th scope="col">Entry Timing</th>
                          <th scope="col">Exit Timing</th>
                          <th scope="col">Break Hours</th>
                          <th scope="col">Working Hours</th>
                          <th scope="col">Total Hours</th>
                          <th scope="col">Action</th>
                      
                        </tr>
                      </thead>
                    <tbody>
@foreach ($OfficeTiming as $OfficeTimings)

@php
$start = \Carbon\Carbon::parse($OfficeTimings->timing_start);
$end   = \Carbon\Carbon::parse($OfficeTimings->timing_off);

$totalMinutes = $start->diffInMinutes($end);
$totalHours = floor($totalMinutes / 60);
$totalMins  = $totalMinutes % 60;
@endphp

<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $OfficeTimings->timing_start }}</td>
<td>{{ $OfficeTimings->timing_off }}</td> 
<td>{{ $OfficeTimings->break }}</td>
<td>{{ $OfficeTimings->totalworkinghours }}</td>
<td>{{ $totalHours }}h {{ $totalMins }}m</td>
<td>
    <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button"
            data-bs-toggle="dropdown">
            Actions
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            @if(Auth::user()->isAllowed('web:Leave:edit'))
            <li><a class="dropdown-item" href="{{ route('editofficetiming', $OfficeTimings->id) }}">Edit</a></li>
            @endif

            @if(Auth::user()->isAllowed('web:Leave:delete'))
            <li><a class="dropdown-item" href="{{ route('deleteofficetiming', $OfficeTimings->id) }}">Delete</a></li>
            @endif
        </ul>
    </div>
</td>
</tr>

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
  document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('alert-success');
    if (successMessage) {
      setTimeout(() => {
        successMessage.style.display = 'none';
      }, 4000); // 4000 milliseconds = 4 seconds
    }
  });
</script>