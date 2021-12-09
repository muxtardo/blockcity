<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Building;
use App\Models\UserBuilding;

class BuildingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }  

    public function buyHouse(Request $request)
    {
        $randomBuilding = Building::mint();

        $userBuilding = UserBuilding::create([
            'user_id' => Auth::id(),
            'building_id' => $randomBuilding->id,
            'name' => generateRandomWords(2),
            'image' => rand(1, $randomBuilding->images),
        ]);

        return response()->json([
            'success' => true,
            'userItem' => $userBuilding,
            'image' => $userBuilding->getImage(true),
            'name' => $userBuilding->name,
            'rarity' => $randomBuilding->rarity,
        ]);
        
    }
    
}
