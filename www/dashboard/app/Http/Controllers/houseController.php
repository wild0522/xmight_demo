<?php

namespace App\Http\Controllers;

use App\Libraries\CallApiLibrary;
use Illuminate\Http\Request;

class houseController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = CallApiLibrary::get(config('app.API_URL').'producer/channel');
        return response()->json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = CallApiLibrary::post(config('app.API_URL').'producer/channel', [
            'name' => $request->name
        ]);

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = CallApiLibrary::get(config('app.API_URL').'producer/channel/'.$id);
        return response()->json($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $result = CallApiLibrary::patch(config('app.API_URL').'producer/channel/'.$id, [
            'name' => $request->name
        ]);
        
        return response()->json($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = CallApiLibrary::delete(config('app.API_URL').'producer/channel/'.$id);

        return response()->json($result);
    }

    public function addItem($id)
    {
        $result = CallApiLibrary::post(config('app.API_URL').'producer/channel/'.$id.'/item');
        return response()->json($result);
    }
    
    public function showRange($id, $form, $to)
    {
        $result = CallApiLibrary::get(config('app.API_URL').'consumer/channel/'.$id.'/'.$form.'/'.$to);
        return response()->json($result);
    }
}
