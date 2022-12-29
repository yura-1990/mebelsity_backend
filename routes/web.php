<?php

use App\Http\Controllers\MebelController;
use App\Http\Controllers\AllOfficeFurnitureController;
use App\Http\Controllers\AllSoftMebelController;
use App\Http\Controllers\HomeMebelController;
use App\Http\Controllers\KitchenMebelController;
use App\Http\Controllers\LoftController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/toggle/office/mebel', [MebelController::class, 'toggle'])->middleware('auth');

Route::get('/mebel', [MebelController::class, 'index'])->middleware('auth')->name('mebel.index');
Route::post('/mebel', [MebelController::class, 'store'])->middleware('auth')->name('mebel.store');
Route::get('/mebel/edit/{id}', [MebelController::class, 'edit'])->middleware('auth');
Route::post('/mebel/update', [MebelController::class, 'update'])->middleware('auth')->name('mebel.update');
Route::delete('/mebel/destroy/{id}', [MebelController::class, 'destroy'])->middleware('auth');


Route::get('/toggle', [AllOfficeFurnitureController::class, 'toggle'])->middleware('auth');

Route::get('/allmebel', [AllOfficeFurnitureController::class, 'index'])->middleware('auth')->name('allmebel.index');
Route::post('/allmebel', [AllOfficeFurnitureController::class, 'store'])->middleware('auth')->name('allmebel.store');
Route::get('/allmebel/edit/{id}', [AllOfficeFurnitureController::class, 'editAllData'])->middleware('auth');
Route::post('/allmebel/update', [AllOfficeFurnitureController::class, 'update'])->middleware('auth')->name('allmebel.update');
Route::delete('/allmebel/destroy/{id}', [AllOfficeFurnitureController::class, 'destroy'])->middleware('auth');

/* Soft */
Route::get('/toggle/soft/mebels', [AllSoftMebelController::class, 'toggle'])->middleware('auth');

Route::get('/softmebel', [AllSoftMebelController::class, 'index'])->middleware('auth')->name('softmebel.index');
Route::post('/softmebel', [AllSoftMebelController::class, 'store'])->middleware('auth')->name('softmebel.store');
Route::get('/softmebel/edit/{id}', [AllSoftMebelController::class, 'editSoft'])->middleware('auth');
Route::post('/softmebel/update', [AllSoftMebelController::class, 'update'])->middleware('auth')->name('softmebel.update');
Route::delete('/softmebel/destroy/{id}', [AllSoftMebelController::class, 'destroy'])->middleware('auth');
/* Soft */

Route::get('/toggle/loft/office', [LoftController::class, 'toggle'])->middleware('auth');

Route::get('/loftmebel', [LoftController::class, 'index'])->middleware('auth')->name('loftmebel.index');
Route::post('/loftmebel', [LoftController::class, 'store'])->middleware('auth')->name('loftmebel.store');
Route::get('/loftmebel/edit/{id}', [LoftController::class, 'editLoft'])->middleware('auth');
Route::post('/loftmebel/update', [LoftController::class, 'update'])->middleware('auth')->name('loftmebel.update');
Route::delete('/loftmebel/destroy/{id}', [LoftController::class, 'destroy'])->middleware('auth');
/*
Route::get('/toggle/office/mebel', [HomeMebelController::class, 'toggle'])->middleware('auth'); */

Route::get('/toggle/home/mebels', [HomeMebelController::class, 'toggle'])->middleware('auth');

Route::get('/homemebel', [HomeMebelController::class, 'index'])->middleware('auth')->name('homemebel.index');
Route::post('/homemebel', [HomeMebelController::class, 'store'])->middleware('auth')->name('homemebel.store');
Route::get('/homemebel/edit/{id}', [HomeMebelController::class, 'editHome'])->middleware('auth');
Route::post('/homemebel/update', [HomeMebelController::class, 'update'])->middleware('auth')->name('homemebel.update');
Route::delete('/homemebel/destroy/{id}', [HomeMebelController::class, 'destroy'])->middleware('auth');

Route::get('/toggle/kitchen/mebels', [KitchenMebelController::class, 'toggle'])->middleware('auth');

Route::get('/kitchenmebel', [KitchenMebelController::class, 'index'])->middleware('auth')->name('kitchenmebel.index');
Route::post('/kitchenmebel', [KitchenMebelController::class, 'store'])->middleware('auth')->name('kitchenmebel.store');
Route::get('/kitchenmebel/edit/{id}', [KitchenMebelController::class, 'editKitchen'])->middleware('auth');
Route::post('/kitchenmebel/update', [KitchenMebelController::class, 'update'])->middleware('auth')->name('kitchenmebel.update');
Route::delete('/kitchenmebel/destroy/{id}', [KitchenMebelController::class, 'destroy'])->middleware('auth');

Route::get('/storage-link', function (){
   \Illuminate\Support\Facades\Artisan::all();
});

