<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MAttend;
use App\MLogging;
use App\MProf;
use App\Tmtable;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\User;
use App\Vector;
use App\OfficeLocat;
use App\MappingModLoc;
use App\day_off;
use App\NotificationMod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\MappingMachineUserOri;
use Intervention\Image\Facades\Image;

class ApiController extends Controller
{
    public function recive(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $data =  $request->json()->all();
        // var_dump($data);

        // die();
        $id_user = $data['id'];

        $name = $data['name'];
        $now = Carbon::now()->format('Y-m-d');
        $date = $data['date_time'];
        $type = $data['attend_by'];
        $time = Carbon::parse($date)->format('H:i:s');
        $name_day = Carbon::parse($date)->format('l');


        $get_profuser = MProf::where('user_id', $id_user)->first();
        //dd($get_profuser);
        if($get_profuser->phone_number == "0"){
            $number_phone = "62859141490060";
        }else{
            $number_phone = $get_profuser->phone_number;
        }

        $check = MAttend::where('mprof_id',$get_profuser->id)->where('date', Carbon::parse($date)->format('Y-m-d'))->first();
        //dd($get_profuser);

        //dd($check);
        //$check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '>=', $time)->where('end_at', '<=', $time)->first();
        $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $time)->where('end_at', '>=', $time)->first();
        //dd($check_tmtable);
        //dd(Carbon::parse($date)->format('Y-m-d'));
        //Log::debug($check_tmtable);
        Log::debug($data);
        Log::debug($check_tmtable);
        // $x = -6.226729;   $y = 107.004427;  // user lat long recive
        // $circle_x = -6.226931; //target lat
        // $circle_y = 107.004768; //target long
        // $rad = 20;


        // $dist = computeDistance($circle_x, $circle_y, $x, $y);
        // print($dist);
        // print($rad);
        // if($dist <= $rad){
        //     echo "Inside";
        // }else{
        //     echo "Outside";

        // }
	//var_dump($check_tmtable);
	//die();
        if($check == null && $now == Carbon::parse($date)->format('Y-m-d')){

            if($check_tmtable){
                $imageName = "";
                if($data['img64'] != ""){
                    $image = $data['img64'];  // your base64 encoded
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = strtotime(date('Y-m-d H:i:s')) . '.jpg';

                    Storage::disk('public')->put($imageName, base64_decode($image));

                    $img = Image::make(public_path('storage/'.$imageName));

                    // now you are able to resize the instance
                    $img->resize(350, 250);

                    // // and insert a watermark for example
                    // //$img->insert('public/watermark.png');

                    // // finally we save the image as a new file
                    $img->save(public_path('thumbnail/absen-'.$imageName));

                    unlink(public_path('storage/'.$imageName));
                }
                $hourn = Carbon::parse($date)->format('H');
                $minuten = Carbon::parse($date)->format('i');
                if($get_profuser->position == 0  && $hourn >= 9 && $minuten > 0 || $get_profuser->position == 2  && $hourn >= 9 && $minuten > 0){ //khusus pasming 09:01 dianggap telat get by position 0
                    $gettmbtl = Tmtable::where('day', $name_day)->where('type', 'late')->first();

                    MAttend::create([
                        'date'=> Carbon::parse($date)->format('Y-m-d'),
                        'mprof_id'=> $get_profuser->id,
                        'tmtable_id'=> $gettmbtl->id,
                        'in_time' => ( $gettmbtl->type == "in") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                        'in_tolerance_time'  => ( $gettmbtl->type == "in_tolerance") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                        'out_time' => ( $check_tmtable->type == "out") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                        'over_time' => ( $check_tmtable->type == "over") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                        'late_time' => ( $gettmbtl->type == "late") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                        'first_attend' => Carbon::parse($date)->format('H:i:s'),
                        'last_attend'=> Carbon::parse($date)->format('H:i:s'),
                        'type_data' => ($imageName != "" ) ? 'absen-'.$imageName : null,
                        'noted' => (isset($data['address_name']) ? $data['address_name'] : null),
                        'status_employee' => null,
                        'machine_id' => $type,
                        'lat_attend' => (isset($data['lat']) ? $data['lat'] : null),
                        'lon_attend' => (isset($data['long']) ? $data['long'] : null)
                    ]);
                    $this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *MASUK*\n ⏰ - ".date('Y-m-d H:i:s')." \n 📍 " .(isset($data['address_name']) ? $data['address_name'] : null). " \n ");
                }else{
                    MAttend::create([
                            'date'=> Carbon::parse($date)->format('Y-m-d'),
                            'mprof_id'=> $get_profuser->id,
                            'tmtable_id'=> $check_tmtable->id,
                            'in_time' => ( $check_tmtable->type == "in") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                            'in_tolerance_time'  => ( $check_tmtable->type == "in_tolerance") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                            'out_time' => ( $check_tmtable->type == "out") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                            'over_time' => ( $check_tmtable->type == "over") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                            'late_time' => ( $check_tmtable->type == "late") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                            'first_attend' => Carbon::parse($date)->format('H:i:s'),
                            'last_attend'=> Carbon::parse($date)->format('H:i:s'),
                            'type_data' => ($imageName != "" ) ? 'absen-'.$imageName : null,
                            'noted' => $data['address_name'],
                            'status_employee' => null,
                            'machine_id' => $type,
                            'lat_attend' => $data['lat'],
                            'lon_attend' => $data['long']
                        ]);
                        $this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *MASUK*\n ⏰ - ".date('Y-m-d H:i:s')." \n 📍 " .(isset($data['address_name']) ? $data['address_name'] : null). " \n ");
                }
                return response()->json(['status'=> 'Your Data Success Send To Server']);
            }else{
                return response()->json(['status'=> 'You are not position time to attend, next time']);
            }
        }else{
            if($check_tmtable->type == "out" && $check->in_time != "00:00:00" || $check_tmtable->type == "out" && $check->in_tolerance_time != "00:00:00" || $check_tmtable->type == "out" && $check->late_time != "00:00:00"){
                if($data['img64'] != ""){
                    $image = $data['img64'];  // your base64 encoded
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = strtotime(date('Y-m-d H:i:s')) . '.jpg';

                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $img = Image::make(public_path('storage/'.$imageName));

                    // now you are able to resize the instance
                    $img->resize(350, 250);

                    // // and insert a watermark for example
                    // //$img->insert('public/watermark.png');

                    // // finally we save the image as a new file
                    $img->save(public_path('thumbnail/absen-out-'.$imageName));

                    unlink(public_path('storage/'.$imageName));
                }
                if($check->out_time == "00:00:00"){
                    Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($date)->format('H:i:s'), 'out_location'=> $data['address_name'], 'out_face'=> 'absen-out-'.$imageName]);
                    Log::debug("DONE UPDATE");
                    $this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *PULANG*\n ⏰ - ".date('Y-m-d H:i:s')." \n 📍 " .(isset($data['address_name']) ? $data['address_name'] : null). " \n ");

                }else{
                    Log::debug("OUT TIME  ALREADY");
                }
            }elseif($check_tmtable->type == "over" && $check->in_time != "00:00:00" || $check_tmtable->type == "over" && $check->in_tolerance_time != "00:00:00" || $check_tmtable->type == "over" && $check->late_time != "00:00:00"){

                if($data['img64'] != ""){
                    $image = $data['img64'];  // your base64 encoded
                    $image = str_replace('data:image/jpeg;base64,', '', $image);
                    $image = str_replace(' ', '+', $image);
                    $imageName = strtotime(date('Y-m-d H:i:s')) . '.jpg';

                    Storage::disk('public')->put($imageName, base64_decode($image));
                    $img = Image::make(public_path('storage/'.$imageName));

                    // now you are able to resize the instance
                    $img->resize(350, 250);

                    // // and insert a watermark for example
                    // //$img->insert('public/watermark.png');

                    // // finally we save the image as a new file
                    $img->save(public_path('thumbnail/absen-out-over-'.$imageName));

                    unlink(public_path('storage/'.$imageName));
                }
                if($check->over_time == "00:00:00"){
                    $imageName = null;
		            Mattend::where('id', $check->id)->update(['over_time'=> Carbon::parse($date)->format('H:i:s'), 'out_location'=> $data['address_name'], 'out_face'=> 'absen-out-over-'.$imageName]);
                    Log::debug("DONE UPDATE");
                    $this->sendwa($number_phone, "🎺 Terimakasih sudah absen *PULANG* dengan waktu yang over dari jam normal kantor\n ⏰ - ".date('Y-m-d H:i:s')." \n 📍 " .(isset($data['address_name']) ? $data['address_name'] : null). " \n ");

                }else{
                    Log::debug("OVER TIME  ALREADY");
                }

            }else{
                Log::debug($request);
            }

            return response()->json(['status'=> 'Already , and go update lasted attend']);
        }

        // MLogging::create(['mprof_id' => $id_user, 'name' => $name, 'log_time'=> null, 'sunrise'=> null, '1']);
        // Log::info(var_dump($request, true));

        // return $request;
    }

    public function reciveaPY(Request $data){
        date_default_timezone_set("Asia/Jakarta");

        $id_user = $data->id;

        $now = Carbon::now()->format('Y-m-d');
        $date = $data->date_time;
        $type = $data->attend_by;
        $time = Carbon::parse($date)->format('H:i:s');
        $name_day = Carbon::parse($date)->format('l');
        if($id_user == 4 && $type == 2){
            $idnya = 2;
        }elseif($id_user == 106 && $type == 2){
            $idnya = 146;
        }else{
            $idnya = $id_user;
        }

        //dd($idnya);
        $get_profuser = MProf::where('user_id', $idnya)->first();

        if($get_profuser->phone_number == "0"){
            $number_phone = "62859141490060";
        }else{
            $number_phone = $get_profuser->phone_number;
        }
        // $get_profuser = MappingMachineUserOri::where('user_id', $idnya)->where('office_id', $type)->first();
        // //dd($get_profuser);
        // if($get_profuser == null){
        //     return response()->json(['status'=> 'User not found in mapping']);
        // }


        $check = MAttend::where('mprof_id',$idnya)->where('date', Carbon::parse($date)->format('Y-m-d'))->first();
        //dd($get_profuser);
        if($type == 5){
            //dd($check->id);
            if(Carbon::parse($date)->format('Y-m-d') == Carbon::parse(date('Y-m-d'))->format('Y-m-d')){
                return response()->json(['status'=> 'Data hari ini belum ada ,akan diberlakukan sync berkala..\n'.$date.'-'.Carbon::parse(date('Y-m-d'))->format('Y-m-d')]);
            }else{
                $update = $check->update(['last_attend'=> date('H:i:s'), 'status_sync'=> 3]);
                if($update){
                    return response()->json(['status'=> 'Success Sync ,tapi tidak ada data nya di mesin status menjadi 3 pada server..\n', 'data'=> $check]);
                }else{
                    return response()->json(['status'=> 'Failed Sync ,tapi tidak ada data nya di mesin status menjadi 3 pada server..\n']);
                }

            }
        }else{
            //dd($check);
            //$check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '>=', $time)->where('end_at', '<=', $time)->first();
            $check_tmtable = Tmtable::where('day', $name_day)->where('start_at', '<=', $time)->where('end_at', '>=', $time)->first();
            //dd($check_tmtable);
            //dd(Carbon::parse($date)->format('Y-m-d'));
            //Log::debug($check_tmtable);
            Log::debug($data);
            Log::debug($check_tmtable);
            // $x = -6.226729;   $y = 107.004427;  // user lat long recive
            // $circle_x = -6.226931; //target lat
            // $circle_y = 107.004768; //target long
            // $rad = 20;


            // $dist = computeDistance($circle_x, $circle_y, $x, $y);
            // print($dist);
            // print($rad);
            // if($dist <= $rad){
            //     echo "Inside";
            // }else{
            //     echo "Outside";

            // }
            if($check == null && $now == Carbon::parse($date)->format('Y-m-d')){
                if($name_day == "Saturday" || $name_day == "Sunday"){
                    //$this->sendwa($number_phone, "🎺 Anda Masuk di hari libur ,silahkan anda absen menggunakan aplikasi mobile jika dinyatakan lembur terimakasih, karena mesin tidak di aktifkan saat hari sabtu dan minggu ...*");
                    return response()->json(['status'=> 'Sabtu Minggu Libur']);
                }else{
                    if($check_tmtable){

                            MAttend::create([
                                'date'=> Carbon::parse($date)->format('Y-m-d'),
                                'mprof_id'=> $idnya,
                                'tmtable_id'=> $check_tmtable->id,
                                'in_time' => ( $check_tmtable->type == "in") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                                'in_tolerance_time'  => ( $check_tmtable->type == "in_tolerance") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                                'out_time' => ( $check_tmtable->type == "out") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                                'over_time' => ( $check_tmtable->type == "over") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                                'late_time' => ( $check_tmtable->type == "late") ? Carbon::parse($date)->format('H:i:s') : "00:00:00",
                                'first_attend' => Carbon::parse($date)->format('H:i:s'),
                                'last_attend'=> Carbon::parse($date)->format('H:i:s'),
                                'type_data' => null,
                                'noted' => 'Hikvision',
                                'status_employee' => null,
                                'machine_id' => $type,
                                'lat_attend' => $data['lat'],
                                'lon_attend' => $data['long']
                            ]);

                            //$this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *MASUK*\n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n *nb: report ini dikirim terlambat dikarenakan menunggu proses sinkronisasi data terlebih dahulu*");
                        return response()->json(['status'=> 'Your Data Success Send To Server']);
                    }else{
                        return response()->json(['status'=> 'You are not position time to attend, next time']);
                    }
                }
            }else{
                $status_action = "no action";
                if(isset($check_tmtable)){

                    if($name_day == "Saturday" || $name_day == "Sunday"){
                        //status employee == 99 ,lembur artinya
                        Mattend::where('id', $check->id)->update(['last_attend'=> Carbon::parse($date)->format('H:i:s'), 'status_employee'=> 99, 'updated_at'=> Carbon::parse($date)->format('H:i:s')]);
                        $status_action = "UP DATE LEMBUR WEEKEND";
                        Log::debug("DONE UPDATE FOR LEMBUR Weekend");
                    }else{
                        if($check_tmtable->type == "out" && $check->in_time != "00:00:00" || $check_tmtable->type == "out" && $check->in_tolerance_time != "00:00:00" || $check_tmtable->type == "out" && $check->late_time != "00:00:00"){

                            if(Carbon::parse($date)->format('H') <= 23 && Carbon::parse($date)->format('H') >= 19){
                                Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>1]);
                                Log::debug("DONE UPDATE");
                                //$this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *PULANG* , melewati batas maximum jam normal kantor\n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n ");
                                $status_action = "UP DATE OUT TIME Kurang dari pukul 23 and lebih dari pukul 19";
                            }else{
                                if($check->out_time == "00:00:00" && Carbon::parse($date)->format('H') <= 18){

                                    Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>1]);
                                    Log::debug("DONE UPDATE");
                                    //$this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *PULANG*\n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n *nb : report ini terlambat dikarenakan melakukan sinkronisasi data terlebih dahulu*");
                                    $status_action = "UP DATE OUT TIME";
                                }else{
                                    Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>1]); //INI BARU DI EDIT ON 19/02/2021
                                    $status_action = "OUT TIME ALREADY";
                                    Log::debug("OUT TIME  ALREADY");
                                }
                            }
                        }elseif($check_tmtable->type == "over" && $check->in_time != "00:00:00" || $check_tmtable->type == "over" && $check->in_tolerance_time != "00:00:00" || $check_tmtable->type == "over" && $check->late_time != "00:00:00"){

                            if(date("H") == 23 && date("i") == 59){
                                Mattend::where('id', $check->id)->update(['over_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>1]);//INI BARU DI EDIT ON 19/02/2021
                                $status_action = "Over time already and finaly sync 1";
                            }else{
                                Mattend::where('id', $check->id)->update(['over_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>0]);
                                $status_action = "Over time already, go update end 23 ";
                            }

                            // if($check->over_time == "00:00:00"){
                            //     Mattend::where('id', $check->id)->update(['over_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>0]);
                            //     Log::debug("DONE UPDATE");
                            //     $status_action = "Done Update Over Time";
                            //     //$this->sendwa($number_phone, "🎺 Terimakasih anda baru saja melakukan absen *PULANG*, melebihi batas normal jam kerja kantor\n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n ");
                            //     return response()->json(['status'=> 'Done Update over Time']);
                            // }else{



                            //     Log::debug("OVER TIME  ALREADY");
                            // }

                        }else{

                            Log::debug($data);
                        }
                    }
                    return response()->json(['status'=> 'Already , and go update lasted attend', 'datafrom'=> $status_action]);
                }else{
                    // if(Carbon::parse($date)->format('H') == 17 && Carbon::parse($date)->format('i') > 20){
                    //     Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>2]);
                    //     $this->sendwa("62859141490060", "🎺 Mencoba Absen Pulang sebelum pukul 17:30 ,bernama ".$get_profuser->name."\n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n ");
                    // }else{

                    // }
                    // Mattend::where('id', $check->id)->update(['out_time'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>2]);
                    Mattend::where('id', $check->id)->update(['last_attend'=> Carbon::parse($date)->format('H:i:s'), 'status_sync'=>1]);
                    // $this->sendwa($number_phone, "🎺 Anda mencoba absen pulang sebelum JAM : \n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n ");
                    //$this->sendwa("62859141490060", "🎺 Mencoba Absen Pulang sebelum pukul 17:30 ,bernama ".$get_profuser->name."\n ⏰ - ".$date." \n 📍 LOKASI : MESIN ABSEN SII-TEBET \n ");

                    return response()->json(['status'=> 'No  Timetable']);
                }


            }

            // MLogging::create(['mprof_id' => $id_user, 'name' => $name, 'log_time'=> null, 'sunrise'=> null, '1']);
            // Log::info(var_dump($request, true));

            // return $request;
        }
    }

    public function checkRangeLocation($id, Request $request){
        date_default_timezone_set("Asia/Jakarta");


        $x = $request->lat;   //user
        $y = $request->long;  // user lat long recive
        $date = Carbon::now()->format('Y-m-d');
        $get_location_allow = MappingModLoc::where('user_id', $id)->get();
        $get_profuser = MProf::where('user_id', $id)->first();
        $check = MAttend::where('date', $date)->where('mprof_id', $get_profuser->id)->first();
        //dd($check);
        $name_day = Carbon::parse($date)->format('l');
        $list_location_allow = [];
        $time = date('H');
        //$time = 17;
        foreach($get_location_allow as $gla){

            if($gla->office_id != null){
                $get_office_latlong = OfficeLocat::find($gla->office_id);
                $lat = $get_office_latlong->lat;
                $long = $get_office_latlong->long;
                $name_loc = $get_office_latlong->name_office;
                $radius = $get_office_latlong->radius_allow;
            }else{
                $lat = $gla->lat;
                $long = $gla->long;
                $name_loc = $gla->name_location;
                $radius = $gla->radius_allow;
            }
            $circle_x = $lat; //target lat
            $circle_y = $long; //target long
            $rad = $radius;


            $dist = $this->computeDistance($circle_x, $circle_y, $x, $y);

            if($dist <= $rad){
                $list_location_allow [] =  $name_loc;
                //echo "Inside ".$gla->id."\n";
            }else{
                //echo "Outside ".$gla->id."\n";

            }
        }
        if(isset($check)){
            if($check->in_time != "00:00:00"){
                $timenya = (isset($check->in_time) ? $check->in_time : "00:00");
            }elseif($check->in_tolerance_time != "00:00:00"){
                $timenya = $check->in_tolerance_time;
            }else{
                $timenya = $check->late_time;
            }
        }else{
            $timenya = "00:00:00";
        }
        //dd($list_location_allow);
        if($time >=0 && $time <= 11){
            $ucapan = 'Selamat Pagi 🌞 Keluarga SII';
        }elseif($time >= 12 && $time < 15){
            $ucapan = 'Selamat Siang 🌤 Keluarga SII';
        }elseif($time >= 15 && $time <= 16){
            $ucapan = 'Selamat Sore 🌝 Keluarga SII';
        }else{
            $ucapan = 'Selamat Malam 🌛 Keluarga SII';
        }
        if($name_day == "Saturday" || $name_day == "Sunday"){
            if(count($get_location_allow) == 0){
                return response()->json(['status'=> true, 'message'=> 'Silahkan absen, selama "WFH" akses tidak dibatasi berdasarkan Lokasi,Stay Safe... ', 'time'=> 'TIME IN : 🕰 '.$timenya]);
            }else{
                if(count($list_location_allow) > 0){
                    return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen masuk 😎, sekarang anda berada dalam radius yang kami tentukan , Hati-Hati di  jalan ,Tetap Stay Safe ..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                }else{
                    return response()->json(['status'=> false, 'message' => 'Kamu di luar dari radius yang sudah kami tetapkan 😭😩']);
                    //return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, sekarang anda masih di izinkan untuk absen pulang ,dikarenakan admin belum menentukan whitelist area untuk akun anda , Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                }
            }
            //return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka dan untuk yang lembur jangan lupa, absen pulang nya ya 😎 ~ '.$ucapan.'', 'time'=> 'TIME IN : -']);
        }
        if(!isset($check) && $time >= 05 && $time <= 12){
            if(count($get_location_allow) == 0){
                return response()->json(['status'=> true, 'message'=> 'Silahkan absen, selama "WFH" akses tidak dibatasi berdasarkan Lokasi,Stay Safe... ', 'time'=> 'TIME IN : 🕰 '.$timenya]);
            }else{
                if(count($list_location_allow) > 0){
                    return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen masuk 😎, sekarang anda berada dalam radius yang kami tentukan , Hati-Hati di  jalan ,Tetap Stay Safe ..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                }else{
                    return response()->json(['status'=> false, 'message' => 'Kamu di luar dari radius yang sudah kami tetapkan 😭😩']);
                    //return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, sekarang anda masih di izinkan untuk absen pulang ,dikarenakan admin belum menentukan whitelist area untuk akun anda , Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                }
            }


            // if(count($list_location_allow) > 0){
            //     return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen masuk 😎,sekarang anda berada dalam radius yang sudah kami tentukan ~ '.$ucapan.'', 'time'=> 'TIME IN : -']);
            // }else{
            //     return response()->json(['status'=> false, 'message'=> 'Akses Menu ditutup dikarenakan anda berada diluar jangkauan '.$ucapan.'', 'time'=> 'TIME IN : -']);
            // }
        }elseif(isset($check)){
            if($check->in_time != "00:00:00"){
                $timenya = $check->in_time;
            }elseif($check->in_tolerance_time != "00:00:00"){
                $timenya = $check->in_tolerance_time;
            }else{
                $timenya = $check->late_time;
            }

            if($check->out_time != "00:00:00" || $check->over_time != "00:00:00"){
                //return response()->json(['status'=> false, 'message'=> 'Terimakasih sudah absen hari ini 👏', 'time'=> ($check->out_time != "00:00:00") ? 'TIME OUT :'.$check->out_time : $check->over_time]);
            }elseif(isset($check) && $time <= 13 && $time >= 05){
                echo 'Terimakasih sudah absen masuk ,jangan lupa absen keluarnya ya 🙏🏻🙏🏻🤓, Thanks ~ '.$ucapan.'';
                //return response()->json(['status'=> false, 'message'=> 'Terimakasih sudah absen masuk ,jangan lupa absen keluarnya ya 🙏🏻🙏🏻🤓, Thanks ~ '.$ucapan.'', 'time'=> 'TIME IN : 🕰 '.$timenya]);
            }elseif($time > 12 && $time <= 15){

                if(isset($check->in_time) != "00:00:00" || isset($check->in_tolerance_time) != "00:00:00" || isset($check->late_time) != "00:00:00"){
                    return response()->json(['status'=> false, 'message'=> 'Anda tidak diperbolehkan akses menu,dikarenakan tidak absen masuk, dan absen masuk mulai 05:00 - 12:00', 'time'=> '00:00:00']);
                }else{

                    return response()->json(['status'=> false, 'message'=> 'Belum Waktunya Absen Pulang Tunggu Jam 17:00 😬😜, Thanks ~ '.$ucapan.'', 'time'=> 'TIME IN : 🕰 '.$timenya, 'imagesprof'=> $file]);
                }
            }else{
                if(count($get_location_allow) == 0){
                    return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, sekarang anda masih di izinkan untuk absen pulang ,dikarenakan admin belum menentukan whitelist area untuk akun anda , Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                }else{
                    if(count($list_location_allow) > 0){
                        return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, sekarang anda berada dalam radius yang kami tentukan , Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                    }else{
                        return response()->json(['status'=> false, 'message' => 'Kamu di luar dari radius yang sudah kami tetapkan 😭😩']);
                        //return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, sekarang anda masih di izinkan untuk absen pulang ,dikarenakan admin belum menentukan whitelist area untuk akun anda , Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya]);
                    }
                }

            }

        }else{
            return response()->json(['status'=> true, 'message'=> 'Silahkan absen, menu ini di open sementara, thanks...,jika ada masalah silahkan hubungi team IT/Support']);
            // return response()->json(['status'=> false, 'message'=> 'Tidak diperbolehkan absen ,dikarenakan data jam masuk anda tidak ditemukan .... ']);

        }



    }

    public function list_attend(Request $request){
        date_default_timezone_set("Asia/Jakarta");
        $date = Carbon::now()->format('Y-m-d');
        //$date = "2020-09-25";
        $in = MAttend::join('office_location', 'office_location.id', '=', 'm_attends.machine_id')->where('in_time', '!=', '00:00:00')->where('date', $date)->orWhere('late_time', '!=', '00:00:00')->where('date', $date)->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->orWhere('over_time', '!=', '00:00:00')->where('date', $date)->orWhere('out_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.late_time','DESC')->orderBy('m_attends.in_tolerance_time','DESC')->orderBy('m_attends.in_time','DESC')->get();
        $list_attend = [];
        foreach($in as $dec){
            if($dec->in_tolerance_time != "00:00:00"){
                $name_day = \Carbon\Carbon::parse($dec->date." ".$dec->in_tolerance_time)->format('l');
                $getINtmtable = \App\Tmtable::where('day', $name_day)->where('type', 'in_tolerance')->first();
                $start_time = $getINtmtable->start_at;
                $login_time = $dec->in_tolerance_time;
                $start = \Carbon\Carbon::createFromFormat('H:i:s', $start_time);
                $end = \Carbon\Carbon::createFromFormat('H:i:s', $login_time);
                $hasil = $start->diff($end);
                $h = $hasil->h;
                $m = $hasil->i;
                $s = $hasil->s;
                if($hasil->format('%H') == "00"){
                    $hasil2 = $hasil->format('%i:%s');
                }else{
                    $hasil2 = $hasil->format('%H:%i:%s');
                }
            }
            $file = (isset($getImages->file)) ? $getImages->file : asset("assets/img/default.png");
            if(file_exists($file)){
                $img = asset($getImages->file);
            }else{
                $img = asset("assets/img/default.png");
            }
            if($dec->machine_id == 1){
                $st = trim($dec->name_office);
            }elseif($dec->machine_id == 4){
                $st = trim($dec->name_office);
            }elseif($dec->status_employee != null){
                $st = "Manual Attend";
            }elseif($dec->machine_id == 2){
                $st = trim($dec->name_office);
            }elseif($dec->machine_id == 3){
                $st = trim($dec->name_office);
            }else{
                $st = "MachineNoSet";
            }

            if($dec->out_time != "00:00:00" || $dec->over_time != "00:00:00"){
                $time_out = ($dec->out_time != "00:00:00") ? $dec->out_time : $dec->over_time;
            }else{
                $time_out = "";
            }

            if($dec->in_time != "00:00:00"){
                $time = $dec->in_time;
            }elseif($dec->in_tolerance_time != "00:00:00"){
                $time = $dec->in_tolerance_time;
            }elseif($dec->late_time != "00:00:00"){
                $time = $dec->late_time;
            }else{
                $time = "00:00:00";
            }
            $arr['name'] = $dec->name;
            $arr['keterangan']= $st;
            $arr['waktu'] = $time ." - ". $time_out;

            $list_attend []= $arr;
        }

        return $list_attend;
    }

    public function list_lateattend(){
        date_default_timezone_set("Asia/Jakarta");
        $date = Carbon::now()->format('Y-m-d');
        //$date = "2020-09-25";
        //$in = MAttend::where('late_time', '!=', '00:00:00')->where('date', $date)->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->orWhere('over_time', '!=', '00:00:00')->where('date', $date)->orWhere('out_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.late_time','DESC')->orderBy('m_attends.in_tolerance_time', 'DESC')->get();
        $in = MAttend::where('late_time', '!=', '00:00:00')->where('date', $date)->orWhere('in_time', '!=', '00:00:00')->where('date', $date)->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date', $date)->orWhere('over_time', '!=', '00:00:00')->where('date', $date)->orWhere('out_time', '!=', '00:00:00')->where('date', $date)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.in_time','DESC')->orderBy('m_attends.late_time','DESC')->orderBy('m_attends.in_tolerance_time', 'DESC')->get();
        $list_attend = [];
        foreach($in as $dec){

            $file = (isset($dec->type_data)) ? asset("storage/".$dec->file) : asset("assets/img/default.png");
            if($dec->out_face != ""){
                $img = asset("storage/".$dec->out_face);
            }elseif($dec->type_data != ""){
                $img = asset("storage/".$dec->type_data);
            }else{
                $img = asset("assets/img/default.png");
            }
            if($dec->machine_id == 1){
                $st = "At Bekasi";
            }elseif($dec->machine_id == 4){
                $st = "Mobile Attend";
            }elseif($dec->status_employee != null){
                $st = "Manual";
            }else{
                $st = "At Pasming";
            }
            if($dec->late_time != "00:00:00"){
                if($dec->out_time != "00:00:00"){
                    $time = $dec->out_time;
                    $ket = "OUT";
                }elseif($dec->over_time != "00:00:00"){
                    $time = $dec->over_time;
                    $ket = "(OUT) Over Time";
                }else{
                    $time = $dec->late_time;
                    $ket = "(IN) Late";
                }

            }elseif($dec->in_tolerance_time != "00:00:00"){
                if($dec->out_time != "00:00:00"){
                    $time = $dec->out_time;
                    $ket = "OUT";
                }elseif($dec->over_time != "00:00:00"){
                    $time = $dec->over_time;
                    $ket = "(OUT) Over Time";
                }else{
                    $time = $dec->in_tolerance_time;
                    $ket = "(IN) Tolerance";
                }
            }elseif($dec->in_time != "00:00:00"){
                if($dec->out_time != "00:00:00"){
                    $time = $dec->out_time;
                    $ket = "OUT";
                }elseif($dec->over_time != "00:00:00"){
                    $time = $dec->over_time;
                    $ket = "(OUT) Over Time";
                }else{
                    $time = (isset($dec->in_time)) ? $dec->in_time : $dec->in_tolerance_time;
                    $ket = "On Time";
                }
            }else{
                $time = "00:00:00";
                $ket = "Nothing";
            }
            $arr['name'] = $dec->name;
            $arr['location']= $st;
            $arr['keterangan'] = $ket;
            $arr['waktu'] = $time;
            $arr['img'] = $img;

            $list_attend []= $arr;
        }

        return $list_attend;
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if( Auth::attempt(['email'=>$request->email, 'password'=>$request->password]) ) {
            $cek = User::where('email', $request->email)->first();
            // $div = day_off::all();
            $mprof = MProf::all();

            if($cek->team_id==0){
                $jabatan="Support";
            }else if($cek->team_id==1){
                $jabatan="Programmer";
            }else if($cek->team_id==3){
                $jabatan="Finance";
            }else if($cek->team_id==4){
                $jabatan="Admin";
            }else if($cek->team_id==5){
                $jabatan="HRGA";
            }else if($cek->team_id==7){
                $jabatan="PM";
            }else if($cek->team_id==8){
                $jabatan="MicroController";
            }else if($cek->team_id==null){
                $jabatan="NULL!";
                return response()->json(['status'=> 'NO DIVISION ID']);
            }else{
                $jabatan="Undefined";
                return response()->json(['status'=> 'DIVISION ID UNDEFINED']);
            }
            $mprof = MProf::where('user_id',Auth::user()->id)->first();
            $remaining_days_off=day_off::select('remaining_days_off')->where('name',$mprof->name)->orderBy('id','desc')->first();
            // dd($remaining_days_off);
            $data = array(
                'id' => $cek->id,
                'name' => (strlen($mprof->name) > 15) ? substr($mprof->name, 0, 15).'... ': $mprof->name,
                'email' => $cek->email,
                'teamID' => $cek->team_id,
                'departement'=>$jabatan,
                'sisa cuti'=>$remaining_days_off->remaining_days_off,
            );
            if($cek && Hash::check($request->password, $cek->password)){
                $getImages = Vector::where('mprof_id', $cek->id)->first();
                $file = (isset($getImages->file)) ? asset($getImages->file) : asset("assets/img/default.png");
                return response()->json(['status'=> 'ok', 'data'=> $data, 'image_profile'=> $file]);
            }else{
                return response()->json(['status'=> 'fail']);
            }
            // $user = Auth::user();

            // $token = $user->createToken($user->email.'-'.now());

            // return response()->json([
            //     'token' => $token->accessToken
            // ]);
        }



    }

    public function getstatusIN($id)
    {

        date_default_timezone_set("Asia/Jakarta");
        $id_user = $id;
        $date = Carbon::now()->format('Y-m-d');
        //$date = "2020-10-24";
        $uid = MProf::where('user_id', $id_user)->first();
        $getImages = Vector::where('mprof_id', $id_user)->first();
        $name_day = Carbon::parse($date)->format('l');
        $check = MAttend::where('date', $date)->where('mprof_id', $uid->id)->first();
        $time = date('H');
        $file = (isset($getImages->file)) ? asset($getImages->file) : asset("assets/img/default.png");
        //sabtu => Saturday
        //minggu => Sunday
        //dd($name_day);
        //$time = 19;
        //return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen 😎', 'time'=> 'TIME IN : -']);
        //dd($date);
        if(isset($check)){
            if($check->in_time != "00:00:00"){
                $timenya = (isset($check->in_time) ? $check->in_time : "00:00");
            }elseif($check->in_tolerance_time != "00:00:00"){
                $timenya = $check->in_tolerance_time;
            }else{
                $timenya = $check->late_time;
            }
        }

        if($time >=0 && $time <= 11){
            $ucapan = 'Selamat Pagi 🌞 Keluarga SII';
        }elseif($time >= 12 && $time < 15){
            $ucapan = 'Selamat Siang 🌤 Keluarga SII';
        }elseif($time >= 15 && $time <= 16){
            $ucapan = 'Selamat Sore 🌝 Keluarga SII';
        }else{
            $ucapan = 'Selamat Malam 🌛 Keluarga SII';
        }
        if($name_day == "Saturday" || $name_day == "Sunday"){
            return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka dan untuk yang lembur jangan lupa, absen pulang nya ya 😎 ~ '.$ucapan.'', 'time'=> 'TIME IN : -', 'imagesprof'=> $file]);
        }
        if(!isset($check) && $time <= 12 && $time >= 05){
            return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen masuk 😎 ~ '.$ucapan.'', 'time'=> 'TIME IN : -', 'imagesprof'=> $file]);
        }elseif(isset($check) && $time <= 13 && $time >= 05){

            return response()->json(['status'=> false, 'message'=> 'Terimakasih sudah absen masuk ,jangan lupa absen keluarnya ya 🙏🏻🙏🏻🤓, Thanks ~ '.$ucapan.'', 'time'=> 'TIME IN : 🕰 '.$timenya, 'imagesprof'=> $file]);
        }elseif($time > 12 && $time <= 15){

            if(isset($check->in_time) != "00:00:00" || isset($check->in_tolerance_time) != "00:00:00" || isset($check->late_time) != "00:00:00"){
                return response()->json(['status'=> false, 'message'=> 'Anda tidak diperbolehkan akses menu,dikarenakan tidak absen masuk, dan absen masuk mulai 05:00 - 12:00', 'time'=> '-', 'imagesprof'=> $file]);
            }else{

                return response()->json(['status'=> false, 'message'=> 'Belum Waktunya Absen Pulang Tunggu Jam 17:00 😬😜, Thanks ~ '.$ucapan.'', 'time'=> 'TIME IN : 🕰 '.$timenya, 'imagesprof'=> $file]);
            }
        }elseif(isset($check)){
            if($check->in_time != "00:00:00"){
                $timenya = $check->in_time;
            }elseif($check->in_tolerance_time != "00:00:00"){
                $timenya = $check->in_tolerance_time;
            }else{
                $timenya = $check->late_time;
            }

            if($check->out_time != "00:00:00" || $check->over_time != "00:00:00"){
                return response()->json(['status'=> false, 'message'=> 'Terimakasih sudah absen hari ini 👏', 'time'=> ($check->out_time != "00:00:00") ? 'TIME OUT :'.$check->out_time : $check->over_time, 'imagesprof'=> $file]);
            }else{
                return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> 'TIME IN : 🕰 '.$timenya, 'imagesprof'=> $file]);
            }
            // if($check->over_time == "00:00:00" || $check->out_time == "00:00:00"){
            //     return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲', 'time'=> $timenya, 'imagesprof'=> $file]);
            // }elseif(isset($check) && $check->out_time != "00:00:00" || $check->over_time != "00:00:00"){
            //     return response()->json(['status'=> false, 'message'=> 'Silahkan Absen besok lagi', 'time'=> $timenya, 'imagesprof'=> $file]);
            // }
            //return response()->json(['status'=> false, 'message'=> 'Silahkan Absen besok lagi', 'time'=> $timenya, 'imagesprof'=> $file]);
        }else{
            //return response()->json(['status'=> true, 'message'=> 'Akses menu dibuka silahkan absen pulang 😎, sekarang anda masih di izinkan untuk absen pulang ,dikarenakan admin belum menentukan whitelist area untuk akun anda , Hati-Hati di  jalan ,sampai jumpa esok..🚗🛵🚲']);
            //return response()->json(['status'=> false, 'message'=> 'Menu disable dikarenakan data tidak ada / belum waktunya untuk absen dan absen masuk mulai 05:00 - 13:00']);
        }
    }
    public function getAlert($id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $id_user = $id;
        $date = Carbon::now()->format('Y-m-d');
        $uid = MProf::where('user_id', $id_user)->first();
        $check = MAttend::where('mprof_id', $uid->id)->where('date', $date)->first();
        $time = date('H');


        if($time >= 17){

            //return response()->json(['status'=> true, 'message'=> $check->date]);
            if($time >= 22 && $time <= 23){
                return response()->json(['status'=> true, 'message'=> 'Selamat Malam 🌛 Keluarga SII ']);
            }else{
                if(!isset($check->in_time) && !isset($check->in_tolerance_time) && !isset($check->late_time) && !isset($check->out_time) && !isset($check->over_time)){
                    return response()->json(['status'=> true, 'message'=> "Hari ini kamu tidak masuk yaa ?? / tidak absen hari ini ?? 😩 ."]);
                }
                if($check->in_time != "00:00:00" && $check->in_tolerance_time == "00:00:00" && $check->late_time == "00:00:00"){
                    if($check->out_time != "00:00:00" || $check->over_time != "00:00:00"){

                        return response()->json(['status'=> true, 'message'=> "Terimakasih sudah absen hari ini dan terus tepat waktu yaa hari esok..👏"]);
                    }else{
                        return response()->json(['status'=> true, 'message'=> "Kamu belum absen pulang nih, jangan lupa absen yaa nanti 😋."]);
                    }
                }elseif($check->in_time == "00:00:00" && $check->in_tolerance_time != "00:00:00" && $check->late_time == "00:00:00"){
                    if($check->out_time != "00:00:00" || $check->over_time != "00:00:00"){
                        return response()->json(['status'=> true, 'message'=> "Terimakasih sudah absen hari ini dan besok harus tepat waktu yaa, walaupun hari ini kamu dapet tolerance time 😋."]);
                    }else{
                        return response()->json(['status'=> true, 'message'=> "Kamu belum absen pulang nih, lagi deadline apa yaa hehe 😋."]);
                    }
                }elseif($check->in_time == "00:00:00" && $check->in_tolerance_time == "00:00:00" && $check->late_time != "00:00:00"){
                    if($check->out_time != "00:00:00" || $check->over_time != "00:00:00"){
                        return response()->json(['status'=> true, 'message'=> "Terimakasih sudah absen hari ini dan besok jangan terlambat lagi ya :)😬😜 ."]);
                    }else{
                        return response()->json(['status'=> true, 'message'=> "Kamu belum absen pulang nih, mantap datang terlambat balik pun ikut terlambat juga 👍😋."]);
                    }
                }elseif($check->out_time != "00:00:00" && $check->in_time == "00:00:00"  || $check->over_time != "00:00:00" && $check->in_time == "00:00:00"){
                    return response()->json(['status'=> true, 'message'=> "Kamu tidak absen masuk tadi ,tapi kok kamu absen pulang ehhmm 😩 ."]);
                }elseif($check->in_time != "00:00:00" && $check->out_time == "00:00:00" && $check->over_time == "00:00:00" || $check->in_tolerance_time != "00:00:00" && $check->out_time == "00:00:00" && $check->over_time == "00:00:00" || $check->late_time != "00:00:00" && $check->out_time == "00:00:00" && $check->over_time == "00:00:00"){
                    return response()->json(['status'=> false, 'message'=> "Kamu jangan lupa absen pulang ya nanti 🤗 ."]);
                }else{
                    return response()->json(['status'=> true, 'message'=> "Jika ada masalah pada aplikasi silahkan hubungi team IT ,Thank You 🤓.. :)"]);
                }



            }
            // if(isset($check->in_time) && isset($check->out_time) || isset($check->in_time) && isset($check->over_time)){
            //     return response()->json(['status'=> true, 'message'=> 'Terimakasih Sudah Absen Hari Ini ,Selamat Istirahat']);
            // }elseif(isset($check->over_time) != "00:00:00"){
            //     return response()->json(['status'=> true, 'message'=> 'Absen Out Keisi']);
            // }else{
            //     return response()->json(['status'=> true, 'message'=> 'Selamat Malam Keluarga SII, Selamat Istirahat :)']);
            // }

        }else{
            if($time >=0 && $time <= 11){
                return response()->json(['status'=> true, 'message'=> 'Selamat Pagi 🌞 Keluarga SII, Selamat Beraktifitas 🏃‍♂️🏃‍♀️']);
            }elseif($time >= 12 && $time <= 15){
                return response()->json(['status'=> true, 'message'=> 'Selamat Siang 🌤 Keluarga SII']);
            }elseif($time >= 15 && $time <= 16){
                return response()->json(['status'=> true, 'message'=> 'Selamat Sore 🌝 Keluarga SII']);
            }
        }
    }

    public function shownotif()
    {


        $data = NotificationMod::get();
        // $data = [
        //     ["https://la-att.intek.co.id/assets/img/logo-intek.png", "Cuti Bersama Guys..", "Ini merupakan contoh isi artikel 1 \n dan seterusnya untuk notification"],
        //     ["https://la-att.intek.co.id/assets/img/logo-intek.png", "Update Apps Segera..", "Ini merupakan contoh isi artikel 2 \n dan seterusnya untuk notification"],
        //     ["https://la-att.intek.co.id/assets/img/logo-intek.png", "Syukuran di Kantor Baru", "Ini merupakan contoh isi artikel 3 \n dan seterusnya untuk notification"],
        // ];
        //dd($data);
        $banner = [];
        foreach($data as $dt){
            $databan["title"] = $dt->title;
            //$databan["images"] = "https://la-att.intek.co.id/assets/img/".$dt->images;
            $databan["images"] = $dt->images;
            $json = str_replace("\\n","\n",$dt->contents);
            $databan["keterangan"] =  $json;
            $banner [] =$databan;
        }
        //dd($banner);
        return $banner;
    }
    private function randomQuote()
    {
        $get = file_get_contents("https://type.fit/api/quotes");
        $dec = json_decode($get);
        return $dec;
    }
    private function computeDistance($lat1, $lng1, $lat2, $lng2, $radius = 6378137)
    {
        static $x = M_PI / 180;
        $lat1 *= $x; $lng1 *= $x;
        $lat2 *= $x; $lng2 *= $x;
        $distance = 2 * asin(sqrt(pow(sin(($lat1 - $lat2) / 2), 2) + cos($lat1) * cos($lat2) * pow(sin(($lng1 - $lng2) / 2), 2)));

        return $distance * $radius;
    }



    private function sendwa($number, $message)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://panel.pelanggan.net/api/send/message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => array('number' => $number,'message' => $message,'wa_send' => '1','api_key' => 'NDViNWMwMDZjMWNlYTNkZTY4ZTE2YmUyMWVmOTkxMjU='),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        echo "DONE";
    }


    public function getUser($id)
    {
        $get_profuser = MProf::where('user_id', $id)->first();

        return $get_profuser;
    }


    public function daysOff(request $request)
    {
        $mprof=MProf::where('user_id',$request->user_id)->first();
        $days_off_view = day_off::where('name',$mprof->name)->get();
        // dd($days_off_view);
        // day_off::where("id",$request->id)->update([
        //     "name"=>$request->name
        // ]);
        // dd($days_off_view);
        $data=[];
        foreach($days_off_view as $d){
            $data_valid["id"]=$d["id"];
            $data_valid["name"]=$d["name"];
            $data_valid["position"]=$d["position"];
            $data_valid["departement"]=$d["departement"];
            $data_valid["supervisor"]=$d["supervisor"];
            $data_valid["replacement_pic"]=$d["replacement_pic"];
            $data_valid["reason"]=$d["reason"];
            $data_valid["submitted_job"]=$d["submitted_job"];
            $data_valid["days_off_date"]=$d["days_off_date"];
            $data_valid["total_days"]=$d["total_days"];
            $data_valid["remaining_days_off"]=$d["remaining_days_off"];
            $data_valid["days_off_balance"]=$d["days_off_balance"];
            $data_valid["status"]=$d["status"];
            $data_valid["user_id"]=$d["user_id"];
            $data_valid["back_to_office"]=$d["back_to_office"];
            $data_valid["phone_number"]=$d["phone_number"];
            $data_valid["response_date"]=$d["response_date"];
            $data_valid["created_at"]=date($d["created_at"]);
            $data_valid["updated_at"]=date($d["updated_at"]);
            $data[]=$data_valid;
        }
        // dd(json_encode($data));
        return response()->json($data);
    }

    public function createDaysOff(Request $request){

        // dd($request);
        $date1 = Carbon::parse($request->days_off_date);
        // dd($date1);
        $date2 = Carbon::parse($request->back_to_office);
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

        $day_off=day_off::select('remaining_days_off','days_off_balance')->where('name',$request->name)->orderBy('created_at','desc')->first();
        if ($count > $day_off->remaining_days_off) {
            return response()->json('Request melebihi batas maksimum, request cuti anda '. $count.' hari'. ', sedangkan sisa cuti anda tersisa '.$day_off->remaining_days_off.' hari');
        } else {
            $cek = day_off::where('user_id',$request->user_id)->orderBy('created_at','DESC')->first();
            // dd($cek);
            if($cek==null){
                $day_off=day_off::select('remaining_days_off','days_off_balance')->where('name',$request->name)->orderBy('created_at','desc')->first();
                // dd($day_off);
                day_off::create([
                    'name' => $request -> name,
                    'position' => $request -> position,
                    'departement' => $request -> departement,
                    'supervisor' => $request -> supervisor,
                    'replacement_pic' => $request -> replacement_pic,
                    'phone_number' => $request -> phone_number,
                    'days_off_date' => $request -> days_off_date,
                    'back_to_office' => $request -> back_to_office,
                    'total_days' => $count,
                    'remaining_days_off'=>12 - $count,
                    'days_off_balance'=>0 + $count,
                    'submitted_job' => $request -> submitted_job,
                    'reason' => $request -> reason,
                    'status' => 0,
                    'user_id' => $request->user_id,
                    'response_date' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
                return response()->json("success");
            }
            if ($cek->remaining_days_off == 0) {
                return response()->json("Error you don't have remaining day_off anymore bro");
            } else {
                $day_off=day_off::select('remaining_days_off','days_off_balance')->where('name',$request->name)->orderBy('created_at','desc')->first();
                // dd($day_off);
                day_off::create([
                    'name' => $request -> name,
                    'position' => $request -> position,
                    'departement' => $request -> departement,
                    'supervisor' => $request -> supervisor,
                    'replacement_pic' => $request -> replacement_pic,
                    'phone_number' => $request -> phone_number,
                    'days_off_date' => $request -> days_off_date,
                    'back_to_office' => $request -> back_to_office,
                    'total_days' => $count,
                    'remaining_days_off'=>$day_off->remaining_days_off - $count,
                    'days_off_balance'=>$day_off->days_off_balance + $count,
                    'submitted_job' => $request -> submitted_job,
                    'reason' => $request -> reason,
                    'status' => 0,
                    'user_id' => $request->user_id,
                    'response_date' => Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }
        return response()->json("success");

    }
    public function chart(Request $request){
        $this_year = Carbon::now()->format('Y');
        $bulanfirst = 1;
        $bulanend = 12;
        $data= [];
        $dates = array();
        $day = [];
        $getAtt = MAttend::where('mprof_id', $request->user_id)->where('in_time', '00:00:00')->where('in_tolerance_time', '00:00:00')->where('late_time', '00:00:00')->where('out_time', '00:00:00')->where('over_time', '00:00:00')->where('mprof_id', $request->user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->get();
        for($i = $bulanfirst; $i <= $bulanend; $i++){
            $array['start'] = Carbon::now()->month($i)->startOfMonth()->format('Y-m-d');
            $array['end'] = Carbon::now()->month($i)->endOfMonth()->format('Y-m-d');
            $attendances = MAttend::join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->where('mprof_id', $request->user_id)->whereBetween('m_attends.date', [$array['start'], $array['end']])->get();
            //->where('m_attends.in_time', '!=', '00:00:00')->orWhere('m_attends.in_tolerance_time', '!=', '00:00:00')->orWhere('m_attends.late_time', '!=', '00:00:00')->
            $array["countInmonth"] = Carbon::now()->month($i)->endOfMonth()->format('d') - count($attendances);
            $dates [] = $array;
        }
        //dd($dates);
        $sum = array();
        foreach($dates as $dtt){
            $sum [] = $dtt["countInmonth"];
        }
        $i=0;
        foreach($sum as $s){
            $i++;
            $month=(string) $i;
            $Month = Carbon::parse("2022-".$month."-12")->format("F");
            // dd($Month);
            $in = MAttend::where('date','like','%-'.(($i<=9)?'0'.$i:$i).'-%')->where('mprof_id', $request->user_id)->where('date','LIKE',$this_year.'%')->where('in_time', '!=', '00:00:00')->orWhere('in_tolerance_time', '!=', '00:00:00')->where('date','like','%-'.(($i<=9)?'0'.$i:$i).'-%')->where('date','LIKE',$this_year.'%')->where('mprof_id', $request->user_id)->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->count();
            $late = MAttend::where('date','like','%-'.(($i<=9)?'0'.$i:$i).'-%')->where('mprof_id', $request->user_id)->where('date','LIKE',$this_year.'%')->where('late_time', '!=', '00:00:00')->join('m_profs', 'm_profs.id', '=', 'm_attends.mprof_id')->join('tmtables', 'tmtables.id', '=', 'm_attends.tmtable_id')->orderBy('m_attends.mprof_id', 'DESC')->count();
        // dd(sum($sum));
            $data_month['in']= $in;
            $data_month['late']= $late;
            $data_month['notatt']= $s;
            $data[$Month]=$data_month;
        }
        return response()->json($data);
        // dd($data);
    } 
}
