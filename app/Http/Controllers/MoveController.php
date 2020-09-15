<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Move;
use App\Sale;

class MoveController extends Controller
{
	/**
     * Create a move
     */
    public function create(Request $request)
    {
        $request->validate([
            'coin' => 'required|integer|exists:App\Coin,id',
            'tag' => 'required|string',
            'type' => 'required|string|in:income,outflow',
            'amount' => 'required'
        ]);

        Move::create([
            'coin_id' => $request->coin,
            'user_id' => auth()->user()->id,
            'tag' => $request->tag,
            'type' => $request->type,
            'amount' => $request->amount
        ]);

        return response()->json([
            'message' => 'Successfully created move!'
        ], 201);
    }
    /**
     * Show all user`s moves
     */
    public function get(Request $request)
    {
    	return Move::join('coins','coins.id','=','money_moves.coin_id')
    			   ->select('money_moves.*','coins.name as coin')
    			   ->where('user_id','=',auth()->user()->id)
    			   ->orderBy('created_at','desc')
    			   ->get();
    }
    /**
     * Show moves by date
     */
    public function getByDate($date, Request $request)
    {
    	return Move::join('coins','coins.id','=','money_moves.coin_id')
    			   ->select('money_moves.*','coins.name as coin')
    			   ->where('user_id','=',auth()->user()->id)
    			   ->where('money_moves.created_at','=',$date)
    			   ->orderBy('money_moves.created_at','desc')
    			   ->get();
    }
    /**
     * Show moves by tag
     */
    public function getByTag($tag, Request $request)
    {
    	return Move::join('coins','coins.id','=','money_moves.coin_id')
    			   ->select('money_moves.*','coins.name as coin')
    			   ->where('user_id','=',auth()->user()->id)
    			   ->where('tag','LIKE','%'.$tag.'%')
    			   ->orderBy('money_moves.created_at','desc')
    			   ->get();
    }
    /**
     * Show a move
     */
    public function show($id)
    {
    	$move = Move::find($id);
    	if($move->user_id = auth()->user()->id) return $move;
    	else return response()->json([
	            					'message' => 'Unauthorized!'
	        					], 401);
    }
    /**
     * Edit a Move
     */
    public function edit($id, Request $request)
    {
    	$move = Move::find($id);
    	$request->validate([
            'tag' => 'string',
            'type' => 'string|in:income,outflow',
			'coin' => 'integer'
        ]);
		if(isset($request->tag)){
		    $move->tag = $request->tag; 
		}
		if(isset($request->type)){
		    $move->type = $request->type; 
		}
		if(isset($request->coin)){
		    $move->coin_id = $request->coin; 
		}
		if(isset($request->amount)){
		    $move->amount = $request->amount; 
		}
        if($move->user_id = auth()->user()->id){
        	$move->save();
        }
        return $move;
    }

    /**
     * Delete a move
     */
    public function delete($id)
    {
    	$move = Move::find($id);
        if(isset($move) && $move->user_id == auth()->user()->id){
        	$move->delete();
	        return response()->json([
	            'message' => 'Successfully deleted!'
	        ], 201);
    	}else{
    		return response()->json([
	            'message' => 'Unauthorized to deleted!'
	        ], 401);
    	}
    }

    /**
     * Show all user`s moves
     */
    public function balance(Request $request)
    {
        $incomeMoves = Move::SelectRaw('sum(amount) as val')
                           ->where('user_id','=',auth()->user()->id)
                           ->where('type','=','income')
                           ->get(1);
        $incomeSales = Sale::SelectRaw('sum(amount) as val')
                           ->where('user_id','=',auth()->user()->id)
                           ->where('type','=','income')
                           ->get(1);
        $outflowMoves = Move::SelectRaw('sum(amount) as val')
                           ->where('user_id','=',auth()->user()->id)
                           ->where('type','=','outflow')
                           ->get(1);
        $outflowSales = Sale::SelectRaw('sum(amount) as val')
                           ->where('user_id','=',auth()->user()->id)
                           ->where('type','=','outflow')
                           ->get(1);
        $result = array('moves(+):' => $incomeMoves[0]->val, 
                   'sales(+):' => $incomeSales[0]->val, 
                   'moves(-):' => $outflowMoves[0]->val, 
                   'sales(-):' => $outflowSales[0]->val,
                    'total:'  => $incomeSales[0]->val + $incomeMoves[0]->val 
                                 - $outflowSales[0]->val - $outflowMoves[0]->val);                   
        return response()->json($result);
    }
}
