<?php

namespace App\Models;

use App\Helpers\CommonHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOffDay extends Model
{
    protected $table = 'user_offday';
    protected $fillable = [
        'user_id',
        'month',
        'week_days',
        'count_work_days'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function days()
    {
        return $this->hasMany(OffDay::class);
    }

    public static function createShiftByMonth()
    {
        $usersData = UserOffDay::get()->all();
        foreach ($usersData as $userData) {
            $userId = $userData['user_id'];
            $month = $userData['month'];
            $weekDaysOff = explode(', ', $userData['week_days']);
            $countWorkDays = $userData['count_work_days'];
    
            // 2. Determine the next month
            $nextMonth = date('F', strtotime($month . ' +1 month'));
            // 3. Create the shift schedule for the next month
            $nextMonthWorkDays = CommonHelpers::get_days_by_month($month, $weekDaysOff);
            return $nextMonthWorkDays;
    
            // 4. Save the generated schedule into your database
            saveNextMonthSchedule($userId, $nextMonth, $nextMonthWorkDays);
        }
    }

    function generateNextMonthSchedule($weekDaysOff, $countWorkDays)
    {
        $nextMonthWorkDays = array();
        $currentDate = strtotime('first day of next month');
        $weekDayNumber = date('N', $currentDate);

        // Loop through the days of the month
        while (count($nextMonthWorkDays) < $countWorkDays) {
            if (!in_array(date('Y-m-d', $currentDate), $weekDaysOff) && $weekDayNumber < 6) {
                // If it's not an off-day and not a weekend (Saturday or Sunday)
                $nextMonthWorkDays[] = date('Y-m-d', $currentDate);
            }

            // Move to the next day
            $currentDate = strtotime('+1 day', $currentDate);
            $weekDayNumber = date('N', $currentDate);
        }

        return $nextMonthWorkDays;
    }

}
