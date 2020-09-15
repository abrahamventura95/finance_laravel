<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Sale;

class SaleController extends Controller
{
    /**
     * Create a sale
     */
    public function create(Request $request)
    {
        $request->validate([
            'coin' => 'required|integer|exists:App\Coin,id',
            'tag' => 'required|string',
            'type' => 'required|string|in:income,outflow',
            'amount' => 'required',
            'product' => 'required|string',
            'quantity' => 'required|integer'
        ]);

        Sale::create([
            'coin_id' => $request->coin,
            'user_id' => auth()->user()->id,
            'tag' => $request->tag,
            'type' => $request->type,
            'amount' => $request->amount,
            'product' => $request->product,
            'quantity' => $request->quantity
        ]);

        return response()->json([
            'message' => 'Successfully created move!'
        ], 201);
    }
    /**
     * Show all user`s sales
     */
    public function get(Request $request)
    {
    	return Sale::join('coins','coins.id','=','money_sales.coin_id')
    			   ->select('money_sales.*','coins.name as coin')
    			   ->where('user_id','=',auth()->user()->id)
    			   ->orderBy('created_at','desc')
    			   ->get();
    }
    /**
     * Show sales by date
     */
    public function getByDate($date, Request $request)
    {
    	return Sale::join('coins','coins.id','=','money_sales.coin_id')
    			   ->select('money_sales.*','coins.name as coin')
    			   ->where('user_id','=',auth()->user()->id)
    			   ->where('money_sales.created_at','=',$date)
    			   ->orderBy('money_sales.created_at','desc')
    			   ->get();
    }
    /**
     * Show sales by tag
     */
    public function getByTag($tag, Request $request)
    {
    	return Sale::join('coins','coins.id','=','money_sales.coin_id')
    			   ->select('money_sales.*','coins.name as coin')
    			   ->where('user_id','=',auth()->user()->id)
    			   ->where('tag','LIKE','%'.$tag.'%')
    			   ->orderBy('money_sales.created_at','desc')
    			   ->get();
    }
    /**
     * Show a sale
     */
    public function show($id)
    {
    	$sale = Sale::find($id);
    	if($sale->user_id = auth()->user()->id) return $sale;
    	else return response()->json([
	            					'message' => 'Unauthorized!'
	        					], 401);
    }
    /**
     * Edit a sale
     */
    public function edit($id, Request $request)
    {
    	$sale = Sale::find($id);
    	$request->validate([
            'tag' => 'string',
            'type' => 'string|in:income,outflow',
            'product' => 'string',
            'quantity' => 'integer',
			'coin' => 'integer'
        ]);
		if(isset($request->tag)){
		    $sale->tag = $request->tag; 
		}
		if(isset($request->type)){
		    $sale->type = $request->type; 
		}
		if(isset($request->coin)){
		    $sale->coin_id = $request->coin; 
		}
		if(isset($request->amount)){
		    $sale->amount = $request->amount; 
		}
		if(isset($request->product)){
		    $sale->product = $request->product; 
		}
		if(isset($request->quantity)){
		    $sale->quantity = $request->quantity; 
		}
        if($sale->user_id = auth()->user()->id){
        	$sale->save();
        }
        return $sale;
    }
    /**
     * Delete a sale
     */
    public function delete($id)
    {
    	$sale = Sale::find($id);
        if(isset($sale) && $sale->user_id == auth()->user()->id){
        	$sale->delete();
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
