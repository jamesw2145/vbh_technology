<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Input;

use App\Api\Markets;
use App\Api\MarketCoins;
use App\Api\Candlesticks;
use App\Api\Movingaverages;

// use App\Database\Customer;
// use App\Business\Order;

class RestAPIController extends Controller
{
    public function markets(Request $request) {
        $data = $request->json()->all();        
        $markets = new Markets();
        return response()->json($markets->json($data));
    }

    public function marketcoins(Request $request) {
        $data = $request->json()->all();        
        $marketcoins = new MarketCoins();
        return response()->json($marketcoins->json($data));
    }

    public function candlesticks(Request $request) {
    	$data = $request->json()->all();
    	$candlesticks = new Candlesticks();
        return response()->json($candlesticks->json($data));
    }

    public function movingaverages(Request $request) {
    	$data = $request->json()->all();
    	$movingaverages = new Movingaverages();
    	return response()->json($movingaverages->json($data));
    }
}
