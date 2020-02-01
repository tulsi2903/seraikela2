<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// // Route::post('apilogin','ApiLoginController@login');
// Route::post('login', 'ApiController@signIn');
// Route::post('logout', 'ApiController@logout')->middleware(['auth:api']);
// Route::post('userDetail', 'ApiController@userDetail')->middleware(['auth:api']);
// Route::post('change_password', 'ApiController@changePassword')->middleware(['auth:api']);
// Route::get('dashboard', 'ApiController@dashboard')->middleware(['auth:api']);
// Route::get('land_registrations', 'ApiController@LandRegistrationList')->middleware(['auth:api']);
// Route::get('land_inventories', 'ApiController@TotalLands')->middleware(['auth:api']);
// Route::get('customers', 'ApiController@Customers')->middleware(['auth:api']);
// Route::get('tickets', 'ApiController@Tickets')->middleware(['auth:api']);
// Route::get('messages', 'ApiController@Messages')->middleware(['auth:api']);

Route::post('login', 'API\UserController@login');
Route::group(['middleware' => 'auth:api'], function(){
    Route::post('details', 'API\UserController@details')->middleware('auth:api');
    Route::post('logout', 'API\UserController@logout');
});
