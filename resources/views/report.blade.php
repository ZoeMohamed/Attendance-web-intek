@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<div>
							  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Report Attendance From ( {{$minTime->format('Y-m-d')}} - {{ $maxTime->format('Y-m-d')}} )</h2>
							  <p class="mg-b-0">Attendance monitoring dashboard</p>
							</div>
						</div>
						<div class="main-dashboard-header-right">
							<!--  -->
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
<style type="text/css">
	.table-fixed tbody {
		height: 300px;
		overflow-y: auto;
		width: 100%;
	}
	.table-fixed thead > tr > th {
		position: fixed;
	}
	</style>
<div class="row row-sm">
	
	<div class="col-xl-3 col-md-12 col-lg-12">
		<div class="card bd-0 mg-b-20 bg-info-transparent alert p-0">
			<div class="card-header text-info font-weight-bold">
				<i class="far fa-check-circle"></i>  Information Color
				<!-- <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button> -->
			</div>
			<div class="card-body text-info">
				<div class="table-responsive">
					<table class="table mg-b-0 text-md-nowrap">
						<tbody>
						<tr>
								<td style="background-color: #454955;color: white; -webkit-print-color-adjust: exact; text-align: center;"> A </td>
								<td>Not Attendance / Tidak Masuk</td>
							</tr>
							<tr>
								<td style='background-color: #72B01D; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>P</td>
								<td>Present / Hadir</td>
							</tr>
							<tr>
								<td style='background-color: #A53F2B; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>L</td>
								<td>Late / Terlambat</td>
							</tr>
							<!-- <tr>
								<td style='background-color: #A53F2B; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>T</td>
								<td>IN Tolerance</td>
							</tr>
							<tr>
								<td style='background-color: #d13c0a; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>L</td>
								<td>Late / Terlambat</td>
							</tr>
							<tr>
								<td style='background-color: #72B01D; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>MP</td>
								<td>Manual Present</td>
							</tr>
							<tr>
								<td style='background-color: #c8d11f; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>S</td>
								<td>Sick</td>
							</tr>
							<tr>
								<td style='background-color: #d1961f; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>LO</td>
								<td>Leave Office</td>
							</tr>-->
							<tr>
								<td style='background-color: #FBB13C; border-color: #72B01D; color: white; -webkit-print-color-adjust: exact;  text-align: center;'>IZ</td>
								<td>Permit</td>
							</tr>
							
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="card mg-b-20 text-center">
			<div class="card-body">
				<img src="{{ asset('assets/img/svgicons/note_taking.svg') }}" alt="" class="wd-35p">
				<h5 class="mg-b-10 mg-t-15 tx-18">Select date for get result report from other date</h5>
				<!--<a href="#" class="text-muted">Check The Settings</a> -->
				<form method="POST" action="{{ url('report') }}">
					@csrf
					<div class="custom-control custom-switch">
						<input type="checkbox" name="syncdata" class="custom-control-input" id="customSwitch1">
						<label class="custom-control-label" for="customSwitch1">Sync Data</label>
					</div>
					<select class="form-select" name="time_filter" aria-label="Default select example">
						<option value="all_time" selected>Select Filter</option>
						<option value="0">All</option>
						<option value="1">Terlambat</option>
						<option value="2">Tepat Waktu</option>
					</select>
					<select class="form-select" name="divisi" aria-label="Default select example">
						
						<option value="all" selected>Select Divisi</option>
						<option value="all">All Divisi</option>
						<option value="0">Support</option>
						<option value="1">Programmer</option>
						<!-- <option value="2">Driver</option> -->
						<option value="3">Finance</option>
						<option value="4">Admin</option>
						<option value="5">HRGA</option>
						<option value="7">PM</option>
						<option value="8">MicroController</option>
						
					</select>
					<br>
					<input class="form-control" id="" name="startdate" type="date" value="{{ $minTime->format('Y-m-d') }}">
					TO
					<input class="form-control" id="" name="enddate" type="date" value="{{ $maxTime->format('Y-m-d') }}">
					<button class="btn btn-primary btn-block">Search</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-xl-9 col-md-12 col-lg-12">
	
    <div class="card mg-b-20">
        <div class="card-header pb-0">
			
            
			@if($session != "all_division")
			<div class="d-flex justify-content-end">
                <!-- <h4 class="card-title mg-b-0">Report Attendance </h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i> -->
				<a href="{{ route('report_export', ['start' => $minTime->format('Y-m-d'), 'end' => $maxTime->format('Y-m-d'), 'division'=> $session, 'option'=> $options]) }}" class="btn btn-primary">Export to Excel</a>
            </div>
			@else
			<div class="d-flex justify-content-end">
                <!-- <h4 class="card-title mg-b-0">Report Attendance </h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i> -->
				<a href="{{ route('report_export', ['start' => $minTime->format('Y-m-d'), 'end' => $maxTime->format('Y-m-d'), 'division'=> 'no', 'option'=> 'no']) }}" class="btn btn-primary">Export to Excel</a>
            </div>
			@endif
            <!-- <p class="tx-12 tx-gray-500 mb-2">Report Attendance</p> -->
        </div>
        <div class="card-body">
            <div class="table-responsive" style="height: 500px;overflow-x:auto;overflow-y: scroll;">
                <table class="table key-buttons text-md-nowrap">
                <thead>
					<tr>
					<th>Name</th>
					@foreach ($dates as $date)
					@php
					@endphp
					<th>
						{{ $date->format('Y-m-d') }}
					</th>
					@endforeach
					<th> Total</th>
					</tr>
				</thead>
				<tbody border="2">
					@foreach($final as $dat)
					<tr>
						<th >{{$dat["name"]}}</th>
						
					@foreach ($dates as $date)
						@php
							$attendance_for_day ="<td style='background-color: #454955;color: white; text-align: center;'>A</td>"; 
						@endphp
						
						@foreach($dat["attendance"] as $att)
								@if(date('Y-m-d', strtotime($att["date"])) == $date->format('Y-m-d'))
								@php
										if($att["time"]["in"] == "08:00:00" && $att["status_employee"] == 1){
											$attendance_for_day = "<td style='background-color: #72B01D; color: white; text-align: center;'>P</td>";

//$attendance_for_day = "<td style='background-color: #72B01D; color: white; text-align: center;'>MP</td>";
										}elseif($att["time"]["in"] != "00:00:00" && $att["status_employee"] == null){
											$attendance_for_day = "<td style='background-color: #72B01D; color: white;text-align: center;'>P</td>";
										}elseif($att["time"]["late"] != "00:00:00"){
											$attendance_for_day = "<td style='background-color: #A53F2B; color: white; -webkit-print-color-adjust: exact; text-align: center;' >L</td>";
										}elseif($att["time"]["in_tolerance_time"] != "00:00:00"){
											//$attendance_for_day = "<td style='background-color: #A53F2B; color: white; -webkit-print-color-adjust: exact; text-align: center;'>T</td>";
											$attendance_for_day = "<td style='background-color: #72B01D; color: white; -webkit-print-color-adjust: exact; text-align: center;'>P</td>";
										}elseif($att["time"]["over"] != "00:00:00"){
											$attendance_for_day = "<td style='background-color: #4263f5; color: white; -webkit-print-color-adjust: exact; text-align: center;'>O</td>";
										}elseif($att["status_employee"] == 1){
											$attendance_for_day = "<td style='background-color: #72B01D; color: white; text-align: center;'>P</td>";

//$attendance_for_day = "<td style='background-color: #72B01D; color: white; text-align: center;'>MP</td>";
										}elseif($att["status_employee"] == 2){
											$attendance_for_day = "<td style='background-color: #c8d11f; color: white; text-align: center;'>S</td>";
										}elseif($att["status_employee"] == 3){
											$attendance_for_day = "<td style='background-color: #d1961f; color: white; text-align: center;'>LO</td>";
										}elseif($att["status_employee"] == 4){
											$attendance_for_day = "<td style='background-color: #FBB13C; color: white; text-align: center;'>IZ</td>";
										}
									@endphp
								@endif
						@endforeach
						@php
						echo $attendance_for_day;
						@endphp
					@endforeach
					<td>
					@php
						$total = count($dat["attendance"]);
					@endphp
					{{ $total }}
					</td>
					@endforeach
					</tr>
				    </tbody>
                </table>
            </div>
        </div>
    </div>
			
	</div>
</div>
@endsection