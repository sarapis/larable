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
Route::auth();

Route::get('/home', function () {
    //return view('welcome');
    return redirect('/');
});
// Route::get('/admin', function () {
//     //return view('welcome');
//     return redirect('/login');
// });

Route::group(['middleware' => ['web', 'frontend'] ], function () {
    Route::get('/', ['uses' => 'HomeController@home']);
    Route::match(['get', 'post'], '/search', [
        'uses'          => 'ExploreController@filter'
    ]);
    Route::match(['get', 'post'], '/search_organization', [
        'uses'          => 'ExploreController@filter_organization'
    ]);

    Route::get('/about', ['uses' => 'HomeController@about']);    
    Route::get('/feedback', ['uses' => 'HomeController@feedback']);

    Route::get('/account/{id}', 'AccountController@account');
    Route::get('/account/{id}/edit', 'AccountController@edit');
    Route::get('/account/{id}/change_password', 'AccountController@change_password');
    Route::get('/account/{id}/update_password', 'AccountController@update_password');
    Route::get('/account/{id}/update', 'AccountController@update');

    Route::get('/services', 'ServiceController@services');
    Route::get('/service/{id}', 'ServiceController@service');
    Route::get('/service/{id}/edit', 'ServiceController@edit');
    Route::get('/service/{id}/update', 'ServiceController@update');
    Route::get('/service_create/{id}', 'ServiceController@create_in_organization');
    Route::get('/service_create/{id}/facility', 'ServiceController@create_in_facility');
    Route::get('/service_create', 'ServiceController@create');
    Route::get('/add_new_service', 'ServiceController@add_new_service');
    Route::get('/add_new_service_in_organization', 'ServiceController@add_new_service_in_organization');
    Route::get('/add_new_service_in_facility', 'ServiceController@add_new_service_in_facility');

    Route::get('/suggest_create', 'SuggestController@create');
    Route::get('/add_new_suggestion', 'SuggestController@add_new_suggestion');

    Route::resource('sessions', 'SessionController');
    Route::get('/session/{id}', 'SessionController@session');
    Route::get('/session_create/{id}', 'SessionController@create_in_organization');
    Route::get('/session_info/{id}/edit', 'SessionController@edit');
    Route::get('/session_info/{id}/update', 'SessionController@update');
    Route::get('/add_new_session_in_organization', 'SessionController@add_new_session_in_organization');
    Route::post('/add_interaction', 'SessionController@add_interaction');
    Route::post('/session_start', 'SessionController@session_start');
    Route::post('/session_end', 'SessionController@session_end');

    Route::resource('facilities', 'LocationController');
    Route::post('/get_all_facilities', 'LocationController@get_all_facilities');
    Route::get('/facility/{id}', 'LocationController@facility');
    Route::get('/facility/{id}/tagging', 'LocationController@tagging');
    Route::get('/facility/{id}/edit', 'LocationController@edit');
    Route::get('/facility/{id}/update', 'LocationController@update');
    Route::post('/facility/{id}/add_comment', 'LocationController@add_comment');
    Route::get('/facility_create/{id}', 'LocationController@create_in_organization');
    Route::get('/facility_create', 'LocationController@create');
    Route::get('/add_new_facility', 'LocationController@add_new_facility');
    Route::get('/add_new_facility_in_organization', 'LocationController@add_new_facility_in_organization');

    Route::resource('/organizations', 'OrganizationController');
    Route::get('/organizations', 'OrganizationController@organizations');
    Route::get('/organization/{id}', 'OrganizationController@organization');
    Route::get('/organization/{id}/tagging', 'OrganizationController@tagging');
    Route::get('/organization/{id}/edit', 'OrganizationController@edit');
    Route::get('/organization/{id}/update', 'OrganizationController@update');
    Route::post('/organization/{id}/add_comment', 'OrganizationController@add_comment');
    Route::get('/organization_create', 'OrganizationController@create');
    Route::post('/organization_delete_filter', 'OrganizationController@delete_organization');

    Route::get('/contacts', 'ContactController@contacts');
    Route::get('/contact/{id}', 'ContactController@contact');
    Route::get('/contact/{id}/edit', 'ContactController@edit');
    Route::get('/contact/{id}/update', 'ContactController@update');
    Route::post('/get_all_contacts', 'ContactController@get_all_contacts');
    Route::get('/contact_create', 'ContactController@create');
    Route::get('/add_new_contact_in_organization', 'ContactController@add_new_contact_in_organization');
    Route::get('/add_new_contact_in_facility', 'ContactController@add_new_contact_in_facility');
    Route::get('/contact_create/{id}', 'ContactController@create_in_organization');
    Route::get('/contact_create/{id}/facility', 'ContactController@create_in_facility');
    Route::get('/add_new_contact', 'ContactController@add_new_contact');

    Route::get('/category/{id}', 'ServiceController@taxonomy');

    Route::get('/services_near_me', 'ExploreController@geolocation');

    Route::post('/filter', 'ExploreController@filter');
    Route::get('/filter', 'ExploreController@filter');

    Route::get('/datapackages', 'PagesController@datapackages');

    // Route::post('/explore', 'ExploreController@index');
    Route::get('/profile/{id}', 'ExploreController@profile');
    Route::get('/explore/status_{id}', 'ExploreController@status');
    Route::get('/explore/district_{id}', 'ExploreController@district');
    Route::get('/explore/category_{id}', 'ExploreController@category');
    Route::get('/explore/cityagency_{id}', 'ExploreController@cityagency');


    //download pdf
    Route::get('/download_service/{id}', 'ServiceController@download');
    Route::get('/download_organization/{id}', 'OrganizationController@download');

    // this is for campaign and message
    Route::resource('campaigns', 'CampaignController');
    Route::post('updateStatus', 'CampaignController@updateStatus')->name('updateStatus');
    Route::post('deleteCampaigns', 'CampaignController@deleteCampaigns')->name('deleteCampaigns');
    Route::post('deleteRecipient', 'CampaignController@deleteRecipient')->name('deleteRecipient');
    Route::get('/confirm/{id}', 'CampaignController@confirm');
    Route::get('/campaign_report/{id}', 'CampaignController@campaign_report')->name('campaign_report');
    Route::post('/campaign_report_action/{id}', 'CampaignController@campaign_report_action')->name('campaign_report_action');
    Route::post('/campaign_report_update_dynamic_group/{id}', 'CampaignController@campaign_report_update_dynamic_group')->name('campaign_report_update_dynamic_group');
    Route::resource('messages', 'MessageController');
    Route::post('send_campaign/{id}', 'BulkSmsController@send_campaign')->name('send_campaign');
    Route::get('message/sent', 'MessageController@messages_sent');
    Route::get('/message/recieved', 'MessageController@messages_recieved');
    Route::post('connect_compaign', 'MessageController@connect_compaign')->name('connect_compaign');
    Route::post('connect_group', 'MessageController@connect_group')->name('connect_group');
    Route::post('getContact', 'MessageController@getContact')->name('getContact');
    Route::get('messagesSetting', 'MessageController@messagesSetting')->name('messagesSetting');
    Route::post('saveMessageCredential', 'MessageController@saveMessageCredential')->name('saveMessageCredential');

    Route::post('/checkSendgrid', 'HomeController@checkSendgrid')->name('checkSendgrid');
    Route::post('/checkTwillio', 'HomeController@checkTwillio')->name('checkTwillio');
    Route::post('/create_group', 'MessageController@create_group')->name('create_group');
    Route::get('download_attachment/{id}', 'CampaignController@download_attachment');
    Route::get('createMessage', 'MessageController@createMessage')->name('createMessage');

    Route::post('send_message/{id}', 'BulkSmsController@send_message')->name('send_message');
    Route::post('group_message/{id}', 'BulkSmsController@group_message')->name('group_message');
    Route::post('getGroupTag', 'MessageController@getGroupTag')->name('getGroupTag');
    Route::post('sendMultipleMessage', 'BulkSmsController@sendMultipleMessage')->name('sendMultipleMessage');
    Route::post('saveContactInfo', 'MessageController@saveContactInfo')->name('saveContactInfo');
    Route::post('addContactToGroup/{id}', 'GroupController@addContactToGroup')->name('addContactToGroup');

    Route::get('tb_alt_taxonomy/all_terms', 'AltTaxonomyController@get_all_terms');
    Route::post('/range', 'ExploreController@filterValues1');
});

 Route::group(['middleware' => ['web', 'auth', 'permission'] ], function () {
        Route::get('dashboard', ['uses' => 'HomeController@dashboard', 'as' => 'home.dashboard']);

        Route::resource('pages', 'PagesController');
        Route::resource('login_register_edit', 'EditLoginRegisterController');

        Route::get('/contact_form', 'ContactFormController@index');
        Route::post('/email_delete_filter', 'ContactFormController@delete_email');
        Route::post('/email_create_filter', 'ContactFormController@create_email');

        //users
        Route::resource('user', 'UserController');
        Route::get('user/{user}/permissions', ['uses' => 'UserController@permissions', 'as' => 'user.permissions']);
        Route::post('user/{user}/save', ['uses' => 'UserController@save', 'as' => 'user.save']);
        Route::get('user/{user}/activate', ['uses' => 'UserController@activate', 'as' => 'user.activate']);
        Route::get('user/{user}/deactivate', ['uses' => 'UserController@deactivate', 'as' => 'user.deactivate']);
          Route::post('user/ajax_all', ['uses' => 'UserController@ajax_all', 'as' => 'user.ajax_all']);

        //roles
        Route::resource('role', 'RoleController');
        Route::get('role/{role}/permissions', ['uses' => 'RoleController@permissions', 'as' => 'role.permissions']);
        Route::post('role/{role}/save', ['uses' => 'RoleController@save', 'as' => 'role.save']);
        Route::post('role/check', ['uses' => 'RoleController@check', 'as' => 'role.check']);

        Route::get('/logout', ['uses' => 'Auth\LoginController@logout']);

        Route::get('/sync_services/{api_key}/{base_url}', ['uses' => 'ServiceController@airtable']);  
        Route::get('/sync_test/{api_key}/{base_url}', ['uses' => 'ServiceController@test_airtable']);      
        
        Route::get('/sync_locations/{api_key}/{base_url}', ['uses' => 'LocationController@airtable']);
        Route::get('/sync_organizations/{api_key}/{base_url}', ['uses' => 'OrganizationController@airtable']);
        Route::get('/sync_contact/{api_key}/{base_url}', ['uses' => 'ContactController@airtable']);
        Route::get('/sync_phones/{api_key}/{base_url}', ['uses' => 'PhoneController@airtable']);
        Route::get('/sync_address/{api_key}/{base_url}', ['uses' => 'AddressController@airtable']);
        Route::get('/sync_schedule/{api_key}/{base_url}', ['uses' => 'ScheduleController@airtable']);
        Route::get('/sync_taxonomy/{api_key}/{base_url}', ['uses' => 'TaxonomyController@airtable']);
        Route::get('/sync_details/{api_key}/{base_url}', ['uses' => 'DetailController@airtable']);

        Route::get('/cron_datasync', ['uses' => 'CronController@cron_datasync', 'as' => 'cron_datasync.cron_datasync']);

        Route::post('/csv_services', ['uses' => 'ServiceController@csv']);  
        Route::post('/csv_locations', ['uses' => 'LocationController@csv']);
        Route::post('/csv_organizations', ['uses' => 'OrganizationController@csv']);
        Route::post('/csv_contacts', ['uses' => 'ContactController@csv']);
        Route::post('/csv_phones', ['uses' => 'PhoneController@csv']);
        Route::post('/csv_address', ['uses' => 'AddressController@csv']);
        Route::post('/csv_languages', ['uses' => 'LanguageController@csv']);
        Route::post('/csv_taxonomy', ['uses' => 'TaxonomyController@csv']);
        Route::post('/csv_services_taxonomy', ['uses' => 'TaxonomyController@csv_services_taxonomy']);
        Route::post('/csv_services_location', ['uses' => 'ServiceController@csv_services_location']);
        Route::post('/csv_accessibility_for_disabilites', ['uses' => 'AccessibilityController@csv']);
        Route::post('/csv_regular_schedules', ['uses' => 'ScheduleController@csv']);
        Route::post('/csv_service_areas', ['uses' => 'AreaController@csv']);

        Route::post('/csv_zip', ['uses' => 'UploadController@zip']);

        //Route::get('/tb_projects', ['uses' => 'ProjectController@index']);
        Route::resource('tb_services', 'ServiceController');
        Route::resource('tb_locations', 'LocationController');
        Route::resource('tb_organizations', 'OrganizationController');
        Route::resource('tb_contact', 'ContactController');
        Route::resource('tb_phones', 'PhoneController');
        Route::resource('tb_address', 'AddressController');
        Route::resource('tb_schedule', 'ScheduleController');
        Route::resource('tb_service_areas', 'AreaController');


        Route::get('/tb_regular_schedules', function () {
            return redirect('/tb_schedule');
        });

        Route::resource('tb_taxonomy', 'TaxonomyController');
        Route::resource('tb_alt_taxonomy', 'AltTaxonomyController');
        Route::get('tb_alt_taxonomy/terms/{id}', 'AltTaxonomyController@open_terms');

        Route::post('/tb_alt_taxonomy', 'AltTaxonomyController@operation');
        Route::resource('tb_details', 'DetailController');
        Route::resource('tb_languages', 'LanguageController');
        Route::resource('tb_accessibility', 'AccessibilityController');

        Route::get('/tb_accessibility_for_disabilites', function () {
            return redirect('/tb_accessibility');
        });

        Route::get('/tb_services_taxonomy', function () {
            return redirect('/tb_services');
        });

        Route::get('/tb_services_location', function () {
            return redirect('/tb_locations');
        });

        Route::resource('layout_edit', 'EditlayoutController');
        Route::resource('home_edit', 'EdithomeController');
        Route::resource('about_edit', 'EditaboutController');

        // Route::resource('meta_filter', 'MetafilterController');

        Route::resource('map', 'MapController');
        Route::get('/scan_ungeocoded_location', 'MapController@scan_ungeocoded_location');
        Route::get('/scan_enrichable_location', 'MapController@scan_enrichable_location');
        Route::get('/apply_geocode', 'MapController@apply_geocode');
        Route::get('/apply_enrich', 'MapController@apply_enrich');
        
        Route::get('/import', ['uses' => 'PagesController@import']);
        Route::get('/export', ['uses' => 'PagesController@export']);
        Route::get('/export_hsds_zip_file', ['uses' => 'PagesController@export_hsds_zip_file']);
        
        Route::get('/meta_filter', ['uses' => 'PagesController@metafilter', 'as' => 'meta_filter.index']);
        Route::post('/meta/{id}', 'PagesController@metafilter_save');
        Route::post('/update_hsds_api_key', 'PagesController@update_hsds_api_key');

        Route::post('/taxonomy_filter', 'PagesController@taxonomy_filter');
        Route::post('/postal_code_filter', 'PagesController@postal_filter');
        Route::post('/service_status_filter', 'PagesController@service_status_filter');

        Route::post('/meta_filter', 'PagesController@operation');

        Route::post('/meta_delete_filter', 'PagesController@delete_operation');

        Route::post('/meta_filter/{id}', 'PagesController@metafilter_edit');

        Route::resource('data', 'DataController');

        Route::resource('analytics', 'AnalyticsController');        

        Route::post('/religions_change_activate', 'backend\ReligionsController@change_activate');
        Route::post('/languages_change_activate', 'backend\LanguageController@change_activate');
        Route::post('/organizationTypes_change_activate', 'backend\OrganizationTypeController@change_activate');
        Route::post('/ContactTypes_change_activate', 'backend\ContactTypeController@change_activate');
        Route::post('/FacilityTypes_change_activate', 'backend\FacilityTypeController@change_activate');
 });

Route::resource('religions', 'backend\ReligionsController');
Route::resource('organizationTypes', 'backend\OrganizationTypeController');
Route::resource('ContactTypes', 'backend\ContactTypeController');
Route::resource('FacilityTypes', 'backend\FacilityTypeController');
Route::resource('languages', 'backend\LanguageController');

Route::post('/contactData', 'ContactController@contactData')->name('contactData');