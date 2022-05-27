<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MAttend;
use App\MLogging;
use App\Tmtable;
use App\MProf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return redirect('/login');
        //return response()->json($query);
        
    }
    

    public function jsonReport($start, $end){

        $minTime = \Carbon\Carbon::parse($start, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($end, 'Asia/Jakarta');

        $month = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('m');
        //dd($month); 
        $year = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('yy');
        $day = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('dd');
        $users = MProf::get();
        
        $final = [];
        
        $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
        
        //sql Server query
        //$attendances = DB::connection('sqlsrv')->table('attendance_log')->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
        
	foreach($users as $user)
            {
                $date = $user->date;
                $name_day = Carbon::parse($date)->format('l');
                $salesman['id'] = $user->id;
                $salesman['name'] = $user->name;
                $attend = [];
                foreach($attendances as $attendance)
                {
                    
                    if($attendance->mprof_id == $user->id)
                    {
                        if($attendance->in_time != "00:00:00"){
                            $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $attendance->in_time)->where('end_at', '>=', $attendance->in_time)->first();
                        }elseif($attendance->late_time != "00:00:00"){
                            $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $attendance->late_time)->where('end_at', '>=', $attendance->late_time)->first();
                        }
                        $attend_2['keterangan'] = ( isset($check_tmtable->type) ) ? $check_tmtable->type : "" ;
                        $attend_2['date'] = $attendance->date;
                        $attend_2['time'] = ["in" => $attendance->in_time, "late"=> $attendance->late_time, "in_tolerance_time"=> $attendance->in_tolerance_time, 'over'=> $attendance->over_time];
                        $attend []= $attend_2;
                    }

                }
                $salesman['attendance'] = $attend;
                $final[] = $salesman;
            }
        //dd($final);
        $period = CarbonPeriod::create($minTime, $maxTime);

        // // Iterate over the period
       

        // Convert the period to an array of dates
        $dates = $period->toArray();
        //dd($dates);
        return response()->json($final);
    }
    public function export_absensi($type,$begind,$end){
        
        // $beg = Carbon::parse($begind, 'Asia/Jakarta');
        // $ending = Carbon::parse($end, 'Asia/Jakarta');
        $minTime = \Carbon\Carbon::parse($begind, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($end, 'Asia/Jakarta');

        
        $users = MProf::get();
        
        $final = [];
        // $attendances = Attendance::where('keterangan', 'Pulang')
        //         // ->where('attend_at', '>=', $minTime )
        //         // ->where('attend_at', '<=', $maxTime )
        //         ->whereBetween('attend_at', [$minTime, $maxTime])
        //         ->get();
        $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->whereBetween('created_at', [$minTime, $maxTime])->get();
        
        foreach($users as $user)
            {
                $date = $user->created_at;
                $name_day = Carbon::parse($date)->format('l');
                $time = Carbon::parse($date)->format('H:i:s');
                $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $time)->where('end_at', '>=', $time)->first();
                $salesman['id'] = $user->id;
                $salesman['name'] = $user->name;
                $attend = [];
                foreach($attendances as $attendance)
                {
                    
                    if($attendance->mprof_id == $user->id)
                    {
                        $attend_2['attendance'] = $attendance->attend_at;
                        $attend_2['keterangan'] = $check_tmtable->type;
                        $attend_2['date'] = $attendance->attend_at;
                        $attend []= $attend_2;
                    }

                }
                $salesman['attendance'] = $attend;
                $final[] = $salesman;
            }

        return json_encode($final);
        //return view('report')->with('final', $final);
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
