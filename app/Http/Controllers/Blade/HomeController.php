<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $isRoleExists = DB::table('model_has_roles')->where('model_id', $userId)->exists();

        if ($isRoleExists) {
            return view('pages.dashboard');
        } else {
            return view('welcome');
        }
    }
}
