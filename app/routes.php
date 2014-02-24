<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/** ------------------------------------------
 *  Route model binding
 *  ------------------------------------------
 */
Route::model('users', 'User');
Route::model('roles', 'Role');
//Route model binding makes controller testing hard with Mockery
//so we're performing our get by id in the controller.
//Route::model('widgets', 'Widget');

// Home route
Route::get('/', 'HomeController@showWelcome');

// Confide routes - would prefer to create a session controller, and then a seperate
// controller for user settings.
Route::get('user',                         'UserController@index');
Route::post('user/{user}/update', 		   'UserController@update')->where('user', '[0-9]+');
Route::get( 'user/login',                  'UserController@login');
Route::post('user/login',                  'UserController@do_login');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::get( 'user/logout',                 'UserController@logout');


// Secure-Routes
Route::group(array('before' => 'auth'), function()
{
    
	/** ------------------------------------------
 	*  Carriers
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /carriers/{carriers}
	Route::get('carriers/data', 'CarrierController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('carriers', 'CarrierController');
	
	// Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {carriers} needs
	// to be used.
	Route::get('carriers/{carriers}/delete', 		'CarrierController@delete');


	/** ------------------------------------------
 	*  CarrierTypes
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /carriers/{carriers}
	Route::get('carrier-types/data', 'CarrierTypeController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('carrier-types', 'CarrierTypeController');
	
	// Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {carriers} needs
	// to be used.
	Route::get('carrier-types/{carrierTypeID}/delete', 	'CarrierTypeController@delete');


	/** ------------------------------------------
 	*  Categories
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /carriers/{carriers}
	Route::get('categories/data', 'CategoryController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('categories', 'CategoryController');
	
	// Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {carriers} needs
	// to be used.
	Route::get('categories/{categoryID}/delete', 	'CategoryController@delete');


	/** ------------------------------------------
 	*  Status
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /carriers/{carriers}
	Route::get('status/data', 'StatusController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('status', 'StatusController');
	
	// Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {carriers} needs
	// to be used.
	Route::get('status/{statusID}/delete', 	'StatusController@delete');

	/** ------------------------------------------
 	*  Artifacts
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /carriers/{carriers}
	Route::get('carriers/{carrier}/artifacts/data', 'ArtifactController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy. Note: We exclude edit and update, because
	// artifacts are handled on the carrier show page. They are either added,
	// or removed. 
	Route::resource('carriers/{carrier}/artifacts', 'ArtifactController',
		array('except' => array('edit', 'update')));
	
	// Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {carriers} needs
	// to be used.
	Route::get('carriers/{carrier}/artifacts/{artifact}/delete', 'ArtifactController@delete');

	//Send an artifact thumbnail, preview or download to the client.
	Route::get('/artifact/{archive_id}/{mode}/{name}', 'ArtifactController@send_artifact');


	/** ------------------------------------------
 	*  Carrier Status Change
 	*  ------------------------------------------
 	*/

 	// Our dedicated Carrier status change route and controller. 
	Route::get('carriers/{carrier}/status',  'StatusChangeController@edit');
	Route::put('carriers/{carrier}/status',  'StatusChangeController@update');

    
    /** ------------------------------------------
 	*  Widgets
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /widgets/{widgets}
	Route::get('widgets/data', 'WidgetController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('widgets', 'WidgetController');
	
	// Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {widgets} needs
	// to be used.
	Route::get('widgets/{widgets}/delete', 		'WidgetController@delete');
});


/** ------------------------------------------
*  Admin routes
*  See filters.php for Entrust filters that restrict admin routes to admin users.
*  ------------------------------------------
*/
Route::group(array('prefix' => 'admin', 'before' => 'auth'), function()
{
	/** ------------------------------------------
 	*  Users
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /users/{user_id}
	Route::get('users/data',            	'AdminUserController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('users', 				'AdminUserController');
	
	//Our special delete confirmation route - uses the show/details view.
	// NOTE: For model biding above to work - the plural paramameter {users} needs
	// to be used.
	Route::get('users/{users}/delete', 		'AdminUserController@delete');
	
	/** ------------------------------------------
 	*  Roles
 	*  ------------------------------------------
 	*/

	// Datatables Ajax route.
	// NOTE: We must define this route first as it is more specific than
	// the default show resource route for /users/{role_id}
	Route::get('roles/data',            	'AdminRolesController@data');
	
	// Pre-baked resource controller actions for index, create, store, 
	// show, edit, update, destroy
	Route::resource('roles', 				'AdminRolesController');
	
	//Our special delete confirmation route - uses the show/details view
	// NOTE: For model biding above to work - the plural paramameter {roles} needs
	// to be used.
	Route::get('roles/{roles}/delete', 		'AdminRolesController@delete');
	
});
