@extends('master')

@section('content')
<main id="main" class="main">

<div class="pagetitle">
  <h1>My Profile</h1>
</div>

<section class="section profile">
<div class="row">

<!-- LEFT SIDE -->
<div class="col-xl-4">

        <div class="card">

          <form action="{{ route('profile') }}" method="POST" enctype="multipart/form-data" class="upload">
            <div class="container profile-container">
              <div class="profile-image-container">

                @csrf
              <input type="hidden" name="id" value="{{ $employee->id ?? '' }}">
                @if($user->image)
                <img src="{{ asset('storage/assets/profile_images/' . $user->image) }}"
                  id="profileImage" />
                @else
                <img src="{{ asset('storage/assets/profile_image/img2.jpeg') }}" width="125px" height="125px" id="profileImage" />
                @endif

                <div class="round">
                  <i class="fa fa-camera camera-icon" style="color: #fff;"></i>
                  <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
                </div>
              </div>
              <div class="profile-name">{{ $user->name }}</div>
              <div class="profile-designation">
               
          {{ $user->roles->pluck('name')->first() }}
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

<!-- RIGHT SIDE -->
<div class="col-xl-8">
  <div class="card">
    <div class="card-body pt-3">

      <!-- TAB -->
      <ul class="nav nav-tabs nav-tabs-bordered">
        <li class="nav-item">
          <button class="nav-link active">Change Password</button>
        </li>
      </ul>

      <div class="pt-3">

        @if (session('password_updated'))
          <div class="alert alert-success text-center">
            {{ session('password_updated') }}
          </div>
        @endif

        <form method="POST" action="{{ route('changePassword') }}">
          @csrf

          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Current Password</label>
            <div class="col-md-8">
              <input name="password" type="password" class="form-control">
              @error('password')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-md-4 col-form-label">New Password</label>
            <div class="col-md-8">
              <input name="newpassword" type="password" class="form-control">
              @error('newpassword')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>

          <div class="row mb-3">
            <label class="col-md-4 col-form-label">Confirm Password</label>
            <div class="col-md-8">
              <input name="renewpassword" type="password" class="form-control">
              @error('renewpassword')
                <small class="text-danger">{{ $message }}</small>
              @enderror
            </div>
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-primary">
              Change Password
            </button>
          </div>

        </form>

      </div>

    </div>
  </div>
</div>

</div>
</section>
</main>
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