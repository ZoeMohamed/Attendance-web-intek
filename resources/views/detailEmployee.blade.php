@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<div>
							  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Detail Employee</h2>
							  <p class="mg-b-0">Detail of the employee in the system and show attendance log</p>
							</div>
						</div>
						<div class="main-dashboard-header-right">
							
						</div>
					</div>
@endsection
@section('main_content')
<div class="row row-sm">
    <div class="col-sm-4 col-xl-4 col-lg-12">
        <div class="card user-wideget user-wideget-widget widget-user">
            <div class="widget-user-header bg-primary">
                <h3 class="widget-user-username">{{ $prof->name }} </h3>
                <h5 class="widget-user-desc">
				<div class="form-check form-switch">
					<input class="form-check-input" type="checkbox" name="active_status" id="flexSwitchCheckChecked" {{ ($prof->status == 1) ? 'checked': ''}}>
					<label class="form-check-label" for="flexSwitchCheckChecked" id="labelactivate">{{ ($prof->status == 1) ? 'Aktif': 'Non-Aktif'}}</label>
				</div>
				</h5>
            </div>
            <div class="widget-user-image">
                @php
				$file = (isset($getImg->file)) ? $getImg->file : asset("assets/img/default.png");

				if(file_exists($file)){
					$img = asset($getImg->file);
				}else{
					$img = asset("assets/img/default.png");
				}
				
                @endphp
                <img src="{{ $img }}" class="brround" style="height: 80px;" alt="User Avatar">
            </div>
            <div class="user-wideget-footer"><br><br>
                <div class="row">
					{{--<div class="col-sm-12">
						<div class="custom-control custom-switch">
							<input type="checkbox" name="duration" class="custom-control-input" id="duration_single" checked="" value="single">
							<label class="custom-control-label" for="customSwitch1">Single</label>
						</div>
					</div>--}}
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ count($in) }}</h5>
                            <span class="description-text">IN</span>
                        </div>
                    </div>
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ count($late) }}</h5>
                            <span class="description-text">Late</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header">{{ array_sum($sum) }}</h5>
                            <span class="description-text">Total Day</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		{{--<div class="card mg-b-20 text-center">
			<div class="card-body">
				<img src="{{ asset('assets/img/svgicons/note_taking.svg') }}" alt="" class="wd-35p">
				<form method="POST" action="{{ url('report') }}">
				<h5 class="mg-b-10 mg-t-15 tx-18">ASSIGN LEAVE</h5>
				<!--<a href="#" class="text-muted">Check The Settings</a> -->
				
					@csrf
					<!-- <div class="custom-control custom-switch">
						<input type="checkbox" name="syncdata" class="custom-control-input" id="customSwitch1">
						<label class="custom-control-label" for="customSwitch1">Sync Data</label>
					</div> -->
					
					@if(Auth::user()->id == 99999)
						<select class="form-control" name="leave_type">
						<option label="Select Employee">
						</option>
						<option value="casual">Azhar Ashilah</option>
						<option value="sick">Eko</option>
						</select>
						<br>
					@else
					{!! Form::hidden('user_id', Auth::user()->id) !!}
					@endif
					<select class="form-control" name="leave_type" id="leave_type_id">
					<option label="Leave Type ..">
					</option>
					<option value="casual">Casual</option>
					<option value="sick">Sick</option>
					<option value="earned">Earned</option>
					</select><br>
					<div class="container">
						<div class="row">
							<div class="col-md-4">
								<div class="custom-control custom-switch">
									<input type="checkbox" name="duration" class="custom-control-input" id="duration_single" checked value="single">
									<label class="custom-control-label" for="customSwitch1">Single</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="custom-control custom-switch">
									<input type="checkbox" name="duration" class="custom-control-input" id="duration_multiple" value="multiple">
									<label class="custom-control-label" for="customSwitch1">Multiple</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="custom-control custom-switch">
									<input type="checkbox" name="duration" class="custom-control-input" id="duration_half_day" value="half day">
									<label class="custom-control-label" for="customSwitch1">Half Day</label>
								</div>
							</div>
						</div>
					</div>	<br>
					
					<div id="single-date">
						<input class="form-control" name="leave_date" id="single_date" type="text" value="{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}">
					</div>
					<br>
					<div class="container">
						<div class="row">
							<div class="col-md-6">Reason for absence</div>
							<div class="col-md-3"></div>
							<div class="col-md-3"></div>
						</div>
					</div>
					
					<textarea class="form-control" name="reason" id="reason" cols="30" rows="5"> Input your reason for absent here ...... </textarea>
					<br>
					{!! Form::hidden('status', 'pending') !!}
					<button class="btn btn-primary btn-block">Search</button>
				</form>
			</div>
			
		</div>--}}
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
		<div class="card bd-0 mg-b-20 bg-info-transparent alert p-0">
			<div class="card-header text-info font-weight-bold">
				<i class="far fa-check-circle"></i> Employee Chart Pie Detail
				<!-- <button aria-label="Close" class="close" data-dismiss="alert" type="button"><span aria-hidden="true">×</span></button> -->
			</div>
			<div class="card-body text-success">
			<div class="ht-200 ht-sm-300" id="chartemploye"></div>
			</div>
		</div>

		
    </div>
	
    <div class="col-md-8 col-lg-8 col-xl-8">
        <div class="card">
            <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">Status</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
                <!-- <p class="tx-12 text-muted mb-0">Attendance Status and Tracking. Track emloyee attendance from date to month</p> -->
				<div class="alert alert-success" id="result"> Attendance Status and Tracking. Track emloyee attendance from date to month</div>
			</div>
            <div class="card-body">
				<div class="col-lg-12 mg-t-20">
					<input id="range" class="rangeslider3" data-extra-classes="irs-outline" name="example_name" type="text">
				</div>
                <div class="total-revenue">
					
                    <div>
                        <h4>{{ count($in) }}</h4>
                        <label><span class="bg-success"></span>IN</label>
                    </div>
                    <div>
                        <h4>{{ count($late) }}</h4>
                        <label><span class="bg-warning"></span>LATE</label>
                    </div>
                    <div>
                        <h4>{{ array_sum($sum) }}</h4>
                        <label><span class="bg-danger"></span>Total Day</label>
                    </div>
					
                    </div>
                <div id="baremployee" class="sales-bar mt-4"></div>
            </div>
        </div>
		<div class="card">
            <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">List of Attendance</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
                <p class="tx-12 text-muted mb-0">Attendance history of week</p>
            </div>
            <div class="card-body">
			<div class="table-responsive" style="height: 500px;overflow-x:auto;overflow-y: scroll;">
                <table class="table key-buttons text-md-nowrap">
                <thead>
					<tr>
					
					@foreach($lasweek as $l)
					<th colspan="2" rowspan="2">
						{{$l->format('Y-m-d')}}
					</th>
					@endforeach
					</tr>
				</thead>
				<tbody border="2">
					
					<tr> 
						@for($i=0; $i<count($lasweek); $i++)
						<th>IN</th> 
						<th>OUT</th> 
						@endfor
						
					</tr> 
					<tr > 
						@foreach($datalastweek as $dl)
						<td style="background-color: #454955;color: white; -webkit-print-color-adjust: exact; text-align: center;" >
							{{ $dl["in"] }}
						</td>
						<td style="background-color: #335486;color: white; -webkit-print-color-adjust: exact; text-align: center;" >
						   {{ $dl["out"] }}
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
<!--Internal Apexchart js-->
<!-- JQuery min js -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
<script src="{{ asset('assets/js/apexcharts.js') }}"></script>
<script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>


    $(".select2").select2({
        formatNoMatches: function () {
            return "{{ __('messages.noRecordFound') }}";
        }
    });

    jQuery('#multi_date').datepicker({
        multidate: true,
        todayHighlight: true,
        weekStart:'{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}',
        format: 'YYY-mm-dd',
    });

    jQuery('#single_date').datepicker({
        autoclose: true,
        todayHighlight: true,
        weekStart:'{{ \Carbon\Carbon::now()->startOfWeek()->format('Y-m-d') }}',
        format: 'YYY-mm-dd',
    });

    $("input[name=duration]").click(function () {
        if($(this).val() == 'multiple'){
            $('#multi-date').show();
            $('#single-date').hide();
        }
        else{
            $('#multi-date').hide();
            $('#single-date').show();
        }
    })


    $('#save-form-2').click(function () {
        $.easyAjax({
            url: '{//{route('member.leaves.store')}}',
            container: '#createLeave',
            type: "POST",
            redirect: true,
            data: $('#createLeave').serialize()
        })
    });
</script>
<script type="text/javascript">
	let start = {{ $bulanfirst }};
	let end = {{ $bulanend }}
	$('.rangeslider3').ionRangeSlider({
		type: 'double',
		grid: true,
		min: 1,
		max: 12,
		from: start,
		to: end,
		prefix: 'Month ',
		onFinish: function(data){
			from = data.from;
			to = data.to;
			// $("#SomeValue").val(value);
			$.ajax({  
				type: 'GET',  
				url: window.location.href.split('?')[0], 
				data: { start: from, end: to },
				success: function(response) {
					// content.html(response);
					window.location.href = window.location.href.split('?')[0]+"?start="+from+"&end="+to;
				}
				
			});
			console.log(from);
			console.log(to);
		}
	});
	// Saving it's instance to var
	var slider = $("#range").data("ionRangeSlider");

	// Get values
	var from = slider.result.from;
	var to = slider.result.to;
</script>
<script type="text/javascript">
	var options = {
		series: [{{ count($in) }}, {{ count($late) }}, {{ array_sum($sum) }}],
		chart: {
		width: 380,
		type: 'pie',
	},
	colors: ["#22c03c", '#fbbc0b', '#ee335e'],
	labels: ['IN', 'LATE', 'NOTATTEND'],
	responsive: [{
		breakpoint: 480,
		options: {
		chart: {
			width: 200
		},
		legend: {
			position: 'bottom'
		}
		}
	}]
	};
	new ApexCharts(document.querySelector('#chartemploye'), options).render();
	// var chart = new ApexCharts(document.querySelector("#chart"), options);
	// chart.render();
</script>
<script type="text/javascript">
    var optionsBar = {
		chart: {
			height: 249,
			type: 'bar',
			toolbar: {
			show: false,
			},
			fontFamily: 'Nunito, sans-serif',
			// dropShadow: {
			//   enabled: true,
			//   top: 1,
			//   left: 1,
			//   blur: 2,
			//   opacity: 0.2,
			// }
		},
		colors: ["#22c03c", '#fbbc0b', '#ee335e'],
		plotOptions: {
				bar: {
					dataLabels: {
					enabled: false
					},
					columnWidth: '42%',
					endingShape: 'rounded',
				}
		},
	  	dataLabels: {
		  enabled: false
	  	},
	  	stroke: {
		  show: true,
		  width: 2,
		  endingShape: 'rounded',
		  colors: ['transparent'],
	  	},
		responsive: [{
			breakpoint: 576,
			options: {
				stroke: {
				show: true,
				width: 1,
				endingShape: 'rounded',
				colors: ['transparent'],
				},
			},
			
			
		}],
	   	series: [{
		  name: 'In time',
		  data: [
              @foreach($dateinArr as $di)
              {{ $di }},
              @endforeach
            ]
		}, {
			name: 'Late time',
			data: [
				@foreach($datelateArr as $dl)
				{{ $dl }},
				@endforeach
			]
		}, {
			name: 'Not attendance',
			data: [
				@foreach($dates as $dt)
					{{ $dt["countInmonth"] }} ,
				@endforeach
			]
		}],
		xaxis: {
			categories: [
				@foreach($list_month as $lm)
				'{{ $lm }}',
				@endforeach
				],
		},
		fill: {
			opacity: 1
		},
		legend: {
			show: false,
			floating: true,
			position: 'top',
			horizontalAlign: 'left',
			// offsetY: -36

		},
		tooltip: {
			y: {
				formatter: function (val) {
					return val + " X"
				}
			}
		}
	}
	new ApexCharts(document.querySelector('#baremployee'), optionsBar).render();
	$("#flexSwitchCheckChecked").click(function() {
		if($(this).prop("checked")){
			$.ajax({
					type: "POST",
					url: "{{url('detail/'.$prof->id)}}",
					data: {"_token": "{{ csrf_token() }}", "status_activate": 1}, 
					success: function(data) {
						$("#result").html(data);
						$("#labelactivate").html("Aktif");
				},
			});
			//alert($(this).val());

		}else{
			$.ajax({
					type: "POST",
					url: "{{url('detail/'.$prof->id)}}",
					data: {"_token": "{{ csrf_token() }}", "status_activate": 0}, 
					success: function(data) {
						$("#result").html(data);
						$("#labelactivate").html("Non-Aktif");
				},
			});
			//alert("UNCHECK "+$(this).val());
		}
	});
	/* Apexcharts (#bar) closed */
</script>
@endsection

