<?php


if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
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


// Route::get('/', function () {
//     return view('admin.login');
// });
// Route::get('logt',function(){
//     Auth::logout();
//     return redirect('/');
// });
Route::get('check_controller','SchemePerformanceController@duplicate_scheme_perfomamce');
Auth::routes(['register' => false]); // register fetaure if disabled from laravel auth

/*dashboard*/
Route::get('/', 'DashboardController@index')->name('home');
Route::get('my-district','DashboardController@dashboard'); // for DC/admin
Route::get('my-subdivision','DashboardController@dashboard'); // for SDO
Route::get('my-block','DashboardController@dashboard'); // for Block
Route::get('my-panchayat','DashboardController@dashboard'); //for panchayat
Route::get('dashboard/dc_dashboard','DashboardController@index');
Route::get('dashboard/asset_department_wise','DashboardController@get_department_wise_asset_data');
Route::get('dashboard/get-block-performance-percentage-data','DashboardController@get_block_performance_percentage_data');


//user
Route::get('user','UserAdd_Controller@adduser');
Route::post('user/store','UserAdd_Controller@store');
Route::get('user/export/excelURL','UserAdd_Controller@exportExcelFunctiuonforusers');
Route::get('user/pdf/pdfURL','UserAdd_Controller@exportpdfFunctiuonforusers');
Route::get('user/getuser-details/{id}','UserAdd_Controller@get_user_details');

Route::post('user/password_change','UserAdd_Controller@change_password');
Route::get('dublicate/entry','UserAdd_Controller@store');



/* department */
Route::get('department','DepartmentController@index');
Route::get('department/add','DepartmentController@add');
Route::post('department/store','DepartmentController@store');
Route::get('department/delete/{dept_id}','DepartmentController@delete');
Route::get('pdfview',array('as'=>'pdfview','uses'=>'DepartmentController@pdfview'));
//Route::get('excelview',array('as'=>'excelview','uses'=>'DepartmentController@excelview'));
// Route::get('/articles/exportExcel','PostsController@exportExcel');
Route::get('export','DepartmentController@export');
Route::get('department/export/excelURL','DepartmentController@exportExcelFunctiuon');
Route::get('department/pdf/pdfURL','DepartmentController@export_PDF_Function');
Route::get('department/changeView','DepartmentController@changeView'); //view for import
Route::post('department/importFromExcel','DepartmentController@importFromExcel'); //list of import
Route::post('department/ImportreviewSave','DepartmentController@ImportreviewSave'); //import in db

/* designation */
Route::get('designation','DesignationController@index');
Route::get('designation/add','DesignationController@add');
Route::post('designation/store','DesignationController@store');
Route::get('designation/delete/{desig_id}','DesignationController@delete');
Route::get('designation/export/excelURL','DesignationController@exportExcelFunctiuonforDesignation');
Route::get('designation/pdf/pdfURL','DesignationController@exportpdfFunctiuonforDesignation');

/* Geo Structure */
Route::get('geo-structure','GeoStructureController@index');
Route::get('geo-structure/add','GeoStructureController@add');
Route::post('geo-structure/store','GeoStructureController@store');
Route::get('geo-structure/delete/{geo_id}','GeoStructureController@delete');
Route::get('geo-structure/get-block-data','GeoStructureController@get_block_data');
Route::get('geo-structure/get-officer-data','GeoStructureController@get_officer_data');
Route::get('geo-structure/export/excelURL','GeoStructureController@exportExcelFunctiuonforgeostructure');
Route::get('geo-structure/pdf/pdfURL','GeoStructureController@exportpdfFunctiuonforgeostructure');

/* Scheme Structure */
Route::get('scheme-structure','SchemeStructureController@index');
Route::get('scheme-structure/add','SchemeStructureController@add');
Route::post('scheme-structure/store','SchemeStructureController@store');
Route::get('scheme-structure/delete/{scheme_id}','SchemeStructureController@delete');
Route::get('scheme-structure/view/{scheme_id}','SchemeStructureController@view');
Route::get('scheme-structure/export/excelURL','SchemeStructureController@exportExcel_Scheme_structure');
Route::get('scheme-structure/pdf/pdfURL','SchemeStructureController@exportPDF_Scheme_structure');
Route::get('scheme-structure/get-panchayat-datas', 'SchemeStructureController@get_panchayat_datas');
Route::get('scheme-structure/get-attributes-details', 'SchemeStructureController@get_attributes_details');

Route::post('scheme-structure/view_diffrent_formate','SchemeStructureController@view_diffrent_formate');



/*Scheme Geo Target*/
Route::get('scheme-geo-target','SchemeGeoTargetController@index');
Route::get('scheme-geo-target','SchemeGeoTargetController@add'); // add and index both add.blade.php, u can change later if needed
Route::get('scheme-geo-target/add','SchemeGeoTargetController@add');
// Route::post('scheme-geo-target/store','SchemeGeoTargetController@store');
// Route::get('scheme-geo-target/store','SchemeGeoTargetController@store');
Route::get('scheme-geo-target/get-panchayat-datas','SchemeGeoTargetController@get_panchayat_datas');
Route::get('scheme-geo-target/get-target-details','SchemeGeoTargetController@get_target_details');
Route::post('scheme-geo-target/save-target','SchemeGeoTargetController@save_target'); // individual panchayat wise
// Route::get('scheme-geo-target/get-all-datas','SchemeGeoTargetController@get_all_datas');
// Route::get('scheme-geo-target/get-scheme-sanction-id','SchemeGeoTargetController@get_scheme_sanction_id');
// Route::get('scheme-geo-target/delete/{scheme_geo_target_id}','SchemeGeoTargetController@delete');
Route::get('scheme-geo-target/export/excelURL','SchemeGeoTargetController@exportExcel_Scheme_Geo_structure');
Route::get('scheme-geo-target/pdf/pdfURL','SchemeGeoTargetController@exportPDF_Scheme_Geo_structure');


/*Indicator*/
// Route::get('scheme-indicator','SchemeIndicatorController@index');
// Route::get('scheme-indicator/add','SchemeIndicatorController@add');
// Route::post('scheme-indicator/store','SchemeIndicatorController@store');
// Route::get('scheme-indicator/delete/{indicator_id}','SchemeIndicatorController@delete');

/*year*/
Route::get('year','YearController@index');
Route::get('year/add','YearController@add');
Route::post('year/store','YearController@store');
Route::get('year/delete/{year_id}','YearController@delete');
Route::get('year/export/excelURL','YearController@exportExcelFunctiuonforyear');
Route::get('year/pdf/pdfURL','YearController@exportpdfFunctiuonforyear');

/*uom*/
Route::get('uom','UomController@index');
// Route::get('uom/add','UomController@add');
Route::post('uom/store','UomController@store');
Route::get('uom/delete/{uom_id}','UomController@delete');
Route::get('uom/export/excelURL','UomController@exportExcelFunctiuonforuom');
Route::get('uom/pdf/pdfURL','UomController@exportpdfFunctiuonforuom');
	
/*asset*/
Route::get('asset','AssetController@index');
Route::get('asset/add','AssetController@add');
Route::get('asset/get-category','AssetController@get_category');
Route::get('asset/get-subcategory','AssetController@get_subcategory');
Route::get('asset/get-asset-details','AssetController@get_asset_details');
Route::post('asset/store','AssetController@store');
Route::get('asset/delete/{asset_id}','AssetController@delete');
Route::get('asset/export/excelURL','AssetController@exportExcelFunctiuonforasset');
Route::get('asset/pdf/pdfURL','AssetController@exportpdfFunctiuonforasset');

/*Asset Category*/
Route::get('assetcat','AssetController@index_cat');
Route::get('assetcat/add','AssetController@add_cat');
Route::post('assetcat/store','AssetController@store_cat');
Route::get('assetcat/delete/{asset_cat_id}','AssetController@delete_cat');
Route::get('assetcat/export/excelURL','AssetController@export_Excel_Asset_Category');
Route::get('assetcat/pdf/pdfURL','AssetController@export_PDF_Asset_Category');

/*Asset Sub Category*/
Route::get('asset_subcat','AssetController@index_subcat');
Route::get('asset_subcat/add','AssetController@add_subcat');
Route::post('asset_subcat/store','AssetController@store_subcat');
Route::get('asset_subcat/delete/{asset_sub_id}','AssetController@delete_subcat');
Route::get('asset_subcat/export/excelURL','AssetController@export_Excel_Asset_SubCategory');
Route::get('asset_subcat/pdf/pdfURL','AssetController@export_PDF_Asset_SubCategory');

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
Route::get('asset-numbers/view/{asset_numbers_id}','AssetNumbersController@view');
Route::get('asset_Numbers/export/excelURL','AssetNumbersController@exportExcelFunctiuonforasset_Numbers');
Route::get('asset_Numbers/pdf/pdfURL','AssetNumbersController@exportpdfFunctiuonforasset_Numbers');
Route::get('asset_number/list_of_childs/{child_id}/{geo_child_id}/{year_child_id}/{hidden_input_id}/{geo_location_id}','AssetNumbersController@list_of_childs');
Route::post('asset-numbers/saveChilddata','AssetNumbersController@saveChilddata'); 
Route::get('asset_number/list_of_imagedata/{loc_id}/{asset_id}/{year_id}/{geo_id}/{hidden_input_id}','AssetNumbersController@list_of_imagedata');
Route::get('asset_number/get-panchayat-datas','AssetNumbersController@get_panchayat_datas'); // to get panchayat data and append in <select>
Route::post('asset-numbers/saveImagesforLoacation','AssetNumbersController@saveImagesforLoacation'); 
Route::get('asset_Numbers/downloadFormat','AssetNumbersController@downloadFormat'); 
Route::get('asset_Numbers/downloadFormatwithLocation','AssetNumbersController@downloadFormatwithLocation'); 
Route::get('asset_Numbers/changeViewforimport','AssetNumbersController@changeViewforimport'); 
Route::post('asset-numbers/saveimporttoExcel','AssetNumbersController@saveimporttoExcel'); 
Route::get('asset-numbers/error_log_download','AssetNumbersController@error_log_download'); 
/* asset review */
Route::get('asset-review', 'AssetReviewController@index');
Route::POST('asset-review/show', 'AssetReviewController@show');
Route::get('asset-review/get-datas', 'AssetReviewController@get_datas');
Route::get('asset-review/get-map-data', 'AssetReviewController@get_map_data');
Route::get('asset-review/get-panchayat-data', 'AssetReviewController@get_panchayat_data');
Route::get('asset-review/export/excelURL','AssetReviewController@export_to_Excel');
Route::get('asset-review/pdf/pdfURL','AssetReviewController@export_pdf');
Route::post('asset-review/export-any','AssetReviewController@export_any');
Route::post('asset-review/send-email','AssetReviewController@send_email');

Route::get('new-asset-review', 'AssetReviewController@index'); // for new changes, old asset review is still on server and index.blade.php
Route::get('asset-review/get-tabular-view-datas', 'AssetReviewController@get_tabular_view_datas'); // for new changes, old asset review is still on server and index.blade.php
Route::get('asset-review/get-assets-datas','AssetReviewController@get_assets_datas');

/*Scheme Type*/
Route::get('scheme-type','SchemeTypeController@index');
Route::get('scheme-type/add','SchemeTypeController@add');
Route::post('scheme-type/store','SchemeTypeController@store');
Route::get('scheme-type/delete/{sch_type_id}','SchemeTypeController@delete');
Route::get('scheme-type/pdf/pdfURL','SchemeTypeController@export_PDF_SchemeType');
Route::get('scheme-type/export/excelURL','SchemeTypeController@export_Excel_SchemeType');

/*scheme-asset*/
Route::get('scheme-asset','Scheme_Asset_Controller@index');
Route::get('scheme-asset/add','Scheme_Asset_Controller@add');
Route::post('scheme-asset/store','Scheme_Asset_Controller@store');
Route::get('scheme-asset/view/{scheme_asset_id}','Scheme_Asset_Controller@view');
Route::get('scheme-asset/delete/{scheme_asset_id}','Scheme_Asset_Controller@delete');

Route::post('scheme-asset/view_diffrent_formate','Scheme_Asset_Controller@view_diffrent_formate');



/*Scheme Performance*/
// Route::get('scheme-performance/add','SchemePerformanceController@add');
// Route::post('scheme-performance/store','SchemePerformanceController@store');
// Route::get('scheme-performance/get-subdivision-name','SchemePerformanceController@get_subdivision_name');
// Route::get('scheme-performance/get-block-name','SchemePerformanceController@get_block_name');
// Route::get('scheme-performance/get-panchayat-name','SchemePerformanceController@get_panchayat_name');
// Route::get('scheme-performance/get-indicator-name','SchemePerformanceController@get_indicator_name');
// // Route::get('scheme-performance/get-indicator-table','SchemePerformanceController@get_indicator_table');
// Route::get('scheme-performance/get-targeGt','SchemePerformanceController@get_target');
// Route::get('scheme-performance/get-target','SchemePerformanceController@get_target');
// Route::get("scheme-performance/get-scheme-performance-datas", "SchemePerformanceController@get_scheme_performance_datas");
Route::get('scheme-performance','SchemePerformanceController@index');
Route::get('scheme-performance/get-panchayat-datas','SchemePerformanceController@get_panchayat_datas'); // to get panchayat data and append in <select>
Route::get('scheme-performance/get-all-datas', 'SchemePerformanceController@get_all_datas');
Route::post('scheme-performance/store', 'SchemePerformanceController@store');
Route::get('scheme-performance/add-datas','SchemePerformanceController@add_datas');
Route::get('scheme-performance/viewimport','SchemePerformanceController@viewimport');
Route::get('scheme-performance/downloadFormat','SchemePerformanceController@downloadFormat');
Route::post('scheme-performance/importtoExcel','SchemePerformanceController@Import_from_Excel');
Route::post('scheme_performance/galleryFile_update','SchemePerformanceController@saveImagesofscheme_performance');
Route::get('scheme-performance/get-gallery/{id}','SchemePerformanceController@get_gallery_image');
Route::post('scheme_performance/coordinatesupdate','SchemePerformanceController@save_coordinate');
Route::get('scheme-performance/get-coordinates/{id}','SchemePerformanceController@get_coordinates_details');
Route::get('scheme-performance/download_error_log','SchemePerformanceController@download_error_log');
Route::get('scheme-performance/get-connectivity/{scheme_id}','SchemePerformanceController@get_connectivity_details'); /* End Spans Across Borders */
Route::get('scheme-performance/getblock_datafor_borders','SchemePerformanceController@getblock_datafor_borders'); /* End Spans Across Borders */
Route::get('scheme-performance/get-panchayat-datas-for-borders','SchemePerformanceController@getpanchayat_datafor_borders'); /* End Spans Across Borders */
Route::post('scheme_performance/savebl_pl_connectivity','SchemePerformanceController@savebl_pl_connectivity'); /* End Spans Across Borders */
Route::get('scheme-performance/check_matching_erformance/{id}/{result}','SchemeReviewDuplicateDataCheckController@insert_mathcingperformance');

Route::any('matching-schemes','CheckMatchingPerformanceController@index');
Route::get('matching-schemes/get-panchayat-datas','CheckMatchingPerformanceController@get_panchayat_datas');

// redo
Route::get('matching-scheme/get-all-matching-datas', 'CheckMatchingPerformanceController@get_all_matching_datas');
Route::post('matching-scheme/assign-to', 'CheckMatchingPerformanceController@assign_to');


/* scheme review */
Route::get('scheme-review', 'SchemeReviewController@index');
Route::get('scheme-review/get-tabular-view-datas', 'SchemeReviewController@get_tabular_view_datas');
Route::get('scheme-review/get-all-performance-datas-individuallly', 'SchemeReviewController@get_all_performance_datas_individuallly');
Route::get('scheme-review/get-datas', 'SchemeReviewController@get_datas');
Route::get('scheme-review/get-map-data', 'SchemeReviewController@get_map_data');
Route::get('scheme-review/get-panchayat-data', 'SchemeReviewController@get_panchayat_data');
Route::post('scheme-review/export', 'SchemeReviewController@export');
Route::post('scheme-review/send-email','SchemeReviewController@send_email');

Route::get('scheme-review/duplicate-review','SchemeReviewDuplicateDataCheckController@index');
Route::get('scheme-review/duplicate-review/get-datas','SchemeReviewDuplicateDataCheckController@get_datas');

/* group*///rohit changes 
Route::get('scheme-group','GroupController@index');
Route::get('scheme-group/add','GroupController@add');
Route::post('scheme-group/store','GroupController@store');
Route::get('scheme-group/delete/{id}','GroupController@delete');
//Route::post('scheme-group/send_mail','GroupController@sendEmail');
Route::get('scheme-group/export/excelURL','GroupController@scheme_group_excel_function');
Route::get('scheme-group/pdf/pdfURL','GroupController@scheme_group_pdf_function');


/*Designation Permission*/
Route::get('designation-permission','DesignationPermissionController@index');
Route::get('designation-permission/add','DesignationPermissionController@add');
Route::post('designation-permission/store','DesignationPermissionController@store');
Route::post('designation-permission/save-permissions','DesignationPermissionController@save_permissions');
Route::get('designation-permission/delete/{desig_permission_id}','DesignationPermissionController@delete');

/*Module*/
Route::get('module','ModuleController@index');
Route::get('module/add','ModuleController@add');
Route::post('module/store','ModuleController@store');
Route::get('module/delete/{mod_id}','ModuleController@delete');
Route::get('module/export/excelURL','ModuleController@exportExcelFunctiuonformodule');
Route::get('module/pdf/pdfURL','ModuleController@exportpdfFunctiuonformodule');
	
//send mail by rohit
Route::post('send_mail','EmailController@sendEmail');	

Route::get('mail', function () {
	return view('index1');
});

Route::post('sendmail','EmailController@sendmail');

 //fav rohit
 Route::any('favourites','FavController@index');
 Route::post('fav_department','FavController@add_fav_departs');
 Route::get('fav_department/export/excelURL','FavController@export_Excel_Department');
 Route::get('fav_department/pdf/pdfURL','FavController@export_PDF_Department');
 
 Route::post('fav_scheme','FavController@add_fav_scheme');
 Route::get('fav_scheme/export/excelURL','FavController@export_Scheme_Excel_Department');
 Route::get('fav_scheme/pdf/pdfURL','FavController@export_Scheme_PDF_Department');
 
 Route::post('fav_block','FavController@add_fav_block');
 Route::get('fav_block/export/excelURL','FavController@export_Block_Excel_Department');
 Route::get('fav_block/pdf/pdfURL','FavController@export_Block_PDF_Department');
 
 Route::post('fav_panchayat','FavController@add_fav_panchayat');
 Route::get('fav_panchayat/export/excelURL','FavController@export_Panchayat_Excel_Department');
 Route::get('fav_panchayat/pdf/pdfURL','FavController@export_Panchayat_PDF_Department');

 Route::post('fav_define_asset','FavController@add_fav_define_asset');
 Route::get('fav_define_asset/export/excelURL','FavController@export_DefineAsset_Excel_Department');
 Route::get('fav_define_asset/pdf/pdfURL','FavController@export_DefineAsset_PDF_Department');

 Route::get('mgnrega','MgnregaCategoryController@index');
 Route::post('mgnrega/store','MgnregaCategoryController@store');
 Route::get('mgnrega/delete/{mgnrega_category_id}','MgnregaCategoryController@delete');
 Route::get('mgnrega/export/excelURL','MgnregaCategoryController@export_ExcelFunction');
 Route::get('mgnrega/pdf/pdfURL','MgnregaCategoryController@export_PDF_Function');

 //language changes
 Route::get('lang/english/{id}','DashboardController@language_change');
 Route::get('lang/hindi/{id}','DashboardController@language_change');

 // Abhishek 
 Route::post('department/view_diffrent_formate','DepartmentController@view_diffrent_formate');
Route::post('designation/view_diffrent_formate','DesignationController@view_diffrent_formate');
Route::post('geo-structure/view_diffrent_formate','GeoStructureController@view_diffrent_formate');
Route::post('year/view_diffrent_formate','YearController@view_diffrent_formate');
Route::post('assetcat/view_diffrent_formate','AssetController@view_diffrent_formate');
Route::post('asset_subcat/view_diffrent_formate','AssetController@view_diffrent_formate_sub_cat');
Route::post('scheme-type/view_diffrent_formate','SchemeTypeController@view_diffrent_formate');
Route::post('scheme-group/view_diffrent_formate','GroupController@view_diffrent_formate');
Route::post('module/view_diffrent_formate','ModuleController@view_diffrent_formate');
Route::post('asset-numbers/view_diffrent_formate','AssetNumbersController@view_diffrent_formate');
Route::post('asset/view_diffrent_formate','AssetController@view_diffrent_formate_for_resources');
Route::post('fav_block/view_diffrent_formate','FavController@view_diffrent_formate');

//For Scheme Import
Route::get('import/scheme','SchemePerformanceController@view_import_forscheme');
Route::get('dashborad/schemeperformance/{year}','DashboardController@scheme_performance_for_dashborad');

//uom_type by rohit 
Route::get('uom_type','UoMType_Controller@index');
Route::post('uom_type/store','UoMType_Controller@store');
Route::get('uom_type/delete/{uom_type_id}','UoMType_Controller@delete');

//block test
Route::get('block','UoMType_Controller@show_block');
Route::get('block/panchyat_data','UoMType_Controller@show_panchayat_datas');

/* testing urls */
Route::get('test-php-geo','TestPhpGeo@index');
Route::get('alert-messages', 'DashboardController@alert_messages');

