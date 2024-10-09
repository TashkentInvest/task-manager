<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TasksHistory extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    protected $table = 'tasks_history';
    protected $fillable = [
        'task_id',
        'category_id',
        'status_id',
        'description',
        'type_request',
        'user_id',
        'old_status_id',
        'new_status_id',
        'created_at'
    ];

    public static function deepFilters(){

        $tiyin = [
        ];

        $obj = new self();
        $request = request();

        $query = self::where('id','!=','0');

        foreach ($obj->fillable as $item) {

            // dump($item);
          

            $operator = $item.'_operator';

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


            if ($request->has($item) && $request->$item != '')
            {

               
                if (isset($tiyin[$item])){
                    $select = $request->$item * 100;
                    $select_pair = $request->{$item.'_pair'} * 100;
                }else{
                    $select = $request->$item;
                    $select_pair = $request->{$item.'_pair'};
                }
                //set value for query
                if ($request->has($operator) && $request->$operator != '')
                {
                    if (strtolower($request->$operator) == 'between' && $request->has($item.'_pair') && $request->{$item.'_pair'} != '')
                    {
                        $value = [
                            $select,
                            $select_pair];

                        $query->whereBetween($item,$value);
                    }
                    elseif (strtolower($request->$operator) == 'wherein')
                    {
                        $value = explode(',',str_replace(' ','',$select));
                        $query->whereIn($item,$value);
                    }
                    elseif (strtolower($request->$operator) == 'like')
                    {
                        if (strpos($select,'%') === false)
                            $query->where($item,'like','%'.$select.'%');
                        else
                            $query->where($item,'like',$select);
                    }
                    else
                    {
                        $query->where($item,$request->$operator,$select);
                    }
                }
                else
                {
                    $query->where($item,$select);
                }
            }
        }

        return $query;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->hasOne(TaskStatus::class, 'id', 'new_status_id');
    }
}
