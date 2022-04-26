<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the users list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::role('user')->orderBy('id','DESC')->get();
        return view('dashboard.admin.usersList', compact('users'));
    }

    /**
     * Show the list of admin users.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getAdminUsers()
    {
        $admins = User::role('admin')->orderBy('id','DESC')->get();
        return view('dashboard.admin.adminList', compact('admins'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('dashboard.admin.userShow', compact( 'user' ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('dashboard.admin.userEditForm', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'email'      => 'required|email|max:256'
        ]);
        $user = User::find($id);
        $user->name       = $request->input('name');
        $user->email      = $request->input('email');
        $user->save();
        $request->session()->flash('message', 'Successfully updated user');
        return redirect()->route('users.index');
    }

    /**
     *  Remove user
     * 
     *  @param int $id 
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function remove( $id )
    {
        $user = User::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('adminUsers');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user){
            $user->delete();
        }
        return redirect()->route('users.index');
    }

    /**
     *  Show the form for editing the user.
     * 
     *  @param int $id
     *  @return \Illuminate\Contracts\Support\Renderable
     */
    public function editForm( $id )
    {
        $user = User::find($id);
        return view('dashboard.admin.userEditForm', compact('user'));
    }

}
