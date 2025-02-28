<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\DatabaseController;

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

Route::get('/', function () {
    if (filter_var(request()->getHttpHost(), FILTER_VALIDATE_IP) || request()->getHttpHost() == \App\Models\Site::where(['panel' => 1])->pluck('domain')->first()) {
        return view('welcome');
    }
    return 'Domain/Subdomain not configured on this Server!';
});


// Route::get('/login', function () {
//     return view('login');
// })->name('login');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified' ])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    Route::get('/servers', function () {
        return view('server.list');
    })->name('server.list');

    Route::get('/servers/{server_id}', function ($server_id) {
        return view('server.edit', compact('server_id'));
    })->name('server.edit');

    Route::get('/servers/{server_id}/fail2ban', function ($server_id) {
        return view('server.fail2ban', compact('server_id'));
    })->name('server.fail2ban');

    Route::get('/servers/{server_id}/packages', function ($server_id) {
        return view('server.packages-installed', compact('server_id'));
    })->name('server.packages-installed');





    Route::get('/sites', function () {
        return view('site.list');
    })->name('site.list');

    Route::get('/sites/{site_id}', function ($site_id) {
        return view('site.edit', compact('site_id'));
    })->name('site.edit');

    Route::get('/settings', function () {
        return view('settings.settings');
    })->name('settings.settings');

    Route::get('/design', function () {
        return view('design');
    })->name('design');

    //phpmyadmin route
    Route::get('/pma', function () {
        return redirect()->to('mysecureadmin/index.php');
    });

    //phpmyadmin route with autologin
    Route::get('/autopma/{site_id}', [NodejsController::class, 'autoLoginPMA'])->name('autopma');
    //database
    Route::get('/data', [DatabaseController::class, 'viewdatabase'])->name('data');
    Route::post('/createdatab', [DatabaseController::class,'createdatabase'])->name('createdatab');
    Route::post('/createuser', [DatabaseController::class,'createuser'])->name('createuser');
    Route::post('/linkdatabuser', [DatabaseController::class,'linkdatabaseuser'])->name('linkdatabuser');


    Route::get('/pdf/{site_id}/{token}', [SiteController::class, 'pdf']);

    Route::get('files/{folder_name?}', [FileManagerController::class,'index'])->where('folder_name', '(.*)')->name('files.index');
    Route::post('files/view', [FileManagerController::class, 'show'])->name('files.show');
    Route::post('files/edit', [FileManagerController::class, 'edit'])->name('files.edit');
    Route::post('files/store', [FileManagerController::class, 'store'])->name('files.store');
    Route::post('files/download', [FileManagerController::class, 'download'])->name('files.download');
    Route::post('files/create-directory', [FileManagerController::class, 'createDirectory'])->name('files.create.directory');
    Route::post('files/create-file', [FileManagerController::class, 'createFile'])->name('files.create.file');
    Route::post('files/rename-file', [FileManagerController::class, 'renameFile'])->name('files.rename.file');
    Route::post('files/copy-file', [FileManagerController::class, 'copy'])->name('files.copy');
    Route::post('files/move-file', [FileManagerController::class, 'move'])->name('files.move');
    Route::post('files/delete', [FileManagerController::class, 'destroy'])->name('files.delete');

    Route::get('download_file_object/{id}', [FileManagerController::class, 'downloadObject']);
    Route::get('show-media-file/{id}', [FileManagerController::class, 'showMediaFile']);
});
