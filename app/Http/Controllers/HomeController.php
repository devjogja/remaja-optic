<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sale;
use App\Returns;
use App\Purchase;
use App\Payment;
use App\Product_Sale;
use App\ReturnPurchase;
use DB;
use Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {                      
        $start_date = date("Y").'-'.date("m").'-'.'01';
        $end_date = date("Y").'-'.date("m").'-'.'31';
        $yearly_sale_amount = []; 
        $general_setting =  \App\GeneralSetting::latest()->first();
        if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own'){
            $revenue = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $return = Returns::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $revenue -= $return;
            $purchase = Purchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $profit = $revenue - $purchase + $purchase_return;
            $sold_qty = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('total_qty');
            $recent_sale = Sale::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_return = Returns::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->where('user_id', Auth::id())->take(5)->get();
        }
        else{
            $revenue = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $revenue -= $return;
            $purchase = Purchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue - $purchase + $purchase_return;
            $sold_qty = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('total_qty');
            $recent_sale = Sale::orderBy('id', 'desc')->take(5)->get();
            $recent_purchase = Purchase::orderBy('id', 'desc')->take(5)->get();
            $recent_return = Returns::orderBy('id', 'desc')->take(5)->get();
            $recent_payment = Payment::orderBy('id', 'desc')->take(5)->get();
        }

        $best_selling_qty = DB::table('sales')
                        ->join('product_sales', 'sales.id', '=', 'product_sales.sale_id')->select(DB::raw('product_sales.product_id, sum(product_sales.qty) as sold_qty'))->whereDate('sales.date', '>=' , $start_date)->whereDate('sales.date', '<=' , $end_date)->groupBy('product_sales.product_id')->orderBy('sold_qty', 'desc')->take(5)->get();

        $yearly_best_selling_qty = DB::table('sales')
                        ->join('product_sales', 'sales.id', '=', 'product_sales.sale_id')->select(DB::raw('product_sales.product_id, sum(product_sales.qty) as sold_qty'))->whereDate('sales.date', '>=' , date("Y").'-01-01')->whereDate('sales.date', '<=' , date("Y").'-12-31')->groupBy('product_sales.product_id')->orderBy('sold_qty', 'desc')->take(5)->get();

        $yearly_best_selling_price = DB::table('sales')
                        ->join('product_sales', 'sales.id', '=', 'product_sales.sale_id')->select(DB::raw('product_sales.product_id, sum(product_sales.total) as total_price'))->whereDate('sales.date', '>=' , date("Y").'-01-01')->whereDate('sales.date', '<=' , date("Y").'-12-31')->groupBy('product_sales.product_id')->orderBy('total_price', 'desc')->take(5)->get();

        $start = strtotime(date("Y") .'-01-01');
        $end = strtotime(date("Y") .'-12-31');
        while($start < $end)
        {
            $start_date = date("Y").'-'.date('m', $start).'-'.'01';
            $end_date = date("Y").'-'.date('m', $start).'-'.'31';
            if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own'){
                $sale_amount = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
                $purchase_amount = Purchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            }
            else{
                $sale_amount = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
                $purchase_amount = Purchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            }

             $yearly_sale_amount[] = number_format((float)$sale_amount, 2, '.', '');
             $yearly_purchase_amount[] = number_format((float)$purchase_amount, 2, '.', '');
             $start = strtotime("+1 month", $start);
        }
        return view('index', compact('revenue', 'return', 'purchase_return', 'profit', 'sold_qty', 'yearly_sale_amount', 'yearly_purchase_amount', 'recent_sale', 'recent_purchase', 'recent_return', 'recent_payment', 'best_selling_qty', 'yearly_best_selling_qty', 'yearly_best_selling_price'));
    }

    public function dashboardFilter($start_date, $end_date)
    {
        $general_setting =  \App\GeneralSetting::latest()->first();
        if(Auth::user()->role_id > 2 && $general_setting->staff_access == 'own'){
            $revenue = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $return = Returns::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $revenue -= $return;
            $purchase = Purchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->where('user_id', Auth::id())->sum('grand_total');
            $profit = $revenue - $purchase + $purchase_return;
        }
        else{
            $revenue = Sale::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $return = Returns::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $purchase_return = ReturnPurchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $revenue -= $return;
            $purchase = Purchase::whereDate('date', '>=' , $start_date)->whereDate('date', '<=' , $end_date)->sum('grand_total');
            $profit = $revenue - $purchase + $purchase_return;
        }

        $data[] = $revenue;
        $data[] = $return;
        $data[] = $purchase_return;
        $data[] = $profit;
        return $data;
    }
}
