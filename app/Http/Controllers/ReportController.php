<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Rating;
use App\Models\User;
use App\Models\UserOffDay;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function users()
    {
        $users = auth()->user()->all();
        
        return view('pages.report.users', compact('users'));
    }

    public function show($id, $name)
    {
        $user = User::findOrFail($id);
        
        if ($user->name !== $name) {
            return redirect()->route('controlReportIndex', ['id' => $id, 'name' => $user->name]);
        }
        
        $rating = null;
        $reports = Order::deepFilters()
                    ->where('user_id', $user->id)
                    // ->where('status', 4)
                    ->get();
        
        foreach ($reports as $report) {
            $rating = Rating::where('order_id', $report->id)->first();
            $report->rating = $rating;
        }
        
        // Pass the user, their reports, and the rating to the view
        return view('pages.report.show', compact('user', 'reports', 'rating','id','name'));
    }
    

    
    public function fetchRating($reportId)
    {
        $rating = Rating::where('order_id', $reportId)->first();
        return response()->json([
            'score' => optional($rating)->score,
            'description' => optional($rating)->description,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'score' => 'required',
            'description' => 'required',
            'user_id' => 'required',
            'order_id' => 'required',
        ]);
    
        $order = Order::find($request->order_id);
    
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }
    
        // Update order report status
        $order->report_status = 1;
        $order->save();
    
        // Create a new rating for the order
        Rating::create([
            'order_id' => $order->id,
            'user_id' => $request->user_id,
            'score' => $request->score,
            'description' => $request->description,
            'action' => $request->input('action'),
            'status' => 1,
        ]);
    
        return redirect()->back()->with('success', 'Rating added successfully');
    }

    
    public function update(Request $request, $id)
    {
        // dd($request);
        $order = Order::find($request->order_id);
        $order->report_status = 1; 
        $order->save();
    
        $rating = Rating::find($id);
        
        $rating->action = $request->action;
        $rating->score = $request->score;
        $rating->description = $request->description;
        $rating->status = 1; 
        
        // if ($request->action == 1) {
        //     $rating->score = $rating->score - $request->score;
        // } else {
        //     $rating->score = $rating->score + $request->score;
        // }
    
        $rating->save();
        
        return redirect()->back()->with('success', 'Rating updated successfully');
    }
    

    public function shift(){
        return view('pages.report.shift');
    }

    public function fines(){
        return view('pages.report.fines');
    }

    public function bonuses(){
        return view('pages.report.bonuses');
    }

    public function cheque() {
        $data = UserOffDay::with(['user', 'days' => function ($query) {
                $query->where('status', 0);
            }])
            ->orderBy('id', 'desc')
            ->get();
    
        $users = [];
    
        if(auth()->user()->roles[0]->name != 'Employee') {
            $users = User::deepFilters()
                ->whereHas('ratings')
                ->with(['orders' => function ($query) {
                    $query->where('status', '!=', 0);
                }])
                ->get();
        } else {
                $users = User::deepFilters()
                ->where('id', auth()->user()->id)
                ->whereHas('ratings')
                ->with(['orders' => function ($query) {
                    $query->where('status', '!=', 0);
                }])
                ->get();
        }
    
        $userScores = [];
        foreach ($users as $user) {
            $totalScore =  Rating::where('action', 0)->where('user_id', $user->id)->sum('score') -  Rating::where('action', 1)->where('user_id', $user->id)->sum('score');
            $userScores[$user->id] = [
                'totalScore' => $totalScore,
                'finesScore' => Rating::where('action', 1)->where('user_id', $user->id)->sum('score'),
                'bonusScore' => Rating::where('action', 0)->where('user_id', $user->id)->sum('score'),
            ];
        }
    
        return view('pages.report.cheque', compact('userScores', 'data', 'users'));
    }
    
}
