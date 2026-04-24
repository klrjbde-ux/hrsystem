@extends('master')
@section('css')
@endsection
@section('content')
<main id="main" class="main">

<div class="pagetitle">
  <h1>Salary Deduction</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/home">Home</a></li>
      <li class="breadcrumb-item"><a href="{{route('salary.index')}}">Salary</a></li>
      <li class="breadcrumb-item active">Salary Deduction</li>
    </ol>
  </nav>
</div>
<section class="section">

  <div class="row">
    <div class="col-lg-12">
        <div class="card">
                    <div class="card-body">
                      @if(isset($salary->id))
                      <form class="row g-3 needs-validation" validate action="{{route('deductionupdateSalary', $salary->id)}}" method="POST">
                        @csrf
                        
                        <!-- Employee Data -->
                        <div class="row col-12 gap">
                          <div class="col-md-6">
                              <label for="bonus" class="form-label">Deduction Amount<span class="text-danger"
                                >*</span></label>
                              <input type="number" name="deduct" class="form-control" id="deduct"  
                              placeholder="Enter Amount" oninput="checkLength(this)">
                              @error('deduct')                        
                              <p class="text-danger"> {{$message}}</p>                   
                               @enderror

                          </div>
                          <div class="col-md-6">
                              <label for="type" class="form-label">Type<span class="text-danger">*</span></label>
                              <select class="form-select" name="type" id="type">
                                  <option value="">Choose...</option>
                                  @foreach($types as $type)
                                      <option value="{{ $type->id }}">{{ $type->status }}</option> 
                                  @endforeach
                              </select>
                              @error('type')                        
                              <p class="text-danger"> {{$message}}</p>                   
                               @enderror
                          </div>
                      </div>
                      <div class="row col-12">
                          <div class="col-md-6 gap1">
                              <label for="reason" class="form-label">Reason<span class="text-danger">*</span></label>
                              <input type="text" name="reason" class="form-control" id="reason" placeholder="Enter Reason" min='0'
                              maxlength="191">
                               @error('reason')                        
                               <p class="text-danger"> {{$message}}</p>                   
                                @enderror
                          </div>
                      </div>
                      
                      <div class="pt-20px gap text-center" > 
                          <button class="btn btn-primary" type="submit">Submit</button>
                      </div>

                      </form>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

      </div>
    </main>


 
    <section class="section">
    <div class="row">
     <div class="col-lg-2"></div>
      <div class="col-lg-7" style="margin-left: 97px">
        <h6 style="font-size: 24px;
    margin-bottom: 0;
    font-weight: 600;
    color: #012970;">Deduction Detail</h6>
          <div class="card">
              <div class="card-body">
                  <table class="table table-responsive-lg table-responsive-sm table-responsive-md">
                      <thead>
                          <tr>
                              <th scope="col">#</th>
                              <th scope="col" style="font-size: 15px">Month</th>
                              <th scope="col" style="font-size: 15px">Deduction</th>
                            
                              <th scope="col" style="font-size: 15px">Reason</th>
                              <th scope="col" style="font-size: 15px">Amount</th>
                              <th scope="col" style="font-size: 15px">Delete</th>
                             
                          </tr>
                      </thead>
                      <tbody>
                        
                          <div class="employee-card">
                            @foreach ($salaryDeductions as $salaryDeductions)
                         
                          <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $salaryDeductions->month }}</td>
                          
                             <!-- Assuming each salary deduction has a relationship to the deduction type -->
                           @foreach ($deduction_types as $deduction_type)
                           @if ($deduction_type->id === $salaryDeductions->deduction_type) <!-- Match deduction types -->
                           <td>{{ $deduction_type->status }}</td>
                             @endif
                            @endforeach
                           
                           
                            <td>{{ $salaryDeductions->deduction_reason }}</td>
                            <td>{{ $salaryDeductions->deduction_amount}}</td>
                           <td>  <a href="{{ route('deductiondelete', $salaryDeductions->id)}}"><i class="fa fa-trash" 
                            style="color: rgba(255, 0, 0, 0.696)" aria-hidden="true"></i></a>
                          </td>
                             
                          </tr>
                        
                          @endforeach
                          </div>
                         
                      </tbody>
                  </table>
              </div>
          </div>
      </div>
    
  </div>

    </section>


@endsection
@section('js')
<script>
   const deduct = document.getElementById('deduct');
   function checkLength(input) {
            const maxDigits = 8; // Set your desired max digits
            const value = input.value;

            // Remove leading zeros and check length
            const digitsOnly = value.replace(/\D/g, ''); // Keep only digits
            
            // Limit to maxDigits
            if (digitsOnly.length > maxDigits) {
                input.value = digitsOnly.slice(0, maxDigits); // Slice to maxDigits
            } else {
                input.value = digitsOnly; // Update input with valid digits
            }
        }
  </script>
@endsection