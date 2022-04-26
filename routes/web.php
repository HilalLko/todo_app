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
Route::get('/', function () {           return view('welcome'); });
Route::prefix('sitemaster')->group(function () {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Auth::routes();
    Route::group(['middleware' => ['auth','get.menu']], function () {
        Route::get('/dashboard','admin\ActivitiesController@index')->name('admin.dashboard');
        Route::get('/global-activities','admin\ActivitiesController@getGlobalActivities')->name('admin.global_activities');
        Route::post('/global-activities','admin\ActivitiesController@addGlobalActivity')->name('admin.activity.add');
        Route::get('/user-activities','admin\ActivitiesController@getUserActivities')->name('admin.global_activities');
        Route::resource('resource/{table}/resource', 'admin\ResourceController')->names([
            'index'     => 'resource.index',
            'create'    => 'resource.create',
            'store'     => 'resource.store',
            'show'      => 'resource.show',
            'edit'      => 'resource.edit',
            'update'    => 'resource.update',
            'destroy'   => 'resource.destroy'
        ]);
        Route::get('/admins','admin\UsersController@getAdminUsers')->name('admin.admins');        
        Route::group(['middleware' => ['role:super']], function () {
            Route::resource('bread',  'admin\BreadController');   //create BREAD (resource)
            Route::resource('users',        'admin\UsersController')->except( ['create', 'store'] );
            Route::resource('roles',        'admin\RolesController');
            Route::resource('mail',        'admin\MailController');
            Route::get('prepareSend/{id}',        'admin\MailController@prepareSend')->name('prepareSend');
            Route::post('mailSend/{id}',        'admin\MailController@send')->name('mailSend');
            Route::get('/roles/move/move-up',      'admin\RolesController@moveUp')->name('roles.up');
            Route::get('/roles/move/move-down',    'admin\RolesController@moveDown')->name('roles.down');
            Route::prefix('menu/element')->group(function () { 
                Route::get('/',             'admin\MenuElementController@index')->name('menu.index');
                Route::get('/move-up',      'admin\MenuElementController@moveUp')->name('menu.up');
                Route::get('/move-down',    'admin\MenuElementController@moveDown')->name('menu.down');
                Route::get('/create',       'admin\MenuElementController@create')->name('menu.create');
                Route::post('/store',       'admin\MenuElementController@store')->name('menu.store');
                Route::get('/get-parents',  'admin\MenuElementController@getParents')->name('menu.parent');
                Route::get('/edit',         'admin\MenuElementController@edit')->name('menu.edit');
                Route::post('/update',      'admin\MenuElementController@update')->name('menu.update');
                Route::get('/show',         'admin\MenuElementController@show')->name('menu.show');
                Route::get('/delete',       'admin\MenuElementController@delete')->name('menu.delete');
            });
            Route::prefix('menu/menu')->group(function () { 
                Route::get('/',         'admin\MenuController@index')->name('menu.menu.index');
                Route::get('/create',   'admin\MenuController@create')->name('menu.menu.create');
                Route::post('/store',   'admin\MenuController@store')->name('menu.menu.store');
                Route::get('/edit',     'admin\MenuController@edit')->name('menu.menu.edit');
                Route::post('/update',  'admin\MenuController@update')->name('menu.menu.update');
                Route::get('/delete',   'admin\MenuController@delete')->name('menu.menu.delete');
            });
        });
    });
});