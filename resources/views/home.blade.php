@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
	<div class="left-content">
		<div>
			<h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back!, {{ Auth::user()->name }} <button class="btn btn-success" data-target="#addperson" data-toggle="modal">Add User</button> <button class="btn btn-warning" data-target="#addprofile" data-toggle="modal">Add Profile</button></h2>
			<p class="mg-b-0">Attendance monitoring dashboard</p>
			
			@if ($message = Session::get('message'))
			<div class="alert alert-success alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button> 
				<strong>{{ $message }}</strong>
			</div>
			@endif
		</div>
	</div>
	<div class="main-dashboard-header-right">
		<div class="input-group" id="dp3">
			<input id="datetimepicker" name="datefilter" class="form-control" placeholder="dd/mm/yy">
			<!-- <input type="date" id="datetimepicker2" name="datefilter" class="form-control" placeholder="Search"> -->
		</div>
		<!-- <div>
			<label class="tx-13">System Ratings</label>
			<div class="main-star">
				<i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star"></i> <span>(14,873)</span>
			</div>
		</div> -->
		<!-- <div>
			<label class="tx-13">Online Sales</label>
			<h5>563,275</h5>
		</div>
		<div>
			<label class="tx-13">Offline Sales</label>
			<h5>783,675</h5>
		</div> -->
	</div>
</div>
@endsection

@section('main_content')

	<!-- row -->
					<div class="row row-sm">
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card bg-primary-gradient text-white">
								<div class="card-body">
									<div class="counter-status d-flex md-mb-0">
										<div class="counter-icon">
											<i class="icon icon-people"></i>
										</div>
										<div class="ml-auto">
											<h5 class="tx-13 tx-white-8 mb-3">Total Employee</h5>
											<h2 class="counter mb-0 text-white">{{ $countEmploye }}</h2>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card bg-success-gradient text-white">
								<div class="card-body">
									<div class="counter-status d-flex md-mb-0">
										<div class="counter-icon">
											<i class="fe fe-pie-chart tx-40"></i>
										</div>
										<div class="ml-auto">
											<h5 class="tx-13 tx-white-8 mb-3">IN Attendance</h5>
											<h2 class="counter mb-0 text-white">{{ count($in) }}</h2>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card bg-danger-gradient text-white">
								<div class="card-body">
									<div class="counter-status d-flex md-mb-0">
										<div class="counter-icon">
											<i class="fe fe-clock tx-40"></i>
										</div>
										<div class="ml-auto">
											<h5 class="tx-13 tx-white-8 mb-3">Late Attendance</h5>
											<h2 class="counter mb-0 text-white">{{ count($late) }}</h2>
										</div>
									</div>
								</div>
							</div>
							
						</div>
						<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
							<div class="card bg-info-gradient text-white">
								<a href="#">
								<div class="card-body">
									<div class="counter-status d-flex md-mb-0">
										<div class="counter-icon">
											<i class="fe fe-arrow-right tx-40"></i>
										</div>
										<div class="ml-auto">
											<h5 class="tx-13 tx-white-8 mb-3">Not Attendance</h5>
											<h2 class="counter mb-0 text-white">{{ count($notatt) }}</h2>
										</div>
									</div>
								</a>
								</div>
							</div>
							<!-- <div class="card bg-info-gradient text-white">
								<div class="card-body">
									<div class="row">
										<div class="col-6">
											<div class="icon1 mt-2 text-center">
												<i class="fe fe-arrow-right tx-40"></i>
											</div>
										</div>
										<div class="col-6">
											<div class="mt-0 text-center">
												<span class="text-white">Not Attendance</span>
												<h2 class="text-white mb-0">{{ count($notatt) }}</h2>
											</div>
										</div>
									</div>
								</div>
							</div> -->
							<!-- <div class="card overflow-hidden sales-card bg-warning-gradient">
								<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
									<div class="">
										<h6 class="mb-3 tx-12 text-white">OUT Attendance</h6>
									</div>
									<div class="pb-0 mt-0">
										<div class="d-flex">
											<div class="">
												<h4 class="tx-20 font-weight-bold mb-1 text-white"></h4>
												<p class="mb-0 tx-12 text-white op-7">Attendance of the Day</p>
											</div>
											
										</div>
									</div>
								</div>
								<span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
							</div> -->
						</div>
					</div>
					<!-- row closed -->
					<!-- row -->
					
					<div class="row row-sm">
						<div class="col-md-12 col-xl-4">
							<div class="card">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<h4 class="card-title">IN Attendance <a href="{{ url('/home') }}?showintolerance=true"><button class="btn btn-sm btn-danger">Show Time Tolerance</button></a><a href="{{ url('/home') }}?mobile=true"><button class="btn btn-sm btn-warning">Mobile Only</button></a></h4>
										
										<i class="mdi mdi-dots-vertical"></i>
									</div>
									<p class="card-description mb-1">Time Category In Start At {{ (isset($timetable_in->start_at)) ? $timetable_in->start_at : "00:00:00"  }} - {{ (isset($timetable_in->end_at)) ? $timetable_in->end_at : "00:00:00" }}</p>
									<div style="max-height: 700px; overflow: auto;">
									
									@if(count($in) > 0 )
										@foreach($in as $dec)
											@php
												$getImages = \App\Vector::where('mprof_id', $dec->mprof_id)->first();
												$hasil2 = "";
												if($dec->in_tolerance_time != "00:00:00"){
													$name_day = \Carbon\Carbon::parse($dec->date." ".$dec->in_tolerance_time)->format('l');
													$getINtmtable = \App\Tmtable::where('day', $name_day)->where('type', 'in_tolerance')->first();
													$start_time = $getINtmtable->start_at;
													$login_time = $dec->in_tolerance_time;
													$start = \Carbon\Carbon::createFromFormat('H:i:s', $start_time);
													$end = \Carbon\Carbon::createFromFormat('H:i:s', $login_time);
													$hasil = $start->diff($end);
													$h = $hasil->h;
													$m = $hasil->i;
													$s = $hasil->s;
													if($hasil->format('%H') == "00"){
														$hasil2 = $hasil->format('%i:%s');
													}else{
														$hasil2 = $hasil->format('%H:%i:%s');
													}
												}
											@endphp
											@php
											//$img = asset($getImages->file);
											
											$file = (isset($getImages->file)) ? $getImages->file : asset("assets/img/default.png");
											$img_capture = (isset($dec->type_data)) ? $dec->type_data : $file;
										
											if($dec->type_data != ""){
												$img = asset('thumbnail/'.$dec->type_data);
											}elseif(file_exists($file)){
												$img = asset($getImages->file);
											}else{
												$img = asset("assets/img/default.png");
											}
											$st = "Mobile Apps";
											@endphp
											<div class="list d-flex align-items-center border-bottom py-3">
												<div class="avatar brround d-block cover-image" data-image-src="{{ $img }}">
													<span class="avatar-status bg-green"></span>
												</div>
												
												<div class="wrapper w-100 ml-3">
													<p class="mb-0">
													
													@if($mobile == true)
													<a class="button" data-toggle="collapse" href="#collapseExample{{$dec->mprof_id}}" role="button" aria-expanded="false" aria-controls="collapseExample"> <b>{{ $dec->name }} </b> <span class='badge badge-{{ ($dec->machine_id == 4) ? 'warning' : 'info' }}'> {{ $st }}</span></p></a>
													@else
													<a href="{{ url('detail/'.$dec->mprof_id) }}"><b>{{ $dec->name }} </b> <span class='badge badge-{{ ($dec->machine_id == 4) ? 'warning' : 'info' }}'> {{ $dec->name_office }}</span></p></a>
													@endif
													<div class="d-sm-flex justify-content-between align-items-center">
														<div class="d-flex align-items-center">
															@if($dec->in_time != "00:00:00")
															<i class="si si-check mr-1 text-success mr-2"></i>
															@else
															<i class="si si-check mr-1 text-warning mr-2"></i>
															@endif
															<!-- <i class="mdi mdi-clock text-muted mr-1"></i> -->
															@if($dec->in_time != "00:00:00" && $dec->status_employee == 1 || $dec->in_time != "00:00:00" && $dec->status_employee == 2 || $dec->in_time != "00:00:00" && $dec->status_employee == 3 || $dec->in_time != "00:00:00" && $dec->status_employee == 4)
															<p class="mb-0">-</p>
															@elseif($dec->in_time != "00:00:00")
															<p class="mb-0">{{ $dec->in_time }}</p>
															@elseif($dec->in_tolerance_time != "00:00:00")
															<p class="mb-0">{{ $dec->in_tolerance_time }}</p>
															@else
															<p class="mb-0">-</p>
															@endif
															
														</div>
														<small class="text-warning ml-auto">
														@if($dec->in_time != "00:00:00" && $dec->status_employee == 1)
														<!-- <span class='badge badge-info'>Attendance Manual</span> -->
														@elseif($dec->in_time != "00:00:00" & $dec->status_employee == 2)
														<span class='badge badge-info'>Sick</span>
														@elseif($dec->in_time != "00:00:00" & $dec->status_employee == 3)
														<span class='badge badge-info'>Leave Office</span>
														@elseif($dec->in_time != "00:00:00" & $dec->status_employee == 4)
														<span class='badge badge-info'>Permit</span>
														@elseif($dec->in_time != "00:00:00")

														@elseif($dec->status_employee == 1)
														<span class='badge badge-info'>Attendance Manual</span>
														@elseif($showIntolerance == true)
														In Tolerance : <span class='badge badge-warning'>{{ $hasil2 }}</span>
														@endif
														</small>
														
													</div>
													
													@if($mobile == true)
													<div class="collapse" id="collapseExample{{$dec->mprof_id}}">
													<div class="card card-body">
														<i class="fa fa-map-marker" aria-hidden="true"></i> {{ $dec->noted }}<br>
														<a href="{{ url('detail/'.$dec->mprof_id) }}">Detail Attendance</a>
													</div>
													</div>
													@endif
												</div>
											</div>
										@endforeach
									@else
										<h3 align="center">No Data Found</h3>
									@endif
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-xl-4">
							<div class="card">
								
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<h4 class="card-title">Late Attendance</h4>
										<i class="mdi mdi-dots-vertical"></i>
									</div>
									<p class="card-description mb-1">Time Category Late Start At {{ (isset($timetable_late->start_at)) ? $timetable_late->start_at : "00:00:00"  }} - {{ (isset($timetable_in->end_at)) ? $timetable_late->end_at : "00:00:00" }} </p>
									<!-- <p class="card-description mb-1">What're people doing right now</p> -->
									<div style="max-height: 700px; overflow: auto;">
									@if(count($late) > 0 )
									@foreach($late as $dec)
										@php
										$getImages = \App\Vector::where('mprof_id', $dec->mprof_id)->first();
										$name_day = \Carbon\Carbon::parse($dec->date." ".$dec->late_time)->format('l');
										$getINtmtable = \App\Tmtable::where('day', $name_day)->where('type', 'in_tolerance')->first();
										$check_tmtable = \App\Tmtable::where('day', $name_day)->where('start_at', '<=', $dec->late_time)->where('end_at', '>=', $dec->late_time)->first();
										$start_time = $getINtmtable->end_at;
        								$login_time = $dec->late_time;
										$start = \Carbon\Carbon::createFromFormat('H:i:s', $start_time);
										$end = \Carbon\Carbon::createFromFormat('H:i:s', $login_time);
										$hasil = $start->diff($end);
										$h = $hasil->h;
										$m = $hasil->i;
										$s = $hasil->s;
										if($hasil->format('%H') == "00"){
											$hasil2 = $hasil->format('%i:%s');
										}else{
											$hasil2 = $hasil->format('%H:%i:%s');
										}
									@endphp
									@php
										$file = (isset($getImages->file)) ? $getImages->file : asset("assets/img/default.png");
										$img_capture = (isset($dec->type_data)) ? $dec->type_data : $file;
										
										if($dec->type_data != ""){
											$img = asset('thumbnail/'.$dec->type_data);
										}elseif(file_exists($file)){
											$img = asset($getImages->file);
										}else{
											$img = asset("assets/img/default.png");
										}
										$st = "Mobile Apps";
									@endphp
									<div class="list d-flex align-items-center border-bottom py-3">
										<div class="avatar brround d-block cover-image" data-image-src="{{ $img }}">
											<span class="avatar-status bg-green"></span>
										</div>
										
										<div class="wrapper w-100 ml-3">
											<p class="mb-0">
											
											@if($mobile == true)
											<a class="button" data-toggle="collapse" href="#collapseExample{{$dec->mprof_id}}" role="button" aria-expanded="false" aria-controls="collapseExample"> <b>{{ $dec->name }} </b> <span class='badge badge-{{ ($dec->machine_id == 4) ? 'warning' : 'info' }}'> {{ $st }}</span></p></a>
											@else
											<a href="{{ url('detail/'.$dec->mprof_id) }}"><b>{{ $dec->name }} </b> <span class='badge badge-{{ ($dec->machine_id == 4) ? 'warning' : 'info' }}'> {{ $dec->name_office}}</span></p></a>
											@endif
											
											<div class="d-sm-flex justify-content-between align-items-center">
												<div class="d-flex align-items-center">
													<i class="mdi mdi-clock text-muted mr-1"></i>
													<p class="mb-0">{{ $dec->late_time }}</p>
												</div>
												<small class="text-danger ml-auto"> Late : <span class="badge badge-danger">{{ $hasil2 }}</span></small>
											</div>
											@if($mobile == true)
											<div class="collapse" id="collapseExample{{$dec->mprof_id}}">
											<div class="card card-body">
												<i class="fa fa-map-marker" aria-hidden="true"></i> {{ $dec->noted }}<br>
												<a href="{{ url('detail/'.$dec->mprof_id) }}">Detail Attendance</a>
											</div>
											</div>
											@endif
										</div>
										
										
									</div>
									@endforeach
									@else
										<h3 align="center">No Data Found</h3>
									@endif
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-xl-4">
							<div class="card">
								<div class="card-body">
									<div class="d-flex justify-content-between">
										<h4 class="card-title">Not Attendance</h4>
										<i class="mdi mdi-dots-vertical"></i>
									</div>
									<p class="card-description mb-1">List of employee not attendance now</p>
									<!-- <p class="card-description mb-1">What're people doing right now</p> -->
									<div style="max-height: 700px; overflow: auto;">
									@if(count($notatt) > 0 )
									@foreach($notatt as $dec)
									@php
										$file = $dec["img"];

										if(file_exists($file)){
											$img = asset($dec["img"]);
										}else{
											$img = asset("assets/img/default.png");
										}
									@endphp
									<div class="list d-flex align-items-center border-bottom py-3">
										<div class="avatar brround d-block cover-image" data-image-src="{{ $img }}">
											<span class="avatar-status bg-green"></span>
										</div>
										<a href="{{ url('detail/'.$dec['mprof_id']) }}" data-target="#select2modal" data-toggle="modal">
										<div class="wrapper w-100 ml-3">
											<p class="mb-0">
											<b>{{ $dec["name"] }} </b></p>
											<div class="d-sm-flex justify-content-between align-items-center">
												<div class="d-flex align-items-center">
													<i class="mdi mdi-clock text-muted mr-1"></i>
													<p class="mb-0">-</p>
												</div>
											</div>
										</div>
										</a>
									</div>
									@endforeach
									@else
										<h3 align="center">No Data Found</h3>
									@endif
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div class="modal" id="select2modal">
						<div class="modal-dialog" role="document">
							<div class="modal-content modal-content-demo">
								<div class="modal-header">
									<h6 class="modal-title">Manual Attendance</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
								</div>
								<form action="{{ url('/reciveManual/data') }}" method="POST">
								@csrf
								<div class="modal-body">
									<h6>Select Employee</h6>
									<!-- Select2 -->
									<select class="form-control" name="employee_id">
										<option label="Choose one">
										</option>
										@foreach($notatt as $nt)
											<option value="{{ $nt['mprof_id'] }}">
											{{ $nt["name"] }}
											</option>
										@endforeach
									</select><br>
									<h6>Status</h6>
									<select class="form-control" name="status_employe">
										<option label="Choose one">
										</option>
										<option value="1">IN / Masuk</option>
										<option value="2">SICK / Sakit</option>
										<option value="10">CUTI</option>
										<!-- <option value="3">LEAVE / Tugas Luar </option>
										<option value="4">PERMIT / Izin </option> -->
									</select><br>
									<h6>Date Range</h6><br>
									<input type="text" name="daterange" class="form-control date" placeholder="Pick the multiple dates"><br>
									<h6>Note</h6>
									<textarea class="form-control mg-t-20" name="noted" placeholder="If any note for status ,please you fill this text area ,thanks" required="" rows="3"></textarea>
									
									<!-- Select2 -->
									<!-- <p class="mt-3">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p> -->
								</div>
								<div class="modal-footer">
									<button class="btn ripple btn-primary" type="submit">Save changes</button>
									<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
								</div>
								</form>
							</div>
						</div>
					</div>

					<div class="modal" id="addperson">
						<div class="modal-dialog" role="document">
							<div class="modal-content modal-content-demo">
								<div class="modal-header">
									<h6 class="modal-title">Add User for Auth Apps</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
								</div>
								<form action="{{ url('/add/auth') }}" method="POST">
								@csrf
								<div class="modal-body">
									<h6>Select Employee</h6>
									<!-- Select2 -->
									<select class="form-control" name="employee_id">
										<option label="Choose one">
										</option>
										@foreach($user_notacc as $nt)
											<option value="{{ $nt['mprof_id'] }}">
											{{ $nt["name"] }}
											</option>
										@endforeach
									</select><br>
									<h6>Email Employee</h6>
									<input type="text" name="email" class="form-control" placeholder="Input Email Employee"><br>

									<h6>Password</h6>
									<input type="password" name="password" class="form-control" placeholder="Input Your Password"><br>
									<div class="form-group">
									<label for="">Team Category/Division</label>
									<select name="team_category" class="form-control">
										<option value="5" selected>Select Divisi</option>
										<option value="0">Support</option>
										<option value="1">Programmer</option>
										<!-- <option value="2">Driver</option> -->
										<option value="3">Finance</option>
										<option value="4">Admin</option>
										<option value="5">HRGA</option>
										<option value="7">PM</option>
										<option value="8">MicroController</option>
									</select>
									<!-- Select2 -->
									<!-- <p class="mt-3">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p> -->
								</div>
								<div class="modal-footer">
									<button class="btn ripple btn-primary" type="submit">Add Employee</button>
									<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
								</div>
								</form>
							</div>
						</div>
					</div>

				

@endsection
@section('footer')
<div class="modal" id="addprofile">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-content-demo2">
			<div class="modal-header">
				<h6 class="modal-title">Add Profile Info</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
			</div>
			<form action="{{ url('/add/person') }}" method="POST">
			@csrf
			<div class="modal-body">
				<h6>ID Employee</h6>
				<input type="text" name="idemployee" class="form-control" placeholder="Input ID Employee "><br>

				<h6>Name</h6>
				<input type="text" name="name" class="form-control" placeholder="Input Full Name"><br>
				<h6>Phone Number</h6>
				<input type="text" name="phone_number" class="form-control" placeholder="Wajib di isi untuk notification"><br>
				<h6>Office Location</h6>
				<!-- Select2 -->
				<select class="form-control" name="office_id">
					<option label="Choose one">
					</option>
					@foreach($list_office as $lo)
					<option value="{{$lo->id}}">{{ $lo->name_office }}</option>
					@endforeach
				</select><br>
			</div>
			<div class="modal-footer">
				<button class="btn ripple btn-primary" type="submit">Add Profile</button>
				<button class="btn ripple btn-secondary" data-dismiss="modal" type="button">Close</button>
			</div>
			</form>
		</div>
	</div>
</div>
@endsection
