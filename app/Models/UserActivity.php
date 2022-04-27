<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_title', 'activity_description', 'activity_image','on_date','user_id','global_activity_id',
    ];

    /**
     * Get the Global Activity that owns the User Activity.
     */
    public function globalActivity()
    {
        return $this->belongsTo(GlobalActivity::class);
    }

    /**
     * Get the User that owns the User Activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function JSONObject()
    {
        $send = array();
        $send['activity_id'] = $this->id;
        $send['activity_title'] = $this->activity_title;
        $send['activity_description'] = $this->activity_description;
        $send['on_date'] = $this->on_date;
        $send['user_assigned'] = ($this->global_activity_id) ? true : false;
        return $send;
    }    
}