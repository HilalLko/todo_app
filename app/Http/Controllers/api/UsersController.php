<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class UsersController extends BaseController
{
    /**
     * @var User
     */
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->user_model = $userModel;
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
     * @group  User Register/Create User
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
     * @group  User Loout
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
}
