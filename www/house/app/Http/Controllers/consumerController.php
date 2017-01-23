<?php

namespace App\Http\Controllers;

use App\Channels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class consumerController extends Controller
{
    public function showRange($id, $from, $to)
    {
        $channel = Channels::find($id);
        if(count($channel) == 0)
        {
            return response()->json(['status' => 201, 'msg' => 'channel id not exist.']);
        }
        
        $items = $channel->items();
        if($from > 0)
        {
            $items->where('created_at', '>=', date('Y-m-d H:i:s', $from));
        }
        
        if($to > 0)
        {
            $items->where('created_at', '<=', date('Y-m-d H:i:s', $to));
        }

        $items = $items->select('*', DB::raw('UNIX_TIMESTAMP(created_at) AS created_at'))->get();
        
        if(count($items) == 0)
        {
            return response()->json(['status' => 201, 'msg' => 'havent item.']);
        }
        return response()->json(['status' => 200, 'data' => $items->toArray()]);
    }
}
