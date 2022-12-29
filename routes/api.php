<?php

use App\Http\Controllers\AllOfficeFurnitureController;
use App\Http\Controllers\AllSoftMebelController;
use App\Http\Controllers\HomeMebelController;
use App\Http\Controllers\KitchenMebelController;
use App\Http\Controllers\LoftController;
use App\Http\Controllers\MebelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('localization')->group(function(){
    Route::get('/mebel', [MebelController::class, 'getData'])->name('mebel.index');
    Route::post('/mebel', [MebelController::class, 'store'])->name('mebel.store');
    Route::get('mebel/show/{id}', [MebelController::class, 'edit']);
    Route::put('mebel/{id}/update/', [MebelController::class, 'updateData'])->name('mebel.updateDate');
    Route::delete('mebel/destroy/{id}', [MebelController::class, 'destroy']);

    Route::get('/allmebel', [AllOfficeFurnitureController::class, 'getAllData'])->name('allmebel.index');
    Route::post('/allmebel', [AllOfficeFurnitureController::class, 'store'])->name('allmebel.store');
    Route::get('/allmebel/show/{id}', [AllOfficeFurnitureController::class, 'editAllData']);
    Route::post('/allmebel/update', [AllOfficeFurnitureController::class, 'update'])->name('allmebel.update');
    Route::delete('/allmebel/destroy/{id}', [AllOfficeFurnitureController::class, 'destroy']);

    Route::get('/loftmebel', [LoftController::class, 'getLoftData'])->name('loftmebel.index');
    Route::post('/loftmebel', [LoftController::class, 'store'])->name('loftmebel.store');
    Route::get('/loftmebel/edit/{id}', [LoftController::class, 'editLoft']);
    Route::post('/loftmebel/update', [LoftController::class, 'update'])->name('loftmebel.update');
    Route::delete('/loftmebel/destroy/{id}', [LoftController::class, 'destroy']);

    /* Soft routes */
    Route::get('/softmebel', [AllSoftMebelController::class, 'getSoftData'])->name('softmebel.index');
    Route::post('/softmebel', [AllSoftMebelController::class, 'store'])->name('softmebel.store');
    Route::get('/softmebel/edit/{id}', [AllSoftMebelController::class, 'editSoft']);
    Route::post('/softmebel/update', [AllSoftMebelController::class, 'update'])->name('softmebel.update');
    Route::delete('/softmebel/destroy/{id}', [AllSoftMebelController::class, 'destroy']);

    Route::get('/homemebel', [HomeMebelController::class, 'getHometData'])->name('homemebel.index');
    Route::post('/homemebel', [HomeMebelController::class, 'store'])->name('homemebel.store');
    Route::get('/homemebel/edit/{id}', [HomeMebelController::class, 'editHome']);
    Route::post('/homemebel/update', [HomeMebelController::class, 'update'])->name('homemebel.update');
    Route::delete('/homemebel/destroy/{id}', [HomeMebelController::class, 'destroy']);

    Route::get('/kitchenmebel', [KitchenMebelController::class, 'getKitchenData'])->name('kitchenmebel.index');
    Route::post('/kitchenmebel', [KitchenMebelController::class, 'store'])->name('kitchenmebel.store');
    Route::get('/kitchenmebel/edit/{id}', [KitchenMebelController::class, 'editKitchen']);
    Route::post('/kitchenmebel/update', [KitchenMebelController::class, 'update'])->name('kitchenmebel.update');
    Route::delete('/kitchenmebel/destroy/{id}', [KitchenMebelController::class, 'destroy']);

});

