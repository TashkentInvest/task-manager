<?php
namespace App\Helpers;
use Carbon\Carbon;

class CommonHelpers
{
    public static function removeHyphens($str)
    {
        return preg_replace("/[^a-zA-Z0-9]+/", "", $str);
    }

    public static function createUserEmail($name)
    {
        $string = str_replace([' ', ''], '', strtolower($name));
        return $string . '@gmail.com';
    }

    public static function generateUserPassword($birthdate)
    {
        // Extract the birthdate digits
        $birthdateDigits = CommonHelpers::removeHyphens($birthdate);
        // Convert the birthdate digits into an array
        $digitsArray = str_split($birthdateDigits);
        // Shuffle the array to randomize the order of digits
        shuffle($digitsArray);
        // Concatenate the shuffled digits back into a string
        $password = implode('', $digitsArray);
        return bcrypt($password);
    }

    public static function get_days($week_days)
    {
        // Parse start and end dates using Carbon
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $month = $start->format('F');
        // Initialize an array to store non-working days
        $dates = array();
        $days = array();

        // Iterate over the range of dates
        for ($date = $start; $date->lte($end); $date->addDay()) {
            // Get the day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
            $day_of_week = $date->dayOfWeek;

            // Check if the day is a non-working day based on the week_days array
            if ($week_days[$day_of_week] == 1) {
                $dates[] = $date->format('Y-m-d');
                // Add day name to the days array
                $day_name = $date->format('l');
                if (!in_array($day_name, $days)) {
                    $days[] = $day_name;
                }
            }
        }
        $total_days_in_month = Carbon::now()->daysInMonth;
        $work_days = $total_days_in_month - count($dates);
        return ['dates' => $dates, 'days' => $days, 'month' => $month, 'work_days' => $work_days];
    }

    public static function other_days($month_name, $days)
    {
        // Initialize an array to hold the days of the month
        $days_of_month = [];
        // Get the number of days in the given month
        $number_of_days = cal_days_in_month(CAL_GREGORIAN, date('m', strtotime($month_name)), date('Y', strtotime($month_name)));

        for ($i = 1; $i <= $number_of_days; $i++) {
            $day = date('Y-m-d', strtotime("$month_name $i"));
            // Check if the day is not in the $days array
            $found = false;
            foreach ($days as $d) {
                if ($d['date'] == $day) {
                    $found = true;
                    break;
                }
            }
            // If the day is not found in the $days array, add it to the result array
            if (!$found) {
                $days_of_month[] = $day;
            }
        }
        // Return the other days of the month
        return $days_of_month;
    }

    public static function get_days_by_month($month_name, $days)
    {
        // Parse the month name to get the month number
        $month_number = Carbon::parse("1 $month_name")->month;

        // Initialize an empty array to store the dates
        $result = [];

        // Loop through the days of the month
        $date = Carbon::createFromDate(null, $month_number, 1); // Start from the first day of the month
        $lastDayOfMonth = $date->endOfMonth();
        while ($date <= $lastDayOfMonth) {
            // Check if the current day is one of the specified weekdays
            if (in_array($date->englishDayOfWeek, $days)) {
                $result[] = $date->toDateString();
            }
            // Move to the next day
            $date->addDay();
        }
        return  $result;

        return $result;
    }
}