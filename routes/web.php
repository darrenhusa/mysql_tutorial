<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/actors', function () {

    // dd('hello');
    // $actors = DB::table('actor')->get();
    $actors = DB::table('actor')->paginate(10);
    // dd($actors);

    return view('actors.index', compact('actors'));
});
