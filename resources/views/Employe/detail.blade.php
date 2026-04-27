@extends('master')
@section('content')
@section('people-management-active','active');

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/employees">Employees</a></li>
        <li class="breadcrumb-item active">Profile</li>

      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">
  <div class="card">

    <div class="container profile-container text-center pt-4">

     <div class="text-center">
    @if($employee->image)
        <img src="{{ asset('storage/assets/profile_images/' . $employee->image) }}"
             class="rounded-circle mb-3 object-fit-cover"
             width="130" height="130">
    @else
        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3 mx-auto"
             style="width:130px; height:130px;">
            <i class="bi bi-camera text-muted" style="font-size:40px;"></i>
        </div>
    @endif
</div>

      {{-- NAME --}}
      <h5>{{ $employee->firstname }} {{ $employee->lastname }}</h5>

      {{-- DESIGNATION --}}
      <p class="text-muted">
        {{ $employee->designation ?? 'N/A' }}
      </p>

    </div>

  </div>
</div>
      <div class="col-xl-8">

        <div class="card">
          <div class="card-body pt-3">
            <!-- Bordered Tabs -->
            <ul class="nav nav-tabs nav-tabs-bordered">

              <li class="nav-item ">
                <button class="nav-link
            {{ session('section') == 'profile-settings' ||
            session('section') == 'profile-change-password'  ? ' ' : 'active' }}

            " data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>

              </li>

              <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Emergency Contact</button>
              </li>


           

            </ul>
            <div class="tab-content pt-2">

              <div class="tab-pane fade  profile-overview
             {{  session('section') == 'profile-change-password' || session('section') == 'profile-settings'  ? '' : 'show active' }}
            " id="profile-overview">

                <h5 class="card-title">Profile Details</h5>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label ">Full Name</div>
                  <div class="col-lg-9 col-md-8">{{ $employee->firstname }} {{ $employee->lastname }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Company</div>
                  <div class="col-lg-9 col-md-8">BLSSOL</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Job</div>
                  <div class="col-lg-9 col-md-8">
                    {{ $employee->designation ?? 'N/A' }}
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">DOB</div>
                  <div class="col-lg-9 col-md-8">{{ $employee->dob }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Phone</div>
                  <div class="col-lg-9 col-md-8">{{ $employee->contact_no }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Email</div>
                  <div class="col-lg-9 col-md-8">{{$employee->personal_email}}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Address</div>
                  <div class="col-lg-9 col-md-8">{{ $employee->permanent_address }}</div>
                </div>

              </div>

              <div class="tab-pane fade profile-overview  pt-3" id="profile-edit">
                <h5 class="card-title">Emergency Details</h5>
                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Relation</div>
                  <div class="col-lg-9 col-md-8">
                    {{ $employee->employeeContactRelation->contact_name ?? 'N/A' }}
                  </div>

                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Contact #</div>
                  <div class="col-lg-9 col-md-8">{{ $employee->emergency_contact }}</div>
                </div>

                <div class="row">
                  <div class="col-lg-3 col-md-4 label">Address</div>
                  <div class="col-lg-9 col-md-8">{{ $employee->emergency_contact_address }}</div>
                </div>

              </div>

              <div class="tab-pane fade pt-3  {{session('section')  == 'profile-settings' ? 'show active' : '' }}"
                id="profile-settings">
                @if (session('profile_updated'))
                <div class="alert  alert-success text-center">
                  {{session('profile_updated') }}
                </div>
                @endif
                @error('image')
                <div class="alert  alert-danger">
                  {{$message}}
                </div>
                @enderror
              </div>
      
            </div><!-- End Bordered Tabs -->

          </div>
        </div>

      </div>
    </div>
  </section>
</main><!-- End #main -->
@endsection
@section('js')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const imageInput = document.getElementById('image');
    const camera = document.querySelector('.camera-icon');
    const profileImage = document.getElementById('profileImage');
    const saveButton = document.getElementById('saveButton');
    const discardButton = document.getElementById('cancelButton');

    let originalImageSrc = profileImage.src;

    profileImage.addEventListener('click', function() {
      imageInput.click();
    });

    camera.addEventListener('click', function() {
      imageInput.click();
    });


    imageInput.addEventListener('change', function() {
      if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          profileImage.src = e.target.result;
          saveButtons.style.display = 'block';
        };
        reader.readAsDataURL(imageInput.files[0]);
      }
    });

    discardButton.addEventListener('click', function() {
      imageInput.value = ''; // Clear the file input
      profileImage.src = originalImageSrc; // Reset the image to the original source
      saveButton.style.display = 'none';
      discardButton.style.display = 'none';
    });

  });
</script>
@endsection