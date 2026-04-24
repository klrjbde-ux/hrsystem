@extends('master')
@section('content')
@section('people-management-active','active');

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Profile</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/home">Employees</a></li>
        <li class="breadcrumb-item active">Profile</li>

      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section profile">
    <div class="row">
      <div class="col-xl-4">

        <div class="card">

          <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data" class="upload">
            <div class="container profile-container">
              <div class="profile-image-container">

                @csrf
                <input type="text" name="id" value="{{ $employee->id }}" style="display:none ">
                @if($employee->image)
                <img src="{{ asset('storage/assets/profile_images/' . $employee->image) }}"
                  id="profileImage" />
                @else
                <img src="{{ asset('storage/assets/profile_image/img2.jpeg') }}" width="125px" height="125px" id="profileImage" />
                @endif

                <div class="round">
                  <i class="fa fa-camera camera-icon" style="color: #fff;"></i>
                  <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                </div>
              </div>
              <div class="profile-name">{{ $employee->firstname }}</div>
              <div class="profile-designation">
                {{ $employee->designation ?? 'N/A' }}
              </div>
              {{-- <input type="text" class="name-input" value="John Doe"> --}}
              <div class="save-buttons" id="saveButtons">
                <button type="submit" class="btn btn-primary" id="saveButton" style="font-size:0.8rem">Save Changes</button>
                <button type="button" class="btn btn-secondary" id="cancelButton" style="font-size:0.8rem">Discard Changes</button>
              </div>
              <br>
            </div>

          </form>
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


              <li class="nav-item">
                <button class="nav-link {{ session('section') == 'profile-change-password' ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
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
                    {{ $employee->relation ?? 'N/A' }}
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
              <div class="tab-pane fade pt-3  {{session('section')  == 'profile-change-password' ? 'show active' : '' }} "
                id="profile-change-password">
                @if (session('password_updated'))
                <div class="alert  alert-success text-center">
                  {{session('password_updated') }}
                </div>
                @endif
                <form method="POST" action="{{ route('changePassword') }}">
                  @csrf
                  <div class="row mb-3">
                    <label for="currentPassword" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="password" type="password" class="form-control" id="currentPassword">
                      @error('password')
                      <p class="text-danger"> {{$message}}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="newpassword" type="password" class="form-control" id="newPassword">
                      @error('newpassword')
                      <p class="text-danger"> {{$message}}</p>
                      @enderror
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                    <div class="col-md-8 col-lg-9">
                      <input name="renewpassword" type="password" class="form-control" id="renewPassword">
                      @error('renewpassword')
                      <p class="text-danger"> {{$message}}</p>
                      @enderror
                    </div>
                    <div class="text-center">
                      <button type="submit" class="btn btn-primary">Change Password</button>
                    </div>
                  </div>
                </form><!-- End Change Password Form -->

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