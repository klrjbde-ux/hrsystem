<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>HR Management</title>
    <link href="{{asset('assets/img/favicon.png')}}" rel="icon">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">
    <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .bg-dark {
            background-color: #343a40;
        }

        .text-white {
            color: #fff;
        }

        /* Print-specific styles */
        @media print {
            .bg-dark {
                background-color: #fff;
                color: #000;
                -webkit-print-color-adjust: exact;
            }
            .text-white {
                color: #000!important;
            }
            .no-print {
                display: none;
            }
        
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <section class="section py-4">
        <div class="container">
            <div class="card mb-5">
                <div class="card-body text-center">
                    <h5 class="card-title fs-4">Salary Slip</h5>
                </div>
            </div>

            <div class="bg-dark">
                <h6 class="pl-2 pt-1 pb-1 text-white">
                    <i class="fas fa-user"></i> Employee Details
                </h6>
            </div>
            <div class="container">
                <div class="row pt-2">
                    <div class="col-sm-6">
                        <h6 class="mb-1"><b>Name: 
                            <span style=" margin-left:59px;">
                             {{$employee->firstname}} {{$employee->lastname}}  
                            </span>
				          <span class="department" style=" margin-left:36px;">
                            </span>
						
						</h6>
                    </div>                  
                </div>
                <div class="row pt-2">
                    <div class="col-sm-6">
                        <h6 class="mb-1"><b>Department:							
                            <span class="department" style=" margin-left:14px;">
                             {{ $employee->Department->department_name ?? 'N/A'}}  
                            </span>
						
						</h6>
                    </div>                  
                </div>

                <div class="row pt-2">
                    <div class="col-sm-6">
                        <h6 class="mb-1"><b>Designation:							
                            <span class="department" style=" margin-left:14px;">
                                {{ $employee->Designation->designation_name ?? 'N/A' }} 
                            </span>
						
						</h6>
                    </div>                  
                </div>

                <div class="row pt-2">
                    <div class="col-sm-6">
                        <h6 class="mb-0"><b>Salary Month:
                          <span class="department" style=" margin-left:7px;">
                            {{$salary->month}}
                            </span>						
						</h6>
                    </div>                  
                </div>

            </div>

            <br>


            <div class="bg-dark mt-3">
                <h6 class="pl-2 pt-1 pb-1 text-white">
                    <i class="fas fa-id-card"></i> Contact Details
                </h6>
            </div>

            <div class="row pt-2">
                <div class="col-sm-6">
                    <h6 class="mb-1"><b>Contact No: 
                        <span style=" margin-left:19px;">
                            {{$employee->contact_no}}
                        </span>
                      <span class="department" style=" margin-left:73px;">

                        <b>Emergency No:</b>
                        <span class="department" style=" margin-left:2px;">
                            {{$employee->emergency_contact}}
                        </span>

                        </span>
                    
                    </h6>
                </div>                  
            </div>

            <div class="row pt-2">
                <div class="col-sm-6">
                    <h6 class="mb-0"><b>Official Email: 
                        <span style=" margin-left:5px;">
                            {{$employee->personal_email}}
                        </span>
                   
                    </h6>
                </div>                  
            </div>

            <br>

            <table style="width: 100%">
                <tr>
                   <td class=" pl-0" colspan="2">
                        <div class="bg-dark  mt-3">
                            <h6 class="pl-2 pt-1 pb-1 text-white">
                                <i class="fas fa-money-bill text-white"></i> Salary Details
                            </h6>
                        </div>
                    </td>
                    <td class=" pl-0" colspan="2">
                        <div class="bg-dark mt-3">
                            <h6 class="pl-2 pt-1 pb-1 text-white">
                                <i class="fas fa-money-bill text-white"></i> Deductions
                            </h6>
                        </div>
                    </td>
                </tr>
          
     
                    <tr>
                        <td class=" pl-0">
                            <h6 class="mb-3">
                                <b>Basic Payment</b>
                            </h6>

                            @if ($salary_bonus_detail->isNotEmpty())
                            @foreach ($allbonustype as $allbonustypes)
                                          <h6 class="mb-3">
                                            <b>{{ $allbonustypes->status }}:</b>
                                        </h6>
                            @endforeach
                        @endif
                           
                         </td>
                         <td class=" pl-0">
                            <h6 class="mb-3">
                                {{ number_format($employee->gross_salary, 2) }}
                            </h6>
                            @if ($salary_bonus_detail->isNotEmpty())
                            @foreach ($allbonustype as $allbonustypes)
                                <div class="width: 100%;">
                                    
                                    
                                        @php
                                            // Find the corresponding bonus amount
                                            $bonusDetail = $salary_bonus_detail->firstWhere('bonus_type', $allbonustypes->id);
                                        @endphp
                                        <h6 class="mb-3">
                                            {{ $bonusDetail ? number_format($bonusDetail->bonus_amount, 2) : '0.00' }}
                                        </h6>
                                    
                                </div>
                            @endforeach
                        @endif
                           
                        </td>
                        <td class=" pl-0">
                            
                            @foreach ($salary_deduction_detail as $salary_deduction_details)                        
                            @foreach ($alldeductiontype as $alldeductiontypes)
                                          <h6 class="mb-3">
                                            <b>{{$alldeductiontypes->status}}:</b>
                                        </h6>
                                        @endforeach     
                                        @endforeach 
                                        
                                        <h6 class="mb-3">
                                            <b>Total Deduction:</b>
                                        </h6>
                                        <h6 class="mb-3">
                                            <b>Salary after Deduction:</b>
                                        </h6>
                           
                         </td>
                         <td class=" pl-0">
                           
                            @foreach ($salary_deduction_detail as $salary_deduction_details)                        
                            @foreach ($alldeductiontype as $alldeductiontypes)
                                <div style="width: 100%;">
                                    <h6 class="mb-3">
                                        @if ($salary_deduction_details->deduction_type == $alldeductiontypes->id)
                                            {{ number_format($salary_deduction_details->deduction_amount, 2) }}
                                        @else
                                            {{ '0.00' }}
                                        @endif
                                    </h6>
                                </div>
                            @endforeach
                        @endforeach
                        <h6 class="mb-3">
                            {{ number_format($totalamountdeduction, 2) }}
                        </h6>
                        <h6 class="mb-3">
                            {{ number_format($totalamountdeductionssalary, 2) }}
                        </h6>
                           
                        </td>
                     </tr>
            </table>

                   
           <hr style=" border: 1px solid #343a40; 
           margin-top: 20px;
           margin-bottom: 20px;height:0.1px;" >



            <div class="row pt-3" style="margin-left: 172px;">
                <div class="col-sm-6">
                    <h6 class="mb-0"><b>Tax: 
                        <span style=" margin-left:255px;">
                              0.00
                        </span>
                 </h6>
                </div>                  
            </div>

            
            <div class="row pt-3"  style="margin-left: 172px; margin-top:10px">
                <div class="col-sm-6">
                    <h6 class="mb-0"><b>Net Payable: 
                        <span style=" margin-left:190px;">
                            {{$salary->payable_salary}}
                        </span>
                 </h6>
                </div>                  
            </div>




            <table style="width: 100% ; margin-left:128 ;  pt-4 ;  margin-top:25px">
                <tr>
                    <td class="pl-0">

                        <h6 class="mb-2"><b>Signature:</b></h6>
 
                    </td>
                    <td class="pl-0">
                        @foreach ($signature as $signatures)
														<img src="data:image/png;base64,{{ $signatures->signature }}" 
														alt="Signature" style="width:150px; height: 50px; position:absolute; 
														margin-left:; margin-top:-40px">		
									
														@endforeach
                            <div class="signature-line mb-2" 
                            style="border-top: 2px solid black;
                            ; margin-top:5px;
                            width: 230px; position:relative
                           ">
                            </div>
                        </h6>
                    </td>
                </tr>
            </table>

            
       
        </div>
    </section>
</div>
</body>
</html>
