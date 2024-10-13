<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role;

class Tasks extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'tasks';

    protected $fillable = [
        'user_id',
        'category_id',
        'status_id',
        'poruchenie', // Поручение
        'issue_date', // Дата выдачи
        // 'author', // Автор поручения

        'planned_completion_date', // Срок выполнения (план)
        'short_title', // Краткое название
        'attached_file', // Закрепленный файл
        'note', // Примичание

        'role_id',
        'bool_status',

        'reject_comment'
    ];


    protected $casts = [
        'issue_date',
        'planned_completion_date'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_task', 'task_id', 'role_id');
    }


    public function task_users()
    {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }



    public static function deepFilters()
    {

        $tiyin = [];

        $obj = new self();
        $request = request();

        $query = self::where('id', '!=', '0');

        foreach ($obj->fillable as $item) {

            // dump($item);


            $operator = $item . '_operator';

            // Search relationed company ***********************************************
            if ($item == 'company_id' && $request->has('company_name')) {
                // Determine the operator
                $operator = $request->input('company_name_operator', 'like');
                $value = '%' . $request->input('company_name') . '%';

                // Apply the filter
                $query->whereHas('company', function ($q) use ($operator, $value) {
                    $q->where('name', $operator, $value);
                });

                continue;
            }
            // END Search relationed company ***********************************************

            // Search relationed category ***********************************************
            if ($item == 'category_id' && $request->has('category_name')) {
                // Determine the operator
                $operator = $request->input('category_name_operator', 'like');
                $value = '%' . $request->input('category_name') . '%';

                // Apply the filter
                $query->whereHas('category', function ($q) use ($operator, $value) {
                    $q->where('name', $operator, $value);
                });

                continue;
            }
            // END Search relationed category ***********************************************

            // Search relationed driver ***********************************************
            if ($item == 'driver_id' && $request->has('driver_full_name')) {
                // Determine the operator
                $operator = $request->input('driver_full_name_operator', 'like');
                $value = '%' . $request->input('driver_full_name') . '%';

                // Apply the filter
                $query->whereHas('driver', function ($q) use ($operator, $value) {
                    $q->where('full_name', $operator, $value);
                });

                continue;
            }
            // END Search relationed driver ***********************************************

            // Search relationed user ***********************************************
            if ($item == 'user_id' && $request->has('user_name')) {
                // Determine the operator
                $operator = $request->input('user_name_operator', 'like');
                $value = '%' . $request->input('user_name') . '%';

                // Apply the filter
                $query->whereHas('user', function ($q) use ($operator, $value) {
                    $q->where('name', $operator, $value);
                });

                continue;
            }
            // END Search relationed user ***********************************************


            // Search relationed status ***********************************************
            if ($item == 'status_id' && $request->has('status_name')) {
                // Determine the operator
                $operator = $request->input('status_name_operator', 'like');
                $value = '%' . $request->input('status_name') . '%';

                // Apply the filter
                $query->whereHas('status', function ($q) use ($operator, $value) {
                    $q->where('name', $operator, $value);
                });

                continue;
            }
            // END Search relationed status ***********************************************


            if ($request->has($item) && $request->$item != '') {


                if (isset($tiyin[$item])) {
                    $select = $request->$item * 100;
                    $select_pair = $request->{$item . '_pair'} * 100;
                } else {
                    $select = $request->$item;
                    $select_pair = $request->{$item . '_pair'};
                }
                //set value for query
                if ($request->has($operator) && $request->$operator != '') {
                    if (strtolower($request->$operator) == 'between' && $request->has($item . '_pair') && $request->{$item . '_pair'} != '') {
                        $value = [
                            $select,
                            $select_pair
                        ];

                        $query->whereBetween($item, $value);
                    } elseif (strtolower($request->$operator) == 'wherein') {
                        $value = explode(',', str_replace(' ', '', $select));
                        $query->whereIn($item, $value);
                    } elseif (strtolower($request->$operator) == 'like') {
                        if (strpos($select, '%') === false)
                            $query->where($item, 'like', '%' . $select . '%');
                        else
                            $query->where($item, 'like', $select);
                    } else {
                        $query->where($item, $request->$operator, $select);
                    }
                } else {
                    $query->where($item, $select);
                }
            }
        }

        return $query;
    }


    // ------------------
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // 

    public function history()
    {
        return $this->hasMany(TasksHistory::class, 'task_id', 'id');
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users_pivot()
    {
        return $this->belongsToMany(User::class, 'task_users'); // Adjust the table name as needed
    }


    public function status()
    {
        return $this->belongsTo(TaskStatus::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'task_id');
    }
}
