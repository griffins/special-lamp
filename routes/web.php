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
Auth::routes(['verify' => true]);

Route::any('verify', 'Auth\\LoginController@verify')->name('login.verify');

Route::get('/home', function () {
    return redirect('/');
});
Route::get('/', 'HomeController@index');
Route::get('/client/dashboard/{client}', 'HomeController@clientDashboard')->name('client.dashboard');
Route::post('password/change', 'ProfileController@changePassword')->name('password.change');

Route::get('/profile', 'ProfileController@index')->name('profile');
Route::post('/profile', 'ProfileController@update')->name('profile.update');
Route::get('/clients/{client}', 'ClientController@index')->name('client');
Route::post('/clients/{client}/transaction', 'ClientController@transaction')->name('transaction');
Route::post('/profit', 'ClientController@profit')->name('profit');
Route::post('/ticket/{client}', 'ClientController@openTicket')->name('client.ticket');
Route::post('/client/mark/investment/{transaction}', 'ClientController@markTransaction')->name('mark');

Route::get('reports/{name?}', 'ReportController@report')->name('report');
Route::any('system/{section?}', 'Admin\\SupportController@index')->name('support');
Route::any('mailbox', 'Admin\\SupportController@mailbox')->name('mailbox');

Route::any('support/{action?}', 'SupportTicketController@index')->name('support.ticket');
Route::any('helpdesk/{action?}', 'Admin\\TicketController@index')->name('support.resolution');


Route::get('/home', 'HomeController@index')->name('home');

Route::get('/home', 'HomeController@index')->name('home');
