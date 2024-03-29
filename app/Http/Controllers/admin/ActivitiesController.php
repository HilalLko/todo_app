<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\GlobalActivity;
use App\Models\UserActivity;
use App\Jobs\CreateUserActivities;

use Carbon\Carbon;

class ActivitiesController extends Controller
{
    /**
     * Function responsible for setting admin dashboard
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View ||
     * @return \Illuminate\Http\JsonResponse 
     */
    public function index(Request $request)
    {
        if($request->ajax()) {  
            $data = GlobalActivity::query()
                ->whereBetween('on_date', [$request->start,$request->end])
                ->get(['id',\DB::raw('activity_title as title, on_date as start, on_date as end')]);
            return response()->json($data);
        }
        return view('dashboard.homepage');
    }

    /**
     * Function responsible for getting Global Activties list
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function getGlobalActivities(Request $request)
    {
        $activities = GlobalActivity::orderBy('id','DESC')->paginate(5);
        return view('dashboard.activities.globalList', compact('activities'));
    }    

    /**
     * Function to store Activity.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addGlobalActivity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_title'        => 'required|string|max:280',
            'activity_description'  => 'required',
        ]);
        if ($validator->fails()) {
            $return = array('code' => 400, 'message' => $validator->errors()->first(), 'data' => []);
            return response()->json($return);
        } else {
            $activityCount = GlobalActivity::where('on_date',$request->on_date)->count();
            if ($activityCount < 4) {
                $activity = new GlobalActivity;
                $activity->activity_title       = $request->activity_title;
                $activity->activity_description = $request->activity_description;
                $activity->on_date              = $request->on_date;
                if ($request->hasFile('activity_image')) {
                    $name = $request->file('activity_image')->getClientOriginalName();
                    $path = $request->file('activity_image')->store('public/images');
                    $activity->activity_image = $path;
                }    
                if ($activity->save()) {
                    $user_id = $request->user_id ?? 0;   
                    CreateUserActivities::dispatch($activity,$user_id);   
                    $return = array('code' => 200, 'message' => 'Ok', 'data' => $activity); 
                } else {
                   $return = array('code' => 400, 'message' => 'Something went wrong', 'data' => []);
                }    
            } else {
                $return = array('code' => 400, 'message' => 'Can only add 4 activities for a day', 'data' => []);
            }            
            return response()->json($return);
        }
    }

    /**
     * Function responsible for getting User Activties list
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function getUserActivities(Request $request)
    {
        $activities = UserActivity::orderBy('id','DESC')
        ->when($request->user != "", function ($q) use ($request) {
            return $q->where('user_id', $request->user);
        })
        ->when($request->title != "", function ($q) use ($request) {
            return $q->where('activity_title', 'like', '%'. $request->title. '%');
        })
        ->paginate(5);
        if($request->ajax()) {
            return view('dashboard.activities.usersList_partial', ['activities' => $activities]);
        }
        return view('dashboard.activities.usersList', compact('activities'));
    }


    /**
     * Function responsible for getting Global Activty details
     * @param int $activity
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGlobalActivity(Request $request,$activity=0)
    {
        if ($activity > 0) {
            $global = GlobalActivity::find($activity);
            $return = array('code' => 200, 'message' => 'Ok', 'data' => $global); 
        } else {
            $return = array('code' => 400, 'message' => 'Something went wrong', 'data' => []);
        }
        return response()->json($return);
    }


    /**
     * Function responsible for deleting Global Activty
     * @param int $activity
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyGlobalActivity(Request $request,$activity=0)
    {
        if ($activity > 0) {
            $global = GlobalActivity::find($activity);
            if($global){
                $global->delete();
                $request->session()->flash('message', 'Activity deleted');
            }
            return redirect()->route('admin.global_activities');
        }
        $request->session()->flash('message', 'Invalid activity id');
        return redirect()->route('admin.global_activities'); 
    }

    /**
     * Function responsible for deleting User Activty
     * @param int $activity
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyUserActivity(Request $request,$activity=0)
    {
        if ($activity > 0) {
            $global = UserActivity::find($activity);
            if($global){
                $global->delete();
                $request->session()->flash('message', 'Activity deleted');
            }
            return redirect()->route('admin.global_activities');
        }
        $request->session()->flash('message', 'Invalid activity id');
        return redirect()->route('admin.global_activities'); 
    }


    /**
     * Function responsible for showing edit User Activty Page/form
     * @param int $activity
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUserActivity(Request $request,$activity=0)
    {
        if ($activity > 0) {
            $userActivity = UserActivity::find($activity);
            return view('dashboard.activities.usersactivityEdit', compact('userActivity'));
        }
        $request->session()->flash('message', 'Invalid activity id');
        return redirect()->route('admin.user_activities'); 
    }

    /**
     * Function responsible for showing edit User Activty Page/form
     * @param int $activity
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserActivity(Request $request,$activity=0)
    {
        if ($activity > 0) {
            $userActivity = UserActivity::find($activity);
            $userActivity->activity_title       = $request->activity_title;
            $userActivity->activity_description = $request->activity_description;
            $userActivity->on_date              = $request->on_date;
            $userActivity->save();
            $request->session()->flash('message', 'User activity updated');
            return redirect()->route('admin.user_activities');
        }
        $request->session()->flash('message', 'Invalid activity id');
        return redirect()->route('admin.user_activities'); 
    }

}
