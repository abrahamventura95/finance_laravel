<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Coin;

class CoinController extends Controller
{
    /**
     * Create a coin
     */
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:coins',
            'cu_conver' => 'required'
        ]);

        if(auth()->user()->type === 'business'){
        	Coin::create([
		        'name' => $request->name,
		        'cu_conver' => $request->cu_conver
		    ]);

		    return response()->json([
		        'message' => 'Successfully created move!'
		    ], 201);
        }else{
        	return response()->json([
		        'message' => 'Unauthorized!'
		    ], 401);
        }
        
    }
    /**
     * Show all user`s coins
     */
    public function get(Request $request)
    {
    	return Coin::orderBy('created_at','desc')
    			   ->get();
    }
    /**
     * Edit a coin
     */
    public function edit($id, Request $request)
    {
    	$coin = Coin::find($id);
		if(isset($request->cu_conver)){
		    $coin->cu_conver = $request->cu_conver; 
		}
		if(auth()->user()->type === 'business') $coin->save();
        
        return $coin;
    }
    /**
     * Delete a coin
     */
    public function delete($id)
    {
    	$coin = Coin::find($id);
        if(auth()->user()->type === 'business'){
        	$coin->delete();
	        return response()->json([
	            'message' => 'Successfully deleted!'
	        ], 201);
    	}else{
    		return response()->json([
	            'message' => 'Unauthorized to deleted!'
	        ], 401);
    	}
    }
}
