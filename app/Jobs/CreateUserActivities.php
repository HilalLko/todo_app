<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use App\Models\UserActivity;
class CreateUserActivities implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The GlobalActivity instance.
     *
     * @var \App\Models\GlobalActivity
     */
    protected $activity;
    /**
     * User Id.
     *
     * @var Int $user_id
     */
    protected $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($activity,$user_id)
    {
        $this->activity = $activity;
        $this->user_id  = $user_id;  
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->user_id > 0){
            $userActivity = new UserActivity;
            $userActivity->global_activity_id    = $this->activity->id;
            $userActivity->activity_title        = $this->activity->activity_title;
            $userActivity->activity_description  = $this->activity->activity_description;
            $userActivity->activity_image        = $this->activity->activity_image;
            $userActivity->on_date               = $this->activity->on_date;
            $userActivity->user_id               = $this->user_id;
            $userActivity->save();
        } else {
            $users = User::role('user')->get();
            foreach ($users as $user) {
               $userActivity = new UserActivity;
               $userActivity->global_activity_id    = $this->activity->id;
               $userActivity->activity_title        = $this->activity->activity_title;
               $userActivity->activity_description  = $this->activity->activity_description;
               $userActivity->activity_image        = $this->activity->activity_image;
               $userActivity->on_date               = $this->activity->on_date;
               $userActivity->user_id               = $user->id;
               $userActivity->save();
            }
        }   
    }
}
