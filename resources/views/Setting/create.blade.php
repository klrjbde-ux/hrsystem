@extends('master')
@section('css')
@endsection
@section('content')
<main id="main" class="main">
  <div class="container">
    <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        @if(session('success'))
        <div class="alert alert-success" role="alert">
          {{ session('success') }}
        </div>
        @endif
        <div class="row justify-content-center">
          <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="col-12 d-flex flex-column align-items-start justify-content-center">
              <div class="card mb-6" style="width: 100%; max-width: 960px;">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Designation</h5>
                  </div>
                  <form class="row g-3 needs-validation" validate action="{{ Route('desigstore') }}" method="post">
                    @csrf

                    <!-- REASON & DETAILS -->
                    <div class="row col-12 gap">
                      <div class="col-md-6">
                        <label for="dsg" class="form-label">Designation Name<span class="text-danger">*</span></label>
                        <input type="text" name="designation_name" class="form-control" id="dsg" placeholder="Please, enter Designation Name" required>
                        <div class="invalid-feedback">Please, enter Designation Name!</div>
                      </div>
                    </div>
                    <div class="row pt-20px gap">
                      <div class="col-3 col-md-4 offset-4">
                        <button class="btn btn-primary w-100" type="submit">Add Designation</button>
                      </div>
                    </div>

                  </form>
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
@section('js')
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("startdate").setAttribute('min', today);
    document.getElementById("enddate").setAttribute('min', today);

    document.getElementById("startdate").addEventListener("change", function() {
      const startDate = this.value;
      const endDateInput = document.getElementById("enddate");
      endDateInput.setAttribute('min', startDate);
      if (endDateInput.value < startDate) {
        endDateInput.value = startDate;
      }
    });
  });
</script>
@endsection