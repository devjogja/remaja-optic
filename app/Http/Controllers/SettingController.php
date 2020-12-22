<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Customer;
use App\CustomerGroup;
use App\Store;
use App\Biller;
use App\PosSetting;
use App\GeneralSetting;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class SettingController extends Controller
{
    public function emptyDatabase()
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
        $tables = DB::select('SHOW TABLES');
        $str = 'Tables_in_' . env('DB_DATABASE');
        foreach ($tables as $table) {
            if($table->$str != 'general_settings' && $table->$str != 'languages' && $table->$str != 'migrations' && $table->$str != 'password_resets' && $table->$str != 'permissions' && $table->$str != 'pos_setting' && $table->$str != 'roles' && $table->$str != 'role_has_permissions' && $table->$str != 'users') {
                DB::table($table->$str)->truncate();    
            }
        }
        return redirect()->back()->with('message', 'Database cleared successfully');
    }

    public function generalSetting()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('general_setting')) {
            $ezpos_general_setting_data = GeneralSetting::latest()->first();
            $zones_array = array();
            $timestamp = time();
            foreach(timezone_identifiers_list() as $key => $zone) {
                date_default_timezone_set($zone);
                $zones_array[$key]['zone'] = $zone;
                $zones_array[$key]['diff_from_GMT'] = 'UTC/GMT ' . date('P', $timestamp);
            }
            return view('setting.general_setting', compact('ezpos_general_setting_data', 'zones_array'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function generalSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');

        $this->validate($request, [
            'site_logo' => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $data = $request->except('site_logo');
        $path = '.env';
        $searchArray = array('APP_TIMEZONE='.env('APP_TIMEZONE'));
        $replaceArray = array('APP_TIMEZONE='.$data['timezone']);

        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));

        $general_setting = GeneralSetting::latest()->first();
        $general_setting->id = 1;
        $general_setting->site_title = $data['site_title'];
        $general_setting->currency = $data['currency'];
        $general_setting->currency_position = $data['currency_position'];
        $general_setting->staff_access = $data['staff_access'];
        $logo = $request->site_logo;
        if ($logo) {
            $logoName = $logo->getClientOriginalName();
            $logo->move('public/logo', $logoName);
            $general_setting->site_logo = $logoName;
        }
        $general_setting->save();
        return redirect()->back()->with('message', 'Data updated successfully');
    }

    public function mailSetting()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('mail_setting'))
            return view('setting.mail_setting');
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function mailSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
        $data = $request->all();
        //writting mail info in .env file
        $path = '.env';
        $searchArray = array('MAIL_HOST='.env('MAIL_HOST'), 'MAIL_PORT='.env('MAIL_PORT'), 'MAIL_FROM_ADDRESS='.env('MAIL_FROM_ADDRESS'), 'MAIL_FROM_NAME='.env('MAIL_FROM_NAME'), 'MAIL_USERNAME='.env('MAIL_USERNAME'), 'MAIL_PASSWORD='.env('MAIL_PASSWORD'), 'MAIL_ENCRYPTION='.env('MAIL_ENCRYPTION'));

        $replaceArray = array('MAIL_HOST='.$data['mail_host'], 'MAIL_PORT='.$data['port'], 'MAIL_FROM_ADDRESS='.$data['mail_address'], 'MAIL_FROM_NAME='.$data['mail_name'], 'MAIL_USERNAME='.$data['mail_address'], 'MAIL_PASSWORD='.$data['password'], 'MAIL_ENCRYPTION='.$data['encryption']);
        
        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));

        return redirect()->back()->with('message', 'Data updated successfully');
    }
    
    public function posSetting()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('pos_setting')) {
        	$ezpos_customer_list = Customer::where('is_active', true)->get();
            $ezpos_store_list = Store::where('is_active', true)->get();
            $ezpos_pos_setting_data = PosSetting::latest()->first();
            
        	return view('setting.pos_setting', compact('ezpos_customer_list', 'ezpos_store_list', 'ezpos_pos_setting_data'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function posSettingStore(Request $request)
    {
        if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
    	$data = $request->all();
        //writting mail info in .env file
        $path = '.env';
        $searchArray = array('PAYPAL_LIVE_API_USERNAME='.env('PAYPAL_LIVE_API_USERNAME'), 'PAYPAL_LIVE_API_PASSWORD='.env('PAYPAL_LIVE_API_PASSWORD'), 'PAYPAL_LIVE_API_SECRET='.env('PAYPAL_LIVE_API_SECRET'));

        $replaceArray = array('PAYPAL_LIVE_API_USERNAME='.$data['paypal_username'], 'PAYPAL_LIVE_API_PASSWORD='.$data['paypal_password'], 'PAYPAL_LIVE_API_SECRET='.$data['paypal_signature']);

        file_put_contents($path, str_replace($searchArray, $replaceArray, file_get_contents($path)));

    	$pos_setting = PosSetting::firstOrNew(['id' => 1]);
    	$pos_setting->id = 1;
    	$pos_setting->customer_id = $data['customer_id'];
    	$pos_setting->store_id = $data['store_id'];
    	$pos_setting->product_number = $data['product_number'];
    	$pos_setting->stripe_public_key = $data['stripe_public_key'];
    	$pos_setting->stripe_secret_key = $data['stripe_secret_key'];
        if(!isset($data['keybord_active']))
            $pos_setting->keybord_active = false;
        else
            $pos_setting->keybord_active = true;
    	$pos_setting->save();
    	return redirect()->back()->with('message', 'POS setting updated successfully');
    }
}
