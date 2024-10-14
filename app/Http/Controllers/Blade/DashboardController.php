<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        abort_if_forbidden('dashboard.show');

        // $users = auth()->user()->all()->count();
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'Super Admin');
        })->count();

        // own orders
        $own_orders = auth()->user()->orders()->where('status', 4)->count(); 
        // $unCompleted_own_orders = auth()->user()->orders()->where('status', 5)->count(); 
        // dd($unCompleted_own_orders);
        $unCompleted_own_orders = auth()->user()->orders()->whereIn('status', [5, 7, 8])->count();

        // all orders 
        $all_orders = Order::where('status', 4)->get()->count();
        $unclomlated_all_orders = Order::whereIn('status', [5, 7, 8])->count();
        // dump($unclomlated_all_orders);
        // monthly average
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $ordersThisMonth = Auth::user()->orders()
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();
        
        $ordersThisMonthAll = Order::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->get();
        
        $orderCountThisMonth = $ordersThisMonth->count();
        
        $orderCountThisMonthAll = $ordersThisMonthAll->count();
        
        $totalMonths = Carbon::now()->diffInMonths(Auth::user()->created_at) + 1;
        
        $monthlyAverage = $totalMonths > 0 ? ceil($orderCountThisMonth / $totalMonths) : 0;
        
        $monthlyAverageAllUsers = $totalMonths > 0 ? ceil($orderCountThisMonthAll / $totalMonths) : 0;

     
        $topUsers = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('name', ['Super Admin','Admin']); 
        })
        ->withCount(['orders' => function ($query) {
            $query->where('status', 4);
        }])
        ->orderByDesc('orders_count')
        ->limit(5)
        ->get();


        return view('pages.dashboard.index', compact('users','monthlyAverage','monthlyAverageAllUsers','own_orders','all_orders','unCompleted_own_orders','unclomlated_all_orders','topUsers'));
    }
    
}
