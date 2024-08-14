<?php


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    $path = public_path('app/index.html');
    abort_unless(file_exists($path), 400, 'Page is not Found!');
   // return file_get_contents($path);
    return File::get(public_path() . '/app/index.html');    
})->name('FrontEndApp');

Route::get('/token', function () {
    return csrf_token();
});

Route::get('/list-users', [Controller::class, 'usersList']);
Route::get('/search-users', [Controller::class, 'searchUsers']);
Route::delete('/delete-user/{id}', [Controller::class, 'deleteUser']);
Route::put('/update-user/{id}', [Controller::class, 'updateUser']);