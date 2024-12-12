<?php

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    return $request->user();
});
 
   
Route::post('/orders', [OrderController::class, 'create_order']);
Route::get('/orders/{id}', [OrderController::class, 'get_order']);
Route::put('/orders/{id}', [OrderController::class, 'update_order']);
Route::delete('/orders/{id}', [OrderController::class, 'delete_order']);
Route::post('/orders/{id}/items', [OrderController::class, 'add_item']);
Route::put('/orders/{id}/items/{item_id}', [OrderController::class, 'update_item']);
Route::delete('/orders/{order_id}/items/{order_item_seq_id}', [OrderController::class, 'delete_item']);



Route::put('/update_contact/{id}', [OrderController::class, 'update_contact_mech']);

Route::get('/generateSanctumToken', [TokenController::class, 'generateSanctumToken']);