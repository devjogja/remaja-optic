<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Store;
use App\Product_Store;
use App\Product;
use App\Adjustment;
use App\ProductAdjustment;
use App\StockCount;
use DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class AdjustmentController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('adjustment')){
            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own')
                $ezpos_adjustment_all = Adjustment::orderBy('id', 'desc')->where('user_id', Auth::id())->get();
            else
                $ezpos_adjustment_all = Adjustment::orderBy('id', 'desc')->get();
            return view('adjustment.index', compact('ezpos_adjustment_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function getProduct($id)
    {
        $ezpos_product_store_data = DB::table('products')
            ->join('product_store', 'products.id', '=', 'product_store.product_id')->where([ ['products.is_active', 1], ['product_store.store_id', $id] ])->select('product_store.qty', 'products.code', 'products.name')->get();
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        foreach ($ezpos_product_store_data as $product_store) 
        {
            $product_qty[] = $product_store->qty;
            $product_code[] =  $product_store->code;
            $product_name[] = $product_store->name;
        }

        $product_data[] = $product_code;
        $product_data[] = $product_name;
        $product_data[] = $product_qty;
        return $product_data;
    }

    public function ezposProductSearch(Request $request)
    {
        $product_code = explode("--", $request['data']);
        $ezpos_product_data = Product::where('code', $product_code[0])->first();

        $product[] = $ezpos_product_data->name;
        $product[] = $ezpos_product_data->code;
        $product[] = $ezpos_product_data->id;
        return $product;
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('adjustment')){
            $ezpos_store_list = Store::where('is_active', true)->get();
            return view('adjustment.create', compact('ezpos_store_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        if( isset($data['stock_count_id']) ){
            $ezpos_stock_count_data = StockCount::find($data['stock_count_id']);
            $ezpos_stock_count_data->is_adjusted = true;
            $ezpos_stock_count_data->save();
        }
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $data['reference_no'] = 'adr-' . date("Ymd") . '-'. date("his");
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/documents/adjustment', $documentName);
            $data['document'] = $documentName;
        }
        Adjustment::create($data);

        $ezpos_adjustment_data = Adjustment::latest()->first();
        $product_id = $data['product_id'];
        $qty = $data['qty'];
        $action = $data['action'];

        foreach ($product_id as $key => $pro_id) {
            $ezpos_product_data = Product::find($pro_id);
            $ezpos_product_store_data = Product_Store::where([
                ['product_id', $pro_id],
                ['store_id', $data['store_id'] ],
                ])->first();
            if($action[$key] == '-'){
                $ezpos_product_data->qty -= $qty[$key];
                $ezpos_product_store_data->qty -= $qty[$key];
            }
            elseif($action[$key] == '+'){
                $ezpos_product_data->qty += $qty[$key];
                $ezpos_product_store_data->qty += $qty[$key];
            }
            $ezpos_product_data->save();
            $ezpos_product_store_data->save();

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['adjustment_id'] = $ezpos_adjustment_data->id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['action'] = $action[$key];
            ProductAdjustment::create($product_adjustment);
        }
        return redirect('qty_adjustment')->with('message', 'Data inserted successfully');
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('adjustment')){
            $ezpos_adjustment_data = Adjustment::find($id);
            $ezpos_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
            $ezpos_store_list = Store::where('is_active', true)->get();
            return view('adjustment.edit', compact('ezpos_adjustment_data', 'ezpos_store_list', 'ezpos_product_adjustment_data'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/documents/adjustment', $documentName);
            $data['document'] = $documentName;
        }

        $ezpos_adjustment_data = Adjustment::find($id);
        $ezpos_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
        $product_id = $data['product_id'];
        $qty = $data['qty'];
        $action = $data['action'];

        foreach ($ezpos_product_adjustment_data as $key => $product_adjustment_data) {
            $old_product_id[] = $product_adjustment_data->product_id;
            $ezpos_product_data = Product::find($product_adjustment_data->product_id);
            $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $product_adjustment_data->product_id],
                    ['store_id', $ezpos_adjustment_data->store_id]
                ])->first();
            if($product_adjustment_data->action == '-'){
                $ezpos_product_data->qty += $product_adjustment_data->qty;
                $ezpos_product_store_data->qty += $product_adjustment_data->qty;
            }
            elseif($product_adjustment_data->action == '+'){
                $ezpos_product_data->qty -= $product_adjustment_data->qty;
                $ezpos_product_store_data->qty -= $product_adjustment_data->qty;
            }
            $ezpos_product_data->save();
            $ezpos_product_store_data->save();

            if( !(in_array($old_product_id[$key], $product_id)) )
                $product_adjustment_data->delete();
        }

        foreach ($product_id as $key => $pro_id) {
            $ezpos_product_data = Product::find($pro_id);
            $ezpos_product_store_data = Product_Store::where([
                ['product_id', $pro_id],
                ['store_id', $data['store_id'] ],
                ])->first();
            if($action[$key] == '-'){
                $ezpos_product_data->qty -= $qty[$key];
                $ezpos_product_store_data->qty -= $qty[$key];
            }
            elseif($action[$key] == '+'){
                $ezpos_product_data->qty += $qty[$key];
                $ezpos_product_store_data->qty += $qty[$key];
            }
            $ezpos_product_data->save();
            $ezpos_product_store_data->save();

            $product_adjustment['product_id'] = $pro_id;
            $product_adjustment['adjustment_id'] = $id;
            $product_adjustment['qty'] = $qty[$key];
            $product_adjustment['action'] = $action[$key];

            if(in_array($pro_id, $old_product_id)){
                ProductAdjustment::where([
                ['adjustment_id', $id],
                ['product_id', $pro_id]
                ])->update($product_adjustment);
            }
            else
                ProductAdjustment::create($product_adjustment);
        }
        $ezpos_adjustment_data->update($data);
        return redirect('qty_adjustment')->with('message', 'Data updated successfully');
    }

    public function destroy($id)
    {
        $ezpos_adjustment_data = Adjustment::find($id);
        $ezpos_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
        foreach ($ezpos_product_adjustment_data as $key => $product_adjustment_data) {
            $ezpos_product_data = Product::find($product_adjustment_data->product_id);
            $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $product_adjustment_data->product_id],
                    ['store_id', $ezpos_adjustment_data->store_id]
                ])->first();
            if($product_adjustment_data->action == '-'){
                $ezpos_product_data->qty += $product_adjustment_data->qty;
                $ezpos_product_store_data->qty += $product_adjustment_data->qty;
            }
            elseif($product_adjustment_data->action == '+'){
                $ezpos_product_data->qty -= $product_adjustment_data->qty;
                $ezpos_product_store_data->qty -= $product_adjustment_data->qty;
            }
            $ezpos_product_data->save();
            $ezpos_product_store_data->save();
            $product_adjustment_data->delete();
        }
        $ezpos_adjustment_data->delete();
        return redirect('qty_adjustment')->with('not_permitted', 'Data deleted successfully');
    }
}
