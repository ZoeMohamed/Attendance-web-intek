@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <div>
            <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Calendar Holiday</h2>
            <p class="mg-b-0">Detail of the employee in the system and show attendance log</p>
        </div>
    </div>
    <div class="main-dashboard-header-right">
        
    </div>
</div>
@endsection
@section('main_content')
<div class="row row-sm">
    <div class="col-lg-12 col-xl-12">
        <div class="main-content-body main-content-body-calendar card p-4">
            <div class="main-calendar" id="calendar"></div>
        </div>
    </div>
</div>

@endsection