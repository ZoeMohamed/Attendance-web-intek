<?php

namespace App\Exports;

use App\MLogging;
use App\Tmtable;
use App\MProf;
use App\MAttend;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

class AttendanceExport implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return MLogging::all();
    // }

    private $start_date = '';
    private $end_date = '';

    public function __construct($start, $end, $division, $options){
        $this->start_date = $start;
        $this->end_date = $end;
        $this->division = $division;
        $this->options = $options;

    }

    public function view(): View{
        date_default_timezone_set('Asia/Jakarta'); 
        // $minTime = \Carbon\Carbon::parse($date->startOfWeek(), 'Asia/Jakarta');
        // $maxTime = \Carbon\Carbon::parse($date->endOfWeek(), 'Asia/Jakarta');
        $minTime = \Carbon\Carbon::parse($this->start_date, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($this->end_date, 'Asia/Jakarta');

        $month = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('m');
        $year = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('yy');
        $day = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('dd');
        
        
        $final = [];
        $period = CarbonPeriod::create($minTime, $maxTime);
        $dates = $period->toArray();
        if($this->division == "no"){
            $users = MProf::get();
            $session = "all_division";

            if($this->options == 1){ //Terlambat
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.late_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }elseif($this->options == 2){ //tepat waktu
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }else{
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }
            
        }else{
            //dd($request->divisi);
            $users = MProf::where('division_id', $this->division)->get();
            $session = $this->division;
            if($this->options == 1){ //Terlambat
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $this->division)->where('m_attends.late_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }elseif($this->options == 2){ //tepat waktu
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $this->division)->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }else{
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $this->division)->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }
            
        }
        //
        //$attendances = MAttend::where('date', '>=', $minTime->format('Y-m-d'))->where('date', '<=', $maxTime->format('Y-m-d'))->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->get();
        //$attendances = MAttend::where('in_time', '!=', '00:00:00')->where('status_employee', 'status_employ')->whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
        $arrdat = array();
        foreach($dates as $ddt){
            $arrdat []= $ddt->format('Y-m-d');
        }
        //dd($arrdat);
        foreach($users as $user)
            {
                $date = $user->date;
                $name_day = Carbon::parse($date)->format('l');
                $salesman['id'] = $user->id;
                $salesman['name'] = $user->name;
                $attend = [];
                $in = MAttend::whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->where('mprof_id', $user->id)->where('in_time', '!=', '00:00:00')->orWhere('in_tolerance_time', '!=', '00:00:00')->where('mprof_id', $user->id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_profs.name', 'ASC')->get();
                $late = MAttend::whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->where('mprof_id', $user->id)->where('late_time', '!=', '00:00:00')->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_profs.name', 'ASC')->get();
                $permit = MAttend::whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->where('mprof_id', $user->id)->where('status_employee', 4)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_profs.name', 'ASC')->get();
                $sick = MAttend::whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->where('mprof_id', $user->id)->where('status_employee', 2)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_profs.name', 'ASC')->get();
                $cuti = MAttend::whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->where('mprof_id', $user->id)->where('status_employee', 10)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_profs.name', 'ASC')->get();
                foreach($attendances as $attendance)
                {
                    
                    if($attendance->mprof_id == $user->id && in_array($attendance->date, $arrdat))
                    {
                        if($attendance->in_time != "00:00:00"){
                            $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $attendance->in_time)->where('end_at', '>=', $attendance->in_time)->first();
                        }elseif($attendance->late_time != "00:00:00"){
                            $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $attendance->late_time)->where('end_at', '>=', $attendance->late_time)->first();
                        }

                        if($attendance->out_time != "00:00:00" && $attendance->over_time == "00:00:00"){
                            $data_out = $attendance->out_time;
                        }else{
                            $data_out = $attendance->over_time;
                        }
                        
                        $attend_2['keterangan'] = ( isset($check_tmtable->type) ) ? $check_tmtable->type : "" ;
                        $attend_2['date'] = $attendance->date;
                        $attend_2['time'] = ["in" => Carbon::parse($attendance->in_time)->format('H:i'), "late"=> Carbon::parse($attendance->late_time)->format('H:i'), "in_tolerance_time"=> Carbon::parse($attendance->in_tolerance_time)->format('H:i'), "over"=> Carbon::parse($attendance->over_time)->format('H:i'), "out"=> Carbon::parse($data_out)->format('H:i')];
                        $attend_2['status_employee'] = $attendance->status_employee;
                        // $attend_2['total_in'] = count($in);
                        // $attend_2['total_late'] = count($late);
                        // $attend_2['total_permit'] = MAttend::where('mprof_id', $user->id)->where('in_time', '!=', '00:00:00')->where('status_employee', 4)->whereBetween('date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->where('mprof_id', $user->id)->count();
                        // $attend_2['total_sick'] = 0;
                        // $attend_2['total'] = $attend_2['total_in'] + $attend_2['total_late'] + $attend_2['total_permit'];
                        // $attend_2['total_in'] = MAttend::where('in_time', '!=', '00:00:00')->where('mprof_id', $user->id)->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->count();
                        // $attend_2['total_late'] = MAttend::where('late_time', '!=', '00:00:00')->where('mprof_id', $user->id)->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->count();
                        // $attend_2['total_permit'] = MAttend::where('status_employee', 4)->where('mprof_id', $user->id)->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->count();
                        // $attend_2['total_sick'] = MAttend::where('status_employee', 2)->where('mprof_id', $user->id)->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->count();
                        
                        $attend []= $attend_2;
                        
                        
                    }

                }
                $salesman['total_in'] = count($attend);
                $salesman['total_late'] = count($late);
                $salesman['total_permit'] = count($permit);
                $salesman['total_sick'] = count($sick);
                $salesman['total_cuti'] = count($cuti);
                $salesman['total'] = $salesman['total_in'] + $salesman['total_late'];
                $salesman['attendance'] = $attend;
                $final[] = $salesman;
            }
        //sort($final);
        //dd($final);
        // return view('reportExports')->with('final', $final)->with('dates', $dates)->with('date', $date);
        return view('reportExports', [
            'final' => $final,
            'dates' => $dates,
            'date' => $date,
            'minTime' => $this->start_date,
            'maxTime' => $this->end_date,
        ]);
    }
}
