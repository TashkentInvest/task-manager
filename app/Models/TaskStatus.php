<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskStatus extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'task_status';
    const ORDER_ACTIVE = 0;
    const ACTIVE = 1;
    const CANCELED = 2;
    const ACCEPTED = 3;
    const Completed = 4;
    const DELETED = 5;
    const TIME_IS_OVER = 7;
    const ADMIN_REJECT = 8;
    const XODIM_REJECT = 9;

    public function getColor()
    {
        switch ($this->name) {
            case 'Active':
                return 'secondary'; // Green
            case 'Canceled':
                return 'danger'; // Red
            case 'Accepted':
                return 'warning'; // Blue
            case 'Completed':
                return 'success'; // Light Blue
            case 'Deleted':
                return 'danger'; // Gray
            case 'ORDER_ACTIVE':
                return 'warning'; // Yellow
            case 'TIME_IS_OVER':
                return 'dark'; // Black
            case 'ADMIN_REJECT':
                return 'danger'; // Red
            case 'XODIM_REJECT':
                return 'danger'; // Red
            default:
                return 'light'; // Default color
        }
    }
}
