@extends('olay')

@section('header_content')
<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<div>
							  <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">Hi, welcome back!, {{ Auth::user()->name }}</h2>
							  <p class="mg-b-0">Attendance monitoring dashboard  </p><br>
                              
							</div>
						</div>
						
					</div>
@endsection
@section('main_content')
<div class="row row-sm">
    <div class="col-xl-12">

        <div class="table-responsive">
            <table id="example" class="table key-buttons text-md-nowrap" border="1" align="center" cellpadding="10px">
                <thead>
                    <tr>
                        <th rowspan="3" style="text-align: center; background-color: #261C15; color: #faf8f7;">Day</th>
                        <th colspan="10" style="text-align: center; background-color: #261C15; color: #faf8f7;"> TIME 
                        @if(Auth::user()->id == 99999)
                            <button class="btn-sm btn-success" data-target="#select2modal" data-toggle="modal"><i class="typcn typcn-edit"></i></button>
                        @endif
                        </th>
                    </tr>
                    <tr>
                        <th colspan="2" style="text-align: center; background-color: #261C15; color: #faf8f7;">IN TIME</th>
                        <th colspan="2" style="text-align: center; background-color: #261C15; color: #faf8f7;">IN Tolerance Time</th>
                        <th colspan="2" style="text-align: center; background-color: #261C15; color: #faf8f7;">LATE Time</th>
                        <th colspan="2" style="text-align: center; background-color: #261C15; color: #faf8f7;">OUT Time</th>
                        <th colspan="2" style="text-align: center; background-color: #261C15; color: #faf8f7;">OVER Time</th>
                    </tr>
                    <tr>
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">Begin</th>
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">End</th>

                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">Begin</th>
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">End</th>

                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">Begin</th>
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">End</th>
                        
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">Begin</th>
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">End</th>

                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">Begin</th>
                        <th style="text-align: center; background-color: #F05D23; color: #faf8f7;">End</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($tmtable as $tb)
                    <tr>
                        <td rowspan="2">{{ $tb[0]->day }}</td>

                        @for($i=0; $i<count($tb); $i++)
                        <td rowspan="2">{{ $tb[$i]->start_at }}</td>
                        <td rowspan="2">{{ $tb[$i]->end_at }}</td>
                        @endfor
                    </tr>
                    <tr>
                    @endforeach
                        
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal" id="select2modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Edit TimeTable</h6><button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ url('/editTmtable') }}" method="POST">
            @csrf
            <div class="modal-body">
                <h6>Select Type Time</h6>
                <!-- Select2 -->
                <select class="form-control selecttime" name="type_time">
                    <option>Choose one
                    </option>
                    <option id="1" value="in">IN</option>
                    <option id="2" value="in_tolerance">IN TOLERANCE TIME</option>
                    <option id="3" value="late">LATE TIME</option>
                    <option id="4" value="out">OUT TIME</option>
                    <option id="5" value="over">OVER TIME</option>
                </select><br>
                <div id="formnya">
                <!-- <h6>Monday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="monday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="monday_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div>
                <h6>Tuesday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="tuesday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="tuesday_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div>
                <h6>Wednesday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="wednesday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="wednesday_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div>
                <h6>Thursday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="thursday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="thursday_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div>
                <h6>Friday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="friday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="friday_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div>
                <h6>Saturday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="saturday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="saturdat_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div>
                <h6>Sunday</h6>
                <div class="row">
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="sunday_begin" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                    <div class="col-md-6 ml-auto">
                        <input type="time" name="sunday_end" class="form-control" placeholder="Pick the multiple dates">
                    </div>
                </div> -->
                </div>
                <!-- <h6>Date Range</h6><br>
                <input type="text" name="daterange" class="form-control date" placeholder="Pick the multiple dates"><br>
                <h6>Date Range</h6><br>
                <input type="text" name="daterange" class="form-control date" placeholder="Pick the multiple dates"><br>
                <h6>Date Range</h6><br>
                <input type="text" name="daterange" class="form-control date" placeholder="Pick the multiple dates"><br>
                <h6>Date Range</h6><br>
                <input type="text" name="daterange" class="form-control date" placeholder="Pick the multiple dates"><br>
                <h6>Date Range</h6><br>
                <input type="text" name="daterange" class="form-control date" placeholder="Pick the multiple dates"><br> -->
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
<!-- JQuery min js -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
//START OBSERVING WHATSAPP ACCOUNT
$(document).ready(function () {
$('.selecttime').change(function () {
    var id = $(this).find(':selected')[0].id;
    //alert(id); 
    $.ajax({
        type: 'GET',
        url: "{{ url('/getTime') }}",
        data: {
            'type_time': id
        },
        success: function (data) {
            // the next thing you want to do 
            var $country = $('#formnya');
            $country.empty();
            $('#formnya').empty();
            for (var i = 0; i < data.length; i++) {
                //$country.append('<option id=' + data[i].sysid + ' value=' + data[i].name + '>' + data[i].name + '</option>');
                $country.append('<h6>'+data[i]['name']+'</h6>\
                <input type="hidden" name="'+data[i]['name']+'[]" value="'+data[i]['name']+'">\
                <div class="row">\
                    <div class="col-md-6 ml-auto">\
                        <input type="time" name="'+data[i]['name']+'[]" class="form-control" placeholder="Pick the multiple dates" value="'+data[i]['begin']+'">\
                    </div>\
                    <div class="col-md-6 ml-auto">\
                        <input type="time" name="'+data[i]['name']+'[]" class="form-control" placeholder="Pick the multiple dates" value="'+data[i]['end']+'">\
                    </div>\
                </div>');
            }

            //manually trigger a change event for the contry so that the change handler will get triggered
            $country.change();
        }
    });

});
});
</script>
@endsection

