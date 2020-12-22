<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Keygen;
use App\Brand;
use App\Category;
use App\Tax;
use App\Store;
use App\Supplier;
use App\Product;
use App\Product_Store;
use App\Product_Supplier;
use Auth;
use DNS1D;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('products-index')) {
            $ezpos_product_all = Product::where('is_active', true)->get();
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';
            return view('product.index', compact('ezpos_product_all', 'all_permission'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::firstOrCreate(['id' => Auth::user()->role_id]);
        if ($role->hasPermissionTo('products-add')) {
            $ezpos_brand_list = Brand::where('is_active', true)->get();
            $ezpos_category_list = Category::where('is_active', true)->get();
            $ezpos_tax_list = Tax::where('is_active', true)->get();
            return view('product.create', compact('ezpos_brand_list', 'ezpos_category_list', 'ezpos_tax_list'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => [
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'name' => [
                'max:255',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $data = $request->except('image', 'file');
        if ($data['type'] == 'digital')
            $data['cost'] = 0;
        if ($data['starting_date'])
            $data['starting_date'] = date('Y-m-d', strtotime($data['starting_date']));
        if ($data['last_date'])
            $data['last_date'] = date('Y-m-d', strtotime($data['last_date']));
        $data['is_active'] = true;
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['name']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/product', $imageName);
            $data['image'] = $imageName;
        } else {
            $data['image'] = 'zummXD2dvAtI.png';
        }

        $file = $request->file;
        if ($file) {
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $fileName = strtotime(date('Y-m-d H:i:s'));
            $fileName = $fileName . '.' . $ext;
            $file->move('public/product/files', $fileName);
            $data['file'] = $fileName;
        }
        Product::create($data);
        return redirect('products')->with('create_message', 'Product created successfully');
    }

    public function edit($id)
    {
        $role = Role::firstOrCreate(['id' => Auth::user()->role_id]);
        if ($role->hasPermissionTo('products-edit')) {
            $ezpos_brand_list = Brand::where('is_active', true)->get();
            $ezpos_category_list = Category::where('is_active', true)->get();
            $ezpos_tax_list = Tax::where('is_active', true)->get();
            $ezpos_product_data = Product::where('id', $id)->first();

            return view('product.edit', compact('ezpos_brand_list', 'ezpos_category_list', 'ezpos_tax_list', 'ezpos_product_data'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('products')->ignore($id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],

            'code' => [
                'max:255',
                Rule::unique('products')->ignore($id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],

            'image' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $ezpos_product_data = Product::findOrFail($id);
        $data = $request->except('image', 'file');
        if ($data['type'] == 'digital') {
            $data['cost'] = $data['unit_id'] = $data['purchase_unit_id'] = $data['sale_unit_id'] = 0;
        }
        if ($data['starting_date'])
            $data['starting_date'] = date('Y-m-d', strtotime($data['starting_date']));
        if ($data['last_date'])
            $data['last_date'] = date('Y-m-d', strtotime($data['last_date']));
        $image = $request->image;
        if ($image) {
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $imageName = preg_replace('/[^a-zA-Z0-9]/', '', $request['name']);
            $imageName = $imageName . '.' . $ext;
            $image->move('public/images/product', $imageName);
            $data['image'] = $imageName;
        }
        $file = $request->file;
        if ($file) {
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $fileName = strtotime(date('Y-m-d H:i:s'));
            $fileName = $fileName . '.' . $ext;
            $file->move('public/product/files', $fileName);
            $data['file'] = $fileName;
        }
        $ezpos_product_data->update($data);
        return redirect('products')->with('edit_message', 'Product updated successfully');
    }

    public function generateCode()
    {
        $id = Keygen::numeric(8)->generate();
        return $id;
    }

    public function saleUnit($id)
    {
        $unit = Unit::where("base_unit", $id)->orWhere('id', $id)->pluck('unit_name', 'id');
        return json_encode($unit);
    }

    public function productStoreData($id)
    {
        $store = [];
        $qty = [];
        $product_store = [];

        $ezpos_product_store_data = Product_Store::where('product_id', $id)->get();
        foreach ($ezpos_product_store_data as $key => $product_store_data) {
            $ezpos_store_data = Store::find($product_store_data->store_id);
            $store[] = $ezpos_store_data->name;
            $qty[] = $product_store_data->qty;
        }

        $product_store[] = $store;
        $product_store[] = $qty;
        return $product_store;
    }

    public function printBarcode()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('print_barcode')) {
            $ezpos_product_list = Product::where('is_active', true)->get();
            return view('product.print_barcode', compact('ezpos_product_list'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function ezposProductSearch(Request $request)
    {
        $product_code = explode("--", $request['data']);
        $ezpos_product_data = Product::where('code', $product_code[0])->first();
        $code = explode(' ', $ezpos_product_data->code);
        $product[] = $ezpos_product_data->name;
        $product[] = $ezpos_product_data->code;
        $product[] = $ezpos_product_data->price;
        $product[] = DNS1D::getBarcodePNG($code[0], $ezpos_product_data->barcode_symbology);
        $product[] = $ezpos_product_data->promotion_price;
        return $product;
    }

    public function importProduct(Request $request)
    {
        //get file
        $upload = $request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if ($ext != 'csv')
            return redirect()->back()->with('message', 'Please upload a CSV file');

        $filePath = $upload->getRealPath();
        //open and read
        $file = fopen($filePath, 'r');
        $header = fgetcsv($file);
        $escapedHeader = [];
        //validate
        foreach ($header as $key => $value) {
            $lheader = strtolower($value);
            $escapedItem = preg_replace('/[^a-z]/', '', $lheader);
            array_push($escapedHeader, $escapedItem);
        }
        //looping through othe columns
        while ($columns = fgetcsv($file)) {
            if ($columns[0] == "")
                continue;
            foreach ($columns as $key => $value) {
                $value = preg_replace('/\D/', '', $value);
            }
            $data = array_combine($escapedHeader, $columns);

            if ($data['brand'] != 'N/A' && $data['brand'] != '') {
                $ezpos_brand_data = Brand::firstOrCreate(['title' => $data['brand'], 'is_active' => true]);
                $brand_id = $ezpos_brand_data->id;
            } else
                $brand_id = null;

            $ezpos_category_data = Category::firstOrCreate(['name' => $data['category'], 'is_active' => true]);

            $product = Product::firstOrNew(['name' => $data['name'], 'is_active' => true]);
            if ($data['image'])
                $product->image = $data['image'];
            else
                $product->image = 'zummXD2dvAtI.png';
            $product->name = $data['name'];
            $product->code = $data['code'];
            $product->type = $data['type'];
            $product->barcode_symbology = 'C128';
            $product->brand_id = $brand_id;
            $product->category_id = $ezpos_category_data->id;
            $product->unit = $data['unit'];
            $product->cost = $data['cost'];
            $product->price = $data['price'];
            $product->tax_method = 1;
            $product->qty = 0;
            $product->product_details = $data['productdetails'];
            $product->is_active = true;
            //return $product;
            $product->save();
        }
        return redirect('products')->with('import_message', 'Product imported successfully');
    }

    public function exportProduct(Request $request)
    {
        $ezpos_product_data = $request['productArray'];
        $csvData = array('name, code, brand, category, quantity, unit, price');
        foreach ($ezpos_product_data as $product) {
            if ($product > 0) {
                $data = product::where('id', $product)->first();
                if ($data->brand_id) {
                    $ezpos_brand_data = Brand::find($data->brand_id);
                    $brand = $ezpos_brand_data->title;
                } else
                    $brand = 'N/A';
                $ezpos_category_data = Category::find($data->category_id);
                $ezpos_unit_data = Unit::find($data->unit_id);

                $csvData[] = $data->name . ',' . $data->code . ',' . $brand . ',' . $ezpos_category_data->name . ',' . $data->qty . ',' . $ezpos_unit_data->unit_code . ',' . $data->price;
            }
        }
        $filename = "product- " . date('d-m-Y') . ".csv";
        $file_path = public_path() . '/downloads/' . $filename;
        $file_url = url('/') . '/downloads/' . $filename;
        $file = fopen($file_path, "w+");
        foreach ($csvData as $exp_data) {
            fputcsv($file, explode(',', $exp_data));
        }
        fclose($file);
        return $file_url;
    }

    public function destroy($id)
    {
        $ezpos_product_data = Product::findOrFail($id);
        $ezpos_product_data->is_active = false;
        $ezpos_product_data->save();
        return redirect('products')->with('message', 'Product deleted successfully');
    }
}
