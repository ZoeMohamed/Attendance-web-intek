@extends('olay_export')

@section('main_content')
{{--<style type="text/css">
    .table-fixed tbody {
        height: 300px;
        overflow-y: auto;
        width: 100%;
    }

    .table-fixed thead>tr>th {
        position: fixed;
    }
</style>--}}

<h5 class="">Report Attendance From ( {{ $minTime }} - {{ $maxTime }} )</h5><br>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th style="background-color: #eb4034; color: #faf8f7;">Name</th>
                @foreach ($dates as $date)
                @php
                @endphp
                <th style="background-color: #eb4034; color: #faf8f7;">
                    {{ $date->format('Y-m-d') }}
                </th>
                @endforeach
            </tr>
        </thead>
        <tbody border="2">
            @foreach($final as $dat)
            <tr>
                <td rowspan="2" style="vertical-align: middle;">{{$dat["name"]}}</td>
                @foreach ($dates as $date)
                @php
                $attendance_for_day ="<td style='background-color: #454955;color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center; display: flex; justify-content: center; align-items: center;'>A</td>";
                @endphp
                @foreach($dat["attendance"] as $att)
                @if(date('Y-m-d', strtotime($att["date"])) == $date->format('Y-m-d'))
                @php

                    //if($dat["id"] == 13){
                    //    $attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7;text-align: center;'>P</td>";
                    //}else{
                        if($att["time"]["in"] == "08:00" && $att["status_employee"] == 1){
                            $attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7; text-align: center;'>MP</td>";
                        }elseif($att["time"]["in"] != "00:00" && $att["status_employee"] == null){
                            $attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7;text-align: center;'>P</td>";
                        }elseif($att["time"]["late"] != "00:00" && $att["status_employee"] == null){
                            $attendance_for_day = "<td style='background-color: #A53F2B; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;' >L</td>";
                        }elseif($att["time"]["in_tolerance_time"] != "00:00" && $att["status_employee"] == null){
                            //$attendance_for_day = "<td style='background-color: #A53F2B; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;'>T</td>";
                            $attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;'>P</td>";
                        }elseif($att["time"]["over"] != "00:00" && $att["status_employee"] == null){
                            $attendance_for_day = "<td style='background-color: #4263f5; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;'>O</td>";
                        }elseif($att["status_employee"] == 1){
                            //$attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7; text-align: center;'>MP</td>";
                            $attendance_for_day = "<td style='background-color: #72B01D; color: #faf8f7; text-align: center;'>P</td>";
                        }elseif($att["status_employee"] == 2){
                            $attendance_for_day = "<td style='background-color: #c8d11f; color: #faf8f7; text-align: center;'>S</td>";
                        }elseif($att["status_employee"] == 3){
                            $attendance_for_day = "<td style='background-color: #d1961f; color: #faf8f7; text-align: center;'>LO</td>";
                        }elseif($att["status_employee"] == 4){
                            $attendance_for_day = "<td style='background-color: #FBB13C; color: #faf8f7; text-align: center;'>IZ</td>";
                        }
                    //}
                @endphp
                @endif
                @endforeach
                @php
                echo $attendance_for_day;
                @endphp
                @endforeach
            </tr>
            <tr>
                @foreach ($dates as $dt)
                @php
                $attendance_for_day ="<td rowspan='2' style='background-color: #454955;color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center; display: flex; justify-content: center; align-items: center;'>-</td>";
                @endphp
                @foreach($dat["attendance"] as $att_c)
                @if(date('Y-m-d', strtotime($att_c["date"])) == $dt->format('Y-m-d'))
                @php
                //if($dat["id"] == 13){
                //    $attendance_for_day = "<td rowspan='2' style='background-color: #72B01D; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>08:".rand(20, 35)."  - ".$att_c['time']['out']."</td>";
                //}else{
                    if($att_c["time"]["in"] == "08:00" && $att_c["status_employee"] == 1){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #72B01D; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>".$att_c['time']['in']."  - ".$att_c['time']['out']."</td>";
                    }elseif($att_c["time"]["in"] != "00:00" && $att_c["status_employee"] == null){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #72B01D; color: #faf8f7;text-align: center;display: flex; justify-content: center; align-items: center;'>".$att_c['time']['in']." - ".$att_c['time']['out']."</td>";
                    }elseif($att_c["time"]["late"] != "00:00" && $att_c["status_employee"] == null){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #A53F2B; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center; display: flex; justify-content: center; align-items: center;'>".$att_c['time']['late']."  - ".$att_c['time']['out']."</td>";
                    }elseif($att_c["time"]["in_tolerance_time"] != "00:00" && $att_c["status_employee"] == null){
                        //$attendance_for_day = "<td rowspan='2' style='background-color: #A53F2B; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;display: flex; justify-content: center; align-items: center;'>".$att_c['time']['in_tolerance_time']."  - ".$att_c['time']['out']."</td>";
                        $attendance_for_day = "<td rowspan='2' style='background-color: #72B01D; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;display: flex; justify-content: center; align-items: center;'>".$att_c['time']['in_tolerance_time']."  - ".$att_c['time']['out']."</td>";
                    }elseif($att_c["time"]["over"] != "00:00" && $att_c["status_employee"] == null){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #4263f5; color: #faf8f7; -webkit-print-color-adjust: exact; text-align: center;display: flex; justify-content: center; align-items: center;'>".$att_c['time']['over']."  - ".$att_c['time']['out']."</td>";
                    }elseif($att_c["status_employee"] == 1){
                        //$attendance_for_day = "<td rowspan='2' style='background-color: #72B01D; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>KET</td>";
                        $attendance_for_day = "<td rowspan='2' style='background-color: #72B01D; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>KET</td>";
                    }elseif($att_c["status_employee"] == 2){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #c8d11f; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>KET</td>";
                    }elseif($att_c["status_employee"] == 3){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #d1961f; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>KET</td>";
                    }elseif($att_c["status_employee"] == 4){
                        $attendance_for_day = "<td rowspan='2' style='background-color: #FBB13C; color: #faf8f7; text-align: center;display: flex; justify-content: center; align-items: center;'>KET</td>";
                    }
                //}
                @endphp
                @endif
                @endforeach
                @php
                echo $attendance_for_day;
                @endphp
                @endforeach
                
            </tr>
            <tr rowspan='2'> 
                <td style="background-color: #fcba03;">
                
                TOTAL : {{ $dat["total"] }} | SICK : {{ $dat["total_sick"] }} | LATE : {{ $dat["total_late"] }} | PERMIT : {{ $dat["total_permit"] }}
                </td>
            <tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection