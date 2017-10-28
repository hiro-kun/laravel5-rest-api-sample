<?php

use Illuminate\Http\Request;

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
Route::resource('members', 'Member\MemberController', ['except' => ['create', 'edit']]);

Route::get('members/{member_id}/orders/', 'Member\OrderController@showOrderAllByMemberId');
Route::get('members/{member_id}/orders/{order_id}', 'Member\OrderController@showOrderDetailByMemberId');
