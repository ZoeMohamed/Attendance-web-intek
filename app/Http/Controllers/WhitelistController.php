<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MappingModLoc;
use App\OfficeLocat;
use App\MProf;
use App\User;
use App\MappingMachineUserOri;
use Auth;
class WhitelistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        //echo "Jumatan dulu broo ...., menu ini berfugsi untuk mengatur lokasi setiap karyawan untuk absen bolehnya dimana aja.";
        // if($request->id_office){
        //     $get_data = MappingModLoc::select('m_profs.name','m_profs.position','m_profs.user_id', 'maping_userloc.*','office_location.name_office', 'office_location.lat','office_location.long','office_location.address','office_location.radius_allow')->join('m_profs','m_profs.user_id', '=', 'maping_userloc.user_id')->join('office_location', 'office_location.id', '=', 'maping_userloc.office_id')->where('maping_userloc.office_id', $request->id_office)->get();
        // }else{
        //     $get_data = MappingModLoc::select('m_profs.name','m_profs.position','m_profs.user_id', 'maping_userloc.*','office_location.name_office', 'office_location.lat','office_location.long','office_location.address','office_location.radius_allow')->join('m_profs','m_profs.user_id', '=', 'maping_userloc.user_id')->join('office_location', 'office_location.id', '=', 'maping_userloc.office_id')->get();
        // }
        $get_data = MProf::orderBy('name')->get();
        $get_office = OfficeLocat::where('status', 1)->get();
        if(Auth::user()->id == 99999){
            return view('whitelist')->with('get_data', $get_data)->with('get_office', $get_office);
        }else{
            return redirect('/home')->with('error', 'Not Access Allowed');
        }
        
    }

    public function api(Request $request)
    {
        if($request->id_office){
            $get_data = MappingModLoc::select('m_profs.name','m_profs.position','m_profs.user_id', 'maping_userloc.*','office_location.name_office', 'office_location.lat','office_location.long','office_location.address','office_location.radius_allow')->join('m_profs','m_profs.user_id', '=', 'maping_userloc.user_id')->join('office_location', 'office_location.id', '=', 'maping_userloc.office_id')->where('maping_userloc.office_id', $request->id_office)->get();
        }else{
            $get_data = MappingModLoc::select('m_profs.name','m_profs.position','m_profs.user_id', 'maping_userloc.*','office_location.name_office', 'office_location.lat','office_location.long','office_location.address','office_location.radius_allow')->join('m_profs','m_profs.user_id', '=', 'maping_userloc.user_id')->join('office_location', 'office_location.id', '=', 'maping_userloc.office_id')->get();
        }
        return response()->json($get_data);
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
    public function store_backup(Request $request)
    {
        //
        $check = MappingMachineUserOri::where('user_id', $request->user_id)->where('office_id', $request->machine_id)->where('ori_user_id', $request->ori_user_id)->first();
        
        
        if($request->checkedupdate == "update"){
            $data = MappingMachineUserOri::where('ori_user_id', $request->ori_user_id)->where('office_id', $request->machine_id)->first();
            //dd($request);
            MappingMachineUserOri::where('ori_user_id', $request->ori_user_id)->where('office_id', $request->machine_id)->update([
                'user_id'=> $request->user_id,
                'office_id'=> $request->machine_id,
                'ori_user_id'=> $request->ori_user_id
            ]);
            //dd($data);
            return redirect('/whitelist')->with('message', 'Data Success insert into database..');
        }
        if($check == null){
            MappingMachineUserOri::create([
                'user_id'=> $request->user_id,
                'office_id'=> $request->machine_id,
                'ori_user_id'=> $request->ori_user_id
            ]);
            //dd($request);
            return redirect('/whitelist')->with('message', 'Data Success insert into database..');
        }else{
            //dd($check);
            // MappingMachineUserOri::where('user_id', $request->user_id)->where('office_id', $request->machine_id)->update([
            //     'user_id'=> $request->user_id,
            //     'office_id'=> $request->machine_id,
            //     'ori_user_id'=> $request->ori_user_id
            // ]);
            return redirect('/whitelist')->with('message', 'Data Already in database..');
            //dd("Data Already");
        }
        
    }

    public function store(Request $request)
    {
        $check_data = MappingModLoc::where('user_id', $request->user_id)->where('office_id', $request->machine_id)->first();

        if($check_data == null){
            // $insert_mapping_id = MappingMachineUserOri::create([
            //     'user_id'=> $request->user_id,
            //     'office_id'=> $request->machine_id,
            //     'ori_user_id'=> $request->ori_user_id
            // ]);
            MappingModLoc::create(['user_id'=> $request->user_id, 'office_id'=> $request->machine_id]);
            return redirect('/whitelist')->with('message', 'Data Success insert into database..');
        }else{
            //MappingMachineUserOri::where('')
            $check_data->delete();
            return redirect('/whitelist')->with('message', 'Data Success delete from database..');
        }
        
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
