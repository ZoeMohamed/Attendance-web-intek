@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<div>
							  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Report Attendance From ( {{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }} - {{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d')}} )</h2>
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
							</tr> -->
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
					
					<input class="form-control" id="" name="startdate" type="date" value="{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}">
					TO
					<input class="form-control" id="" name="enddate" type="date" value="{{ \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d') }}">
					<button class="btn btn-primary btn-block">Search</button>
				</form>
			</div>
		</div>
	</div>
	<div class="col-xl-9 col-md-12 col-lg-12">
	
    <div class="card mg-b-20">
        <div class="card-header pb-0">
            <div class="d-flex justify-content-end">
                <!-- <h4 class="card-title mg-b-0">Bordered Table</h4>
                <i class="mdi mdi-dots-horizontal text-gray"></i> -->
				@php
					$start_date = \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d');
					$end_date = \Carbon\Carbon::now()->endOfWeek()->format('Y-m-d');
				@endphp
				<a href="{{ route('report_export', ['start' => $start_date, 'end' => $end_date]) }}" class="btn btn-primary">Export to Excel</a>
            </div>
            <!-- <p class="tx-12 tx-gray-500 mb-2">Example of Valex Bordered Table.. <a href="">Learn more</a></p> -->
        </div>
        <div class="card-body">
            <div class="table-responsive" style="height: 500px;overflow-x:auto;overflow-y: scroll;">
            <table class="table" border="2">
            <thead>
            <tr>
                <th>Name</th>
                @foreach ($dates as $date)
                @php
                @endphp
                <th style="background-color: #eb4034; color: #faf8f7;">
                    {{ $date->format('Y-m-d') }}
                </th>
                @endforeach
                <!-- <th>Savings</th> -->
                <!-- <th rowspan="3">Savings for holiday!</th> -->
            </tr>
            </thead>
            <tbody>
            @foreach($final as $dat)
            <tr>
            <td rowspan="2" style="vertical-align: middle;">{{$dat["name"]}}</td>
                @foreach ($dates as $date)
                    @foreach($dat["attendance"] as $att)
                    @if(date('Y-m-d', strtotime($att["date"])) == $date->format('Y-m-d'))
                    @php
                        $attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7;text-align: center;'>P</td>";
                    @endphp
                    @endif
                    @endforeach
                @endforeach
                <!-- <td rowspan="0">$100</td> -->
            </tr>
            <tr>
                @foreach ($dates as $date)
                    @foreach($dat["attendance"] as $att)
                    @if(date('Y-m-d', strtotime($att["date"])) == $date->format('Y-m-d'))
                    <td>KET</td>
                    @endif
                    @endforeach
                @endforeach
            </tr>
            @endforeach
            </tbody>
            </table>
            </div>
        </div>
    </div>
			
	</div>
</div>

@endsection