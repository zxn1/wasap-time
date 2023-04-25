<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Livewire\lastActivity;

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
    return view('pages.chat');
})->name('home');

Route::get('/list', function() {
    if(lastActivity::checkSession())
    {
        return view('pages.friend');
    } else {
        return redirect()->route('home');
    }
});


Route::post('/video-calling', [Controller::class, 'makeCallingRequest']);

Route::get('/accept-call', function()
{
    return view('pages.acceptWebRTC');
});