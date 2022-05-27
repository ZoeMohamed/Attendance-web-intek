<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/recive/data', 'ApiController@recive');
Route::post('/recivePY/data', 'ApiController@reciveaPY');
Route::get('/range/check/{id}', 'ApiController@checkRangeLocation');
Route::get('/list/attend', 'ApiController@list_attend');
Route::get('/list/late', 'ApiController@list_lateattend');
Route::get('/getAlert/{id}', 'ApiController@getAlert');
Route::get('/getstatusIN/{id}', 'ApiController@getstatusIN')->middleware('api','throttle:1000,1');
Route::post('/login', 'ApiController@login');
Route::get('/notification/office', 'ApiController@shownotif');
Route::get('/detailUser/{id}', 'ApiController@getUser');
Route::post('/days_off', 'ApiController@daysOff');
Route::post('/create_days_off', 'ApiController@createDaysOff');
Route::post('/chart', 'ApiController@chart');

// Route::get('/notification/office', function(){
    
//     $data = [
//         ["https://la-att.intek.co.id/assets/img/logo-intek.png", "Cuti Bersama Guys..", "Ini merupakan contoh isi artikel 1 \n dan seterusnya untuk notification"],
//         ["https://la-att.intek.co.id/assets/img/logo-intek.png", "Update Apps Segera..", "Ini merupakan contoh isi artikel 2 \n dan seterusnya untuk notification"],
//         ["https://la-att.intek.co.id/assets/img/logo-in      tek.png", "Syukuran di Kantor Baru", "Ini merupakan contoh isi artikel 3 \n dan seterusnya untuk notification"],
//     ];
//     //dd($data);
//     $banner = [];
//     foreach($data as $dt){
//         $databan["title"] = $dt;
//         $databan["images"] = $dt[0];
//         $databan["keterangan"] = $dt[2];
//         $banner [] =$databan; 
//     }
//     return $banner;
// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
