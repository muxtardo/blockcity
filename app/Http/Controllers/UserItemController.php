<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\UserItem;
use App\Models\User;
use App\Models\Item;

class UserItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }  

    public function buyHouse(Request $request)
    {
        $randomItem = Item::getRandomHouse();

        $userItem = UserItem::create([
            'user_id' => Auth::id(),
            'item_id' => $randomItem->id,
            'item_status_id' => $randomItem->id,
            'quantity' => 1,
            'level' => 1,
            'earnings' => 1,
            'last_claim' => 1,
            'name' => generateRandomWords(2),
        ]);

        return response()->json([
            'success' => true,
            'userItem' => $userItem,
        ]);
        
    }

    
}
