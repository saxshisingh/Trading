<?php

use Illuminate\Http\Request;
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

    Route::group(["namespace"=>"App\Http\Controllers\Web"],function(){
        Route::resource('login','LoginController');
        Route::group(['middleware' => ['auth']], function(){
        Route::resource('/','WelcomeController');
        Route::resource('banned','BannedController');
        Route::resource('details','DetailController');
        Route::resource('portfolio','PortfolioController');
        Route::resource('trades','TradeController');
        Route::resource('watchlist','WatchlistController');
        Route::post('fetch_script','WatchlistController@fetchScript')->name('fetchScript');
        Route::post('fetch_expiry_date','WatchlistController@fetchExpiryDate')->name('fetch_expiry_date');
        Route::resource('edit_delete','EditDeleteController');
        Route::resource('rejection','RejectionLogController');
        Route::resource('ledger','LedgerController');
        Route::resource('rules','RulesController');
        Route::resource('change_password','ChangePasswordController');        
        Route::get('logout', 'LoginController@logout');
        });
    });
    Route::group(["namespace"=>"App\Http\Controllers\Admin"],function(){
        Route::group(['middleware' => ['auth']], function(){
        Route::resource('dashboard','DashboarController');
        Route::resource('users','UserManagementController');
        Route::post('toggleActive/{id}','UserManagementController@toggleActive' )->name('toggleActive');
        Route::post('toggleActive/{id}','PositionController@toggleActive' )->name('toggleActive');
        Route::post('toggleActive/{id}','TradeRequestController@toggleActive' )->name('toggleActive');
        Route::post('toggleBan/{id}','BlockScriptController@toggleBan' )->name('toggleBan');
        Route::get('logout', 'LoginController@logout');
        Route::resource('position', 'PositionController');
        Route::resource('lotdetail', 'LotDetailController');
        Route::resource('blockscript', 'BlockScriptController');
        Route::resource('quantity', 'MaxQuantityDetailsController');
        Route::resource('tradeRequest', 'TradeRequestController');
        Route::resource('notification', 'NotificationController');

        });
    });
    