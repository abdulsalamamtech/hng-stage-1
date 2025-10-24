<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});



Route::get('/test', function () {
    return [
        "message" => "testing application..",
        "time" => now()
    ];
});


Route::get('/artisan', function () {
    Artisan::call('inspire');
    Artisan::call('optimize:clear');
    Artisan::call('migrate');
    Artisan::call('db:seed');
    Artisan::call('optimize');
    Artisan::call('inspire');
});

Route::get('/fresh', function () {
    Artisan::call('inspire');
    Artisan::call('optimize:clear');
    Artisan::call('migrate:fresh');
    Artisan::call('db:seed');
    Artisan::call('optimize');
    Artisan::call('inspire');
});
