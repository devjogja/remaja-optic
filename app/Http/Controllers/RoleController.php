<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Roles;
use App\User;
use Auth;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $ezpos_role_all = Roles::where('is_active', true)->get();
        return view('role.create', compact('ezpos_role_all'));
    }

    
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                    Rule::unique('roles')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $data = $request->all();
        Roles::create($data);
        return redirect('role')->with('message', 'Data inserted successfully');
    }

    public function edit($id)
    {
        $ezpos_role_data = Roles::find($id);
        return $ezpos_role_data;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => [
                'max:255',
                Rule::unique('roles')->ignore($request->role_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $input = $request->all();
        $ezpos_role_data = Roles::where('id', $input['role_id'])->first();
        $ezpos_role_data->update($input);
        return redirect('role')->with('message', 'Data updated successfully');
    }

    public function permission($id)
    {
        $ezpos_role_data = Roles::find($id);
        $permissions = Role::findByName($ezpos_role_data->name)->permissions;
        foreach ($permissions as $permission)
            $all_permission[] = $permission->name;
        if(empty($all_permission))
            $all_permission[] = 'dummy text';

        return view('role.permission', compact('ezpos_role_data', 'all_permission'));
    }

    public function setPermission(Request $request)
    {
        if(!env('USER_VERIFIED'))
            redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $role = Role::firstOrCreate(['id' => $request['role_id']]);

        if($request->has('products-index')){
            $permission = Permission::firstOrCreate(['name' => 'products-index']);
            if(!$role->hasPermissionTo('products-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('products-index');

        if($request->has('products-add')){
            $permission = Permission::firstOrCreate(['name' => 'products-add']);
            if(!$role->hasPermissionTo('products-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('products-add');
        if($request->has('products-edit')){
            $permission = Permission::firstOrCreate(['name' => 'products-edit']);
            if(!$role->hasPermissionTo('products-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('products-edit');

        if($request->has('products-delete')){
            $permission = Permission::firstOrCreate(['name' => 'products-delete']);
            if(!$role->hasPermissionTo('products-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('products-delete');

        if($request->has('purchases-index')){
            $permission = Permission::firstOrCreate(['name' => 'purchases-index']);
            if(!$role->hasPermissionTo('purchases-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('purchases-index');

        if($request->has('purchases-add')){
            $permission = Permission::firstOrCreate(['name' => 'purchases-add']);
            if(!$role->hasPermissionTo('purchases-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('purchases-add');

        if($request->has('purchases-edit')){
            $permission = Permission::firstOrCreate(['name' => 'purchases-edit']);
            if(!$role->hasPermissionTo('purchases-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('purchases-edit');

        if($request->has('purchases-delete')){
            $permission = Permission::firstOrCreate(['name' => 'purchases-delete']);
            if(!$role->hasPermissionTo('purchases-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('purchases-delete');

        if($request->has('sales-index')){
            $permission = Permission::firstOrCreate(['name' => 'sales-index']);
            if(!$role->hasPermissionTo('sales-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('sales-index');

        if($request->has('sales-add')){
            $permission = Permission::firstOrCreate(['name' => 'sales-add']);
            if(!$role->hasPermissionTo('sales-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('sales-add');

        if($request->has('sales-edit')){
            $permission = Permission::firstOrCreate(['name' => 'sales-edit']);
            if(!$role->hasPermissionTo('sales-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('sales-edit');

        if($request->has('sales-delete')){
            $permission = Permission::firstOrCreate(['name' => 'sales-delete']);
            if(!$role->hasPermissionTo('sales-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('sales-delete');

        if($request->has('expenses-index')){
            $permission = Permission::firstOrCreate(['name' => 'expenses-index']);
            if(!$role->hasPermissionTo('expenses-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('expenses-index');

        if($request->has('expenses-add')){
            $permission = Permission::firstOrCreate(['name' => 'expenses-add']);
            if(!$role->hasPermissionTo('expenses-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('expenses-add');

        if($request->has('expenses-edit')){
            $permission = Permission::firstOrCreate(['name' => 'expenses-edit']);
            if(!$role->hasPermissionTo('expenses-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('expenses-edit');

        if($request->has('expenses-delete')){
            $permission = Permission::firstOrCreate(['name' => 'expenses-delete']);
            if(!$role->hasPermissionTo('expenses-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('expenses-delete');

        if($request->has('transfers-index')){
            $permission = Permission::firstOrCreate(['name' => 'transfers-index']);
            if(!$role->hasPermissionTo('transfers-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('transfers-index');

        if($request->has('transfers-add')){
            $permission = Permission::firstOrCreate(['name' => 'transfers-add']);
            if(!$role->hasPermissionTo('transfers-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('transfers-add');

        if($request->has('transfers-edit')){
            $permission = Permission::firstOrCreate(['name' => 'transfers-edit']);
            if(!$role->hasPermissionTo('transfers-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('transfers-edit');

        if($request->has('transfers-delete')){
            $permission = Permission::firstOrCreate(['name' => 'transfers-delete']);
            if(!$role->hasPermissionTo('transfers-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('transfers-delete');

        if($request->has('returns-index')){
            $permission = Permission::firstOrCreate(['name' => 'returns-index']);
            if(!$role->hasPermissionTo('returns-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('returns-index');

        if($request->has('returns-add')){
            $permission = Permission::firstOrCreate(['name' => 'returns-add']);
            if(!$role->hasPermissionTo('returns-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('returns-add');

        if($request->has('returns-edit')){
            $permission = Permission::firstOrCreate(['name' => 'returns-edit']);
            if(!$role->hasPermissionTo('returns-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('returns-edit');

        if($request->has('returns-delete')){
            $permission = Permission::firstOrCreate(['name' => 'returns-delete']);
            if(!$role->hasPermissionTo('returns-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('returns-delete');

        if($request->has('return-purchase-index')){
            $permission = Permission::firstOrCreate(['name' => 'return-purchase-index']);
            if(!$role->hasPermissionTo('return-purchase-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('return-purchase-index');

        if($request->has('return-purchase-add')){
            $permission = Permission::firstOrCreate(['name' => 'return-purchase-add']);
            if(!$role->hasPermissionTo('return-purchase-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('return-purchase-add');

        if($request->has('return-purchase-edit')){
            $permission = Permission::firstOrCreate(['name' => 'return-purchase-edit']);
            if(!$role->hasPermissionTo('return-purchase-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('return-purchase-edit');

        if($request->has('return-purchase-delete')){
            $permission = Permission::firstOrCreate(['name' => 'return-purchase-delete']);
            if(!$role->hasPermissionTo('return-purchase-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('return-purchase-delete');

        if($request->has('users-index')){
            $permission = Permission::firstOrCreate(['name' => 'users-index']);
            if(!$role->hasPermissionTo('users-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('users-index');

        if($request->has('users-add')){
            $permission = Permission::firstOrCreate(['name' => 'users-add']);
            if(!$role->hasPermissionTo('users-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('users-add');

        if($request->has('users-edit')){
            $permission = Permission::firstOrCreate(['name' => 'users-edit']);
            if(!$role->hasPermissionTo('users-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('users-edit');

        if($request->has('users-delete')){
            $permission = Permission::firstOrCreate(['name' => 'users-delete']);
            if(!$role->hasPermissionTo('users-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('users-delete');

        if($request->has('customers-index')){
            $permission = Permission::firstOrCreate(['name' => 'customers-index']);
            if(!$role->hasPermissionTo('customers-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customers-index');

        if($request->has('customers-add')){
            $permission = Permission::firstOrCreate(['name' => 'customers-add']);
            if(!$role->hasPermissionTo('customers-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customers-add');

        if($request->has('customers-edit')){
            $permission = Permission::firstOrCreate(['name' => 'customers-edit']);
            if(!$role->hasPermissionTo('customers-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customers-edit');

        if($request->has('customers-delete')){
            $permission = Permission::firstOrCreate(['name' => 'customers-delete']);
            if(!$role->hasPermissionTo('customers-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customers-delete');

        if($request->has('suppliers-index')){
            $permission = Permission::firstOrCreate(['name' => 'suppliers-index']);
            if(!$role->hasPermissionTo('suppliers-index')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('suppliers-index');

        if($request->has('suppliers-add')){
            $permission = Permission::firstOrCreate(['name' => 'suppliers-add']);
            if(!$role->hasPermissionTo('suppliers-add')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('suppliers-add');

        if($request->has('suppliers-edit')){
            $permission = Permission::firstOrCreate(['name' => 'suppliers-edit']);
            if(!$role->hasPermissionTo('suppliers-edit')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('suppliers-edit');

        if($request->has('suppliers-delete')){
            $permission = Permission::firstOrCreate(['name' => 'suppliers-delete']);
            if(!$role->hasPermissionTo('suppliers-delete')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('suppliers-delete');

        if($request->has('profit-loss')){
            $permission = Permission::firstOrCreate(['name' => 'profit-loss']);
            if(!$role->hasPermissionTo('profit-loss')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('profit-loss');

        if($request->has('best-seller')){
            $permission = Permission::firstOrCreate(['name' => 'best-seller']);
            if(!$role->hasPermissionTo('best-seller')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('best-seller');

        if($request->has('product-report')){
            $permission = Permission::firstOrCreate(['name' => 'product-report']);
            if(!$role->hasPermissionTo('product-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('product-report');

        if($request->has('daily-sale')){
            $permission = Permission::firstOrCreate(['name' => 'daily-sale']);
            if(!$role->hasPermissionTo('daily-sale')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('daily-sale');

        if($request->has('monthly-sale')){
            $permission = Permission::firstOrCreate(['name' => 'monthly-sale']);
            if(!$role->hasPermissionTo('monthly-sale')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('monthly-sale');

        if($request->has('daily-purchase')){
            $permission = Permission::firstOrCreate(['name' => 'daily-purchase']);
            if(!$role->hasPermissionTo('daily-purchase')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('daily-purchase');

        if($request->has('monthly-purchase')){
            $permission = Permission::firstOrCreate(['name' => 'monthly-purchase']);
            if(!$role->hasPermissionTo('monthly-purchase')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('monthly-purchase');

        if($request->has('sale-report')){
            $permission = Permission::firstOrCreate(['name' => 'sale-report']);
            if(!$role->hasPermissionTo('sale-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('sale-report');

        if($request->has('payment-report')){
            $permission = Permission::firstOrCreate(['name' => 'payment-report']);
            if(!$role->hasPermissionTo('payment-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('payment-report');

        if($request->has('purchase-report')){
            $permission = Permission::firstOrCreate(['name' => 'purchase-report']);
            if(!$role->hasPermissionTo('purchase-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('purchase-report');

        if($request->has('store-stock-report')){
            $permission = Permission::firstOrCreate(['name' => 'store-stock-report']);
            if(!$role->hasPermissionTo('store-stock-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('store-stock-report');

        if($request->has('product-qty-alert')){
            $permission = Permission::firstOrCreate(['name' => 'product-qty-alert']);
            if(!$role->hasPermissionTo('product-qty-alert')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('product-qty-alert');

        if($request->has('customer-report')){
            $permission = Permission::firstOrCreate(['name' => 'customer-report']);
            if(!$role->hasPermissionTo('customer-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('customer-report');

        if($request->has('supplier-report')){
            $permission = Permission::firstOrCreate(['name' => 'supplier-report']);
            if(!$role->hasPermissionTo('supplier-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('supplier-report');

        if($request->has('due-report')){
            $permission = Permission::firstOrCreate(['name' => 'due-report']);
            if(!$role->hasPermissionTo('due-report')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('due-report');

        if($request->has('general_setting')){
            $permission = Permission::firstOrCreate(['name' => 'general_setting']);
            if(!$role->hasPermissionTo('general_setting')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('general_setting');

        if($request->has('mail_setting')){
            $permission = Permission::firstOrCreate(['name' => 'mail_setting']);
            if(!$role->hasPermissionTo('mail_setting')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('mail_setting');

        if($request->has('pos_setting')){
            $permission = Permission::firstOrCreate(['name' => 'pos_setting']);
            if(!$role->hasPermissionTo('pos_setting')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('pos_setting');

        if($request->has('stock_count')){
            $permission = Permission::firstOrCreate(['name' => 'stock_count']);
            if(!$role->hasPermissionTo('stock_count')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('stock_count');

        if($request->has('adjustment')){
            $permission = Permission::firstOrCreate(['name' => 'adjustment']);
            if(!$role->hasPermissionTo('adjustment')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('adjustment');

        if($request->has('print_barcode')){
            $permission = Permission::firstOrCreate(['name' => 'print_barcode']);
            if(!$role->hasPermissionTo('print_barcode')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('print_barcode');

        if($request->has('empty_database')){
            $permission = Permission::firstOrCreate(['name' => 'empty_database']);
            if(!$role->hasPermissionTo('empty_database')){
                $role->givePermissionTo($permission);
            }
        }
        else
            $role->revokePermissionTo('empty_database');

        return redirect('role')->with('message', 'Permission updated successfully');
    }

    public function destroy($id)
    {
        if(!env('USER_VERIFIED'))
            redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
        
        $ezpos_role_data = Roles::find($id);
        $ezpos_role_data->is_active = false;
        $ezpos_role_data->save();
        return redirect('role')->with('not_permitted', 'Data deleted successfully');
    }
}
