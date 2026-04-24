@extends('master')
@section('content')
@section('people-management-active', 'active')
@section('people-management_addemp_active', 'active')
@section('add-employee-active', 'active')

<main id="main" class="main">
    <div class="container">
        <section class="section register d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="col-12 d-flex flex-column align-items-start justify-content-center">
                            <div class="card mb-6" style="width: 100%; max-width: 960px;">
                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                        <h5 class="card-title text-center pb-0 fs-4">Add Signature</h5>

                                        @if (session('success'))
                                        <div class="alert alert-success" role="alert" id="alert-success">
                                            {{ session('success') }}
                                        </div>
                                        @endif
                                        @if (session('danger'))
                                        <div class="alert alert-danger" role="alert" id="alert-danger">
                                            {{ session('danger') }}
                                        </div>
                                        @endif
                                    </div>
                                    <form method="POST" action="{{ url('addsignaturedata') }}" class="row g-3 needs-validation" novalidate>
                                        @csrf
                                        <!-- Gender & DOB -->
                                        <div class="row col-12 gap">
                                            <div class="col-md-8">
                                                <div id="sign" class="form-control" style=" width: 400px; height: 200px;"></div>
                                                <div id="signature-error" class="text-danger" style="display:none;"></div>
                                                <input type="hidden" name="signature" id="signature">
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3">

                                            <button type="button" class="btn btn-primary" id="clear">Clear</button>
                                            <button class="btn btn-primary" type="submit">Save Signature</button>
                                            <br>
                                            <br>
                                            <br>
                                            <br>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="{{asset('assets/js/jSignature-main/jSignature.min.js')}}"></script>
<script src="{{asset('assets/js/jSignature-main/modernizr.js')}}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize jSignature
        $("#sign").jSignature();

        $("#clear").on("click", function() {
            $("#sign").jSignature("clear");
            $("#signature").val(''); // Clear hidden input
            $("#signature-error").hide(); // Hide error message
        });

        $("form").on("submit", function(e) {
            // Get signature data
            var signatureData = $("#sign").jSignature("getData", "image");
            console.log("Signature Data:", signatureData); // Log the entire signature data

            // Check if the signature data is valid
            if (!signatureData || !signatureData[1] || signatureData[1].trim() === '') {
                e.preventDefault(); // Prevent form submission
                $("#signature-error").text('Signature is required.').show(); // Show error message
            } else {
                $("#signature").val(signatureData[1]); // Set the image data only if it exists
                $("#signature-error").hide(); // Hide error if signature is present
            }
        });
    });
</script>
@endsection