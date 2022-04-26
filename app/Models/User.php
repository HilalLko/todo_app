<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;
    use HasFactory;
    use HasApiTokens;
    
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

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

    protected $dates = [
        'deleted_at'
    ];

    protected $attributes = [ 
        'menuroles' => 'user',
    ];

    
    /**
     * User JSON Object     
     * @return array
     */
    public function getUserObject($authToken="")
    {
        $udata = array();
        $send = array();
        $udata['user_id'] = $this->id;
        $udata['full_name'] = $this->name;
        $udata['email'] = $this->email;
        $udata['role'] = $this->menuroles;
        if ($authToken != "") {
            $udata['auth_token'] = $authToken;
        }
        $send['user_data'] = $udata;
        return $send;
    }
}
