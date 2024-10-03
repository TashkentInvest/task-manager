<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;

class FinesController extends Controller
{

    public function index()
    {
        $fines = Rating::where('action',1)->get()->all();
      
        $totalScore = Rating::where('action', 1)->sum('score');

        
        $users = User::whereHas('ratings', function ($query) {
            $query->where('action', 1);
        })->get();
        
        return view('pages.report.fines', compact('fines', 'users','totalScore'));
    }

    public function bonuses()
    {
        $bonuses = Rating::where('action',0)->get()->all();
      
        $totalScore = Rating::where('action', 0)->sum('score');

        
        $users = User::whereHas('ratings', function ($query) {
            $query->where('action', 0);
        })->get();
        
        return view('pages.report.bonuses', compact('bonuses', 'users','totalScore'));
    }


}
