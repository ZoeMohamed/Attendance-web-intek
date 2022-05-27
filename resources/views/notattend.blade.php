@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<div>
							  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">List of employee not Attend</h2>
							  <p class="mg-b-0">Attendance monitoring dashboard</p>
							</div>
						</div>
						<div class="main-dashboard-header-right">
							
						</div>
					</div>
@endsection
@section('main_content')

<div class="col-xl-12">
							<div class="card mg-b-20">
								<div class="card-header pb-0">
									<div class="d-flex justify-content-between">
										<h4 class="card-title mg-b-0">Bordered Table</h4>
										<i class="mdi mdi-dots-horizontal text-gray"></i>
									</div>
									<p class="tx-12 tx-gray-500 mb-2">Example of Valex Bordered Table.. <a href="">Learn more</a></p>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="example" class="table key-buttons text-md-nowrap">
											<thead>
												<tr>
													<th class="border-bottom-0" width="10%">No</th>
													<th class="border-bottom-0">Name</th>
												</tr>
											</thead>
											<tbody>
												@php
												$no=1;
												@endphp
												@foreach($notatt as $nt)
												<tr>
													<td align="center" >{{ $no++ }}</td>
													<td>{{ $nt["name"] }}</td>
												</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
@endsection