<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\MAttend;
use App\MLogging;
use App\Tmtable;
use App\MProf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GetDataAttendOut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getattend:out';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Attendance Out';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$date = Carbon::now()->format('Y-m-d');
        $minTime = \Carbon\Carbon::parse(Carbon::now()->startOfMonth()->toDateString(), 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse(Carbon::now()->endOfMonth()->toDateString(), 'Asia/Jakarta');
        $employee = MProf::get();

        $data_attendance = array();
        $get_time = [];

        $period = CarbonPeriod::create($minTime, $maxTime);
        $dates = $period->toArray();
        
        foreach($dates as $dt){
            $datenya = $dt->format('Y-m-d');
            foreach($employee as $em){
                $attendances = DB::connection('sqlsrv')->table('attendance_log')
                            ->select('employeeID', 'authDate', 'authDateTime','deviceSerialNo')
                            ->where('authDate', $datenya)
                            ->where('employeeID', $em->id)
                            ->orderBy('authDateTime', 'DESC')
                            ->first();
                //$get_time[$em->name] = Carbon::parse($attendances->authDateTime)->format('H:i:');
                $data_attendance[] = $attendances;
                
                
            }
        }
        //dd($data_attendance);
        for($i=0; $i<count($data_attendance); $i++){
            if($data_attendance[$i] == null){
                echo "KOSONG\n";
            }else{
                $name_day = Carbon::parse($data_attendance[$i]->authDateTime)->format('l');
                foreach($dates as $dt){
                    $datenya = $dt->format('Y-m-d');
                    $check = MAttend::where('mprof_id',$data_attendance[$i]->employeeID)->where('date', Carbon::parse($datenya)->format('Y-m-d'))->first();
                    
                    //$check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '>=', $time)->where('end_at', '<=', $time)->first();
                    $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $data_attendance[$i]->authDateTime)->where('end_at', '>=', $data_attendance[$i]->authDateTime)->first();
                    
                    if($check_tmtable && $check){
                        if($check_tmtable->type == "over" && $check->over != "00:00:00" && Carbon::parse($data_attendance[$i]->authDateTime)->format('Y-m-d') == $datenya){
                            Mattend::where('id', $check->id)->update(['over_time'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s')]);
                            echo "Sukses Update Data si Employe ID =>  ".$data_attendance[$i]->employeeID." TYPE => ".$check_tmtable->type."\n";
                        }elseif($check_tmtable->type == "out" && $check->in_time != "00:00:00" && Carbon::parse($data_attendance[$i]->authDateTime)->format('Y-m-d') == $datenya){
                            Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s')]);
                            echo "Sukses Update Data si Employe ID =>  ".$data_attendance[$i]->employeeID." TYPE => ".$check_tmtable->type."\n";
                        }else{
                            echo "Nothing \n";
                        }
                        //echo $check_tmtable->type."\n";
                        // if($check_tmtable->type == "over" && $check->over_time == "00:00:00"){
                        //     Mattend::where('id', $check->id)->update(['over_time'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s')]);
                        // }elseif($check_tmtable->type == "out" && $check->in_time != "00:00:00"){
                        //     Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s')]);
                        // }else{
                        //     Mattend::where('id', $check->id)->update(['last_attend'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s')]);
                        // }
                        
                    }else{
                        echo "Tidak ada data time yang sesuai dengan timetable\n";
                    }
                }
            }
        }
    }
}
