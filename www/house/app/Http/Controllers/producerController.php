<?php

namespace App\Http\Controllers;

use App\Items;
use Illuminate\Http\Request;
use App\Channels;
use Illuminate\Support\Facades\Validator;
class producerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $channel = Channels::get();
        $list = array();
        if(count($channel) > 0){
            foreach ($channel as $ch){
                $list[] = ['id' => $ch->id, 'name' => $ch->name, 'created_at' => $ch->created_at];
            }
            return response()->json(['status' => 200, 'data' => $list]);
        }else{
            return response()->json(['status' => 201, 'msg' => 'not found']);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($v->fails())
        {
            return response()->json(['status' => 201, 'msg' => $v->errors()->first('name')]);
        }
        
        $channel = new Channels();
        $channel->name = $request->input('name');
        if($channel->save() === TRUE)
        {
            return response()->json(['status' => 200, 'msg' => 'Success']);
        }else{
            return response()->json(['status' => 301, 'msg' => 'Insert Error']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $channel = Channels::where('id', $id)->first();
        if(count($channel) > 0)
        {
            return response()->json(['status' => 200, 'data' => ['id' => $id, 'name' => $channel->name, 'created_at' => $channel->created_at]]);
        }else{
            return response()->json(['status' => 201, 'msg' => 'not found']);
        }
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
        $v = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($v->fails())
        {
            return response()->json(['status' => 201, 'msg' => $v->errors()->first('name')]);
        }

        $channel = Channels::find($id);
        if(count($channel) == 0)
        {
            return response()->json(['status' => 202, 'msg' => 'channel id not exists']);
        }
        
        $channel->name = $request->name;
    
        if($channel->save() === TRUE)
        {
            return response()->json(['status' => 200, 'msg' => 'Success']);
        }else{
            return response()->json(['status' => 301, 'msg' => 'Insert Error']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $channel = Channels::find($id);
        if(count($channel) == 0)
        {
            return response()->json(['status' => 201, 'msg' => 'channel id not exist']);
        }
        
        if($channel->delete() === TRUE)
        {
            return response()->json(['status' => 200, 'msg' => 'Success']);
        }else{
            return response()->json(['status' => 301, 'msg' => 'Insert Error']);
        }
    }
    
    public function addItem($id)
    {
        $channel = Channels::find($id);
        if(count($channel) == 0)
        {
            return response()->json(['status' => 201, 'msg' => 'channel id not exist']);
        }
        
        $item = new Items();
        $item->name = str_random(8);
        $item->value = random_int(0, 100);
        $item->channels_id = $id;

        if($item->save() === TRUE)
        {
            return response()->json(['status' => 200, 'msg' => 'Success']);
        }else{
            return response()->json(['status' => 301, 'msg' => 'Insert Error']);
        }
    }
}
