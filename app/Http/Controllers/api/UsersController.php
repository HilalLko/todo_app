<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
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
    public function loginWithEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email:rfc,dns',
            'password' => 'required|string|min:6'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), $validator->errors()->first(), 404);
        } 
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Credentials not match', 404);
        }
        $apiToken = auth()->user()->createToken('HilalCypress')->plainTextToken;
        $user = auth()->user()->getUserObject($apiToken);
        return $this->sendResponse($user,'User successfully logged in.');
    }

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
   
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('LaravelSanctumAuth')->plainTextToken;
        $success['name'] =  $user->name;
   
        return $this->handleResponse($success, 'User successfully registered!');
    }
}
