<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Team;
use Storage;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->role == '4') {
            $teamId = Team::where('name','Innovations')->first('team_id');
            $result = Inventory::where('inv_team',$teamId->team_id)->orderBy('inv_id','desc')->get();
        } else {
            $result = Inventory::orderBy('inv_id','desc')->get();
        }
        $i = 0;
        foreach($result as $val) {
            $data[$i]['inv_serial'] = $val->inv_serial;
            $data[$i]['inv_type'] = $val->inv_type;
            $data[$i]['inv_brand'] = $val->inv_brand;
            $data[$i]['inv_specs'] = $val->inv_specs;
            $data[$i]['inv_team'] = $val->inv_team;
            $data[$i]['inv_invoice'] = config('app.url').'storage/invoices/'.$val->inv_invoice;
            $data[$i]['inv_id'] = $val->inv_id;
            $data[$i]['GST'] = $val->GST;
            $i++;
        }
        return response()->json([
            'data' => $data,
            'message' => 'Success'
        ], 200);
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
        $validated = $request->validate([
            'select_inv_item' => 'required',
            'serialno' => 'required',
            'brandname' => 'required',
            'item_spec' => 'required',
            'select_team2' => 'required',
            'invoice' => 'required',
        ]);
        $inventory = new Inventory;
        $inventory->inv_type = $validated['select_inv_item'];
        $inventory->inv_serial = $validated['serialno'];
        $inventory->inv_brand = $validated['brandname'];
        $inventory->inv_specs = $validated['item_spec'];
        $inventory->inv_team = $validated['select_team2'];
        if($request->hasFile('invoice')){
            // Get filename with the extension
            $filenameWithExt = $request->file('invoice')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $request->file('invoice')->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = 'inventory'.'_'.time().'.'.$extension;
            // Upload Image
            $path = $request->file('invoice')->storeAs('public/invoices',$fileNameToStore);

            $inventory->inv_invoice = $fileNameToStore ;
        }
        $result = $inventory->save();
        if($result) {
            return response()->json([
                'status' => true,
                'message' => 'Success'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Error'
            ], 200);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $inventory = Inventory::where('inv_id',$id)->first();
        Storage::delete('public/invoices/'.$inventory->inv_invoice);
        $result = $inventory->delete();
            if($result) {
                return response()->json([
                    'status' => true,
                    'message' => 'Success'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Error'
                ], 200);
            }
    }
}
