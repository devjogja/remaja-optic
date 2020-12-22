<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReturnPurchase;
use App\PurchaseProductReturn;
use App\Store;
use App\Tax;
use App\Supplier;
use App\Product;
use App\Product_Store;
use DB;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;

class ReturnPurchaseController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('return-purchase-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            $general_setting =  \App\GeneralSetting::latest()->first();
            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own')
                $ezpos_return_all = ReturnPurchase::orderBy('id', 'desc')->where('user_id', Auth::id())->get();
            else
                $ezpos_return_all = ReturnPurchase::orderBy('id', 'desc')->get();
            return view('return_purchase.index', compact('ezpos_return_all', 'all_permission'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }


    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('return-purchase-add')){
            $ezpos_store_list = Store::where('is_active',true)->get();
            $ezpos_supplier_list = Supplier::where('is_active',true)->get();
            $ezpos_tax_list = Tax::where('is_active',true)->get();
            return view('return_purchase.create', compact('ezpos_store_list', 'ezpos_supplier_list', 'ezpos_tax_list'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function getProduct($id)
    {
        $ezpos_product_store_data = DB::table('products')->join('product_store', 'products.id', '=', 'product_store.product_id')->where([
                ['product_store.store_id', $id],
                ['products.is_active', true] ])->get();
        
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        foreach ($ezpos_product_store_data as $product_store) 
        {
            $product_qty[] = $product_store->qty;
            $ezpos_product_data = Product::find($product_store->product_id);
            $product_code[] =  $ezpos_product_data->code;
            $product_name[] = $ezpos_product_data->name;
            $product_type[] = $ezpos_product_data->type;
        }

        $product_data[] = $product_code;
        $product_data[] = $product_name;
        $product_data[] = $product_qty;
        $product_data[] = $product_type;
        return $product_data;
    }

    public function ezposProductSearch(Request $request)
    {
        $product_code = explode("--", $request['data']);
        $ezpos_product_data = Product::where('code', $product_code[0])->first();

        $product[] = $ezpos_product_data->name;
        $product[] = $ezpos_product_data->code;
        $product[] = $ezpos_product_data->cost;
        
        if ($ezpos_product_data->tax_id) {
            $ezpos_tax_data = Tax::find($ezpos_product_data->tax_id);
            $product[] = $ezpos_tax_data->rate;
            $product[] = $ezpos_tax_data->name;
        } else {
            $product[] = 0;
            $product[] = 'No Tax';
        }
        $product[] = $ezpos_product_data->tax_method;
        $product[] = $ezpos_product_data->unit;
        $product[] = $ezpos_product_data->id;
        return $product;
    }

    public function store(Request $request)
    {
        $data = $request->except('document');
        //return dd($data);
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $data['reference_no'] = 'prr-' . date("Ymd") . '-'. date("his");
        $data['user_id'] = Auth::id();
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/return_purchase/documents', $documentName);
            $data['document'] = $documentName;
        }

        ReturnPurchase::create($data);
        $ezpos_return_data = ReturnPurchase::latest()->first();
        if($data['supplier_id']){
            $ezpos_supplier_data = Supplier::find($data['supplier_id']);
            //collecting male data
            $mail_data['email'] = $ezpos_supplier_data->email;
            $mail_data['reference_no'] = $ezpos_return_data->reference_no;
            $mail_data['total_qty'] = $ezpos_return_data->total_qty;
            $mail_data['total_price'] = $ezpos_return_data->total_cost;
            $mail_data['order_tax'] = $ezpos_return_data->order_tax;
            $mail_data['order_tax_rate'] = $ezpos_return_data->order_tax_rate;
            $mail_data['grand_total'] = $ezpos_return_data->grand_total;
        }

        $product_id = $data['product_id'];
        $qty = $data['qty'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];

        foreach ($product_id as $key => $pro_id) {
            $ezpos_product_data = Product::find($pro_id);
            $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $pro_id],
                    ['store_id', $data['store_id'] ],
                    ])->first();

            $ezpos_product_data->qty -=  $qty[$key];
            $ezpos_product_store_data->qty -= $qty[$key];

            $ezpos_product_data->save();
            $ezpos_product_store_data->save();

            $mail_data['products'][$key] = $ezpos_product_data->name;
            $mail_data['unit'][$key] = $purchase_unit[$key];
            $mail_data['qty'][$key] = $qty[$key];
            $mail_data['total'][$key] = $total[$key];
            PurchaseProductReturn::insert(
                ['return_id' => $ezpos_return_data->id, 'product_id' => $pro_id, 'qty' => $qty[$key], 'unit' => $purchase_unit[$key], 'net_unit_cost' => $net_unit_cost[$key], 'discount' => $discount[$key], 'tax_rate' => $tax_rate[$key], 'tax' => $tax[$key], 'total' => $total[$key] ]
            );
        }
        $message = 'Return created successfully';
        if($data['supplier_id']){
            try{
                Mail::send( 'mail.return_details', $mail_data, function( $message ) use ($mail_data)
                {
                    $message->to( $mail_data['email'] )->subject( 'Return Details' );
                });
            }
            catch(\Exception $e){
                $message = 'Return created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        return redirect('return-purchase')->with('message', $message);
    }

    public function productReturnData($id)
    {
        $ezpos_product_return_data = PurchaseProductReturn::where('return_id', $id)->get();
        foreach ($ezpos_product_return_data as $key => $product_return_data) {
            $product = Product::find($product_return_data->product_id);

            $product_return[0][$key] = $product->name . '[' . $product->code . ']';
            $product_return[1][$key] = $product_return_data->qty;
            $product_return[2][$key] = $product_return_data->unit;
            $product_return[3][$key] = $product_return_data->tax;
            $product_return[4][$key] = $product_return_data->tax_rate;
            $product_return[5][$key] = $product_return_data->discount;
            $product_return[6][$key] = $product_return_data->total;
        }
        return $product_return;
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('return-purchase-edit')){
            $ezpos_supplier_list = Supplier::where('is_active',true)->get();
            $ezpos_store_list = Store::where('is_active',true)->get();
            $ezpos_tax_list = Tax::where('is_active',true)->get();
            $ezpos_return_data = ReturnPurchase::find($id);
            $ezpos_product_return_data = PurchaseProductReturn::where('return_id', $id)->get();
            return view('return_purchase.edit',compact('ezpos_supplier_list', 'ezpos_store_list', 'ezpos_tax_list', 'ezpos_return_data','ezpos_product_return_data'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/return_purchase/documents', $documentName);
            $data['document'] = $documentName;
        }

        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $ezpos_return_data = ReturnPurchase::find($id);
        $ezpos_product_return_data = PurchaseProductReturn::where('return_id', $id)->get();

        $product_id = $data['product_id'];
        $qty = $data['qty'];
        $purchase_unit = $data['purchase_unit'];
        $net_unit_cost = $data['net_unit_cost'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];

        foreach ($ezpos_product_return_data as $key => $product_return_data) {
            $old_product_id[] = $product_return_data->product_id;
            $ezpos_product_data = Product::find($product_return_data->product_id);                

            $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $product_return_data->product_id],
                    ['store_id', $ezpos_return_data->store_id],
                    ])->first();

            $ezpos_product_data->qty += $product_return_data->qty;
            $ezpos_product_store_data->qty += $product_return_data->qty;
            $ezpos_product_data->save();
            $ezpos_product_store_data->save();

            if( !(in_array($old_product_id[$key], $product_id)) )
                $product_return_data->delete();
        }
        foreach ($product_id as $key => $pro_id) {
            $ezpos_product_data = Product::find($pro_id);
            $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $pro_id],
                    ['store_id', $data['store_id'] ],
                    ])->first();

            $ezpos_product_data->qty -=  $qty[$key];
            $ezpos_product_store_data->qty -= $qty[$key];

            $ezpos_product_data->save();
            $ezpos_product_store_data->save();

            $mail_data['products'][$key] = $ezpos_product_data->name;
            if($purchase_unit[$key])
                $mail_data['unit'][$key] = $purchase_unit[$key];
            else
                $mail_data['unit'][$key] = '';
            $mail_data['qty'][$key] = $qty[$key];
            $mail_data['total'][$key] = $total[$key];

            $product_return['return_id'] = $id ;
            $product_return['product_id'] = $pro_id;
            $product_return['qty'] = $qty[$key];
            $product_return['unit'] = $purchase_unit[$key];
            $product_return['net_unit_cost'] = $net_unit_cost[$key];
            $product_return['discount'] = $discount[$key];
            $product_return['tax_rate'] = $tax_rate[$key];
            $product_return['tax'] = $tax[$key];
            $product_return['total'] = $total[$key];

            if((in_array($pro_id, $old_product_id))){
                PurchaseProductReturn::where([
                    ['return_id', $id],
                    ['product_id', $pro_id]
                    ])->update($product_return);
            }
            else
                PurchaseProductReturn::create($product_return);
        }
        $ezpos_return_data->update($data);
        $message = 'Return updated successfully';
        if($data['supplier_id']) {
            $ezpos_supplier_data = Supplier::find($data['supplier_id']);
            //collecting male data
            $mail_data['email'] = $ezpos_supplier_data->email;
            $mail_data['reference_no'] = $ezpos_return_data->reference_no;
            $mail_data['total_qty'] = $ezpos_return_data->total_qty;
            $mail_data['total_price'] = $ezpos_return_data->total_cost;
            $mail_data['order_tax'] = $ezpos_return_data->order_tax;
            $mail_data['order_tax_rate'] = $ezpos_return_data->order_tax_rate;
            $mail_data['grand_total'] = $ezpos_return_data->grand_total;
            
            try{
                Mail::send( 'mail.return_details', $mail_data, function( $message ) use ($mail_data)
                {
                    $message->to( $mail_data['email'] )->subject( 'Return Details' );
                });
            }
            catch(\Exception $e){
                $message = 'Return updated successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        return redirect('return-purchase')->with('message', $message);
    }

    public function destroy($id)
    {
        $ezpos_return_data = ReturnPurchase::find($id);
        $ezpos_product_return_data = PurchaseProductReturn::where('return_id', $id)->get();

        foreach ($ezpos_product_return_data as $key => $product_return_data) {
            $ezpos_product_data = Product::find($product_return_data->product_id);
            $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $product_return_data->product_id],
                    ['store_id', $ezpos_return_data->store_id],
                    ])->first();

            $ezpos_product_data->qty += $product_return_data->qty;
            $ezpos_product_store_data->qty += $product_return_data->qty;
            $ezpos_product_data->save();
            $ezpos_product_store_data->save();
            $product_return_data->delete();
        }
        $ezpos_return_data->delete();
        return redirect('return-purchase')->with('not_permitted', 'Data deleted successfully');;
    }
}
