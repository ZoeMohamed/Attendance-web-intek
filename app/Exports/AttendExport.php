<?php

namespace App\Exports;

use Illuminate\Http\Request;
use App\MLogging;
use App\Tmtable;
use App\MProf;
use Carbon\Carbon;
use App\MAttend;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
class AttendExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private $min,$max;

    public function __construct($minTime, $maxTime) {
        $this->min = $minTime;
        $this->max = $maxTime;
    }

    public function collection()
    {
        $minTime = \Carbon\Carbon::parse($this->min, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($this->max, 'Asia/Jakarta');

        $month = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('m');
        $year = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('yy');
        $day = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('dd');
        $users = MProf::get();
        
        $final = [];
        $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->whereBetween('m_attends.created_at', [$minTime, $maxTime])->get();
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

                        
                        $attend_2['keterangan'] = $check_tmtable->type;
                        $attend_2['date'] = $attendance->date;
                        $attend_2['time'] = ["in" => Carbon::parse($attendance->in_time)->format('H:i'), "late"=> Carbon::parse($attendance->late_time)->format('H:i'), "in_tolerance_time"=> Carbon::parse($attendance->in_tolerance_time)->format('H:i'), "over"=> Carbon::parse($attendance->over_time)->format('H:i'), "out"=> Carbon::parse($attendance->over_time)->format('H:i')];
                        $attend []= $attend_2;
                    }

                }
                $salesman['attendance'] = $attend;
                $final[] = $salesman;
            }
        return $final;
    }
}
