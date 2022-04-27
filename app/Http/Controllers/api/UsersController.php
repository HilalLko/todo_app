<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\UserActivity;

use Carbon\Carbon;

class UsersController extends BaseController
{
    /**
     * @var User
     */
    protected $userModel;
    /**
     * @var UserActivity
     */
    protected $userActivityModel;

    public function __construct(User $userModel, UserActivity $userActivityModel)
    {
        $this->user_model = $userModel;
        $this->user_activity_model = $userActivityModel;
    }
    
    /**
     * @group  User Login
     * Login with Email
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors()->first(), 404);
        } 
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Credentials not match', 'Credentials not match', 404);
        }
        $apiToken = auth()->user()->createToken('HilalCypress')->plainTextToken;
        $user = auth()->user()->getUserObject($apiToken);
        return $this->sendResponse($user,'User successfully logged in.');
    }

    /**
     * @group  User Login
     * Create New User
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|unique:App\Models\User,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);
   
        if($validator->fails()){
            return $this->sendError($validator->errors()->first(), $validator->errors()->first(), 404);    
        }
        $newUser            = new $this->user_model;
        $newUser->name      = $request->name;
        $newUser->email     = $request->email;
        $newUser->password  = bcrypt($request->password);
        if ($newUser->save()) {
            $apiToken =  $user->createToken('HilalCypress')->plainTextToken;
            $return = $user->getUserObject($apiToken);
            return $this->sendResponse($return,'User successfully registered.');
        }
        return $this->sendError('Unable to register user', 'Unable to register user',404);
    }

    /**
     * @group  User Login
     * Logout User
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userLogout(Request $request)
    {
        $uid   = $request->user()->id;
        if (auth()->user()->tokens()->delete()) {
            return $this->sendResponse('You are logged out successfully', 'You are logged out successfully');
        } 
        return $this->sendError(trans('messages.server_error'), trans('messages.server_error'), 401);
    }

    /**
     * @group  User Activities
     * Get List of User Activities
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userActivities(Request $request)
    {
        $polArr = array();
        $result = array();
        $date_range = $request->date_range ?? '';
        $userActivities = $this->user_activity_model::where('user_id',$request->user()->id)
        ->when($date_range != "", function ($q) use ($date_range) {
            $rangeExploaded = explode('-', $date_range);
            $date1 = Carbon::createFromFormat('d/m/Y', $rangeExploaded[0]);
            $date2 = Carbon::createFromFormat('d/m/Y', $rangeExploaded[1]);
            return $q->whereBetween('on_date', [$date1->format('Y-m-d'),$date2->format('Y-m-d')]);
        })
        ->paginate(10);
        foreach ($userActivities as $userActivity) {
            $actArr[] = $userActivity->JSONObject();
            $result['activities'] = $actArr;
        }     
        $result['pagination']['count'] = $userActivities->total();
        $result['pagination']['currentPage'] = $userActivities->currentPage();
        $result['pagination']['lastPage'] = $userActivities->lastPage();
        $result['pagination']['total'] = count($userActivities->items());
        return $this->sendResponse($result,'Account Top-up successful');
    }
}
