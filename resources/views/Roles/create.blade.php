@extends('master')
@section('content')
<main id="main" class="main">
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        @if(session('success'))
          <div class="alert alert-success" role="alert">
            {{ session('success') }}
          </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-10  col-md-12 d-flex flex-column align-items-center justify-content-center">
                <div class="col-12 d-flex flex-column align-items-left justify-content-center">
                    <div class="card mb-6" style="width: 100%; max-width: 960px;">
                        <div class="card-body">
                            <div class="pt-4 pb-2">
                            <h5 class="card-title text-center pb-0 fs-4">Roles</h5>
                            </div>
                            <form class="row g-3 needs-validation" validate action="{{route('store.routes')}}" method="post">
                              @csrf
                              <div class="row justify-content-between">
                                  <div class="col-6">
                                    <div class="form-group">
                                      <label class="control-label">Role Name<span class="text-danger">*</span></label>
                                      <input  type="text" name="name" placeholder="Enter role name here" class="form-control" >                                    </div>
                                  </div>
                                  <div class="col-6 mt-5 text-right">
                                    <label for="check_all"><input type="checkbox" class="mr-1" id="check_all"/>  Select All</label>
                                  </div>
                              </div>
                              
							<hr class="mt-0">

<div class="form-group row">
  @foreach ($all_controllers as $key => $row)
    <div class="col-md-12">
      <div class="row">
        <label class="col-12" for="{{$key}}"><input type="checkbox" class="check_all_sub mr-1" id="{{$key}}"><b>  {{$key}}</b></label>
      </div>

      <hr class="mt-0">

      <div class="{{$key}} row">
        @foreach ($row as $route)
          <div class="col-lg-4 col-md-4 col-sm-4">
            <label class="font-weight-normal col-12" for="{{$key}}:{{$route}}"><input type="checkbox" class="mr-1" id="{{$key}}:{{$route}}" name="permissions[]" value="web:{{$key}}:{{$route}}">  {{$route}}</label>
          </div>
        @endforeach
      </div>
      <br>
    </div>
  @endforeach
</div>

<hr>

                              <button type="submit" class="btn btn-primary"><span class="d-xs-inline d-sm-none d-md-none d-lg-none"><i class="fas fa-check-circle"></i></span><span class="d-none d-xs-none d-sm-inline d-md-inline d-lg-inline"> Create Role</span></button>
                              <button type="button" onclick="window.location.href=''" class="btn btn-default"><span class="d-xs-inline d-sm-none d-md-none d-lg-none"><i class="fas fa-window-close"></i></span><span class="d-none d-xs-none d-sm-inline d-md-inline d-lg-inline"> Cancel</span></button>
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
<script type="text/javascript">
$(document).ready(function () {
    $(function () {
	    $("#check_all").click(function(){
		    $('input:checkbox').not(this).prop('checked', this.checked);
		});
		$(".check_all_sub").click(function(){
		    $('div.'+ this.id +' input:checkbox').prop('checked', this.checked);
		});
    });
});
</script>
@endsection