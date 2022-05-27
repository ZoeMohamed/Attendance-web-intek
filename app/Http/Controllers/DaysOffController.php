<?php

namespace App\Http\Controllers;

use App\MProf;
use App\day_off;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade as PDF;
// use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class DaysOffController extends Controller
{
    public function index(){
        //admin page
        if(Auth::user()->id == 99999){
            $this_year = Carbon::now()->format('Y');
            $accept = day_off::where('days_off_date','like',$this_year.'%')->where('status',1)->get();
            $reject = day_off::where('days_off_date','like',$this_year.'%')->where('status',2)->get();
            $data_all = [];
            $data_accept = [];
            $data_reject = [];
            // $data_month_accept=[];
            for ($i=1; $i < 13; $i++) { 
                $data_month_accept[(string)$i]=0;
                $data_month_reject[(string)$i]=0;
            }

            foreach ($accept as $a) {
                
                $data1=Carbon::parse($a->days_off_date)->format('Y-m-d');
                $data2=Carbon::parse($a->back_to_office)->subDay(1)->format('Y-m-d');
                $data_Fix=CarbonPeriod::create($data1,$data2 );
                $total_date = count($data_Fix);
                $i=0;
                foreach($data_Fix as $d){
                    $i++;
                    $data = carbon::parse($d);
                    $fix = carbon::parse($data);
                    // dd($fix);
                    // if($total_date==$i){
                    //     // dd('masuk om');
                    // }else{
                    //     // echo " isi i adalah ".$i;
                    //     // echo " isi total date adalah ".$total_date;
                        if($fix->isWeekend()){
                            
                        }
                        else{
                            $month= (int) explode('-',$d->format('Y-m-d'))[1];
                            $data_month_accept[(string) $month]+=1;
                        }
                    // }
                    // dd($data_month);
                }
                // dd($data_month);
                $data_accept=$data_month_accept;
            }
            // dd($data_accept);
            foreach ($reject as $a) {
                
                $data1=Carbon::parse($a->days_off_date)->format('Y-m-d');
                $data2=Carbon::parse($a->back_to_office)->subDay(1)->format('Y-m-d');
                $data_Fix=CarbonPeriod::create($data1,$data2 );
                $total_date = count($data_Fix);
                $i=0;
                foreach($data_Fix as $d){
                    $i++;
                    $data = carbon::parse($d);
                    $fix = carbon::parse($data);
                    // dd($fix);
                    // if($total_date==$i){
                    //     // dd('masuk om');
                    // }else{
                    //     // echo " isi i adalah ".$i;
                    //     // echo " isi total date adalah ".$total_date;
                        if($fix->isWeekend()){
                            
                        }
                        else{
                            $month= (int) explode('-',$d->format('Y-m-d'))[1];
                            $data_month_reject[(string) $month]+=1;
                        }
                    // }
                    // dd($data_month);
                }
                // dd($data_month);
                $data_reject=$data_month_reject;
            }
            
        
            // dd($data_all);
            
            $card_request = day_off::where('status',0)->orderBy('id','desc')->get();
            // dd($card_request);   
            $table = day_off::select('name','days_off_date as tanggal_awal','submitted_job as pekerjaan','status','replacement_pic as pengganti', 'reason','status','response_date as respon','user_id','back_to_office as cuti')->where("status","!=",0)->get();
            // dd($table);
            $status_employee = day_off::select('name','days_off_date as cuti','status','response_date as respon')->get();
            // dd($status_employee);
            return view('daysoffAdmin')
                -> with('data_accept',$data_accept)
                -> with('data_reject',$data_reject)
                -> with('card_request',$card_request)
                -> with('table',$table)
                -> with('$status_employee',$status_employee);
        //user page
        }else{
            $all = day_off::all();
            // dd($all);
            $card_profile = day_off::select('days_off.name','days_off.remaining_days_off as sisa_cuti', 'days_off.days_off_balance as total')->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->first();
            $card_status = day_off::select('days_off.days_off_date as cuti', 'days_off.back_to_office as masuk', 'days_off.response_date as respon','status')->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
            $card = day_off::where('user_id',Auth::user()->id)->orderBy('created_at','desc')->get();
            $check= day_off::join('m_profs','m_profs.name','=','days_off.name')->first();
            // $report_data=day_off::where();
            // dd($card_status);
            if($check->division_id==0){
                $jabatan="Support";
            }else if($check->division_id==1){
                $jabatan="Programmer";
            }else if($check->division_id==3){
                $jabatan="Finance";
            }else if($check->division_id==4){
                $jabatan="Admin";
            }else if($check->division_id==5){
                $jabatan="HRGA";
            }else if($check->division_id==7){
                $jabatan="PM";
            }else if($check->division_id==8){
                $jabatan="MicroController";
            }else if($check->division_id==null){
                $jabatan="NULL!";
            }else{
                $jabatan="Undefined";
            }


            return view('daysoff') 
            -> with('jabatan',$jabatan)
            -> with('card_profile',$card_profile)
            -> with('card',$card)
            -> with('card_status',$card_status)
            -> with ('all', $all);
        }
    } 
    public function create(Request $request){
        // dd($request);
        $date1 = Carbon::parse($request->cuti);
        // dd($date1);
        $date2 = Carbon::parse($request->masuk);
        $between = CarbonPeriod::create($date1,$date2 );
        $total_date = count($between);
        $count=0;
        $i=0;
        foreach ($between as $b){
            $i++;
            $data=$b->format('d-m-Y');
            $fix = carbon::parse($data);
            // dd($fix);
            if($total_date==$i){
                // dd('masuk om');
            }else{
                // echo " isi i adalah ".$i;
                // echo " isi total date adalah ".$total_date;
                if($fix->isWeekend()){
                    
                }
                else{
                    $count+=1;
                }
            }
        }
    //  dd($cek);
        
        //  dd($count);
        // $diff = $date1->diffInDays($date2);
        // dd($diff);
        // $days_off=day_off::where('status',0)->limit($limit)->offset(($cuti - 1) * $limit)->get()->toArray();

        if ($count >= 12) {
            Alert::error('Error', 'Request melebihi batas maksimum');
        } else {
            $cek = day_off::where('user_id',Auth::user()->id)->orderBy('created_at','DESC')->first();
            // dd($cek);
            if ($cek->remaining_days_off == 0) {
                Alert::error('Error Title', 'Error Message');
            } else {
                $day_off=day_off::select('remaining_days_off','days_off_balance')->where('name',$request->name)->orderBy('created_at','desc')->first();
                day_off::create([
                    'name' => $request -> name,
                    'position' => $request -> posisi,
                    'departement' => $request -> jabatan,
                    'supervisor' => $request -> atasan,
                    'replacement_pic' => $request -> pic_pengganti,
                    'phone_number' => $request -> nomor_telepon,
                    'days_off_date' => $request -> cuti,
                    'back_to_office' => $request -> masuk,
                    'total_days' => $count,
                    'remaining_days_off'=>$day_off->remaining_days_off - $count,
                    'days_off_balance'=>$day_off->days_off_balance + $count,
                    'submitted_job' => $request -> pekerjaan,
                    'reason' => $request -> alasan,
                    'status' => 0,
                    'user_id' => Auth::user()->id,
                    'response_date' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }
            
        

       
    
       

        // $mprof=MProf::where('name',$request->name)->first();

        // MProf::where('name',$mprof->name)->update([
        //     'remaining_days_off'=>$mprof->remaining_days_off - $count,
        //     'days_off_balance'=>$mprof->days_off_balance + $count
        // ]);
        return back();
    }
    public function accept($id){
        $now=Carbon::now()->format('Y-m-d H:i:s');
        day_off::where("id",$id)->update([
            "status"=>1,
            'response_date'=>$now
        ]);
        return redirect('/days_off');
    }
    
    public function reject($id){
        $now=Carbon::now()->format('Y-m-d H:i:s');
        // dd($id);
        $after_reject =day_off::where('id',$id)->first();
        $before_reject =day_off::where('name',$after_reject->name)->orderBy('id',"desc")->skip(1)->first();
        // dd($before_reject);

        day_off::where("id",$id)->update([
            "status"=>2,
            "remaining_days_off"=>($before_reject==null)?12:$before_reject->remaining_days_off,
            "days_off_balance"=>($before_reject==null)?0:$before_reject->days_off_balance,
            'response_date'=>$now
        ]);
        return redirect('/days_off');
    }

    public function print($id){
        // dd($id);
        $data = day_off::where('id',$id)->first();
        // dd($data);
        $pdf=PDF::loadView('print',$data)->setPaper('a4'); 
        // day_off::all();
        return $pdf->stream('Bukti_Fisik_Formulir_Cupdf');
    }
}