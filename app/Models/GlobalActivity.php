<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_title', 'activity_description', 'activity_image','on_date',
    ];

    /**
     * Get the User Activites for the Global Activity.
     */
    public function UserActivities()
    {
        return $this->hasMany(UserActivity::class);
    }
}
