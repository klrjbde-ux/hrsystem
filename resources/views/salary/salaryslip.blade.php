@extends('master')

@section('content')
<main id="main" class="main">
    <div class="container-fluid">
        <section class="section d-flex flex-column align-items-center justify-content-center">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
								<div class="d-flex align-items-center justify-content-between">
									<a href="#" class="logo d-flex align-items-center">
										<img src="/assets/img/logo.png" alt="">
										<span class="d-none d-lg-block">BLS Sol</span>
									</a>
									
								</div>
									<hr>
								
										<div class="bg-primary">
											<h6 class="pl-2 pt-1 pb-1 text-white">
												<i class="fas fa-user fa-sm text-white"></i> Employee Details
											</h6>
										</div>
										<div class="row pt-2">
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Name:</b></h6>
													<h6 class="col-8">{{$employee->firstname}} {{$employee->lastname}}</h6>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Department:</b></h6>
													<h6 class="col-8">
													{{ $employee->Department->department_name ?? 'N/A'}}
													</h6>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Designation:</b></h6>
													<h6 class="col-8">{{ $employee->Designation->designation_name ?? 'N/A' }}</h6>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Salary Month:</b></h6>
													<h6 class="col-8">{{$salary->month}}</h6>
												</div>
											</div>
										</div>

										<div class="bg-primary mt-3">
											<h6 class="pl-2 pt-1 pb-1 text-white">
												<i class="fas fa-id-card fa-sm text-white"></i> Contact Details
											</h6>
										</div>
										<div class="row pt-2">
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Contact No:</b></h6>
													<h6 class="col-8">{{$employee->contact_no}}</h6>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Emergency No:</b></h6>
													<h6 class="col-8">{{$employee->emergency_contact}}</h6>
												</div>
											</div>
											
											<div class="col-sm-6">
												<div class="row">
													<h6 class="col-4"><b>Official Email:</b></h6>
													<h6 class="col-8">{{$employee->personal_email}}</h6>
												</div>
											</div>
										</div>

										<table style="width: 100%;">
											<tr class="bg-transparent shadow-none">
											
												<td class="col-sm-6 pl-0">
													<div class="bg-primary mt-3">
														<h6 class="pl-2 pt-1 pb-1 text-white">
															<i class="fas fa-money-bill text-white"></i> Salary Details
														</h6>
													</div>
												</td>
												<td class="col-sm-6 pl-0">
													<div class="bg-primary mt-3">
														<h6 class="pl-2 pt-1 pb-1 text-white">
															<i class="fas fa-money-bill text-white"></i> Deductions
														</h6>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="col-12">
														<div class="row">
															<h6 class="col-8"><b>Basic Payment:</b></h6>
															<h6 class="col-4">{{ $employee->gross_salary }}</h6>
														</div>
													</div>
													
													<div class="col-12">
														@foreach ($allbonustype as $allbonustypes)
															<div class="row">
																<h6 class="col-8"><b>{{ $allbonustypes->status }}</b></h6>
																@php
																	// Find the corresponding bonus amount
																	$bonusDetail = $salary_bonus_detail->firstWhere('bonus_type', $allbonustypes->id);
																@endphp
																<h6 class="col-4">{{ $bonusDetail ? $bonusDetail->bonus_amount : 0.00 }}</h6>
															</div>
														@endforeach
													</div>
													
													<div class="col-12">
														<div class="row">
															<h6 class="col-8"><b>Total Gross Salary:</b></h6>
															<h6 class="col-4">{{ $totalamount }}</h6>
														</div>
													</div>
												</td>
											
											
											




												
												<td>
													
												
														
													@foreach ($salary_deduction_detail as $salary_deduction_details)
													
													<div class="col-12">
														<div class="row">

															@foreach ($alldeductiontype as $alldeductiontypes)

															<h6 class="col-8"><b>{{$alldeductiontypes->status}}</b></h6>
															@if ($salary_deduction_details->deduction_type == $alldeductiontypes->id)
															<h6 class="col-4">{{$salary_deduction_details->deduction_amount}}</h6>																
															@else
															<h6 class="col-4">{{0.00}}</h6>	
															@endif
															@endforeach
														</div>
													</div>
											
													<div class="col-12">
														<div class="row">
															<h6 class="col-8"><b>Total Deduction:</b></h6>
															<h6 class="col-4">{{$totalamountdeduction}}</h6>
														</div>
													</div>
													<div class="col-12">
														<div class="row">
															<h6 class="col-8"><b>Salary after Deduction:</b></h6>
															<h6 class="col-4">{{$totalamountdeductionssalary}}</h6>
														</div>
													</div>
													

												</td>

												@endforeach
												
													
													{{-- <div class="col-12">
														<div class="row">
															
															<h6 class="col-4"><b>{{$deduction->status}}</b></h6>
															<h6 class="col-8">{{$salary->deduction}}</h6>
														</div>
													</div> --}}
												
											</tr>
	                                 	</table>
										<div class="row">
										</div>
										<div class="row pt-2">
											<div class="col-sm-6"></div>
											
											<div class="col-sm-6">
												<div>
													<h6 class="d-flex justify-content-between pr-4"><b>Tax</b>
														
													</h6>
												</div>

												<hr class="mr-2">

												<div>
													<h6 class="d-flex justify-content-between pr-4"><b>Net Payable:</b>
														{{$salary->payable_salary}}
													</h6>
												</div>
											
												<hr class="mr-2">

												<div>
													<h6 class="d-flex justify-content-between pr-4"><b>Signature:</b>
														@foreach ($signature as $signatures)
														<img src="data:image/png;base64,{{ $signatures->signature }}" 
														alt="Signature" style="width:150px; height: 50px; position:absolute; 
														margin-left:270px; margin-top:-40px">		
									
														@endforeach

														<div class="signature-line" 
														style="border-top: 2px solid black;
														
														width: 230px; 
														margin-top: 15px;"></div>
													</h6>
												</div>
												

</div>
										</div>
										<div class="row no-print pt-3">
											<div class="col-12 text-right">
											@if(Auth::user()->isAllowed('web:Pdf:generateSlipPDF'))
												<a href="{{ route('pdf', ['id' => $salary->id, 'employee_id' => $salary->employee->id]) }}" rel="noopener" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Generate PDF</a>
											@endif
											</div>
										</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
    </div>
</main>






@stop