<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $ezpos_categories = Category::where('is_active', true)->pluck('name', 'id');
        $ezpos_category_all = Category::where('is_active', true)->get();
        return view('category.create',compact('ezpos_categories', 'ezpos_category_all'));
    }

    public function store(Request $request)
    {

        $request->name = preg_replace('/\s+/', ' ', $request->name);
        $this->validate($request, [
            'name' => [
                'max:255',
                    Rule::unique('categories')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);
        $ezpos_category_data['name'] = $request->name;
        $ezpos_category_data['parent_id'] = $request->parent_id;
        $ezpos_category_data['is_active'] = true;
        Category::create($ezpos_category_data);
        return redirect('category')->with('message', 'Data inserted successfully');
    }

    public function edit($id)
    {
        $ezpos_category_data = Category::findOrFail($id);
        $ezpos_parent_data = Category::where('id',
            $ezpos_category_data['parent_id'])->first();
        $ezpos_category_data['parent'] = $ezpos_parent_data['name'];
        return $ezpos_category_data;
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'name' => [
                'max:255',
                Rule::unique('categories')->ignore($request->category_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $input = $request->all();
        $ezpos_category_data = Category::findOrFail($request->category_id);
        $ezpos_category_data->update($input);
        return redirect('category')->with('message', 'Data updated successfully');
    }

    public function import(Request $request)
    {
        //get file
        $upload=$request->file('file');
        $ext = pathinfo($upload->getClientOriginalName(), PATHINFO_EXTENSION);
        if($ext != 'csv')
            return redirect()->back()->with('not_permitted', 'Please upload a CSV file');
        $filename =  $upload->getClientOriginalName();
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
            $category = Category::firstOrNew(['name' => $data['name'], 'is_active' => true ]);
            if($data['parentcategory']){
                $parent_category = Category::firstOrNew(['name' => $data['parentcategory'], 'is_active' => true ]);
                $parent_id = $parent_category->id;
            }
            else
                $parent_id = null;

            $category->parent_id = $parent_id;
            $category->is_active = true;
            $category->save();
        }
        return redirect('category')->with('message', 'Category imported successfully');
    }

    public function destroy($id)
    {
        $ezpos_category_data = Category::findOrFail($id);
        $ezpos_category_data->is_active = false;
        $ezpos_product_data = Product::where('category_id', $id)->get();
        foreach ($ezpos_product_data as $product_data) {
            $product_data->is_active = false;
            $product_data->save();
        }
        $ezpos_category_data->save();
        return redirect('category')->with('not_permitted', 'Data deleted successfully');
    }
}
