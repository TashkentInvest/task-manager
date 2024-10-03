<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\CommonHelpers;
use App\Models\User;
use App\Models\OffDay;
use App\Models\UserOffDay;

class ShiftController extends Controller
{
    public function index()
    {
        $data = UserOffDay::with(['user', 'days' => function ($query) {
                    $query->where('status', 0);
                }])
                ->orderBy('id', 'desc')
                ->get()
                ->all();    
        // dd($data);
        return view('pages.shift.index', compact('data'));
    }

    public function add()
    {
        $users = User::where('name','!=','Super Admin')->get()->all();
        return view('pages.shift.add', compact('users'));
    }

    public function create(Request $request)
    {
        $off_days = CommonHelpers::get_days($request->get('off_days'));
        $dates = $off_days['dates'];
        $days = $off_days['days'];
        $month = $off_days['month'];
        $work_days = $off_days['work_days'];
        $user_id = $request->get('user_id');

        $existingRecord = UserOffDay::where('user_id', $user_id)->first();
        if ($existingRecord) {
            return response()->json(['error' => 'User already has an off day record.'], 400);
        };
        $week_days_string = implode(', ', $days);

        $userDayInfo = new UserOffDay();
        $userDayInfo->user_id = $user_id;
        $userDayInfo->month = $month;
        $userDayInfo->week_days = $week_days_string;
        $userDayInfo->count_work_days = $work_days;
        $userDayInfo->save();

        $data = [];
        // UserOffDay
        foreach ($dates as $off_day) {
            // Push regular date record
            $data[] = [
                'user_id' => $user_id,
                'date' => date('Y-m-d', strtotime($off_day)),
            ];
        }

        foreach ($data as $item) {
            $day = new OffDay();
            $day->user_off_day_id = $userDayInfo->id;
            $day->user_id = $item['user_id'];
            $day->date = $item['date'];
            $day->save();
        }
        
        $response = [
            'status' => 0,
            'message' => 'Success',
            'redirect_url' => route('shiftIndex')
        ];
        
        return response()->json($response);
        
    }

    public function extraDays(Request $request, $id)
    {
        $extra_work_days = $request->extra_work_days;
        if(!empty($extra_work_days)){
            foreach ($extra_work_days as $day) {
                $off_day = OffDay::where('date', $day)->first();
                $off_day->status = 1;
                $off_day->save();
            }
            $userDayInfo = UserOffDay::find($id);
            $userDayInfo->count_work_days = $userDayInfo->count_work_days + count($extra_work_days);
            $userDayInfo->save();
            return redirect()->back()->with('success', 'User off days updated successfully');
        }
        return redirect()->back()->with('danger', 'User off days updated successfully');
    }

    public function edit($id)
    {
        $userDayInfo = UserOffDay::find($id);
        $days = OffDay::where('user_off_day_id', $userDayInfo->id)->get()->all();
        $other_days = CommonHelpers::other_days($userDayInfo->month, $days);
        return view('pages.shift.edit', compact('userDayInfo', 'other_days'));
    }

    public function update(Request $request, $id)
    {
        if ($request->has('edit_week_days')) {
            if($request->input('edit_week_days') === 'true'){
                $user_id = $request->input('user_id');
                $off_days = CommonHelpers::get_days($request->input('off_days'));
    
                $userDayInfo = UserOffDay::find($id);
                if (!$userDayInfo) {
                    return response()->json(['status' => 1, 'message' => 'User off-day not found'], 404);
                }
    
                $userDayInfo->update([
                    'month' => $off_days['month'],
                    'week_days' => implode(', ', $off_days['days']),
                    'count_work_days' => $off_days['work_days'],
                ]);
    
                OffDay::where('user_off_day_id', $id)->delete();
    
                $data = [];
                foreach ($off_days['dates'] as $off_day) {
                    $data[] = [
                        'user_off_day_id' => $id,
                        'user_id' => $user_id,
                        'date' => date('Y-m-d', strtotime($off_day)),
                    ];
                }
    
                OffDay::insert($data);

            }
            else{
                $additional_off_day = $request->additional_off_day;
                foreach($additional_off_day as $item){
                    $day = new OffDay();
                    $day->user_off_day_id = $id;
                    $day->user_id = $request->input('user_id');
                    $day->date = $item;
                    $day->type = 2;
                    $day->save();
                };
                $userDayInfo = UserOffDay::find($id);
                $userDayInfo->count_work_days = $userDayInfo->count_work_days - count($additional_off_day);
                $userDayInfo->save();
            }
            // return response()->json(['status' => 0, 'message' => 'Success']);
            return response()->json([
                'status' => 1,
                'message' => 'Success',
                'redirect_url' => route('shiftIndex')
            ]);
        }

        // return response()->json(['status' => 1, 'message' => 'Invalid request']);
        return response()->json([
            'status' => 1,
            'message' => 'Invalid request',
            'redirect_url' => route('shiftIndex')
        ]);
        
    }

    public function destroy($id)
    {
        // Delete related OffDay records
        OffDay::where('user_off_day_id', $id)->delete();
        $userDayInfo = UserOffDay::find($id);
        
        
        if (!$userDayInfo) {
            // UserOffDay not found
            message_set("User off days not found", 'error', 1);
            return redirect()->back();
        }

        $userDayInfo->delete();
        
        // Set success message
        message_set("User off days deleted", 'success', 1);
        
        return redirect()->back();
    }

}
