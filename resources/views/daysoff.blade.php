@extends('olay')

@section('header_content')
@if(Auth::user()->id == 99999)

@else
    <div class="breadcrumb-header justify-content-between">
                            <div class="left-content">
                                <div>
                                    <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Days Off</h2>
                                    <p class="mg-b-0">Detail of the employee in the system and show Days Off log</p>
                                </div>
                            </div>
                            <div class="main-dashboard-header-right">
                                
                            </div>
                        </div>
    @endsection
    @section('main_content')
    <div class="row row-sm">
        <div class="col-sm-4 col-xl-4 col-lg-12">
            <div class="card user-wideget user-wideget-widget widget-user mb-3">
                <div class="widget-user-header bg-primary">
                    <h3 class="widget-user-username">{{ $card_profile->name }} </h3>
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
                    <img src="{{ $img }}" class="brround" stle="height:70px 80px;" alt="User Avatar">
                </div>
                <div class="user-wideget-footer"><br><br>
                    <div class="row">
                        {{--<div class="col-sm-12">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" name="duration" class="custom-control-input" id="duration_single" checked="" value="single">
                                <label class="custom-control-label" for="customSwitch1">Single</label>
                            </div>
                        </div>--}}
                        <div class="col-sm-6 border-right">
                            <div class="description-block">
                                <h5 class="description-header">{{ $card_profile->total }}</h5>
                                <span class="description-text">Total Cuti Diambil</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="description-block">
                                <h5 class="description-header">{{ $card_profile->sisa_cuti }}</h5>
                                <span class="description-text">Sisa Cuti</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- edit card disini --}}
            <div class="card">
                <div class="d-flex flex-row flex-nowrap overflow-auto" style="height: 255px">
                    @foreach ($card_status as $c)
                    <div class="card card mx-2 mt-2 mb-2" style="min-width: 175px; height:225px;">
                        @if($c->status==1)
                            <a class="card-header bg-success d-flex justify-content-center">
                                <h6 class="">Accept</h6>
                            </a>
                        @elseif($c->status==2)
                            <a class="card-header bg-danger d-flex justify-content-center">
                                <h6 class="">Reject</h6>
                            </a>
                        @else
                            <a class="card-header bg-warning d-flex justify-content-center">
                                <h6 class="">Waiting</h6>
                            </a>
                        @endif
                        <div class="card-body" style="padding: 1.0rem">
                            {{-- @foreach ($card_status as $item)
                            @endforeach --}}
                                
                            <!-- <h5 class="card-title">Special title treatment</h5>
                            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> -->
                            <!-- <button type="button"style="width:50px; height:50px;border-radius:0%;" class="btn btn-primary btn-circle btn-xl">Blue circular button</button> -->
                            <div class="row-3">
                                <div class="description-block mt-0 mb-2 text-align-left" style="text-align: left">
                                    <label style="margin-bottom: 2px">Tanggal Cuti</label>
                                    <span class="description-text">{{ $c->cuti }}</span>
                                    
                                </div>
                                <div class="description-block mt-0 mb-2 text-align-left" style="text-align: left">
                                    <label style="margin-bottom: 2px">Tanggal Masuk</label>
                                    <span class="description-text">{{ $c->masuk }}</span>
                                </div>
                                <div class="description-block mt-0 mb-2 text-align-left" style="text-align: left">
                                    <label style="margin-bottom: 2px">Tanggal Respon</label>
                                    <span class="description-text">{{ $c->respon }}</span>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                        
                    @endforeach
                </div>
            </div>
        </div>
            

        <div class="col-md-8 col-lg-8 col-xl-8 justify-content-start">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Form Cuti</h4>
                    </div>
                </div>
                <form action={{ url('days_off/create') }} method="get">

                    <div class="card-body">
                    <div class="col-lg-12 mg-t-20">
                        <div class="row">
                            <div class="col-4">
                                <label class="form-label">Nama</label>
                                <input required class="form-control" name='name' value={{ $card_profile->name }} readonly>
                            </div>
                            <div class="col-4">
                                <label class="form-label">Posisi</label>
                                <input required class="form-control" name='posisi' placeholder="Staff Programmer" >
                            </div>
                            <div class="col-4">
                                <label class="form-label">Jabatan</label>
                                <input required class="form-control" name='jabatan' value={{ $jabatan }} readonly>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                            <div class="col-5">
                                <label class="form-label">Atasan Langsung</label>
                                <input required class="form-control" name='atasan' placeholder="Nama Atasan">
                            </div>
                            <div class="col-5">
                                <label class="form-label">PIC Pengganti</label>
                                <input required class="form-control" name='pic_pengganti' placeholder="Nama PIC">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Hak Cuti Tahun</label>
                                <input required class="form-control" name='hak_cuti' value={{ $card_profile->sisa_cuti }} readonly>
                            </div>
                            <div class="col-12"></div>
                            <div class="col-4 mt-3">
                                <label class="form-label">Cuti</label>
                                <input required class="form-control" name='cuti' placeholder="DD/MM/YYYY" type="date" id="Date" value="">
                            </div>
                            <div class="col-4 mt-3">
                                <label class="form-label">Masuk Kantor</label>
                                <input required class="form-control" name='masuk' placeholder="DD/MM/YYYY" type="date">
                            </div>
                            <div class="col-4 mt-3">
                                <label class="form-label">Nomor yang dapat dihubungi</label>
                                <input required class="form-control" name='nomor_telepon' placeholder="62XXXXXXXXXXX" type="tel">
                            </div>
                            <div class="col-6 mt-3">
                                <label class="form-label">Pekerjaan yang diserahkan</label>
                                <textarea required class="form-control" name='pekerjaan' placeholder="Tugas 1 : " ></textarea>
                            </div>
                            <div class="col-6 mt-3">
                                <label class="form-label">Alasan</label>
                                <textarea required class="form-control" name='alasan' placeholder="Tambah Alasan..."></textarea>
                            </div>
                            <div class="col-12 mt-3 d-flex justify-content-end">
                                <button class="btn btn-success " type="submit">Submit</button>
                            </div>
                        </div>
                        
                       
                        
                        <!-- <input id="range" class="rangeslider3" data-extra-classes="irs-outline" name="example_name" type="text"> --}}
                </div> -->
                {{-- <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-success " type="submit">Submit</button>
                </div> --}}
                    <!-- <div class="total-revenue">  
                        <div>
                            <h4>6</h4>
                            <label><span class="bg-success"></span>Total</label>
                        </div>
                        <div>
                            <h4>6</h4>
                            <label><span class="bg-warning"></span>Sisa</label>
                        </div>
                        <div> -->
    
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    <!--Internal Apexchart js-->
    <!-- JQuery min js -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    {{-- <script>
    //         $('.datetimepicker1').datepicker(function(){
    //         startDate: new Date(),
    //         format: 'dd-mm-yyyy',
    //         todayHighlight:'TRUE',
    //         autoclose: true
    //  });

 $(document).ready(function() {
    $('#Date').datepicker({
        onSelect: function(dateText, inst) {
          
            var today = new Date();
            today = Date.parse(today.getMonth()+1+'/'+today.getDate()+'/'+today.getFullYear());
            
            var selDate = Date.parse(dateText);
            
            if(selDate < today) {
            
                $('#Date').val('');
                $(inst).datepicker('show');
            }
        }
    });
});
    </script> --}}

    
    {{-- <script>
    
    
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
            height:70px 249,
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
    </script> --}}
@endif
@endsection

