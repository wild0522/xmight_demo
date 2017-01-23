<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libraries\CallApiLibrary;
use Illuminate\Support\Facades\Storage;
class ChartController extends Controller
{
    public function index()
    {
        if( ! Storage::disk('local')->exists('file.txt')){
            return redirect(config('app.API_URL').'../home');
        }
        
        $channels = CallApiLibrary::get(config('app.API_URL').'producer/channel');
        
        if($channels['status'] == 200)
        {
            return view('chart', ['channels' => $channels['data']]);
        }else{
            return view('chart', ['channels' => []]);
        }
    }
    
}
