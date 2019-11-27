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
Route::get('clear-cache', function () {
	$exitCode = Artisan::call('config:clear');
	$exitCode = Artisan::call('cache:clear');
	$exitCode = Artisan::call('config:cache');
	$exitCode = Artisan::call('view:clear');
	Session::flash('success', 'All Clear');
	echo "DONE";
});
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
Route::get('/', function () {
    return view('index');
});

/* custom homepage */
Route::get("homepage", function(){
   return view("index"); 
});

/* department */
Route::get('department','DepartmentController@index');
Route::get('department/add','DepartmentController@add');
Route::post('department/store','DepartmentController@store');
Route::get('department/delete/{dept_id}','DepartmentController@delete');

/* designation */
Route::get('designation','DesignationController@index');
Route::get('designation/add','DesignationController@add');
Route::post('designation/store','DesignationController@store');
Route::get('designation/delete/{desig_id}','DesignationController@delete');

/* Geo Structure */
Route::get('geo-structure','GeoStructureController@index');
Route::get('geo-structure/add','GeoStructureController@add');
Route::post('geo-structure/store','GeoStructureController@store');
Route::get('geo-structure/delete/{geo_id}','GeoStructureController@delete');

/* Scheme Structure */
Route::get('scheme-structure','SchemeStructureController@index');
Route::get('scheme-structure/add','SchemeStructureController@add');
Route::post('scheme-structure/store','SchemeStructureController@store');
Route::get('scheme-structure/delete/{scheme_id}','SchemeStructureController@delete');

/*Scheme Geo Target*/
Route::get('scheme-geo-target','SchemeGeoTargetController@index');
Route::get('scheme-geo-target/add','SchemeGeoTargetController@add');
Route::post('scheme-geo-target/store','SchemeGeoTargetController@store');
Route::get('scheme-geo-target/get-indicator-name','SchemeGeoTargetController@get_indicator_name');
Route::get('scheme-geo-target/delete/{scheme_geo_target_id}','SchemeGeoTargetController@delete');

/*Indicator*/
Route::get('scheme-indicator','SchemeIndicatorController@index');
Route::get('scheme-indicator/add','SchemeIndicatorController@add');
Route::post('scheme-indicator/store','SchemeIndicatorController@store');
Route::get('scheme-indicator/delete/{indicator_id}','SchemeIndicatorController@delete');

/*year*/
Route::get('year','YearController@index');
Route::get('year/add','YearController@add');
Route::post('year/store','YearController@store');
Route::get('year/delete/{year_id}','YearController@delete');


/*uom*/
Route::get('uom','UomController@index');
Route::get('uom/add','UomController@add');
Route::post('uom/store','UomController@store');
Route::get('uom/delete/{uom_id}','UomController@delete');


/*asset*/
Route::get('asset','AssetController@index');
Route::get('asset/add','AssetController@add');
Route::post('asset/store','AssetController@store');
Route::get('asset/delete/{asset_id}','AssetController@delete');

/*Scheme Type*/
Route::get('scheme-type','SchemeTypeController@index');
Route::get('scheme-type/add','SchemeTypeController@add');
Route::post('scheme-type/store','SchemeTypeController@store');
Route::get('scheme-type/delete/{sch_type_id}','SchemeTypeController@delete');

/*Asset Numbers*/
Route::get('asset-numbers','AssetNumbersController@index');
Route::get('asset-numbers/add','AssetNumbersController@add');
Route::get('asset-numbers/current_value','AssetNumbersController@current_value');
Route::post('asset-numbers/store','AssetNumbersController@store'); 

/* review */
Route::get('asset-review', 'AssetReviewController@index');
Route::POST('asset-review/show', 'AssetReviewController@show');
Route::get('asset-review/get-datas', 'AssetReviewController@get_datas');
Route::get('asset-review/get-map-data', 'AssetReviewController@get_map_data');


/* group*///rohit changes 
Route::get('scheme-group','GroupController@index');
Route::get('scheme-group/add','GroupController@add');
Route::post('scheme-group/store','GroupController@store');
Route::get('scheme-group/delete/{id}','GroupController@delete');
//Route::post('scheme-group/send_mail','GroupController@sendEmail');

/*Designation Permission*/
Route::get('designation-permission','DesignationPermissionController@index');
Route::get('designation-permission/add','DesignationPermissionController@add');
Route::post('designation-permission/store','DesignationPermissionController@store');
Route::get('designation-permission/delete/{desig_permission_id}','DesignationPermissionController@delete');

/*Module*/
Route::get('module','ModuleController@index');
Route::get('module/add','ModuleController@add');
Route::post('module/store','ModuleController@store');
Route::get('module/delete/{mod_id}','ModuleController@delete');

/* pdf generate testing */
Route::get('test-pdf','TestPdfController@index');


//send mail by rohit
Route::post('send_mail','EmailController@sendEmail');


/* testing layout */
Route::get('/test', function () {
    return view('test-department');
});