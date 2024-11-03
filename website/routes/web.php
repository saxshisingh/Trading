<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TradeController;

Route::group(['namespace' => 'App\Http\Controllers\Web'], function() {
    Route::resource('login', 'LoginController');
    Route::get('generate/token', "LoginController@generateToken")->name("generateToken");
    Route::middleware(['auth', 'check.role'])->group(function () {
        Route::resource('/', 'WelcomeController');
        Route::resource('banned', 'BannedController');
        Route::resource('details', 'DetailController');
        Route::post('check_password', 'PortfolioController@check')->name('portfolio.check');
        Route::resource('portfolio', 'PortfolioController');
        Route::resource('trades', 'TradeController');
        Route::get('ledger/export/', "LedgerController@export")->name('ledger.export');
        Route::resource('watchlist', 'WatchlistController');
        Route::post('fetch_script', 'WatchlistController@fetchScript')->name('fetchScript');
        Route::post('fetch_expiry_date', 'WatchlistController@fetchExpiryDate')->name('fetch_expiry_date');
        Route::get('fetch_strike', 'WatchlistController@fetchStrike')->name('fetch_strike');
        Route::post('fetch_watchlist_data', 'WatchlistController@fetch_log')->name('fetch_watchlist_data');
        Route::resource('edit_delete', 'EditDeleteController');
        Route::resource('rejection', 'RejectionLogController');
        Route::resource('ledger', 'LedgerController');
        Route::resource('notifications', 'NotificationController');
        Route::resource('rules', 'RulesController');
        Route::resource('change_password', 'ChangePasswordController');
        Route::get('place_order', 'TradeController@placingOrder');
        Route::get('instruments', 'TradeController@instruments');
        Route::get('trade_list','TradeController@trade_list');
        Route::get('trade_list/{id}','TradeController@trade_by_order');
        Route::post('/trigger-close-all', 'PortfolioController@triggerCloseAll')->name('trigger.close.all');
        // Route::get('get_position/{id}','PortfoilioController@get_position');
        Route::get('order_list', 'TradeController@order_list');
        Route::get('logout', 'LoginController@logout');
        Route::get('kite_callback', [TradeController::class, 'handleCallback'])->name('kite.callback');
        Route::get('/fetch-lot-size', 'WatchlistController@fetchLotSize')->name('fetch.lot.size');

    });
});

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function() {
    Route::middleware(['auth', 'check.role'])->group(function () {
        Route::resource('dashboard', 'DashboarController');
        Route::resource('users', 'UserManagementController');
        Route::post('toggleActiveUser/{id}', 'UserManagementController@toggleActive')->name('toggleActiveUser');
        Route::post('toggleActive/{id}', 'PositionController@toggleActive')->name('toggleActive');
        Route::post('toggleActiveTrade/{id}', 'TradeRequestController@toggleActive')->name('toggleActiveTrade');
        Route::post('toggleBan/{id}', 'BlockScriptController@toggleBan')->name('toggleBan');
        Route::get('logout', 'LoginController@logout');
        Route::resource('position', 'PositionController');
        Route::get('kite_login', 'MarketLoginController@login')->name('kite.login');
        Route::post('handle_call_back', 'MarketLoginController@handleCallback')->name('market.handleCallback');
        Route::resource('active_position', 'ActivePositionController');
        Route::resource('withdrawRequest','WithdrawRequestController');
        Route::resource('login_password','PasswordController');
        Route::resource('transaction_password','TransPasswordController');
        Route::resource('pending','PendinOrderController');
        Route::resource('close_position', 'ClosePositionController');
        Route::resource('lotdetail', 'LotDetailController');
        Route::resource('blockscript', 'BlockScriptController');
        Route::resource('quantity', 'MaxQuantityDetailsController');
        Route::resource('tradeRequest', 'TradeRequestController');
        Route::resource('notification', 'NotificationController');
        Route::resource('closed_trades', 'ClosedTradeController');
        Route::resource('trader_funds', 'TraderFundController');
        Route::resource('deleted_trades', 'DeletedTradeController');
        Route::resource('transaction_history', 'TransactionController');
        Route::resource('trade_history', 'TradeHistoryController');
        Route::get('transaction_history/export', "TransactionController@export")->name('transaction_history.export');
        Route::get('trade_history/exports', "TradeHistoryController@create");
    });
});
