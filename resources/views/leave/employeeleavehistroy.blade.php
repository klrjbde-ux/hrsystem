@extends('master')

@section('content')
<main id="main" class="main">
<div class="pagetitle">
<h1>LeaveS History</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Home</a></li>
      <li class="breadcrumb-item">Leaves</li>
      <li class="breadcrumb-item active">LeaveS History</li>
    </ol>
  </nav>
</div>
<section class="section">
  <div class="row">
    <div class="col-lg-12">
                            <div class="card-body">
                                
                                    <table class="table  table-responsive-lg table-responsive-sm table-responsive-md">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Leave Type</th>
                                                <th scope="col">Start Date</th>
                                                <th scope="col">End Date</th>
                                                <th scope="col">No of Days</th>
                                                <th scope="col">Reason</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($leavereqest as $index => $leave)
                                                <tr style="background-color: {{ $loop->iteration % 2 == 0 ? '#f2f2f2' : '#ffffff' }};">
                                                    <th scope="row">{{ $loop->iteration }}</th>
                                                    <td>
                                                        @if($leave->Employee)
                                                      
                                                            {{ $leave->Employee->firstname }} {{ $leave->Employee->lastname }}
                                                        </a>
                                                        @else
                                                            N/A
                                                        @endif
                                                    </td>
                                                    <td style=" word-wrap: break-word;
    max-width: 50px;">{{ $leave->TotalLeaves->Name ?? 'N/A' }}
                                                        @if ($leave->paid=='No')
                                                        <span style="color:red;font-weight:bold">{{'Unpaid'}}</span>
                                                      @endif
                                                    </td>
                                                    <td>{{ $leave->start_date }}</td>
                                                    <td>{{ $leave->end_date }}</td>
                                                    <td>{{ $leave->no_of_leaves }}</td>
                                                    <td style=" word-wrap: break-word;
    max-width: 170px;">{{ $leave->reason }}</td>
                                                    <td>
                                                        @if($leave->status == 'Approved')
                                                        <span class="badge  rounded-pill bg-success text-center p-1 ">{{ $leave->status }}</span>
                                                        @elseif($leave->status == 'Declined')
                                                        <span class="badge  rounded-pill bg-danger text-center p-1 pr-2 pl-2">{{ $leave->status }}</span>
                                                        @else
                                                        <span class="badge rounded-pill text-bg-warning text-center p-1 pr-2 pl-2">{{ $leave->status }}</span>
                                                        @endif
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
</main>
@endsection
