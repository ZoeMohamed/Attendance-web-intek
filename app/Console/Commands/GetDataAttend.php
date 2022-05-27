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
class GetDataAttend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:attend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        date_default_timezone_set('Asia/Jakarta'); 
        $minTime = \Carbon\Carbon::parse(Carbon::now()->startOfMonth()->toDateString(), 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse(Carbon::now()->endOfMonth()->toDateString(), 'Asia/Jakarta');
        // $minTime = \Carbon\Carbon::parse("2020-10-12", 'Asia/Jakarta');
        // $maxTime = \Carbon\Carbon::parse("2020-10-13", 'Asia/Jakarta');
        //dd($minTime);
        $this->data($minTime, $maxTime);
        // //
        // // print_r($attendances)

    }


    private function data($minTime, $maxTime)
    {
        $employee = MProf::get();

        $data_attendance = array();
        $get_time = [];

        $period = CarbonPeriod::create($minTime, $maxTime);
        $dates = $period->toArray();
        //dd($dates);
        foreach($dates as $dt){
            $datenya = $dt->format('Y-m-d');
            foreach($employee as $em){
                $attendances = DB::connection('sqlsrv')->table('attendance_log')
                            ->select('employeeID', 'authDate', 'authDateTime','deviceSerialNo')
                            ->where('authDate', $datenya)
                            ->where('employeeID', $em->id)
                            ->orderBy('authDateTime', 'ASC')
                            ->first();
                //$get_time[$em->name] = Carbon::parse($attendances->authDateTime)->format('H:i:');
                $data_attendance[] = $attendances;
                
                
            }
        }
        //dd($data_attendance);
        $datalex = [];

        for($i=0; $i<count($data_attendance); $i++){
            if($data_attendance[$i] == null){
                //echo "KOSONG\n";
            }else{
                $employee = MProf::where('id', $data_attendance[$i]->employeeID)->first();
                //echo "TANGGAL => ". $data_attendance[$i]->authDateTime. " NAME  => ". $employee->name."\n";
                $check = MAttend::where('mprof_id',$data_attendance[$i]->employeeID)->where('date', Carbon::parse($data_attendance[$i]->authDateTime)->format('Y-m-d'))->first();
                $name_day = Carbon::parse($data_attendance[$i]->authDateTime)->format('l');
                //$check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '>=', $time)->where('end_at', '<=', $time)->first();
                $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $data_attendance[$i]->authDateTime)->where('end_at', '>=', $data_attendance[$i]->authDateTime)->first();
                $datalex [] = $check;
                if($check_tmtable != NULL){
                    if($data_attendance[$i]->deviceSerialNo == "DS-K1T33120200602V030104END64843605"){
                        $id_office = 1;
                    }elseif($data_attendance[$i]->deviceSerialNo == "DS-K1T33120200602V030104END64843605"){
                        $id_office = 2;
                    }else{
                        $id_office = 3;
                    }
                    if($check_tmtable->type == "in" || $check_tmtable->type == "in_tolerance" || $check_tmtable->type == "late"){
                        if($check == NULL){
                            MAttend::create([
                                'date'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('Y-m-d'),
                                'mprof_id'=> $data_attendance[$i]->employeeID,
                                'tmtable_id'=> $check_tmtable->id,
                                'in_time' => ( $check_tmtable->type == "in") ? Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s') : "00:00:00",
                                'in_tolerance_time'  => ( $check_tmtable->type == "in_tolerance") ? Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s') : "00:00:00",
                                'out_time' => ( $check_tmtable->type == "out") ? Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s') : "00:00:00",
                                'over_time' => ( $check_tmtable->type == "over") ? Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s') : "00:00:00",
                                'late_time' => ( $check_tmtable->type == "late") ? Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s') : "00:00:00",
                                'first_attend' => Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s'),
                                'last_attend'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s'),
                                'type_data' => null,
                                'noted' => "Hikvision",
                                'status_employee' => null,
                                'machine_id' => $id_office,
                                'lat_attend' => null,
                                'lon_attend' => null
                            ]);
                            echo "SUKSES INSERT , TYPE => ".$check_tmtable->type."\n";
                        }else{
                            echo "DATA ALREADY\n";
                        }
                        
                    }
                }else{
                    echo "Tidak ada pada timetable NAME => ".$employee->name." JAM => ".$data_attendance[$i]->authDateTime."\n";
                }
            }
        }
        //dd($datalex);
    }
}
