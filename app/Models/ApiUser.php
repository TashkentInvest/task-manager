<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiUser extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at']; 
    protected $fillable = [
        'name',
        'created_by',
        'password',
        'token_valid_period',
        'is_active'
    ];

    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function tokenList()
    {
        return $this->tokens()
            ->select('token','token_expires_at','is_active')
            ->where('token_expires_at','>',date('Y-m-d H:i:s'))
            ->get();
    }

    public function deleteTokens()
    {
        foreach ($this->tokens as $token) {
            $token->delete();
        }
    }

    public function toggleUserActivation()
    {
        $this->is_active = !$this->is_active;
        $this->save();
        return $this->is_active;
    }
}
