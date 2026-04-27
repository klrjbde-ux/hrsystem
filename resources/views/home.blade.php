@extends('master')
@section('content')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-12">
        <div class="row">

          <!-- Sales Card -->
          <!-- <div class="col-xxl-4 col-md-6">
          <div class="card info-card sales-card">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Sales <span>| Today</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-cart"></i>
                </div>
                <div class="ps-3">
                  <h6>145</h6>
                  <span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                </div>
              </div>
            </div>

          </div>
        </div>End Sales Card -->

          <!-- Revenue Card -->
          <!-- <div class="col-xxl-4 col-md-6">
          <div class="card info-card revenue-card">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Revenue <span>| This Month</span></h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                  <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="ps-3">
                  <h6>$3,264</h6>
                  <span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span>

                </div>
              </div>
            </div>

          </div>
        </div>End Revenue Card -->

          <!-- Customers Card -->
          <div class="col-xxl-4 col-xl-12">

            <div class="card info-card customers-card">


              <div class="card-body">
                <h5 class="card-title"> Total Employees </h5>

                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="ps-3">
                    <h6>{{ $employees }}</h6>
                  </div>
                </div>

              </div>
            </div>
          </div><!-- End Customers Card -->

          <!-- Attendance -->
          <div class="col-12">
            <div class="card">
              <div class="card-body pb-0">
                <h5 class="card-title">Attendance <span style="color: #012970"> | Today</span></h5>
                <div class="d-flex flex-wrap gap-2 mb-3">
                  <span class="badge bg-success fs-6">Present: {{ $presentEmp }}</span>
                  <span class="badge bg-danger fs-6">Absent: {{ $absentemp }}</span>
                </div>

                <h6 class="mb-2">Employee Attendance Details</h6>
                <div class="table-responsive mb-3">
                  <table class="table table-sm table-striped align-middle">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>In Time</th>
                        <th>Out Time</th>
                        <th>Total Time</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse(($todayAttendance ?? collect()) as $index => $attendance)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $attendance->employee ? ($attendance->employee->firstname . ' ' . $attendance->employee->lastname) : 'N/A' }}</td>
                        <td>{{ $attendance->first_time_in ?? 'N/A' }}</td>
                        <td>{{ $attendance->last_time_out ?? 'N/A' }}</td>
                        <td>{{ $attendance->total_time ?? 'N/A' }}</td>
                        <td>{{ ucfirst($attendance->status ?? 'Present') }}</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="6" class="text-muted">No attendance marked today.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><!-- End Attendance -->

          <!-- Reports -->
          <div class="col-12">
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Reports <span style="color: #012970"> | Today</span></h5>
                <div class="d-flex flex-wrap gap-3 mb-3">
                  <span class="badge bg-primary fs-6">Total Reports: {{ $totalReportsToday ?? 0 }}</span>
                  <span class="badge bg-success fs-6">Today Appraisals: {{ ($todayAppraisals ?? collect())->count() }}</span>
                  <a href="{{ route('performance.reports.index') }}" class="btn btn-sm btn-outline-primary">Open Performance Reports</a>
                </div>

                <h6 class="mb-2">Review Status Summary (Today)</h6>
                <div class="table-responsive mb-3">
                  <table class="table table-sm table-bordered align-middle">
                    <thead>
                      <tr>
                        <th>Status</th>
                        <th>Count</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse(($reviewsByStatusToday ?? []) as $status => $count)
                      <tr>
                        <td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
                        <td>{{ $count }}</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="2" class="text-muted">No performance review status recorded today.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                <h6 class="mb-2">Appraisals Added Today</h6>
                <div class="table-responsive">
                  <table class="table table-sm table-striped align-middle">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Reviewer</th>
                        <th>Rating</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse(($todayAppraisals ?? collect()) as $index => $appraisal)
                      <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $appraisal->employee ? ($appraisal->employee->firstname . ' ' . $appraisal->employee->lastname) : 'N/A' }}</td>
                        <td>{{ $appraisal->reviewer ? ($appraisal->reviewer->firstname . ' ' . $appraisal->reviewer->lastname) : 'N/A' }}</td>
                        <td>{{ $appraisal->rating ?? 'N/A' }}</td>
                        <td>{{ ucfirst($appraisal->status ?? 'N/A') }}</td>
                      </tr>
                      @empty
                      <tr>
                        <td colspan="5" class="text-muted">No appraisal records added today.</td>
                      </tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

              </div>

            </div>
          </div><!-- End Reports -->

          <!-- Recent Sales -->
          <div class="col-12">
            <div class="card recent-sales overflow-auto">

              <div class="card-body">
                <h5 class="card-title">Leaves Status <span style="color: #012970">| Today</span></h5>

                <div class="table-responsive">
                  <table class="table table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Leave Type</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">No of Days</th>
                        <th scope="col">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($leavereqest as $index => $leave)
                      @php
                      $rowClass = $loop->iteration % 2 == 0 ? 'even-row' : 'odd-row';
                      @endphp
                      <tr class="{{ $rowClass }}">
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>@if($leave->Employee){{ $leave->Employee->firstname }} {{ $leave->Employee->lastname }}@else N/A @endif</td>
                        <td>{{ $leave->TotalLeaves->Name ?? 'N/A' }}</td>
                        <td>{{ $leave->start_date }}</td>
                        <td>{{ $leave->end_date }}</td>
                        <td>{{ $leave->no_of_leaves }}</td>
                        <td>
                          @if($leave->status == 'Approved')
                          <span class="badge bg-success">{{ $leave->status }}</span>
                          @elseif($leave->status == 'Declined')
                          <span class="badge bg-danger">{{ $leave->status }}</span>
                          @else
                          <span class="badge bg-secondary">{{ $leave->status }}</span>
                          @endif
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><!-- End Recent Sales -->

          <!-- Top Selling -->
          <!-- <div class="col-12">
          <div class="card top-selling overflow-auto">
            <div class="card-body pb-0">
              <h5 class="card-title">Top Selling <span>| Today</span></h5>

              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">Preview</th>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Revenue</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th scope="row"><a href="#"><img src="assets/img/product-1.jpg" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">Ut inventore ipsa voluptas nulla</a></td>
                    <td>$64</td>
                    <td class="fw-bold">124</td>
                    <td>$5,828</td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#"><img src="assets/img/product-2.jpg" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">Exercitationem similique doloremque</a></td>
                    <td>$46</td>
                    <td class="fw-bold">98</td>
                    <td>$4,508</td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#"><img src="assets/img/product-3.jpg" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">Doloribus nisi exercitationem</a></td>
                    <td>$59</td>
                    <td class="fw-bold">74</td>
                    <td>$4,366</td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#"><img src="assets/img/product-4.jpg" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">Officiis quaerat sint rerum error</a></td>
                    <td>$32</td>
                    <td class="fw-bold">63</td>
                    <td>$2,016</td>
                  </tr>
                  <tr>
                    <th scope="row"><a href="#"><img src="assets/img/product-5.jpg" alt=""></a></th>
                    <td><a href="#" class="text-primary fw-bold">Sit unde debitis delectus repellendus</a></td>
                    <td>$79</td>
                    <td class="fw-bold">41</td>
                    <td>$3,239</td>
                  </tr>
                </tbody>
              </table>

            </div>

          </div>
        </div>End Top Selling -->

        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4 d-none">

        <!-- Recent Activity -->
        <!-- <div class="card">
        <div class="card-body">
          <h5 class="card-title">Recent Activity <span> | Today</span></h5>

          <div class="activity">

            <div class="activity-item d-flex">
              <div class="activite-label">32 min</div>
              <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
              <div class="activity-content">
                Quia quae rerum <a href="#" class="fw-bold text-dark">explicabo officiis</a> beatae
              </div>
            </div>

            <div class="activity-item d-flex">
              <div class="activite-label">56 min</div>
              <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
              <div class="activity-content">
                Voluptatem blanditiis blanditiis eveniet
              </div>
            </div>

            <div class="activity-item d-flex">
              <div class="activite-label">2 hrs</div>
              <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
              <div class="activity-content">
                Voluptates corrupti molestias voluptatem
              </div>
            </div>

            <div class="activity-item d-flex">
              <div class="activite-label">1 day</div>
              <i class='bi bi-circle-fill activity-badge text-info align-self-start'></i>
              <div class="activity-content">
                Tempore autem saepe <a href="#" class="fw-bold text-dark">occaecati voluptatem</a> tempore
              </div>
            </div>

            <div class="activity-item d-flex">
              <div class="activite-label">2 days</div>
              <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
              <div class="activity-content">
                Est sit eum reiciendis exercitationem
              </div>
            </div>

            <div class="activity-item d-flex">
              <div class="activite-label">4 weeks</div>
              <i class='bi bi-circle-fill activity-badge text-muted align-self-start'></i>
              <div class="activity-content">
                Dicta dolorem harum nulla eius. Ut quidem quidem sit quas
              </div>
            </div>

          </div>

        </div>
      </div> -->
        <!-- End Recent Activity -->

        <!-- Budget Report -->
        <!-- <div class="card">
        <div class="card-body pb-0">
          <h5 class="card-title">Budget Report <span> | This Month</span></h5>

          <div id="budgetChart" style="min-height: 400px;" class="echart"></div>

          <script>
            document.addEventListener("DOMContentLoaded", () => {
              var budgetChart = echarts.init(document.querySelector("#budgetChart")).setOption({
                legend: {
                  data: ['Allocated Budget', 'Actual Spending']
                },
                radar: {
                  ,
                  indicator: [{
                      name: 'Sales',
                      max: 6500
                    },
                    {
                      name: 'Administration',
                      max: 16000
                    },
                    {
                      name: 'Information Technology',
                      max: 30000
                    },
                    {
                      name: 'Customer Support',
                      max: 38000
                    },
                    {
                      name: 'Development',
                      max: 52000
                    },
                    {
                      name: 'Marketing',
                      max: 25000
                    }
                  ]
                },
                series: [{
                  name: 'Budget vs spending',
                  type: 'radar',
                  data: [{
                      value: [4200, 3000, 20000, 35000, 50000, 18000],
                      name: 'Allocated Budget'
                    },
                    {
                      value: [5000, 14000, 28000, 26000, 42000, 21000],
                      name: 'Actual Spending'
                    }
                  ]
                }]
              });
            });
          </script>

        </div>
      </div>End Budget Report -->

        <!-- Website Traffic removed from right side -->







        <!--       
        <div class="card-body pb-0">
          <h5 class="card-title">News &amp; Updates <span>| Today</span></h5>

          <div class="news">
            <div class="post-item clearfix">
              <img src="assets/img/news-1.jpg" alt="">
              <h4><a href="#">Nihil blanditiis at in nihil autem</a></h4>
              <p>Sit recusandae non aspernatur laboriosam. Quia enim eligendi sed ut harum...</p>
            </div>

            <div class="post-item clearfix">
              <img src="assets/img/news-2.jpg" alt="">
              <h4><a href="#">Quidem autem et impedit</a></h4>
              <p>Illo nemo neque maiores vitae officiis cum eum turos elan dries werona nande...</p>
            </div>

            <div class="post-item clearfix">
              <img src="assets/img/news-3.jpg" alt="">
              <h4><a href="#">Id quia et et ut maxime similique occaecati ut</a></h4>
              <p>Fugiat voluptas vero eaque accusantium eos. Consequuntur sed ipsam et totam...</p>
            </div>

            <div class="post-item clearfix">
              <img src="assets/img/news-4.jpg" alt="">
              <h4><a href="#">Laborum corporis quo dara net para</a></h4>
              <p>Qui enim quia optio. Eligendi aut asperiores enim repellendusvel rerum cuder...</p>
            </div>

            <div class="post-item clearfix">
              <img src="assets/img/news-5.jpg" alt="">
              <h4><a href="#">Et dolores corrupti quae illo quod dolor</a></h4>
              <p>Odit ut eveniet modi reiciendis. Atque cupiditate libero beatae dignissimos eius...</p>
            </div>

          </div> 

        </div>-->
      </div>

    </div><!-- End Right side columns -->

    </div>
  </section>

</main><!-- End #main -->

@endsection

@section('js')

@endsection