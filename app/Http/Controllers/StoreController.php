<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use Illuminate\Validation\Rule;
use Keygen;

class StoreController extends Controller
{

    public function index()
    {
        $ezpos_store_all = Store::where('is_active', true)->get();
        return view('store.create', compact('ezpos_store_all'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                    Rule::unique('stores')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);
        $input = $request->all();
        $input['is_active'] = true;
        store::create($input);
        return redirect('store')->with('message', 'Data inserted successfully');
    }

    public function edit($id)
    {
        $ezpos_store_data = store::findOrFail($id);
        return $ezpos_store_data;
    }
   
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                    Rule::unique('stores')->ignore($request->store_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);
        $input = $request->all();
        $ezpos_store_data = store::find($input['store_id']);
        $ezpos_store_data->update($input);
        return redirect('store')->with('message', 'Data updated successfully');
    }

    public function importstore(Request $request)
    {
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
        $upload=$request->file('file');
        $filePath=$upload->getRealPath();
        //open and read
        $file=fopen($filePath, 'r');
        $header= fgetcsv($file);
        $escapedHeader=[];
        //validate
        foreach ($header as $key => $value) {
            $lheader=strtolower($value);
            $escapedItem=preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while($columns=fgetcsv($file))
        {
            if($columns[0]=="")
                continue;
            foreach ($columns as $key => $value) {
                $value=preg_replace('/\D/','',$value);
            }
           $data= array_combine($escapedHeader, $columns);

           $store = store::firstOrNew([ 'name'=>$data['name'], 'is_active'=>true ]);
           $store->name = $data['name'];
           $store->phone = $data['phone'];
           $store->email = $data['email'];
           $store->address = $data['address'];
           $store->is_active = true;
           $store->save();
        }
        return redirect('store')->with('message', 'store imported successfully');
        
    }

    public function destroy($id)
    {
        $ezpos_store_data = store::find($id);
        $ezpos_store_data->is_active = false;
        $ezpos_store_data->save();
        return redirect('store')->with('not_permitted', 'Data deleted successfully');
    }
}
