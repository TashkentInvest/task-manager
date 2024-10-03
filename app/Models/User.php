<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable. 
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','password','theme','last_finishedtask'
    ];

    public static function deepFilters(){

        $tiyin = [
        ];

        $obj = new self();
        $request = request();

        $query = self::where('id','!=','0');

        foreach ($obj->fillable as $item) {
            //request operator key
            $operator = $item.'_operator';

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

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setTheme(string $theme)
    {
        $this->theme = $theme;
        $this->save();
    }

    public function theme():array
    {
        $classes = [
            'default' => [
                'body' => '',
                'navbar' => ' navbar-light ',
                'sidebar' => 'dark',
            ],
            'light' => [
                'body' => 'light',
                'navbar' => ' navbar-white ',
                'sidebar' => 'light'
            ],
            'dark' => [
                'body' => 'dark',
                'navbar' => ' navbar-dark ',
                'sidebar' => 'dark'
            ]
        ];
        return $classes[$this->theme] ?? [
                'body' => '',
                'navbar' => ' navbar-light ',
                'sidebar' => ' sidebar-dark-primary ',
            ];
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function tasks()
    {
        return $this->hasMany(Tasks::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function userOfDays()
    {
        return $this->hasMany(UserOffDay::class);
    }


}
