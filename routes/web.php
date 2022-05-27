<?php

use Illuminate\Support\Facades\Route;
use App\Exports\AttendanceExportSingle;
use App\OfficeLocat;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\MAttend;
use Illuminate\Http\Request;
//use ImageOptimizer;
//use Image;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('get/data/{type}', 'DashboardController@index');
Route::get('report/{type}/{begind}/{end}', 'DashboardController@export_absensi');
Route::get('/', 'DashboardController@index');

Auth::routes();


Route::get('/what-is-my-ip', function(){ 
    return request()->ip();
});

Route::group(['middleware' => ['auth']], function () {
    //
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/report', 'HomeController@reportView');
    Route::post('/report', 'HomeController@report');
    Route::get('/report_export/{start}/{end}/{division}/{option}', 'HomeController@export_report')->name('report_export');
    Route::get('/report_export_view/{start}/{end}', 'HomeController@export_report_view');
    Route::get('/time-table', 'HomeController@tmtableView');
    Route::get('/detail/{user_id}', 'HomeController@detailUser');
    Route::post('/detail/{user_id}', 'HomeController@detailUser');
    Route::get('/reportJson/{start}/{end}', 'DashboardController@jsonReport');
    Route::post('/reciveManual/data', 'HomeController@reciveManual');
    Route::get('/calendar/Cuti', 'HomeController@calendarCuti');
    Route::get('/getTime', 'HomeController@gettimetable');
    Route::post('/editTmtable', 'HomeController@editTmtable');
    Route::post('/add/person', 'HomeController@addPerson');
    Route::post('/add/auth', 'HomeController@addUser');
    Route::get('/whitelist', 'WhitelistController@index');
    Route::get('/whitelist/api', 'WhitelistController@api');
    Route::post('/add/mapping', 'WhitelistController@store');
    Route::get('/list-employee', 'EmployeeController@index');
    Route::get('/days_off', 'DaysOffController@index');
    Route::get('/days_off/create', 'DaysOffController@create');
    Route::post('/days_off/accept/{id}', 'DaysOffController@accept');
    Route::post('/days_off/reject/{id}', 'DaysOffController@reject');
    Route::get('/days_off/print/{id}', 'DaysOffController@print');
});

Route::get('/list/people', function(){
    $data = \App\MProf::select('user_id')->get();

    $a = [];
    foreach($data as $dt){
        if($dt->user_id != null){
            $a []= $dt->user_id;
        }
    }
    return $a;
});
Route::get('/list/az', function(){
    // $data = \App\MProf::select('name','phone_number')->get();

    // $a = [];
    // foreach($data as $dt){
    //     if($dt->phone_number != 0){
    //         $a[]= $dt->phone_number;
    //         //echo $dt->name .", ".$dt->phone_number."<br>";
    //     }
    // }
    // return $a;
});



// Route::get('/list/people/1', function($id_office){
//     $data = \App\MappingMachineUserOri::join('m_profs', 'm_profs.user_id', '=', 'mapping_machine_and_userid.ori_user_id')->select('m_profs.name','mapping_machine_and_userid.user_id','mapping_machine_and_userid.ori_user_id')->where('mapping_machine_and_userid.office_id', $id_office)->get();
//     //ID OFFICE 
//     //3 CTC
//     //1 BEKASI
//     //2 PASMING
//     $a = [];
//     foreach($data as $dt){
//         if($dt->user_id != null){
//             $a []= array(
//                 "user_id" => $dt->user_id,
//                 "ori_userid" => $dt->ori_user_id,
//                 "name"=> $dt->name
//             );
//         }
//     }
//     return $a;
    
// });
Route::get('/list/people/lex2', function(){
    //$data = \App\MProf::select('id','name','position','user_id')->where('position', 2)->get(); //1 bekasi, 0 pasming, 2 ctc
    $data = \App\MProf::select('id','name','position','user_id')->where('user_id', 6)->get();
    //dd(count($data));
    $a = [];
    foreach($data as $dt){
        if($dt->user_id != null){
            $a []= array(
                "user_id" => $dt->user_id,
                "ori_userid" => $dt->user_id,
                "name"=> $dt->name
            );
        }
    }
    return $a;
   // return $a;
});
Route::get('/list/people/lex', function(){
    
    $data = \App\MProf::select('id','name','position','user_id')->get();
    
    $a = [];
    $get_att_today = [];
    foreach($data as $le){
        $cek = MAttend::where('mprof_id', $le->id)->where('date', date('Y-m-d'))->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->first();
        if(!isset($cek) && $le->user_id != null && $le->user_id != 99999){
            // $getImg = Vector::where('mprof_id', $le->id)->first();
            $a []= array(
                "user_id" => $le->user_id,
                "ori_userid" => $le->user_id,
                "name"=> $le->name
            );
            
        }
        
    }

    return $a;
    
});

Route::get('/list/people/lex_old', function(){
    
    $get_att_today = MAttend::whereDate('date', date('Y-m-d'))->where('status_sync', 0)->where('machine_id', 1)->get();
    $a = [];
    foreach($get_att_today as $dt){
        if($dt->in_time != "00:00:00" && $dt->in_tolerance_time == "00:00:00" && $dt->late_time == "00:00:00"){

        }elseif($dt->in_tolerance_time != "00:00:00" && $dt->in_time == "00:00:00" && $dt->late_time == "00:00:00"){
            
        }elseif($dt->late_time != "00:00:00" && $dt->in_time == "00:00:00" && $dt->in_tolerance_time == "00:00:00"){
        
        }else{
            $get_name = \App\MProf::where('user_id', $dt->mprof_id)->first();
            if($get_name->user_id != null){
                $a []= array(
                    "user_id" => $get_name->user_id,
                    "ori_userid" => $get_name->user_id,
                    "name"=> $get_name->name
                );
            }
        }
    }

    return $a;
    
});
Route::get('/list/people/lex/today', function(){
    
    $get_att_today = MAttend::where('date', date('Y-m-d'))->where('status_sync', 0)->where('machine_id', 1)->get();
    $a = [];
    foreach($get_att_today as $dt){
        if($dt->over_time != "00:00:00" && $dt->out_time == "00:00:00"){

        }elseif($dt->out_time != "00:00:00" && $dt->over_time == "00:00:00"){
            
        }else{
            $get_name = \App\MProf::where('user_id', $dt->mprof_id)->first();
            if($get_name->user_id != null){
                $a []= array(
                    "user_id" => $get_name->user_id,
                    "ori_userid" => $get_name->user_id,
                    "name"=> $get_name->name
                );
            }
        }
    }
    //dd(count($a));
    //$data = \App\MProf::select('id','name','position','user_id')->where('position', 2)->get(); //1 bekasi, 0 pasming, 2 ctc
    // $data = \App\MProf::select('id','name','position','user_id')->get();
    // //dd(count($data));
    // $a = [];
    // foreach($data as $dt){
    //     if($dt->user_id != null){
    //         $a []= array(
    //             "user_id" => $dt->user_id,
    //             "ori_userid" => $dt->user_id,
    //             "name"=> $dt->name
    //         );
    //     }
    // }
    return $a;
    
});



Route::get('/list/people/lex/2021', function(){
    date_default_timezone_set('Asia/Jakarta'); 
    $date = Carbon::now();
    $minTime = \Carbon\Carbon::parse($date->startOfWeek(), 'Asia/Jakarta');
    $maxTime = \Carbon\Carbon::parse($date->endOfWeek(), 'Asia/Jakarta');
    //$minTime = \Carbon\Carbon::parse($date->subWeek()->startOfWeek(), 'Asia/Jakarta');
    //$maxTime = \Carbon\Carbon::parse($date->subWeek()->endOfWeek(), 'Asia/Jakarta');
    //$minTime = "2021-03-01";
    //$maxTime = "2021-03-08";
    $period = CarbonPeriod::create($minTime, $maxTime);
    $dates = $period->toArray();

    //dd(\Carbon\Carbon::parse($date->subWeek()->endOfWeek(), 'Asia/Jakarta'));


    
    //dd("OK");
    //$data = \App\MProf::select('id','name','position','user_id')->where('position', 2)->get(); //1 bekasi, 0 pasming, 2 ctc
    //$data = \App\MProf::select('id','name','position','user_id')->get();
    
    $attendances = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->whereBetween('m_attends.date', [$minTime, $maxTime])->get();
    $list = [];

    foreach($attendances as $att){
        if($att->over_time != "00:00:00" && $att->out_time == "00:00:00"){

        }elseif($att->out_time != "00:00:00" && $att->over_time == "00:00:00"){
            
        }else{
            $get_name = \App\MProf::where('user_id', $att->mprof_id)->first();
            $time = strtotime($att->date);

            $newformat = date('d',$time);
            if($get_name != null && $att->machine_id == 1 && $att->status_sync == 0){
                $list [] = array(
                    "user_id" => $att->mprof_id,
                    "ori_userid" => $att->mprof_id,
                    "name"=> $get_name->name,
                    "date"=> $newformat
                );
            }
        }
    }

    return $list;
    // $a = [];
    // foreach($data as $dt){
    //     foreach($dates as $dat){
    //         $attendances = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('mprof_id', $dt->user_id)->where('m_attends.date', $dat->format('Y-m-d'))->first();
    //         if($dt->user_id != null && $attendances != null && $attendances->status != 4){
    //             if($attendances->over_time != "00:00:00" && $attendances->out_time == "00:00:00"){

    //             }elseif($attendances->out_time != "00:00:00" && $attendances->over_time == "00:00:00"){
                    
    //             }else{
    //                 $a []= array(
    //                     "user_id" => $dt->user_id,
    //                     "ori_userid" => $dt->user_id,
    //                     "name"=> $dt->name,
    //                     "date"=> $dat->format('Y-m-d')
    //                 );
    //             }
                
    //         }
    //     }
        
    // }
    // return $a;
    
});

Route::get('/list/people/lex/2021/new', function(){
    date_default_timezone_set('Asia/Jakarta'); 
    $date = Carbon::now();
    
    $minTime = \Carbon\Carbon::parse(strtotime('last week'), 'Asia/Jakarta');
    $maxTime = \Carbon\Carbon::parse($date->endOfWeek(), 'Asia/Jakarta');
    //$minTime = \Carbon\Carbon::parse($date->subWeek()->startOfWeek(), 'Asia/Jakarta');
    //$maxTime = \Carbon\Carbon::parse($date->subWeek()->endOfWeek(), 'Asia/Jakarta');
    //$minTime = "2021-03-01";
    //$maxTime = "2021-03-08";
    
    // dd(strtotime('last week'));
    $period = CarbonPeriod::create($minTime, $maxTime);
    $dates = $period->toArray();
    //dd($dates);
    //dd(\Carbon\Carbon::parse($date->subWeek()->endOfWeek(), 'Asia/Jakarta'));


    
    //dd("OK");
    //$data = \App\MProf::select('id','name','position','user_id')->where('position', 2)->get(); //1 bekasi, 0 pasming, 2 ctc
    //$data = \App\MProf::select('id','name','position','user_id')->get();
    
    $attendances = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->whereBetween('m_attends.date', [$minTime, $maxTime])->get();
    $list = [];
    //dd($dates);

    // foreach($dates as $datenya){
    //     echo $datenya->format('d')."<br>";
    // }
    $a = [];
    foreach($dates as $dat){
        $att = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('m_attends.machine_id', 1)->where('m_attends.date', $dat->format('Y-m-d'))->where('out_time', '00:00:00')->where('status_sync', 0)->orWhere('over_time', '00:00:00')->where('m_attends.date', $dat->format('Y-m-d'))->where('status_sync', 0)->get();
        foreach($att as $dt){
            if($dt->over_time != "00:00:00" && $dt->out_time == "00:00:00"){

            }elseif($dt->out_time != "00:00:00" && $dt->over_time == "00:00:00"){
                
            }else{
                

                $get_name = \App\MProf::where('user_id', $dt->mprof_id)->first();
                $time = strtotime($dt->date);
                $created_at = strtotime($dt->date);
                $newformat = date('d',$time);
                $formatcreated = date('Y-m-d H:i:s',$created_at);
                if($get_name->user_id != null){
                    $a []= array(
                        "id_attend" => $dt->id,
                        "user_id" => $get_name->user_id,
                        "ori_userid" => $get_name->user_id,
                        "name"=> $get_name->name,
                        "date"=> $newformat,
                        "created_at" => $formatcreated
                    );
                }
            }
        }
        //echo "Count ditanggal => ".$dat->format('d')." ada ".count($att)."<br>";
    }
    return $a;
    
});


Route::get('reportexcel/{id}/{start}/{end}', function($id, $start, $end){
    
    $filename = 'ReportBulananAbsen_'.$start."_to_".$end.".xlsx";
    return Excel::download(new AttendanceExportSingle($start,$end, $id), $filename);
});
Route::get('/list/people/email', function(){
    $data = \App\User::select('email')->get();

    $a = [];
    foreach($data as $dt){
        if($dt->email != null){
            $a []= $dt->email;
        }
    }
    return $a;
});
Route::get('/list/people/emailid', function(){
    $data = \App\User::select('id','email', 'name')->get();

    $a = [];
    foreach($data as $dt){
        if($dt->email != null){
            $a []= [$dt->id, $dt->email, $dt->name];
        }
    }
    return $a;
});
Route::get('/test', function(){
    $office = OfficeLocat::create(['id'=> 5, 'name_office'=> 'SII-TMBUN', 'lat'=> -6.2477086, 'long'=> 107.0714564, 'address'=> '2, Jl. Flamboyan 5 No.4, RW.8, Tridaya Sakti, Kec. Tambun Sel., Bekasi, Jawa Barat 17510, Indonesia', 'status'=> 1]);

    return "OK";
});

Route::get('/list/maping/andro', function(){
    $data = \App\MappingModLoc::select('users.name','office_location.name_office')->join('users', 'users.id', '=', 'maping_userloc.user_id')->join('office_location', 'office_location.id', '=', 'maping_userloc.office_id')->get();

    foreach($data as $d){
        echo "NAMA => ".$d->name." - OFFICE -> ".$d->name_office."<br>";
    }

});

Route::post('/images/upload/struk', function(Request $request){
    $data =  $request->json()->all();
    $image = $data["image"];
    $name = $data["name"];
 
    $realImage = base64_decode($image);
    $image = str_replace('data:image/jpeg;base64,', '', $image);
    $image = str_replace(' ', '+', $image);
    $imageName = strtotime(date('Y-m-d H:i:s')) . '.jpg';
    $imageNameCompress = strtotime(date('Y-m-d H:i:s')) . '-compress-oke.jpg';

    Storage::disk('public')->put($imageName, base64_decode($image));
    // $imageName = strtotime(date('Y-m-d H:i:s')) . '.jpg';
        
    // Storage::disk('public')->put($imageName, $realImage);
    //file_put_contents($name, $realImage);

    // $pathToImage = public_path('/storage/'.$imageName);
    // $pathToOptimizedImage = public_path('/storage/'.$imageNameCompress);
    // ImageOptimizer::optimize($pathToImage, $pathToOptimizedImage);

    // $destinationPath = public_path('thumbnail');

    // // open an image file
    $img = Image::make(public_path('storage/'.$imageName));

    // now you are able to resize the instance
    $img->resize(350, 250);

    // // and insert a watermark for example
    // //$img->insert('public/watermark.png');

    // // finally we save the image as a new file
    $img->save(public_path('thumbnail/'.$imageName));

    
    // $img = Image::make(public_path('/storage/'.$imageName));
    // $img->resize(100, 100, function ($constraint) {
    //     $constraint->aspectRatio();
    // })->save($destinationPath.'/'.$imageNameCompress);

    // if($img == false){
    //     return response(['status'=> 'OK', 'data'=> "error"]);
    // }


    unlink(public_path('storage/'.$imageName));

    // $destinationPath = public_path('/images');
    // $image->move($destinationPath, $input['imagename']);

    //$this->postImage->add($input);
    //echo "Image Uploaded Successfully.";
    $file_path = url('/').'/thumbnail/'.$imageName;
    //$file_path = url('/').'/storage/'.$imageNameCompress;
    return response(['status'=> 'OK', 'data'=> $file_path]);
});

Route::post('/store/databudget', function(Request $request){
    $data =  $request->json()->all();
    $message = $data["message"];
    $number = $data["number"];
 
    
    // $imageName = strtotime(date('Y-m-d H:i:s')) . '.jpg';
        
    // Storage::disk('public')->put($imageName, $realImage);
    //file_put_contents($name, $realImage);
 
    //echo "Image Uploaded Successfully.";
    $file_path = url('/').'/storage/'.$imageName;
    return response(['status'=> 'OK', 'data'=> "data", 'message'=> $message, 'number'=> $number]);
});