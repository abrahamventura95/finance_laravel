<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
	/**
     * Show all users
     */
    public function users(Request $request)
    {
    	return User::orderBy('type','desc')->get();
    }
    /**
     * Show a user
     */
    public function show($id)
    {
        return User::find($id);
    }
    /**
     * Edit a user
     */
    public function edit($id, Request $request)
    {
    	$user = User::find($id);
    	$request->validate([
            'password' => 'string',
            'name' => 'string',
            'gender' => 'string|in:male,female,other',
			'coin' => 'integer'
        ]);
        if(isset($request->password)){
		    $user->password = bcrypt($request->password); 
		}
		if(isset($request->name)){
		    $user->name = $request->name; 
		}
		if(isset($request->gender)){
		    $user->gender = $request->gender; 
		}
		if(isset($request->coin)){
		    $user->coin = $request->coin; 
		}
        if($user->email = auth()->user()->email){
        	$user->save();
        }
        return $user;
    }

    /**
     * Delete a user
     */
    public function delete($id)
    {
    	$user = User::find($id);
        if($user->email = auth()->user()->email){
        	$user->delete();
	        return response()->json([
	            'message' => 'Successfully deleted user!'
	        ], 201);
    	}else{
    		return response()->json([
	            'message' => 'Unauthorized to deleted user!'
	        ], 401);
    	}
    }
}
