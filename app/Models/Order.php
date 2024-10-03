<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    protected $table = 'orders';

    protected $fillable = [
        'user_id','task_id','shipped_time','status','checked_status','report_status', 'created_at'
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
      
            if ($request->filled('company_name')) {
                $operator = $request->input('company_operator', 'like');
                $value = '%' . $request->input('company_name') . '%';
            
                $query->whereHas('task.company', function ($query) use ($operator, $value) {
                    $query->where('name', $operator, $value);
                });
            
                // Continue with other filters...
            }
            
            // END Search relationed company ***********************************************

            // Search relationed category ***********************************************
            if ($request->filled('category_name')) {
                $operator = $request->input('category_name_operator', 'like');
                $value = '%' . $request->input('category_name') . '%';
        
                $query->whereHas('task.category', function ($q) use ($operator, $value) {
                    $q->where('name', $operator, $value);
                });
        
                continue; 
            }
            // END Search relationed category ***********************************************

            // Search relationed driver ***********************************************
            if ($request->filled('driver_full_name')) {
                // Determine the operator
                $operator = $request->input('driver_full_name_operator', 'like');
                $value = '%' . $request->input('driver_full_name') . '%';
        
                // Apply the filter
                $query->whereHas('task.driver', function ($q) use ($operator, $value) {
                    $q->where('full_name', $operator, $value);
                });
        
                continue; 
            }
            // END Search relationed driver ***********************************************

            // Search relationed status ***********************************************
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


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function task()
    {
        return $this->belongsTo(Tasks::class);
    }


    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

}
