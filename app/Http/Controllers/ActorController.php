<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ActorController extends Controller
{
    public function index()
    {
        // $actors = DB::table('actor')->get();
        $actors = DB::table('actor'
          )->orderBy('last_name', 'asc'
          )->orderBy('first_name', 'asc'
          )->paginate(10);
       // dd($actors);
        return view('actors.index', compact('actors'));
    }
}
