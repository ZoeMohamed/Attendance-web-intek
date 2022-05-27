<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\MProf;
use App\MAttend;
class PushNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Attendance Notification';

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
        //
        date_default_timezone_set("Asia/Jakarta");
        if(Carbon::parse(date('Y-m-d H:i:s'))->format('H:i') == "10:01"){
            
            $date = date('Y-m-d H:i:s');
            $time = Carbon::parse($date)->format('H:i:s');
            
            $name_day = Carbon::parse($date)->format('l');
            $position = 1;
            $list_employe = MProf::get(); //BEKASI EMPLOYE
            $d = ($position == 1) ? "BEKASI": "PASMING";
            $list_attend = " ** Not Attend  <".date('Y-m-d H:i:s')."> **\n";
            $count = [];
            foreach($list_employe as $le){

                
                if($le->name != "Administrator" || $le->name != "Sindu Irawan" || $le->name != "Mr.Lex"){
                    $check = MAttend::where('mprof_id',$le->id)->where('date', Carbon::parse($date)->format('Y-m-d'))->first();
                    //$check = MAttend::where('mprof_id', $le->id)->where('machine_id', 1)->first();
                    if($check && $check->in_time != "00:00:00"){
                        $keterangan = "Tepat Waktu";
                        $time = $check->in_time;
                    }elseif($check && $check->in_tolerance_time != "00:00:00"){
                        $keterangan = "Tolerance Waktu";
                        $time = $check->in_tolerance_time;
                    }elseif($check && $check->late_time != "00:00:00"){
                        $keterangan = "Terlambat";
                        $time = $check->late_time;
                    }else{
                        $keterangan = "Nothing";
                        $time = "00:00:00";
                    }
                

                    if($keterangan == "Nothing"){
                        if($le->name != "Administrator" && $le->name != "Sindu Irawan" && $le->name != "Mr.Lex"){
                            $list_attend .= "** ".$le->name."\n - ⏰ - ".$time."\n - ✏️ - ".$keterangan."\n";
                            $count [] = $le->name;
                        }
                        
                    }
                }
                
                // $list_attend .= "** ".$le->name."\n - ⏰ - ".$time."\n - ✏️ - ".$keterangan."\n";
                // $count [] = $le->name;
            }
            
            $datanumber = ["62859141490060","447509757453","628111291888"];
            if(count($count) > 0){
                
                foreach($datanumber as $mgr){
                    print_r($list_attend);
                    $this->send_whatsapp_api($mgr,$list_attend);
                    // $curl = curl_init();

                    // curl_setopt_array($curl, array(
                    // CURLOPT_URL => "http://app.wasapbro.net/api/send/wa", 
                    // CURLOPT_RETURNTRANSFER => true,
                    // CURLOPT_ENCODING => "",
                    // CURLOPT_MAXREDIRS => 10,
                    // CURLOPT_TIMEOUT => 0,
                    // CURLOPT_FOLLOWLOCATION => false,
                    // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    // CURLOPT_CUSTOMREQUEST => "POST",
                    // //CURLOPT_POSTFIELDS => array('number' => '6281286791289-1555425045@g.us','message' => $list_attend, 'user_id'=> 37),
                    // CURLOPT_POSTFIELDS => array('number' => $mgr,'message' => $list_attend."\n(Report by System)", 'user_id'=> 37),
                    // ));

                    // $response = curl_exec($curl);

                    // curl_close($curl);
                    // echo $response;
                }
            }else{
                echo "Kosong\n";
                foreach($datanumber as $mgr){
                    $this->send_whatsapp_api($mgr,$list_attend);
                    // $curl = curl_init();

                    // curl_setopt_array($curl, array(
                    // CURLOPT_URL => "http://app.wasapbro.net/api/send/wa", 
                    // CURLOPT_RETURNTRANSFER => true,
                    // CURLOPT_ENCODING => "",
                    // CURLOPT_MAXREDIRS => 10,
                    // CURLOPT_TIMEOUT => 0,
                    // CURLOPT_FOLLOWLOCATION => false,
                    // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    // CURLOPT_CUSTOMREQUEST => "POST",
                    // //6281286791289-1555425045@g.us
                    // CURLOPT_POSTFIELDS => array('number' => $mgr,'message' => $list_attend."\n Terimakasih Sudah Absen Semua Hari Ini 👍 👏 (Report by System)", "user_id"=> 37),
                    // ));

                    // $response = curl_exec($curl);

                    // curl_close($curl);
                    // echo $response;
                }
            }
        }else{
            //dd(Carbon::parse(date('Y-m-d H:i:s'))->format('H:i'));
            echo "Report success send\n";
        }
        
    }

    private function send_whatsapp_api($number, $message){
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
    }
}
