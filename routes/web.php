<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\admin\ActivitiesController;
use App\Http\Controllers\admin\ResourceController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\BreadController;
use App\Http\Controllers\admin\RolesController;
use App\Http\Controllers\admin\MailController;
use App\Http\Controllers\admin\MenuController;
use App\Http\Controllers\admin\MenuElementController;
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
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Auth::routes();
    Route::group(['middleware' => ['auth','get.menu']], function () {
        Route::get('/dashboard',[ActivitiesController::class, 'index'])->name('admin.dashboard');
        Route::get('/global-activities',[ActivitiesController::class, 'getGlobalActivities'])->name('admin.global_activities');
        Route::post('/global-activities',[ActivitiesController::class, 'addGlobalActivity'])->name('admin.activity.add');
        Route::get('/global-activities/{activity}',[ActivitiesController::class, 'getGlobalActivity'])->name('admin.activity.view');
        Route::get('/user-activities',[ActivitiesController::class, 'getUserActivities'])->name('admin.global_activities');
        Route::resource('resource/{table}/resource', ResourceController::class)->names([
            'index'     => 'resource.index',
            'create'    => 'resource.create',
            'store'     => 'resource.store',
            'show'      => 'resource.show',
            'edit'      => 'resource.edit',
            'update'    => 'resource.update',
            'destroy'   => 'resource.destroy'
        ]);
        Route::get('/admins',[UsersController::class, 'getAdminUsers'])->name('admin.admins');        
        Route::group(['middleware' => ['role:super']], function () {
            Route::resource('bread', BreadController::class);
            Route::resource('users', UsersController::class)->except( ['create', 'store'] );
            Route::resource('roles', RolesController::class);
            Route::resource('mail',  MailController::class);
            Route::get('prepareSend/{id}', [MailController::class, 'prepareSend'])->name('prepareSend');
            Route::post('mailSend/{id}', [MailController::class, 'send'])->name('mailSend');
            Route::get('/roles/move/move-up', [RolesController::class, 'moveUp'])->name('roles.up');
            Route::get('/roles/move/move-down', [RolesController::class, 'moveDown'])->name('roles.down');
            Route::prefix('menu/element')->group(function () { 
                Route::get('/',             [MenuElementController::class, 'index'])->name('menu.index');
                Route::get('/move-up',      [MenuElementController::class, 'moveUp'])->name('menu.up');
                Route::get('/move-down',    [MenuElementController::class, 'moveDown'])->name('menu.down');
                Route::get('/create',       [MenuElementController::class, 'create'])->name('menu.create');
                Route::post('/store',       [MenuElementController::class, 'store'])->name('menu.store');
                Route::get('/get-parents',  [MenuElementController::class, 'getParents'])->name('menu.parent');
                Route::get('/edit',         [MenuElementController::class, 'edit'])->name('menu.edit');
                Route::post('/update',      [MenuElementController::class, 'update'])->name('menu.update');
                Route::get('/show',         [MenuElementController::class, 'show'])->name('menu.show');
                Route::get('/delete',       [MenuElementController::class, 'delete'])->name('menu.delete');
            });
            Route::prefix('menu/menu')->group(function () { 
                Route::get('/',         [MenuController::class, 'index'])->name('menu.menu.index');
                Route::get('/create',   [MenuController::class, 'create'])->name('menu.menu.create');
                Route::post('/store',   [MenuController::class, 'store'])->name('menu.menu.store');
                Route::get('/edit',     [MenuController::class, 'edit'])->name('menu.menu.edit');
                Route::post('/update',  [MenuController::class, 'update'])->name('menu.menu.update');
                Route::get('/delete',   [MenuController::class, 'delete'])->name('menu.menu.delete');
            });
        });
    });
});