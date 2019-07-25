<?php

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function (Request $request) {

	// var_dump($request);
    // $location_text = "The IP address {$request->ipinfo->ip} is located in the city of {$request->ipinfo->city}.";

    // return view('main', ['location' => $location_text]);

    return view('main');
    
});
