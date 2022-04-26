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
}