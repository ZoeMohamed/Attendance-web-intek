<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use Illuminate\Http\Request;
use App\MAttend;
use App\MLogging;
use App\Tmtable;
use App\MProf;
use App\Vector;
use App\User;
use App\OfficeLocat;
use Carbon\Carbon;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendExport;
use Rap2hpoutre\FastExcel\FastExcel;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Auth;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        
        if($request->datefilter != ""){
            $date = $request->datefilter;
        }else{
            $date = Carbon::now()->format('Y-m-d');
        }


        if(Auth::user()->id != 99999){
            return redirect('/detail/'.Auth::user()->id);
        }
        //SQL SERVER USING
        
        $showIntolerance = ($request->showintolerance) ? true : false;


        $countEmploye = count(MProf::get());
        $list_emplo = MProf::get();
        $list_office = OfficeLocat::where('status', 1)->get();
        $today = count(MAttend::where('date', $date)->get());
        $yesterday = count(MAttend::where('date', Carbon::yesterday()->format('Y-m-d'))->get());
        $mobile = $request->mobile;
        if($mobile == true){
            $in = MAttend::where('in_time', '!=', '00:00:00')->where('date', $date)->where('machine_id', 4)->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->where('machine_id', 4)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.in_tolerance_time', 'DESC')->orderBy('m_attends.in_time','DESC')->get();
        }else{
            $in = MAttend::join('office_location', 'office_location.id', '=', 'm_attends.machine_id')->where('in_time', '!=', '00:00:00')->where('date', $date)->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.in_tolerance_time', 'DESC')->orderBy('m_attends.in_time','DESC')->get();
        }
        //dd($in);
        // $inn = MAttend::where('in_time', '!=', '00:00:00')->where('date', $date)->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->get();

        // $data_in = array();

        // foreach($inn as $di){
        //     $a["mprof_id"] = $di->mprof_id;
        //     $a["name"] = $di->name;
        //     $a['time'] = ($di->in_time != "00:00:00") ? $di->in_time : $di->in_tolerance_time;
        //     $data_in []= $a;
        // }
        
        // $in = collect($data_in)->sortBy('time')->reverse();
        //dd($in);
        //dd(collect($inn)->sortBy('in_time')->reverse()->toArray());
        $otherAttend = MAttend::where('date', $date)->whereIn('status_employee', [1,2,3,4])->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.created_at','DESC')->get();
        //$in_tol = MAttend::where('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        if($mobile == true){
            $late = MAttend::where('late_time', '!=', '00:00:00')->where('date', $date)->where('machine_id', 4)->where('late_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.late_time', 'DESC')->get();
        }else{
            $late = MAttend::join('office_location', 'office_location.id', '=', 'm_attends.machine_id')->where('late_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.late_time', 'DESC')->get();
        }
        $last = MAttend::where('last_attend', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.updated_at', 'DESC')->get();
        $over = MAttend::where('over_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.created_at', 'DESC')->get();
        $out = MAttend::where('out_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.created_at', 'DESC')->get();
        $notatt = array();
        $user_notacc = array();

        $name_day = Carbon::parse($date)->format('l');
        $timetable_in = Tmtable::where('day', $name_day)->where('type', 'in')->first();
        $timetable_in_tolerance = Tmtable::where('day', $name_day)->where('type', 'in_tolerance')->first();
        $timetable_late = Tmtable::where('day', $name_day)->where('type', 'late')->first();
        foreach($list_emplo as $le){
            $cek = MAttend::where('mprof_id', $le->id)->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->first();
            if(!isset($cek)){
                $getImg = Vector::where('mprof_id', $le->id)->first();
                $notatt [] = array("img"=> null, "name"=> $le->name, "mprof_id"=> $le->id);
            }
        }

        foreach($list_emplo as $le){
            $cek = User::where('id', $le->id)->first();
            if(!isset($cek)){
                $user_notacc [] = array("img"=> null, "name"=> $le->name, "mprof_id"=> $le->id);
            }
        }
        return view('home')->with('in', $in)->with('late', $late)->with('last', $last)
                           ->with('over', $over)->with('countEmploye', $countEmploye)
                           ->with('today', $today)->with('yesterday', $yesterday)->with('out', $out)
                           ->with('notatt', $notatt)->with('timetable_in', $timetable_in)->with('timetable_in_tolerance', $timetable_in_tolerance)
                           ->with('timetable_late', $timetable_late)->with('otherAttend', $otherAttend)->with('showIntolerance', $showIntolerance)->with('user_notacc', $user_notacc)->with('mobile', $mobile)
                           ->with('list_office', $list_office);
    }

    public function detailUser(Request $request, $user_id){
        
        
        if(Auth::user()->id == 99999 && Auth::user()->id != $user_id){
            
        }elseif(Auth::user()->id != $user_id){
            return redirect('/detail/'.Auth::user()->id);
        }
        date_default_timezone_set('Asia/Jakarta'); 
        if($request->start && $request->end){
            $bulanfirst = $request->start;
            $bulanend = $request->end;
        }else{
            $bulanfirst = 1;
            $bulanend = 12;
        }
        if(isset($request->status_activate)){
            $prof = MProf::where('id', $user_id)->update(["status"=> $request->status_activate]);
            if($request->status_activate == 1){
                $status = "Berhasil Melakukan Perubahan Status dan user berhasil Aktif";
            }else{
                $status = "Berhasil Melakukan Perubahan Status dan user berhasil  Non-Aktif";
            }
            return $status;
            //return redirect("/detail/".$user_id)->with('message_activate', "Berhasil Melakukan Perubahan Status dan user berhasil ".$status);
        }
        $prof = MProf::where('id', $user_id)->first();
        $cek = MAttend::where('mprof_id', $user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        $in = MAttend::where('mprof_id', $user_id)->where('in_time', '!=', '00:00:00')->orWhere('in_tolerance_time', '!=', '00:00:00')->where('mprof_id', $user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        $late = MAttend::where('mprof_id', $user_id)->where('late_time', '!=', '00:00:00')->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        $notatt = MAttend::where('mprof_id', $user_id)->where('in_time', '00:00:00')->where('in_tolerance_time', '00:00:00')->where('late_time', '00:00:00')->where('out_time', '00:00:00')->where('over_time', '00:00:00')->where('mprof_id', $user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        $getImg = Vector::select('file')->where('mprof_id', $user_id)->first();
        $list_month = array();
        for($i=$bulanfirst; $i<= $bulanend; $i++){ 
            $list_month [] = date('F', mktime(0, 0, 0, $i, 10)); 
        }

        //============Counting IN ,LATE,NOT ATTEND WITH MONTH =============
        //================= START COUNT IN ===========================
        $getDate_in = MAttend::where('mprof_id', $user_id)
        ->where('in_time', '!=', '00:00:00')->orWhere('in_tolerance_time', '!=', '00:00:00')
        ->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')
        ->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')
        ->get()
        ->groupBy(function($date){
            return Carbon::parse($date->date)->format('m');
        });
        $incount = [];
        $dateinArr = [];
        foreach($getDate_in as $key => $gin){
            $incount [(int)$key] = count($gin);
        }
        for($ii=$bulanfirst; $ii<= $bulanend; $ii++){
            if(!empty($incount[$ii])){
                $dateinArr[$ii] = $incount[$ii];
            }else{
                $dateinArr[$ii] = 0;
            }
        }
        // ================== END COUNT IN =========================

        //================= START COUNT LATE ===========================
        $getDate_late = MAttend::where('mprof_id', $user_id)
        ->where('late_time', '!=', '00:00:00')
        ->where('mprof_id', $user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get()->groupBy(function($date){
            return Carbon::parse($date->date)->format('m');
        });
        $latecount = [];
        $datelateArr = [];
        foreach($getDate_late as $key => $glate){
            $latecount [(int)$key] = count($glate);
        }
        for($ll=$bulanfirst; $ll<= $bulanend; $ll++){
            if(!empty($latecount[$ll])){
                $datelateArr[$ll] = $latecount[$ll];
            }else{
                $datelateArr[$ll] = 0;
            }
        }
        // ================== END COUNT LATE =========================

        //================= START COUNT NOTATT ===========================
        $dates = array();
        $day = [];
        $getAtt = MAttend::where('mprof_id', $user_id)->where('in_time', '00:00:00')->where('in_tolerance_time', '00:00:00')->where('late_time', '00:00:00')->where('out_time', '00:00:00')->where('over_time', '00:00:00')->where('mprof_id', $user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        for($i = $bulanfirst; $i <= $bulanend; $i++){
            $array['start'] = Carbon::now()->month($i)->startOfMonth()->format('Y-m-d');
            $array['end'] = Carbon::now()->month($i)->endOfMonth()->format('Y-m-d');
            $attendances = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('mprof_id', $user_id)->whereBetween('m_attends.date', [$array['start'], $array['end']])->get();
            //->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->
            $array["countInmonth"] = Carbon::now()->month($i)->endOfMonth()->format('d') - count($attendances);
            $dates [] = $array;
        }
        //dd($dates);
        $sum = array();
        foreach($dates as $dtt){
            $sum [] = $dtt["countInmonth"];
        }
        $hmin = \Carbon\Carbon::now()->modify('this week -5 days');
        $today = \Carbon\Carbon::now();
        // $tanggal = [];
        //$hmin5 = $hmin;
        $minTime = \Carbon\Carbon::parse($hmin, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($today, 'Asia/Jakarta');
        $period = CarbonPeriod::create($minTime, $maxTime);
        $lasweek = $period->toArray();
        $datalastweek = [];
        foreach($lasweek as $lw){
            $data = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('mprof_id', $user_id)->where('m_attends.date', $lw->format('Y-m-d'))->first();
            if(isset($data)){
                if($data->late_time != "00:00:00"){
                    $datenya = $data->late_time;
                }elseif($data->in_time != "00:00:00"){
                    $datenya = $data->in_time;
                }elseif($data->in_tolerance_time != "00:00:00"){
                    $datenya = $data->in_tolerance_time;
                }else{
                    $datenya = "00:00:00";
                }
                $datalastweek [] = array(
                    "date"=> $lw->format('Y-m-d'),
                    "in" => $datenya,
                    "out" => ($data->out_time != "00:00:00") ? $data->out_time : $data->over_time
                );
            }else{
                $datalastweek [] = array(
                    "date"=> $lw->format('Y-m-d'),
                    "in" => "-",
                    "out" => "-"
                );
            }
        }
        //dd($datalastweek);


        //dd(json_encode($sum));
        return view('detailEmployee')->with('prof', $prof)->with('cek', $cek)->with('getImg', $getImg)
                                     ->with('in', $in)->with('late', $late)->with('notatt', $notatt)
                                     ->with('list_month', $list_month)->with('dateinArr', $dateinArr)->with('datelateArr', $datelateArr)
                                     ->with('day', $day)->with('getAtt', $getAtt)->with('dates', $dates)->with('sum', $sum)->with('bulanfirst', $bulanfirst)->with('bulanend', $bulanend)->with('lasweek', $lasweek)->with('datalastweek', $datalastweek);
    }
    public function notAttend(Request $request){
        $list_emplo = MProf::get();
        $notatt = array();
        $date = Carbon::now()->format('Y-m-d');
        //dd($list_emplo);
        foreach($list_emplo as $le){
            $cek = MAttend::where('mprof_id', $le->id)->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->first();
            
            if(!isset($cek)){
                $getImg = Vector::where('mprof_id', $le->id)->first();
                $notatt [] = array("img"=> $getImg->file, "name"=> $le->name);
            }
        }
        //dd($notatt);
        return view('notattend')->with('notatt', $notatt);
    }
    public function reportView(Request $request){
        date_default_timezone_set('Asia/Jakarta'); 
        if(Auth::user()->id != 99999){
            return redirect('/detail/'.Auth::user()->id);
        }
        $date = Carbon::now();
        $minTime = \Carbon\Carbon::parse($date->startOfWeek(), 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($date->endOfWeek(), 'Asia/Jakarta');

        $month = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('m');
        //dd($month); 
        $year = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('yy');
        $day = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('dd');
        $final = [];
        $period = CarbonPeriod::create($minTime, $maxTime);
        $dates = $period->toArray();
        
        if($request->divisi == "all" || $request->divisi == ""){
            $users = MProf::get();
            $session = "all_division";
            $options = "all_time";
            if($request->time_filter == 1){ //Terlambat
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.late_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }elseif($request->time_filter == 2){ //tepat waktu
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }else{
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }
            
        }else{
            //dd($request->divisi);
            $users = MProf::where('division_id', $request->divisi)->get();
            $session = $request->divisi;
            $options = $request->time_filter;
            if($request->time_filter == 1){ //Terlambat
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $request->divisi)->where('m_attends.late_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }elseif($request->time_filter == 2){ //tepat waktu
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $request->divisi)->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }else{
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $request->divisi)->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }
            
        }

        
        //$attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
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
                
                if($attendance->mprof_id == $user->id)
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
                    $attend_2['time'] = ["in" => $attendance->in_time, "late"=> $attendance->late_time, "in_tolerance_time"=> $attendance->in_tolerance_time, 'over'=> $attendance->over_time, "out"=> Carbon::parse($data_out)->format('H:i')];
                    $attend_2['status_employee'] = $attendance->status_employee;
                    $attend []= $attend_2;
                }

            }
            $salesman['total_in'] = count($attend);
            $salesman['total_late'] = count($late);
            $salesman['total_permit'] = count($permit);
            $salesman['total_sick'] = count($sick);
            $salesman['total_cuti'] = count($cuti);
            $salesman['total'] = $salesman['total_in'];
            $salesman['attendance'] = $attend;
            $final[] = $salesman;
        }
        if($request->showtime){
            return view('reportViewWithTime')->with('final', $final)->with('dates', $dates)->with('date', $date)->with('session', $session)->with('options', $options);
        }else{
            return view('reportView')->with('final', $final)->with('dates', $dates)->with('date', $date)->with('session', $session)->with('options', $options);
        }
    }
    public function report(Request $request){
       
        date_default_timezone_set('Asia/Jakarta'); 
        
        $minTime = \Carbon\Carbon::parse($request->startdate, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($request->enddate, 'Asia/Jakarta');
        //dd($minTime);
        if($request->syncdata != null){
            $this->dataAbsen($minTime, $maxTime);
            $this->dataAbsenOut($minTime, $maxTime);
        }
        
        
        $month = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('m');
        //dd($month); 
        $year = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('yy');
        $day = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('dd');
        
        
        $final = [];
        // $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->whereBetween('m_attends.created_at', [$minTime, $maxTime])->get();
        if($request->divisi == "all" || $request->divisi == ""){
            $users = MProf::get();
            $session = "all_division";
            $options = "all_time";
            if($request->time_filter == 1){ //Terlambat
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }elseif($request->time_filter == 2){ //tepat waktu
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }else{
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }
            
        }else{
            //dd($request->divisi);
            $users = MProf::where('division_id', $request->divisi)->get();
            $session = $request->divisi;
            $options = $request->time_filter;
            if($request->time_filter == 1){ //Terlambat
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $request->divisi)->where('m_attends.late_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }elseif($request->time_filter == 2){ //tepat waktu
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $request->divisi)->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }else{
                $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_profs.division_id', $request->divisi)->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
            }
            
        }
        //$attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
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
                    $attend_2['status_employee'] = $attendance->status_employee;
                    $attend []= $attend_2;
                }

            }
            $salesman['attendance'] = $attend;
            $final[] = $salesman;
        }
        //dd($final);
        $period = CarbonPeriod::create($minTime, $maxTime);
        $dates = $period->toArray();
        return view('report')->with('final', $final)->with('year', $year)->with('month', $month)->with('day', $day)->with('dates', $dates)->with('minTime', $minTime)->with('maxTime', $maxTime)->with('session', $session)->with('options', $options);
    }

    /////
    public function export_report($start, $end, $division, $options){
        
        $filename = 'attendance_export_'.$start."_to_".$end.".xlsx";
        return Excel::download(new AttendanceExport($start,$end, $division, $options), $filename);
    }

    public function export_report_view($start, $end){
        $date = Carbon::now();
        // $minTime = \Carbon\Carbon::parse($date->startOfWeek(), 'Asia/Jakarta');
        // $maxTime = \Carbon\Carbon::parse($date->endOfWeek(), 'Asia/Jakarta');
        $minTime = \Carbon\Carbon::parse($start, 'Asia/Jakarta');
        $maxTime = \Carbon\Carbon::parse($end, 'Asia/Jakarta');

        $month = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('m');
        //dd($month); 
        $year = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('yy');
        $day = \Carbon\Carbon::parse($minTime, 'Asia/Jakarta')->format('dd');
        $users = MProf::get();
        
        $final = [];
        $period = CarbonPeriod::create($minTime, $maxTime);
        $dates = $period->toArray();
        $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhereIn('m_attends.status_employee', array('1'))->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
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
                        $attend_2['status_employee'] = $attendance->status_employee;
                        $attend []= $attend_2;
                    }

                }
                $salesman['attendance'] = $attend;
                $final[] = $salesman;
            }
        return view('reportExports')->with('final', $final)->with('dates', $dates)->with('date', $date)->with('minTime', $start)->with('maxTime', $end);
    }
    /////

    public function tmtableView(Request $request){
        // if(Auth::user()->id != 99999){
        //     return redirect('/detail/'.Auth::user()->id);
        // }
        //$tmtble = Tmtable::whereIn('type', ['in','in_tolerance','late','over','out'])->get()->groupBy('day');
        $tmtble = Tmtable::orderBy('updated_at')->get()->groupBy('day');
        //dd($tmtble);
        // $data = [];
        // foreach($tmtble as $tb){
        //     $data []= Tmtable::where('day')
        // }
        //dd($tmtble[0]);
        return view('tmtable')->with('tmtable', $tmtble);
    }

    public function editTmtable(Request $request){
       $data = Tmtable::where('type', $request->type_time)->get();
       $dd = array();
       foreach($data as $dt){

                
                Tmtable::where('day', $request->input($dt->day)[0])->where('type', $request->type_time)->update([
                        "start_at"=> $request->input($dt->day)[1],
                        "end_at" => $request->input($dt->day)[2]
                ]);
                //$dd [] = $request->input($dt->day)[2]; 
       }
       //dd($dd);
        //    foreach($data as $dt){
        //         //    $split = explode('_', $request->input($dt->day."_begin"));
        //        Tmtable::where('day', $split[0])->update([
        //             "start_at"=> $request->input($dt->day."_begin"),
        //             "end_at" => $request->input($dt->day."_end")
        //        ]);
        //    }
        //    $get_day = array();
        //    foreach($request->input() as $rq){
        //         $explode = explode("_", $rq);
        //         $get_day [] = array("day"=> $explode[0], "start_at"=> $rq."_begin", "end_at"=> $rq."_end");
        //    }
        //    dd($get_day);
        //dd($request);
       return redirect('/time-table');

    }
    public function gettimetable(Request $request){
        if($request->type_time == 1){

            $data = Tmtable::where('type', 'in')->get();
            $parse = array();
            foreach($data as $dt){
                $parse [] = array("name"=> $dt->day , "begin"=> $dt->start_at, "end"=> $dt->end_at);
            }
            return response()->json($parse);
        }elseif($request->type_time == 2){
            $data = Tmtable::where('type', 'in_tolerance')->get();
            $parse = array();
            foreach($data as $dt){
                $parse [] = array("name"=> $dt->day , "begin"=> $dt->start_at, "end"=> $dt->end_at);
            }
            return response()->json($parse);
        }elseif($request->type_time == 3){
            $data = Tmtable::where('type', 'late')->get();
            $parse = array();
            foreach($data as $dt){
                $parse [] = array("name"=> $dt->day , "begin"=> $dt->start_at, "end"=> $dt->end_at);
            }
            return response()->json($parse);
        }elseif($request->type_time == 4){
            $data = Tmtable::where('type', 'out')->get();
            $parse = array();
            foreach($data as $dt){
                $parse [] = array("name"=> $dt->day , "begin"=> $dt->start_at, "end"=> $dt->end_at);
            }
            return response()->json($parse);
        }elseif($request->type_time == 5){
            $data = Tmtable::where('type', 'over')->get();
            $parse = array();
            foreach($data as $dt){
                $parse [] = array("name"=> $dt->day , "begin"=> $dt->start_at, "end"=> $dt->end_at);
            }
            return response()->json($parse);
        }else{
            return redirect('/time-table');
        }
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
        // $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->whereBetween('m_attends.created_at', [$minTime, $maxTime])->get();
        $attendances = MAttend::join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->whereBetween('m_attends.date', [$minTime->format('Y-m-d'), $maxTime->format('Y-m-d')])->get();
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

    public function reciveManual(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $expl = explode(',', $request->daterange);
        $ee = array();
        $id_user = $request->employee_id;
        $name_day = [];
        $cid = [];
        foreach($expl as $ex){
            $date = $ex;
            $name_day= Carbon::parse($date)->format('l');
            $time = Carbon::parse(date('Y-m-d H:i:s'))->format('H:i:s');
            $check_tmtable []= Tmtable::where('day', $name_day)->get();
            $check = MAttend::where('mprof_id',$id_user)->where('date', Carbon::parse($date)->format('Y-m-d'))->first();
            //$check_tmtable = Tmtable::where('day', $name_day)->where('type', 'in')->first();
            
            if($check == null){
                MAttend::create([
                    'date'=> Carbon::parse($date)->format('Y-m-d'),
                    'mprof_id'=> $id_user,
                    'tmtable_id'=> 2,
                    'in_time' => '08:'.rand(10,40).':00',
                    //'in_time' => ( $check_tmtable->type == "in") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                    'in_tolerance_time'  => "00:00:00",
                    'out_time' => "00:00:00",
                    'over_time' => "00:00:00",
                    'late_time' => "00:00:00",
                    'first_attend' => Carbon::parse($date)->format('H:i:s'),
                    'last_attend'=> Carbon::parse($date)->format('H:i:s'),
                    'type_data' => "Manual Attend",
                    'noted' => $request->noted,
                    'status_employee' => $request->status_employe,
                    'machine_id' => $request->machine_id
                ]);
            }
        }
        //dd($check_tmtable);
        return redirect('/');

    }

    public function calendarCuti(){
        return view('calendarCuti');
    }

    public function addPerson(Request $request){
        $check_id = MProf::find($request->idemployee);
        $msisdn = preg_replace('~^[0]++~', '62', $request->phone_number);
        if($check_id){
            return redirect('/home')->with('message', 'ID Employee Already in database');
        }else{
            MProf::create(['id'=> $request->idemployee, 'name'=> $request->name, 'position'=> $request->office_id, 'phone_number'=> $msisdn, 'status'=> 1]);
            return redirect('/home')->with('message', 'Success Add Employee in database');
        }
    }

    public function addUser(Request $request){
        $check_user = User::where('email', $request->email)->first();

        if($check_user){
            return redirect('/home')->with('message', 'Email Already in database');
        }else{
            $check_prof = MProf::find($request->employee_id);
            //dd($check_prof->id);
            $user = User::create(['id'=> $check_prof->id, 'name'=> $check_prof->name, 'email'=> $request->email, 'password'=> bcrypt($request->password), 'team_id'=> $request->team_category]);
           
            $check_prof->where('id', $check_prof->id)->update(['user_id'=> $user->id, 'division_id'=> $request->team_category]);
            return redirect('/home')->with('message', 'Success Add User Apps Login in database');
        }
    }

    private function dataAbsen($minTime, $maxTime)
    {
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
                            ->orderBy('authDateTime', 'ASC')
                            ->first();
                //$get_time[$em->name] = Carbon::parse($attendances->authDateTime)->format('H:i:');
                $data_attendance[] = $attendances;
                
                
            }
        }
        //dd($data_attendance);
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
                if($check_tmtable != NULL){

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
                                'machine_id' => ($data_attendance[$i]->deviceSerialNo == "DS-K1T33120200602V030104END64843605") ? 1:2,
                                'lat_attend' => null,
                                'lon_attend' => null
                            ]);
                            //echo "SUKSES INSERT , TYPE => ".$check_tmtable->type."\n";
                        }else{
                            //echo "DATA ALREADY\n";
                        }
                        
                    }
                }else{
                    //echo "Tidak ada pada timetable NAME => ".$employee->name." JAM => ".$data_attendance[$i]->authDateTime."\n";
                }
            }
        }
    }

    private function dataAbsenOut($minTime, $maxTime)
    {
        
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
                //echo "KOSONG\n";
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
                            //echo "Sukses Update Data si Employe ID =>  ".$data_attendance[$i]->employeeID." TYPE => ".$check_tmtable->type."\n";
                        }elseif($check_tmtable->type == "out" && $check->in_time != "00:00:00" && Carbon::parse($data_attendance[$i]->authDateTime)->format('Y-m-d') == $datenya){
                            Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($data_attendance[$i]->authDateTime)->format('H:i:s')]);
                            //echo "Sukses Update Data si Employe ID =>  ".$data_attendance[$i]->employeeID." TYPE => ".$check_tmtable->type."\n";
                        }else{
                            //echo "Nothing \n";
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
                        //echo "Tidak ada data time yang sesuai dengan timetable\n";
                    }
                }
            }
        }
    }
}
