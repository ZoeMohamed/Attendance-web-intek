@extends('olay')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
@section('header_content')
<style>
  @import url(https://fonts.googleapis.com/css?family=Roboto);

  body {
    font-family: Roboto, sans-serif;
  }

  #chart {
    max-width: 650px;
    max-height: 350px;
    margin: 35px auto;
  }
</style>
  
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
    <div class="row">
        <div class="col-sm-7 col-xl-7 col-lg-12">
          <div class="card">
            <div class="d-flex flex-row flex-nowrap overflow-auto" style="height: 400px">
              @foreach ($card_request as $card)
              <div class="modal fade" id="myModal{{ $card->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content" style="overflow-y: scroll">
                    <div class="modal-body">
                    <div class="d-flex justify-content-center">
                          @php
                          $getImg = App\Vector::where('mprof_id', $card->user_id)->first();
                          $file = (isset($getImg->file)) ? $getImg->file : asset("assets/img/default.png");
          
                          if(file_exists($file)){
                              $img = asset($getImg->file);
                          }else{
                              $img = asset("assets/img/default.png");
                          }

                          @endphp
                          <img src="{{$img}}" class="brround" style="height:70px 80px; max-width: 35%;" alt="User Avatar">
                    </div>
                    <h5 class="text-center mt-3">{{ $card->name }}</h5>
                    <hr style="margin-top:3px; margin-bottom:3px;">
                    <div class="row pd-12">
                        <form style="margin-bottom: 0px; height: 230px">
                          <div class="form-group row mb-0">
                            <label class="col-sm-5 col-form-label">Waktu Cuti</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control-plaintext" value=": {{ $card->total_days }} Hari">
                            </div>
                          </div>
                          <div class="form-group row mb-0">
                            <label class="col-sm-5 col-form-label">No. Telp</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$card->phone_number}}">
                            </div>
                          </div>
                          <div class="form-group row mb-0">
                            <label class="col-sm-5 col-form-label">Tanggal Cuti</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$card->days_off_date}}">
                            </div>
                          </div>
                          <div class="form-group row mb-0">
                            <label class="col-sm-5 col-form-label">Tanggal Masuk</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$card->back_to_office}}">
                            </div>
                          </div>
                          <div class="form-group row mb-1">
                            <label class="col-sm-5 col-form-label">Karyawan Pengganti</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control-plaintext" value=": {{$card->replacement_pic}}">
                            </div>
                          </div>
                          {{-- <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Pekerjaan yang diserahkan</label>
                            <div class="col-sm-7">
                              <input type="text" readonly class="form-control-plaintext" id="staticEmail" value=": {{ $card->submitted_job }}">
                            </div>
                          </div> --}}
                          
                          <div class="form-group mb-1">
                            <label class="form-label">Pekerjaan yang diserahkan:</label>
                            <textarea rows="4" cols="55" {{--  style="width: 450px; min-height: 90px; height: 50px;"" --}} style="width:100%; overflow-y: scroll; resize:none;" readonly>{{ $card->submitted_job }}</textarea>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Alasan:</label>
                          <textarea rows="4" cols="55" {{--  style="width: 450px; min-height: 90px; height: 50px;"" --}} style="width:100%; overflow-y: scroll; resize:none;" readonly>{{$card->reason}}</textarea>
                        </div>
                        <div class="col-12 d-flex justify-content-end pd-0">
                          <a href="{{ url('/days_off/print/')."/".$card->id }}" class="btn btn-primary mb-3" style="height:35px;">
                            <i class="mdi mdi-file-import"></i>
                            <span>Print</span> 
                          </a>
                        </div>
                        </form>
                            
                        
                    </div>
                    </div>
                  </div>
                </div>
              </div>
                <div class="card-blobodyck shadow p-2 mb-2 bg-body rounded mx-2 mg-t-20" style="max-width: 270px; height:325px;">
                    <div class="card-body ">
                      <!-- detail modal -->
                        <i class="mdi mdi-information-outline d-flex justify-content-end" data-toggle="modal" data-target="#myModal{{ $card->id }}" style="font-size: 20px; color: grey"></i>
                        
                        <div class="d-flex justify-content-center">
                                    @php  
                                    $getImg = App\Vector::where('mprof_id', $card->user_id)->first();
                                    $file = (isset($getImg->file)) ? $getImg->file : asset("assets/img/default.png");
                    
                                    if(file_exists($file)){
                                        $img = asset($getImg->file);
                                    }else{
                                        $img = asset("assets/img/default.png");
                                    }
                                    
                                    @endphp
                                <img src="{{$img}}" class="brround" style="height:70px 80px; max-width: 35%;" alt="User Avatar">
                        </div>
                          <h5 class="text-center mt-3">{{ $card->name }}</h5>
                          <hr style="margin-top:3px; margin-bottom:3px;">
                            <div class="col-12 mg-t-15">
                                    <label class="form-label">Alasan:</label>
                                    <p style="margin-bottom: 2px;">{{ substr($card->reason, 0, 20) }}
                                        {{ strlen($card->reason) > 20 ? "..." : "" }}</p>      
                            </div>
                        <div class="d-flex justify-content-between mt-3">
                          <form action={{ url('/days_off/reject/')."/".$card->id }} method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger mr-3" style="height: 35px; width: 82px; font-size: small; font-weight: bold">Reject</button>
                          </form>
                          <form action={{ url('/days_off/accept/')."/".$card->id }} method="post">
                            @csrf
                            <button type="submit" class="btn btn-success" style="height: 35px; width: 82px; font-size: small; font-weight: bold">Accept</button>
                          </form>

                          {{-- <button class="btn btn-danger" onclick= style="height: 35px; width: 82px; font-size: small; ">Reject</button>
                          <button class="btn btn-success" onclick={{ url('/days_off/reject/')."/".$card->id }} style="height: 35px; width: 82px; font-size: small; ">Accept</button> --}}
                        </div>
                    </div>
                </div>
              @endforeach
            </div>
          </div>
         </div>
    
        
        <div class="col-md-5 col-lg-5 col-xl-5">
            <div class="card">
                <div class="card-body user-widget widget-user" style="position: relative; height: 402px">
                      <div id="chart" style="min-height: 365px; margin-bottom:1px; margin-top:1px"></div>
                </div>
            </div>
        </div>
    </div>

        <div class=""></div>
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                  <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-0">List Of Employee</h4>
                    <!-- <i class="mdi mdi-sort"></i> -->
                </div>
                    <p class="tx-12 text-muted mb-0">Attendance history of week</p>
                  </div>
               
                  <div class="card-body">
                  <div class="table-responsive">
                  <table class="table"id="myTable" >
                        <div class="col-sm-12" style="overflow-x:auto;">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Cuti</th>
                          <th scope="col">Masuk</th>
                          <th scope="col">Status</th>
                          <th scope="col">Tanggal Respon HRD</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php
                        $iteration=0;
                        @endphp
                        @foreach ($table as $t)
                        @php
                        $iteration+=1;
                        @endphp
                          <tr>
                            <td>{{ $iteration }}</td>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->cuti }}</td>
                            <td>{{ $t->tanggal_awal }}</td>
                            <td>
                              @if ($t['status'] == 1)
                                  <span class='badge badge-success'>Diterima</span>
                              @elseif ($t['status'] == 2)
                                  <span class='badge badge-danger'>Ditolak</span>
                              @endif
                          </td>
                            <td>{{ $t->respon }}</td>
                          </tr>
                          @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
                </div>
            </div>
    </div>
        
    </div>
    <!--Internal Apexchart js-->
    <!-- JQuery min js -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')}}"></script>
    <script src="{{ asset('assets/js/apexcharts.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/custom-select/custom-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script>
  $(document).ready( function () {
    $('#myTable').DataTable();
} );
  var accept = {!! json_encode($data_accept) !!};
  var reject = {!! json_encode($data_reject) !!};
  // console.log(accept)
  // let data=0
  // accept.forEach(myFunction);
  console.log(accept.valueOf())
        var options = {
          series: [
          {
            name: "Accepted",
            data: [
              @foreach($data_accept as $da)
                  {{ $da }},
              @endforeach
            
            ]
          },
          {
            name: "Rejected",
            data: [
              @foreach($data_reject as $dr)
                  {{ $dr }},
              @endforeach
            ]
          }
        ],
          chart: {
          height: 350,
          type: 'line',
          dropShadow: {
            enabled: true,
            color: '#000',
            top: 18,
            left: 7,
            blur: 10,
            opacity: 0.2
          },
          toolbar: {
            show: false
          }
        },
        colors: ['#77B6EA', '#D60000'],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: 'smooth'
        },
        title: {
          text: 'Employee days off data statistics',
          align: 'left'
        },
        grid: {
          borderColor: '#e7e7e7',
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        markers: {
          size: 1
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sep','Oct','Nov','Dec'],
          title: {
            text: 'Month'
          }
        },
        yaxis: {
          title: {
            // text: 'Temperature'
          },
          min: 0,
          max: 10
        },
        legend: {
          position: 'top',
          horizontalAlign: 'right',
          floating: true,
          offsetY: -25,
          offsetX: -5
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();


        // function myFunction(item,index) {
        //   data+=item+','
        // }
      
    
      
      // var options = {
      //     series: [
      //     {
      //       name: "Accepted",
      //       data: [28, 29, 33, 36, 32, 32, 33]
      //     },
      //     {
      //       name: "Rejected",
      //       data: [12, 11, 14, 18, 17, 13, 13]
      //     }
      //   ],
      //     chart: {
      //     height: 350,
      //     type: 'line',
      //     dropShadow: {
      //       enabled: true,
      //       color: '#000',
      //       top: 18,
      //       left: 7,
      //       blur: 10,
      //       opacity: 0.2
      //     },
      //     toolbar: {
      //       show: false
      //     }
      //   },
      //   colors: ['#77B6EA', '#545454'],
      //   dataLabels: {
      //     enabled: true,
      //   },
      //   stroke: {
      //     curve: 'smooth'
      //   },
      //   title: {
      //     text: 'Average High & Low Temperature',
      //     align: 'left'
      //   },
      //   grid: {
      //     borderColor: '#e7e7e7',
      //     row: {
      //       colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
      //       opacity: 0.5
      //     },
      //   },
      //   markers: {
      //     size: 1
      //   },
      //   xaxis: {
      //     categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
      //     title: {
      //       text: 'Month'
      //     }
      //   },
      //   yaxis: {
      //     title: {
      //       text: 'Temperature'
      //     },
      //     min: 5,
      //     max: 40
      //   },
      //   legend: {
      //     position: 'top',
      //     horizontalAlign: 'right',
      //     floating: true,
      //     offsetY: -25,
      //     offsetX: -5
      //   }
      //   };

      //   var chart = new ApexCharts(document.querySelector("#chart"), options);
      //   chart.render();
      
      
    
    </script>
    @endsection

