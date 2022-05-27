@extends('olay')
<style type="text/css">
</style>
@section('header_content')
<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<div>
							  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back!, {{ Auth::user()->name }}</h2>
							  <p class="mg-b-0">Attendance monitoring dashboard  </p><br>
                <!-- <h3 align="center">On Progress , Regards LEX</h3> -->
                <div class="alert alert-warning"><h3 style="color: grey;"> Untuk melakukan pembatasan akses di perbolehkan izin absen ,dapat kita aktifkan dengan cara menekan button di setiap nama yang ingin kita batasi berdasarkan lokasi office </div>
							</div>
						</div>
						
					</div>
@endsection
@section('main_content')
<div class="row row-sm">
    <div class="col-xl-12">
        <div id="map"></div>
        <br><br>
        <table class="table table-striped">
            <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              
              <th scope="col">Ori ID</th>
              @foreach($get_office as $dto)
              <th scope="col">On {{$dto->name_office}}</th>
              @endforeach
            </tr>
            </thead>
            <tbody>
              @php 
              $i = 1;
              $a = 1;
              @endphp
              @foreach($get_data as $data)
              
              <tr>
                <th scope="row">{{ $a++ }}</th>
                <td>{{ $data->name }}</td>
                <td>{{ $data->user_id }}</td>
                
                @foreach($get_office as $dto)
                    @php
                    $check_datanya = \App\MappingModLoc::where('office_id', $dto->id)->where('user_id', $data->user_id)->first();
                    @endphp
                    @if($check_datanya != null)
                    <td>
                        <form action="{{ url('/add/mapping') }}" method="POST" id="form" class="form-inline">
                        @csrf
                        <input type="hidden" name="ori_user_id" id="oriid{{$data->user_id}}{{$dto->id}}" value="{{$data->user_id}}">
                        <input type="hidden" name="machine_id" id="mac{{$data->user_id}}{{$dto->id}}" value="{{$dto->id}}">
                        <input type="hidden" name="user_id" id="uid{{$data->user_id}}{{$dto->id}}" value="{{$data->user_id}}">
                        <label class="sr-only" for="inlineFormInputName2">UID</label>
                        {{--<input type="text" name="user_id" value="{{$check_datanya->user_id}}" class="form-control mb-2 mr-sm-2" style="width: 100px;" id="uid{{$data->user_id}}{{$dto->id}}" placeholder="User ID" disabled>--}}

                        <div class="custom-control custom-switch">
                        <input  type="checkbox" name ="active" onclick="disableform({{$data->user_id}}{{$dto->id}})" class="custom-control-input" id="customSwitch{{$data->user_id}}{{$dto->id}}" checked>
                        <label class="custom-control-label" for="customSwitch{{$data->user_id}}{{$dto->id}}"></label>
                        </div>
                        <!-- <div class="form-group">
                            
                            <select class="form-control" name="active" id="exampleFormControlSelect1">
                            <option value="1" selected>Aktif</option>
                            <option value="2">NonAktif</option>
                            
                            </select>
                        </div> -->

                        <!-- <button type="submit" class="btn btn-primary mb-2">Submit</button> -->
                        </form>
                        
                    </td>

                    @else
                    <td>
                        <form action="{{ url('/add/mapping') }}" method="POST" id="form" class="form-inline">
                        @csrf
                        <input type="hidden" name="ori_user_id" id="oriid{{$data->user_id}}{{$dto->id}}" value="{{$data->user_id}}">
                        <input type="hidden" name="machine_id" id="mac{{$data->user_id}}{{$dto->id}}" value="{{$dto->id}}">
                        <input type="hidden" name="user_id" id="uid{{$data->user_id}}{{$dto->id}}" value="{{$data->user_id}}">
                        <label class="sr-only" for="inlineFormInputName2">UID</label>
                        {{--<input type="text" name="user_id" class="form-control mb-2 mr-sm-2" style="width: 100px;" id="uid{{$data->user_id}}{{$dto->id}}" placeholder=" {{ $dto->name_office }}">--}}

                        <div class="custom-control custom-switch">
                        <input  type="checkbox" name ="active" onclick="disableform({{$data->user_id}}{{$dto->id}})" class="custom-control-input" id="customSwitch{{$data->user_id}}{{$dto->id}}">
                        <label class="custom-control-label" for="customSwitch{{$data->user_id}}{{$dto->id}}" ></label>
                        </div>
                        <!-- <div class="form-group">
                            
                            <select class="form-control" name="active" id="exampleFormControlSelect1">
                            <option value="1">Aktif</option>
                            <option value="2" selected>NonAktif</option>
                            
                            </select>
                        </div> -->

                        <!-- <button type="submit" class="btn btn-primary mb-2">Submit</button> -->
                        </form>
                        
                    </td>
                    @endif
                @endforeach
                
                
              </tr>
              @endforeach
            </tbody>
            
        </table>
        

    </div>
</div>

@endsection
@section('script')

<script>
        function disableform(id){
          var switchStatus = false;
          $("#customSwitch"+id).on('change', function() {
              if ($(this).is(':checked')) {
                  switchStatus = $(this).is(':checked');
                  document.getElementById('uid'+id).setAttribute("disabled", "true");
                  var input = document.createElement("input");
                  input.setAttribute("type", "hidden");
                  input.setAttribute("name", "checkedupdate");
                  input.setAttribute("value", "update");

                  var input_hidden = document.createElement("input");
                  input_hidden.setAttribute("type", "hidden");
                  input_hidden.setAttribute("name", "user_id");
                  input_hidden.setAttribute("value", $('#uid'+id).val());

                  var input_hidden_mac = document.createElement("input");
                  input_hidden_mac.setAttribute("type", "hidden");
                  input_hidden_mac.setAttribute("name", "machine_id");
                  input_hidden_mac.setAttribute("value", $('#mac'+id).val());

                  var input_hidden_oriid = document.createElement("input");
                  input_hidden_oriid.setAttribute("type", "hidden");
                  input_hidden_oriid.setAttribute("name", "ori_user_id");
                  input_hidden_oriid.setAttribute("value", $('#oriid'+id).val());

                  //alert($('#oriid'+id).val());
                  //append to form element that you want .
                  
                  document.getElementById("form").appendChild(input);
                  document.getElementById("form").appendChild(input_hidden);
                  document.getElementById("form").appendChild(input_hidden_mac);
                  document.getElementById("form").appendChild(input_hidden_oriid);
                  $("#form").submit();
              }
              else {
                switchStatus = $(this).is(':checked');
                document.getElementById('uid'+id).removeAttribute("disabled");
                document.getElementById('uid'+id).focus();
              }
          });
          // document.getElementById('customSwitch'+id).addEventListener('change', function() {
          //   if (document.getElementById('customSwitch'+id).unchecked) {
          //     document.getElementById('uid'+id).removeAttribute("disabled");
          //     document.getElementById('uid'+id).focus();
          //   }

          //   // if (!document.getElementById('customSwitch'+).checked) {
          //   //   document.getElementById('customMessageTextArea').setAttribute("disabled", "true");
          //   // }
          // });
        }
        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(-6.1753924,106.8271528),
          
          zoom: 11
        });
        var infoWindow = new google.maps.InfoWindow;

          downloadUrl('{{ url('/whitelist/api') }}', function(data) {

            var markers=JSON.parse(data.responseText);

            markers.forEach(function(element) {          
                var id_office = element.office_id;
                var nama_pariwisata = element.name_office;
                var alamat = element.address;
                var point = new google.maps.LatLng(
                    parseFloat(element.lat),
                    parseFloat(element.long));

                var infowincontent = document.createElement('div');
                var strong = document.createElement('strong');
                strong.textContent = nama_pariwisata
                infowincontent.appendChild(strong);
                infowincontent.appendChild(document.createElement('br'));

                var text = document.createElement('text');
                text.textContent = alamat
                infowincontent.appendChild(text);
                var marker = new google.maps.Marker({
                  map: map,
                  position: point
                });
                marker.addListener('click', function() {
                  console.log("CLIK"+element.office_id);
                  $.ajax({
                    url: "{{ url('whitelist') }}",
                    type: "get", //send it through get method
                    data: { 
                      id_office: id_office, 
                    },
                    success: function(response) {
                      //Do Something
                      window.location.href = window.location.href.split('?')[0]+"?id_office="+id_office;
                    },
                    error: function(xhr) {
                      //Do Something to handle error
                    }
                  });
                  infoWindow.setContent(infowincontent);
                  infoWindow.open(map, marker);
                });
                var circle = new google.maps.Circle({
                  map: map,
                  radius: element.radius_allow,    // 10 miles in metres
                  fillColor: '#AA0000'
                });
                circle.bindTo('center', marker, 'position');
            });
            
          });
        }
      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
    </script>
     <script
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAlQLZyf324-A8BGbeKn8KtYvTOa-S0eMI&callback=initMap&libraries=&v=weekly"
      defer
    ></script>
@endsection