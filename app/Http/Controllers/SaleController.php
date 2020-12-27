<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Customer;
use App\User;
use App\CustomerGroup;
use App\Store;
use App\Biller;
use App\Brand;
use App\Category;
use App\Product;
use App\Unit;
use App\Tax;
use App\Sale;
use App\Delivery;
use App\PosSetting;
use App\Product_Sale;
use App\Product_Store;
use App\Payment;
use App\GiftCard;
use App\PaymentWithCheque;
use App\PaymentWithGiftCard;
use App\PaymentWithCreditCard;
use App\PaymentWithPaypal;
use DB;
use Stripe\Stripe;
use NumberToWords\NumberToWords;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;
use Srmklive\PayPal\Services\ExpressCheckout;
use Srmklive\PayPal\Services\AdaptivePayments;

class SaleController extends Controller
{
    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-index')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';
            $general_setting =  \App\GeneralSetting::latest()->first();
            if (Auth::user()->role_id > 2 && $general_setting->staff_access == 'own')
                $ezpos_sale_all = Sale::orderBy('id', 'desc')->where('user_id', Auth::id())->get();
            else
                $ezpos_sale_all = Sale::orderBy('id', 'desc')->get();
            $ezpos_gift_card_list = GiftCard::where("is_active", true)->get();
            $ezpos_pos_setting_data = PosSetting::latest()->first();

            return view('sale.index', compact('ezpos_sale_all', 'ezpos_gift_card_list', 'all_permission', 'ezpos_pos_setting_data'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function create()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-add')) {
            return redirect('pos');
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        //return dd($data);
        $data['user_id'] = Auth::id();
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        if ($data['sale_status'] == 1)
            $data['reference_no'] = 'sr-' . date("Ymd") . '-' . date("his");
        else
            $data['reference_no'] = 'dr-' . date("Ymd") . '-' . date("his");

        $balance = $data['grand_total'] - $data['paid_amount'];
        if ($balance > 0 || $balance < 0)
            $data['payment_status'] = 2;
        else
            $data['payment_status'] = 4;
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/sale/documents', $documentName);
            $data['document'] = $documentName;
        }
        if ($data['draft']) {
            $ezpos_sale_data = Sale::find($data['sale_id']);
            $ezpos_product_sale_data = Product_Sale::where('sale_id', $data['sale_id'])->get();
            foreach ($ezpos_product_sale_data as $product_sale_data) {
                $product_sale_data->delete();
            }
            $ezpos_sale_data->delete();
        }

        Sale::create($data);
        $ezpos_sale_data = Sale::latest()->first();
        $ezpos_customer_data = Customer::find($data['customer_id']);
        //collecting male data
        $mail_data['email'] = $ezpos_customer_data->email;
        $mail_data['reference_no'] = $ezpos_sale_data->reference_no;
        $mail_data['sale_status'] = $ezpos_sale_data->sale_status;
        $mail_data['payment_status'] = $ezpos_sale_data->payment_status;
        $mail_data['total_qty'] = $ezpos_sale_data->total_qty;
        $mail_data['total_price'] = $ezpos_sale_data->total_price;
        $mail_data['order_tax'] = $ezpos_sale_data->order_tax;
        $mail_data['order_tax_rate'] = $ezpos_sale_data->order_tax_rate;
        $mail_data['order_discount'] = $ezpos_sale_data->order_discount;
        $mail_data['shipping_cost'] = $ezpos_sale_data->shipping_cost;
        $mail_data['grand_total'] = $ezpos_sale_data->grand_total;
        $mail_data['paid_amount'] = $ezpos_sale_data->paid_amount;

        $product_id = $data['product_id'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $product_sale = [];
        $i = 0;

        foreach ($product_id as $id) {
            $ezpos_product_data = Product::where('id', $id)->first();
            if ($data['sale_status'] == 1 && $ezpos_product_data->type == 'standard') {
                $message = 'Sale created successfully';
                //deduct quantity
                $ezpos_product_data->qty = $ezpos_product_data->qty - $qty[$i];
                $ezpos_product_data->save();
                //deduct quantity from store
                $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $id],
                    ['store_id', $data['store_id']],
                ])->first();
                $ezpos_product_store_data->qty = $ezpos_product_store_data->qty - $qty[$i];
                $ezpos_product_store_data->save();
            } elseif ($data['sale_status'] == 1)
                $message = 'Sale created successfully';
            else
                $message = 'Sale successfully added to draft';

            $mail_data['products'][$i] = $ezpos_product_data->name;
            if ($ezpos_product_data->type == 'digital')
                $mail_data['file'][$i] = url('/public/product/files') . '/' . $ezpos_product_data->file;
            else
                $mail_data['file'][$i] = '';
            if ($sale_unit[$i])
                $mail_data['unit'][$i] = $sale_unit[$i];
            else
                $mail_data['unit'][$i] = '';

            $product_sale['sale_id'] = $ezpos_sale_data->id;
            $product_sale['product_id'] = $id;
            $product_sale['qty'] = $mail_data['qty'][$i] = $qty[$i];
            $product_sale['unit'] = $sale_unit[$i];
            $product_sale['net_unit_price'] = $net_unit_price[$i];
            $product_sale['discount'] = $discount[$i];
            $product_sale['tax_rate'] = $tax_rate[$i];
            $product_sale['tax'] = $tax[$i];
            $product_sale['total'] = $mail_data['total'][$i] = $total[$i];
            Product_Sale::create($product_sale);
            $i++;
        }

        if ($mail_data['email'] && $data['sale_status'] == 1) {
            try {
                Mail::send('mail.sale_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Sale Details');
                });
            } catch (\Exception $e) {
                $message = 'Sale created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }

        if ($data['paid_amount'] > 0) {
            if ($data['paid_by_id'] == 1) {
                $paying_method = 'Cash';
            } elseif ($data['paid_by_id'] == 2) {
                $paying_method = 'Gift Card';
            } elseif ($data['paid_by_id'] == 3) {
                $paying_method = 'Credit Card';
            } elseif ($data['paid_by_id'] == 7) {
                $paying_method = 'Debit Card';
            } elseif ($data['paid_by_id'] == 4) {
                $paying_method = 'Cheque';
            } elseif ($data['paid_by_id'] == 5) {
                $paying_method = 'Paypal';
            } else {
                $paying_method = 'Deposit';
            }
            $ezpos_payment_data = new Payment();
            $ezpos_payment_data->date = $data['date'];
            $ezpos_payment_data->user_id = Auth::id();
            $ezpos_payment_data->sale_id = $ezpos_sale_data->id;
            $data['payment_reference'] = 'spr-' . date("Ymd") . '-' . date("his");
            $ezpos_payment_data->payment_reference = $data['payment_reference'];
            $ezpos_payment_data->amount = $data['paying_amount'];
            $ezpos_payment_data->change = $data['paying_amount'] - $data['paid_amount'];
            $ezpos_payment_data->paying_method = $paying_method;
            $ezpos_payment_data->payment_note = $data['payment_note'];
            $ezpos_payment_data->save();

            $ezpos_payment_data = Payment::latest()->first();
            $data['payment_id'] = $ezpos_payment_data->id;
            if ($paying_method == 'Credit Card') {
                $ezpos_pos_setting_data = PosSetting::find(1);
                Stripe::setApiKey($ezpos_pos_setting_data->stripe_secret_key);
                $token = $data['stripeToken'];
                $grand_total = $data['grand_total'];

                $ezpos_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $data['customer_id'])->first();

                if (!$ezpos_payment_with_credit_card_data) {
                    // Create a Customer:
                    $customer = \Stripe\Customer::create([
                        'source' => $token
                    ]);

                    // Charge the Customer instead of the card:
                    $charge = \Stripe\Charge::create([
                        'amount' => $grand_total * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id
                    ]);
                    $data['customer_stripe_id'] = $customer->id;
                } else {
                    $customer_id =
                        $ezpos_payment_with_credit_card_data->customer_stripe_id;

                    $charge = \Stripe\Charge::create([
                        'amount' => $grand_total * 100,
                        'currency' => 'usd',
                        'customer' => $customer_id, // Previously stored, then retrieved
                    ]);
                    $data['customer_stripe_id'] = $customer_id;
                }
                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            } elseif ($paying_method == 'Gift Card') {
                $ezpos_gift_card_data = GiftCard::find($data['gift_card_id']);
                $ezpos_gift_card_data->expense += $data['paid_amount'];
                $ezpos_gift_card_data->save();
                PaymentWithGiftCard::create($data);
            } elseif ($paying_method == 'Cheque') {
                PaymentWithCheque::create($data);
            } elseif ($paying_method == 'Debit Card') {
                PaymentWithDebitCard::create($data);
            } elseif ($paying_method == 'Paypal') {
                $provider = new ExpressCheckout;
                $paypal_data = [];
                $paypal_data['items'] = [];
                foreach ($data['product_id'] as $key => $product_id) {
                    $ezpos_product_data = Product::find($product_id);
                    $paypal_data['items'][] = [
                        'name' => $ezpos_product_data->name,
                        'price' => ($data['subtotal'][$key] / $data['qty'][$key]),
                        'qty' => $data['qty'][$key]
                    ];
                }
                $paypal_data['items'][] = [
                    'name' => 'Order Tax',
                    'price' => $data['order_tax'],
                    'qty' => 1
                ];
                $paypal_data['items'][] = [
                    'name' => 'Order Discount',
                    'price' => $data['order_discount'] * (-1),
                    'qty' => 1
                ];
                $paypal_data['items'][] = [
                    'name' => 'Shipping Cost',
                    'price' => $data['shipping_cost'],
                    'qty' => 1
                ];
                if ($data['grand_total'] != $data['paid_amount']) {
                    $paypal_data['items'][] = [
                        'name' => 'Due',
                        'price' => ($data['grand_total'] - $data['paid_amount']) * (-1),
                        'qty' => 1
                    ];
                }
                //return $paypal_data;
                $paypal_data['invoice_id'] = $ezpos_sale_data->reference_no;
                $paypal_data['invoice_description'] = "Reference # {$paypal_data['invoice_id']} Invoice";
                $paypal_data['return_url'] = url('/sale/paypalSuccess');
                $paypal_data['cancel_url'] = url('/sale/create');

                $total = 0;
                foreach ($paypal_data['items'] as $item) {
                    $total += $item['price'] * $item['qty'];
                }

                $paypal_data['total'] = $total;
                $response = $provider->setExpressCheckout($paypal_data);
                // This will redirect user to PayPal
                return redirect($response['paypal_link']);
            } elseif ($paying_method == 'Deposit') {
                $ezpos_customer_data = Customer::find($data['customer_id']);
                $ezpos_customer_data->expense += $data['paid_amount'];
                $ezpos_customer_data->save();
            }
        }
        if ($ezpos_sale_data->sale_status == '1')
            return redirect('sale/gen_invoice/' . $ezpos_sale_data->id)->with('message', $message);
        else
            return redirect('pos')->with('message', $message);
    }

    public function paypalSuccess(Request $request)
    {
        $ezpos_sale_data = Sale::latest()->first();
        $ezpos_payment_data = Payment::latest()->first();
        $ezpos_product_sale_data = Product_Sale::where('sale_id', $ezpos_sale_data->id)->get();
        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerID = $request->PayerID;
        $paypal_data['items'] = [];
        foreach ($ezpos_product_sale_data as $key => $product_sale_data) {
            $ezpos_product_data = Product::find($product_sale_data->product_id);
            $paypal_data['items'][] = [
                'name' => $ezpos_product_data->name,
                'price' => ($product_sale_data->total / $product_sale_data->qty),
                'qty' => $product_sale_data->qty
            ];
        }
        $paypal_data['items'][] = [
            'name' => 'order tax',
            'price' => $ezpos_sale_data->order_tax,
            'qty' => 1
        ];
        $paypal_data['items'][] = [
            'name' => 'order discount',
            'price' => $ezpos_sale_data->order_discount * (-1),
            'qty' => 1
        ];
        $paypal_data['items'][] = [
            'name' => 'shipping cost',
            'price' => $ezpos_sale_data->shipping_cost,
            'qty' => 1
        ];
        if ($ezpos_sale_data->grand_total != $ezpos_sale_data->paid_amount) {
            $paypal_data['items'][] = [
                'name' => 'Due',
                'price' => ($ezpos_sale_data->grand_total - $ezpos_sale_data->paid_amount) * (-1),
                'qty' => 1
            ];
        }

        $paypal_data['invoice_id'] = $ezpos_payment_data->payment_reference;
        $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
        $paypal_data['return_url'] = url('/sale/paypalSuccess');
        $paypal_data['cancel_url'] = url('/sale/create');

        $total = 0;
        foreach ($paypal_data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $paypal_data['total'] = $ezpos_sale_data->paid_amount;
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($paypal_data, $token, $payerID);
        $data['payment_id'] = $ezpos_payment_data->id;
        $data['transaction_id'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
        PaymentWithPaypal::create($data);
        return redirect('pos')->with('message', 'Sale created successfully');
    }

    public function paypalPaymentSuccess(Request $request, $id)
    {
        $ezpos_payment_data = Payment::find($id);
        $provider = new ExpressCheckout;
        $token = $request->token;
        $payerID = $request->PayerID;
        $paypal_data['items'] = [];
        $paypal_data['items'][] = [
            'name' => 'Paid Amount',
            'price' => $ezpos_payment_data->amount,
            'qty' => 1
        ];
        $paypal_data['invoice_id'] = $ezpos_payment_data->payment_reference;
        $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
        $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess');
        $paypal_data['cancel_url'] = url('/sale');

        $total = 0;
        foreach ($paypal_data['items'] as $item) {
            $total += $item['price'] * $item['qty'];
        }

        $paypal_data['total'] = $total;
        $response = $provider->getExpressCheckoutDetails($token);
        $response = $provider->doExpressCheckoutPayment($paypal_data, $token, $payerID);
        $data['payment_id'] = $ezpos_payment_data->id;
        $data['transaction_id'] = $response['PAYMENTINFO_0_TRANSACTIONID'];
        PaymentWithPaypal::create($data);
        return redirect('sale')->with('message', 'Payment created successfully');
    }

    public function getProduct($id)
    {
        $ezpos_product_store_data = Product_Store::where([
            ['store_id', $id],
            ['qty', '>', 0]
        ])->get();
        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        foreach ($ezpos_product_store_data as $product_store) {
            $product_qty[] = $product_store->qty;
            $ezpos_product_data = Product::find($product_store->product_id);
            $product_code[] =  $ezpos_product_data->code;
            $product_name[] = $ezpos_product_data->name;
            $product_type[] = $ezpos_product_data->type;
        }
        $ezpos_product_data = Product::whereNotIn('type', ['standard'])
            ->get();
        foreach ($ezpos_product_data as $product) {
            $product_qty[] = $product->qty;
            $ezpos_product_data = $product->id;
            $product_code[] =  $product->code;
            $product_name[] = $product->name;
            $product_type[] = $product->type;
        }
        $product_data[] = $product_code;
        $product_data[] = $product_name;
        $product_data[] = $product_qty;
        $product_data[] = $product_type;
        return $product_data;
    }

    public function getProductByFilter($category_id, $brand_id)
    {
        $data = [];
        if (($category_id != 0) && ($brand_id != 0)) {
            $ezpos_product_list = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where([
                    ['products.is_active', true],
                    ['products.category_id', $category_id],
                    ['brand_id', $brand_id]
                ])->orWhere([
                    ['categories.parent_id', $category_id],
                    ['products.is_active', true],
                    ['brand_id', $brand_id]
                ])->select('products.name', 'products.code', 'products.image')->get();
        } elseif (($category_id != 0) && ($brand_id == 0)) {
            $ezpos_product_list = DB::table('products')
                ->join('categories', 'products.category_id', '=', 'categories.id')
                ->where([
                    ['products.is_active', true],
                    ['products.category_id', $category_id],
                ])->orWhere([
                    ['categories.parent_id', $category_id],
                    ['products.is_active', true]
                ])->select('products.name', 'products.code', 'products.image')->get();
        } elseif (($category_id == 0) && ($brand_id != 0)) {
            $ezpos_product_list = Product::where([
                ['brand_id', $brand_id],
                ['is_active', true]
            ])->get();
        } else
            $ezpos_product_list = Product::where('is_active', true)->get();

        foreach ($ezpos_product_list as $key => $product) {
            $data['name'][$key] = $product->name;
            $data['code'][$key] = $product->code;
            $data['image'][$key] = $product->image;
        }
        return $data;
    }

    public function getFeatured()
    {
        $data = [];
        $ezpos_product_list = Product::where([
            ['is_active', true],
            ['featured', true]
        ])->get();
        foreach ($ezpos_product_list as $key => $product) {
            $data['name'][$key] = $product->name;
            $data['code'][$key] = $product->code;
            $data['image'][$key] = $product->image;
        }
        return $data;
    }

    public function getCustomerGroup($id)
    {
        $ezpos_customer_data = Customer::find($id);
        $ezpos_customer_group_data = CustomerGroup::find($ezpos_customer_data->customer_group_id);
        return $ezpos_customer_group_data->percentage;
    }

    public function ezposProductSearch(Request $request)
    {
        $todayDate = date('Y-m-d');
        $product_code = explode("--", $request['data']);
        $ezpos_product_data = Product::where('code', $product_code[0])->first();

        $product[] = $ezpos_product_data->name;
        $product[] = $ezpos_product_data->code;
        if ($ezpos_product_data->promotion && $todayDate <= $ezpos_product_data->last_date) {
            $product[] = $ezpos_product_data->promotion_price;
        } else
            $product[] = $ezpos_product_data->price;

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

    public function getGiftCard()
    {
        $gift_card = GiftCard::where("is_active", true)->whereDate('expired_date', '>=', date("Y-m-d"))->get(['id', 'card_no', 'amount', 'expense']);
        return json_encode($gift_card);
    }

    public function productSaleData($id)
    {
        $ezpos_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        foreach ($ezpos_product_sale_data as $key => $product_sale_data) {

            $product = Product::find($product_sale_data->product_id);
            $product_sale[0][$key] = $product->name . ': ' . $product->code;
            $product_sale[1][$key] = $product_sale_data->qty;
            if ($product_sale_data->unit != 'null')
                $product_sale[2][$key] = $product_sale_data->unit;
            else
                $product_sale[2][$key] = '';
            $product_sale[3][$key] = $product_sale_data->tax;
            $product_sale[4][$key] = $product_sale_data->tax_rate;
            $product_sale[5][$key] = $product_sale_data->discount;
            $product_sale[6][$key] = $product_sale_data->total;
        }
        return $product_sale;
    }

    public function createSale($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-edit')) {
            $ezpos_customer_list = Customer::where('is_active', true)->get();
            $ezpos_customer_group_all = CustomerGroup::where('is_active', true)->get();
            $ezpos_store_list = Store::where('is_active', true)->get();
            $ezpos_tax_list = Tax::where('is_active', true)->get();
            $ezpos_sale_data = Sale::find($id);
            $ezpos_product_sale_data = Product_Sale::where('sale_id', $id)->get();
            $ezpos_product_list = Product::where([
                ['featured', 1],
                ['is_active', true]
            ])->get();
            $product_number = count($ezpos_product_list);
            $ezpos_pos_setting_data = PosSetting::latest()->first();
            $ezpos_brand_list = Brand::where('is_active', true)->get();
            $ezpos_category_list = Category::where('is_active', true)->get();
            return view('sale.create_sale', compact('ezpos_customer_list', 'ezpos_store_list', 'ezpos_tax_list', 'ezpos_sale_data', 'ezpos_product_sale_data', 'ezpos_pos_setting_data', 'ezpos_brand_list', 'ezpos_category_list', 'ezpos_product_list', 'product_number', 'ezpos_customer_group_all'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function edit($id)
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-edit')) {
            $ezpos_customer_list = Customer::where('is_active', true)->get();
            $ezpos_customer_group_all = CustomerGroup::where('is_active', true)->get();
            $ezpos_store_list = Store::where('is_active', true)->get();
            $ezpos_tax_list = Tax::where('is_active', true)->get();
            $ezpos_sale_data = Sale::find($id);
            $ezpos_product_sale_data = Product_Sale::where('sale_id', $id)->get();
            $ezpos_product_list = Product::where([
                ['featured', 1],
                ['is_active', true]
            ])->get();
            $product_number = count($ezpos_product_list);
            $ezpos_pos_setting_data = PosSetting::latest()->first();
            $ezpos_brand_list = Brand::where('is_active', true)->get();
            $ezpos_category_list = Category::where('is_active', true)->get();
            return view('sale.edit', compact('ezpos_customer_list', 'ezpos_store_list', 'ezpos_tax_list', 'ezpos_sale_data', 'ezpos_product_sale_data', 'ezpos_pos_setting_data', 'ezpos_brand_list', 'ezpos_category_list', 'ezpos_product_list', 'product_number', 'ezpos_customer_group_all'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function update(Request $request, $id)
    {
        $data = $request->except('document');
        //return $data;
        $data['date'] = date('Y-m-d', strtotime($data['date']));
        $document = $request->document;
        if ($document) {
            $documentName = $document->getClientOriginalName();
            $document->move('public/sale/documents', $documentName);
            $data['document'] = $documentName;
        }
        $ezpos_sale_data = Sale::find($id);
        $balance = $data['grand_total'] - $ezpos_sale_data->paid_amount;
        if ($balance < 0 || $balance > 0)
            $data['payment_status'] = 2;
        else
            $data['payment_status'] = 4;

        $ezpos_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        $product_id = $data['product_id'];
        $qty = $data['qty'];
        $sale_unit = $data['sale_unit'];
        $net_unit_price = $data['net_unit_price'];
        $discount = $data['discount'];
        $tax_rate = $data['tax_rate'];
        $tax = $data['tax'];
        $total = $data['subtotal'];
        $old_product_id = [];
        $product_sale = [];
        foreach ($ezpos_product_sale_data as  $key => $product_sale_data) {
            $old_product_id[] = $product_sale_data->product_id;
            $ezpos_product_data = Product::find($product_sale_data->product_id);
            if ($ezpos_sale_data->sale_status == 1 && $ezpos_product_data->type == 'standard') {
                $old_product_qty = $product_sale_data->qty;
                $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $product_sale_data->product_id],
                    ['store_id', $ezpos_sale_data->store_id],
                ])->first();
                $ezpos_product_data->qty += $old_product_qty;
                $ezpos_product_store_data->qty += $old_product_qty;
                $ezpos_product_data->save();
                $ezpos_product_store_data->save();
            }
            if (!(in_array($old_product_id[$key], $product_id)))
                $product_sale_data->delete();
        }
        foreach ($product_id as $key => $pro_id) {
            $ezpos_product_data = Product::find($pro_id);
            if ($data['sale_status'] == 1 && $ezpos_product_data->type == 'standard') {
                $new_product_qty = $qty[$key];

                $ezpos_product_store_data = Product_Store::where([
                    ['product_id', $pro_id],
                    ['store_id', $data['store_id']],
                ])->first();
                $ezpos_product_data->qty -= $new_product_qty;
                $ezpos_product_store_data->qty -= $new_product_qty;
                $ezpos_product_data->save();
                $ezpos_product_store_data->save();
            }
            //collecting mail data
            $mail_data['products'][$key] = $ezpos_product_data->name;
            if ($ezpos_product_data->type == 'digital')
                $mail_data['file'][$key] = url('/public/product/files') . '/' . $ezpos_product_data->file;
            else
                $mail_data['file'][$key] = '';
            if ($sale_unit[$key] != 'null')
                $mail_data['unit'][$key] = $sale_unit[$key];
            else
                $mail_data['unit'][$key] = '';

            $product_sale['sale_id'] = $id;
            $product_sale['product_id'] = $pro_id;
            $product_sale['qty'] = $mail_data['qty'][$key] = $qty[$key];
            $product_sale['unit'] = $sale_unit[$key];
            $product_sale['net_unit_price'] = $net_unit_price[$key];
            $product_sale['discount'] = $discount[$key];
            $product_sale['tax_rate'] = $tax_rate[$key];
            $product_sale['tax'] = $tax[$key];
            $product_sale['total'] = $mail_data['total'][$key] = $total[$key];

            if ((in_array($pro_id, $old_product_id))) {
                Product_Sale::where([
                    ['sale_id', $id],
                    ['product_id', $pro_id]
                ])->update($product_sale);
            } else
                Product_Sale::create($product_sale);
        }
        $ezpos_sale_data->update($data);
        $ezpos_customer_data = Customer::find($data['customer_id']);
        $message = 'Sale updated successfully';
        //collecting mail data
        if ($ezpos_customer_data->email) {
            $mail_data['email'] = $ezpos_customer_data->email;
            $mail_data['reference_no'] = $ezpos_sale_data->reference_no;
            $mail_data['sale_status'] = $ezpos_sale_data->sale_status;
            $mail_data['payment_status'] = $ezpos_sale_data->payment_status;
            $mail_data['total_qty'] = $ezpos_sale_data->total_qty;
            $mail_data['total_price'] = $ezpos_sale_data->total_price;
            $mail_data['order_tax'] = $ezpos_sale_data->order_tax;
            $mail_data['order_tax_rate'] = $ezpos_sale_data->order_tax_rate;
            $mail_data['order_discount'] = $ezpos_sale_data->order_discount;
            $mail_data['shipping_cost'] = $ezpos_sale_data->shipping_cost;
            $mail_data['grand_total'] = $ezpos_sale_data->grand_total;
            $mail_data['paid_amount'] = $ezpos_sale_data->paid_amount;
            if ($mail_data['email']) {
                try {
                    Mail::send('mail.sale_details', $mail_data, function ($message) use ($mail_data) {
                        $message->to($mail_data['email'])->subject('Sale Details');
                    });
                } catch (\Exception $e) {
                    $message = 'Sale created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
                }
            }
        }

        return redirect('sale')->with('message', $message);
    }

    public function genInvoice($id)
    {
        $ezpos_sale_data = Sale::find($id);
        $ezpos_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        $ezpos_store_data = Store::find($ezpos_sale_data->store_id);
        $ezpos_customer_data = Customer::find($ezpos_sale_data->customer_id);
        $ezpos_user_data = User::find($ezpos_sale_data->user_id);
        $ezpos_payment_data = Payment::where('sale_id', $id)->get();

        $numberToWords = new NumberToWords();
        if (\App::getLocale() == 'ar')
            $numberTransformer = $numberToWords->getNumberTransformer('en');
        else
            $numberTransformer = $numberToWords->getNumberTransformer(\App::getLocale());
        $numberInWords = $numberTransformer->toWords($ezpos_sale_data->grand_total);

        return view('sale.invoice', compact('ezpos_sale_data', 'ezpos_product_sale_data', 'ezpos_store_data', 'ezpos_customer_data', 'ezpos_user_data', 'ezpos_payment_data', 'numberInWords'));
    }

    public function addPayment(Request $request)
    {
        $data = $request->all();
        if (!$data['amount'])
            $data['amount'] = 0.00;

        $ezpos_sale_data = Sale::find($data['sale_id']);
        $ezpos_customer_data = Customer::find($ezpos_sale_data->customer_id);
        $ezpos_sale_data->paid_amount += $data['amount'];
        $balance = $ezpos_sale_data->grand_total - $ezpos_sale_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $ezpos_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $ezpos_sale_data->payment_status = 4;
        $ezpos_sale_data->save();

        if ($data['paid_by_id'] == 1)
            $paying_method = 'Cash';
        elseif ($data['paid_by_id'] == 2)
            $paying_method = 'Gift Card';
        elseif ($data['paid_by_id'] == 3)
            $paying_method = 'Credit Card';
        elseif ($data['paid_by_id'] == 4)
            $paying_method = 'Cheque';
        elseif ($data['paid_by_id'] == 5)
            $paying_method = 'Paypal';
        else
            $paying_method = 'Deposit';

        $ezpos_payment_data = new Payment();
        $ezpos_payment_data->date = date('Y-m-d', strtotime($data['date']));
        $ezpos_payment_data->user_id = Auth::id();
        $ezpos_payment_data->sale_id = $ezpos_sale_data->id;
        $data['payment_reference'] = 'spr-' . date("Ymd") . '-' . date("his");
        $ezpos_payment_data->payment_reference = $data['payment_reference'];
        $ezpos_payment_data->amount = $data['amount'];
        $ezpos_payment_data->change = $data['paying_amount'] - $data['amount'];
        $ezpos_payment_data->paying_method = $paying_method;
        $ezpos_payment_data->payment_note = $data['payment_note'];
        $ezpos_payment_data->save();

        $ezpos_payment_data = Payment::latest()->first();
        $data['payment_id'] = $ezpos_payment_data->id;

        if ($paying_method == 'Gift Card') {
            $ezpos_gift_card_data = GiftCard::find($data['gift_card_id']);
            $ezpos_gift_card_data->expense += $data['amount'];
            $ezpos_gift_card_data->save();
            PaymentWithGiftCard::create($data);
        } elseif ($paying_method == 'Credit Card') {
            $ezpos_pos_setting_data = PosSetting::find(1);
            Stripe::setApiKey($ezpos_pos_setting_data->stripe_secret_key);
            $token = $data['stripeToken'];
            $amount = $data['amount'];

            $ezpos_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $ezpos_sale_data->customer_id)->first();

            if (!$ezpos_payment_with_credit_card_data) {
                // Create a Customer:
                $customer = \Stripe\Customer::create([
                    'source' => $token
                ]);

                // Charge the Customer instead of the card:
                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'customer' => $customer->id,
                ]);
                $data['customer_stripe_id'] = $customer->id;
            } else {
                $customer_id =
                    $ezpos_payment_with_credit_card_data->customer_stripe_id;

                $charge = \Stripe\Charge::create([
                    'amount' => $amount * 100,
                    'currency' => 'usd',
                    'customer' => $customer_id, // Previously stored, then retrieved
                ]);
                $data['customer_stripe_id'] = $customer_id;
            }
            $data['customer_id'] = $ezpos_sale_data->customer_id;
            $data['charge_id'] = $charge->id;
            PaymentWithCreditCard::create($data);
        } elseif ($paying_method == 'Cheque') {
            PaymentWithCheque::create($data);
        } elseif ($paying_method == 'Paypal') {
            $provider = new ExpressCheckout;
            $paypal_data['items'] = [];
            $paypal_data['items'][] = [
                'name' => 'Paid Amount',
                'price' => $data['amount'],
                'qty' => 1
            ];
            $paypal_data['invoice_id'] = $ezpos_payment_data->payment_reference;
            $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
            $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess/' . $ezpos_payment_data->id);
            $paypal_data['cancel_url'] = url('/sale');

            $total = 0;
            foreach ($paypal_data['items'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $paypal_data['total'] = $total;
            $response = $provider->setExpressCheckout($paypal_data);
            return redirect($response['paypal_link']);
        } elseif ($paying_method == 'Deposit') {
            $ezpos_customer_data->expense += $data['amount'];
            $ezpos_customer_data->save();
        }
        $message = 'Payment created successfully';
        if ($ezpos_customer_data->email) {
            $mail_data['email'] = $ezpos_customer_data->email;
            $mail_data['sale_reference'] = $ezpos_sale_data->reference_no;
            $mail_data['payment_reference'] = $ezpos_payment_data->payment_reference;
            $mail_data['payment_method'] = $ezpos_payment_data->paying_method;
            $mail_data['grand_total'] = $ezpos_sale_data->grand_total;
            $mail_data['paid_amount'] = $ezpos_payment_data->amount;
            try {
                Mail::send('mail.payment_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Payment Details');
                });
            } catch (\Exception $e) {
                $message = 'Payment created successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        return redirect('sale')->with('message', $message);
    }

    public function getPayment($id)
    {
        $ezpos_payment_list = Payment::where('sale_id', $id)->get();
        $date = [];
        $payment_reference = [];
        $paid_amount = [];
        $paying_method = [];
        $payment_id = [];
        $payment_note = [];
        $gift_card_id = [];
        $cheque_no = [];
        $change = [];
        $paying_amount = [];

        foreach ($ezpos_payment_list as $payment) {
            $date[] = date('d-m-Y', strtotime($payment->date));
            $payment_reference[] = $payment->payment_reference;
            $paid_amount[] = $payment->amount;
            $change[] = $payment->change;
            $paying_amount[] = $payment->amount + $payment->change;
            $paying_method[] = $payment->paying_method;
            if ($payment->paying_method == 'Gift Card') {
                $ezpos_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                $gift_card_id[] = $ezpos_payment_gift_card_data->gift_card_id;
            } elseif ($payment->paying_method == 'Cheque') {
                $ezpos_payment_cheque_data = PaymentWithCheque::where('payment_id', $payment->id)->first();
                $cheque_no[] = $ezpos_payment_cheque_data->cheque_no;
            } else {
                $cheque_no[] = $gift_card_id[] = null;
            }
            $payment_id[] = $payment->id;
            $payment_note[] = $payment->payment_note;
        }
        $payments[] = $date;
        $payments[] = $payment_reference;
        $payments[] = $paid_amount;
        $payments[] = $paying_method;
        $payments[] = $payment_id;
        $payments[] = $payment_note;
        $payments[] = $cheque_no;
        $payments[] = $gift_card_id;
        $payments[] = $change;
        $payments[] = $paying_amount;

        return $payments;
    }

    public function updatePayment(Request $request)
    {
        $data = $request->all();
        $ezpos_payment_data = Payment::find($data['payment_id']);
        $ezpos_sale_data = Sale::find($ezpos_payment_data->sale_id);
        $ezpos_customer_data = Customer::find($ezpos_sale_data->customer_id);
        //updating sale table
        $amount_dif = $ezpos_payment_data->amount - $data['edit_amount'];
        $ezpos_sale_data->paid_amount = $ezpos_sale_data->paid_amount - $amount_dif;
        $balance = $ezpos_sale_data->grand_total - $ezpos_sale_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $ezpos_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $ezpos_sale_data->payment_status = 4;
        $ezpos_sale_data->save();

        if ($ezpos_payment_data->paying_method == 'Deposit') {
            $ezpos_customer_data->expense -= $ezpos_payment_data->amount;
            $ezpos_customer_data->save();
        }
        if ($data['edit_paid_by_id'] == 1)
            $ezpos_payment_data->paying_method = 'Cash';
        elseif ($data['edit_paid_by_id'] == 2) {
            if ($ezpos_payment_data->paying_method == 'Gift Card') {
                $ezpos_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $data['payment_id'])->first();

                $ezpos_gift_card_data = GiftCard::find($ezpos_payment_gift_card_data->gift_card_id);
                $ezpos_gift_card_data->expense -= $ezpos_payment_data->amount;
                $ezpos_gift_card_data->save();

                $ezpos_gift_card_data = GiftCard::find($data['gift_card_id']);
                $ezpos_gift_card_data->expense += $data['edit_amount'];
                $ezpos_gift_card_data->save();

                $ezpos_payment_gift_card_data->gift_card_id = $data['gift_card_id'];
                $ezpos_payment_gift_card_data->save();
            } else {
                $ezpos_payment_data->paying_method = 'Gift Card';
                $ezpos_gift_card_data = GiftCard::find($data['gift_card_id']);
                $ezpos_gift_card_data->expense += $data['edit_amount'];
                $ezpos_gift_card_data->save();
                PaymentWithGiftCard::create($data);
            }
        } elseif ($data['edit_paid_by_id'] == 3) {
            $ezpos_pos_setting_data = PosSetting::find(1);
            Stripe::setApiKey($ezpos_pos_setting_data->stripe_secret_key);
            if ($ezpos_payment_data->paying_method == 'Credit Card') {
                $ezpos_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $ezpos_payment_data->id)->first();

                \Stripe\Refund::create(array(
                    "charge" => $ezpos_payment_with_credit_card_data->charge_id,
                ));

                $customer_id =
                    $ezpos_payment_with_credit_card_data->customer_stripe_id;

                $charge = \Stripe\Charge::create([
                    'amount' => $data['edit_amount'] * 100,
                    'currency' => 'usd',
                    'customer' => $customer_id
                ]);
                $ezpos_payment_with_credit_card_data->charge_id = $charge->id;
                $ezpos_payment_with_credit_card_data->save();
            } else {
                $token = $data['stripeToken'];
                $amount = $data['edit_amount'];
                $ezpos_payment_with_credit_card_data = PaymentWithCreditCard::where('customer_id', $ezpos_sale_data->customer_id)->first();

                if (!$ezpos_payment_with_credit_card_data) {
                    $customer = \Stripe\Customer::create([
                        'source' => $token
                    ]);

                    $charge = \Stripe\Charge::create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'customer' => $customer->id,
                    ]);
                    $data['customer_stripe_id'] = $customer->id;
                } else {
                    $customer_id =
                        $ezpos_payment_with_credit_card_data->customer_stripe_id;

                    $charge = \Stripe\Charge::create([
                        'amount' => $amount * 100,
                        'currency' => 'usd',
                        'customer' => $customer_id
                    ]);
                    $data['customer_stripe_id'] = $customer_id;
                }
                $data['customer_id'] = $ezpos_sale_data->customer_id;
                $data['charge_id'] = $charge->id;
                PaymentWithCreditCard::create($data);
            }
            $ezpos_payment_data->paying_method = 'Credit Card';
        } elseif ($data['edit_paid_by_id'] == 4) {
            if ($ezpos_payment_data->paying_method == 'Cheque') {
                $ezpos_payment_cheque_data = PaymentWithCheque::where('payment_id', $data['payment_id'])->first();
                $ezpos_payment_cheque_data->cheque_no = $data['edit_cheque_no'];
                $ezpos_payment_cheque_data->save();
            } else {
                $ezpos_payment_data->paying_method = 'Cheque';
                $data['cheque_no'] = $data['edit_cheque_no'];
                PaymentWithCheque::create($data);
            }
        } elseif ($data['edit_paid_by_id'] == 5) {
            //updating payment data
            $ezpos_payment_data->amount = $data['edit_amount'];
            $ezpos_payment_data->paying_method = 'Paypal';
            $ezpos_payment_data->payment_note = $data['edit_payment_note'];
            $ezpos_payment_data->save();

            $provider = new ExpressCheckout;
            $paypal_data['items'] = [];
            $paypal_data['items'][] = [
                'name' => 'Paid Amount',
                'price' => $data['edit_amount'],
                'qty' => 1
            ];
            $paypal_data['invoice_id'] = $ezpos_payment_data->payment_reference;
            $paypal_data['invoice_description'] = "Reference: {$paypal_data['invoice_id']}";
            $paypal_data['return_url'] = url('/sale/paypalPaymentSuccess/' . $ezpos_payment_data->id);
            $paypal_data['cancel_url'] = url('/sale');

            $total = 0;
            foreach ($paypal_data['items'] as $item) {
                $total += $item['price'] * $item['qty'];
            }

            $paypal_data['total'] = $total;
            $response = $provider->setExpressCheckout($paypal_data);
            return redirect($response['paypal_link']);
        } else {
            $ezpos_payment_data->paying_method = 'Deposit';
            $ezpos_customer_data->expense += $data['edit_amount'];
            $ezpos_customer_data->save();
        }
        //updating payment data
        $ezpos_payment_data->date = date('Y-m-d', strtotime($data['date']));
        $ezpos_payment_data->amount = $data['edit_paying_amount'];
        $ezpos_payment_data->change = $data['edit_paying_amount'] - $data['edit_amount'];
        $ezpos_payment_data->payment_note = $data['edit_payment_note'];
        $ezpos_payment_data->save();
        $message = 'Payment updated successfully';
        //collecting male data
        if ($ezpos_customer_data->email) {
            $mail_data['email'] = $ezpos_customer_data->email;
            $mail_data['sale_reference'] = $ezpos_sale_data->reference_no;
            $mail_data['payment_reference'] = $ezpos_payment_data->payment_reference;
            $mail_data['payment_method'] = $ezpos_payment_data->paying_method;
            $mail_data['grand_total'] = $ezpos_sale_data->grand_total;
            $mail_data['paid_amount'] = $ezpos_payment_data->amount;
            try {
                Mail::send('mail.payment_details', $mail_data, function ($message) use ($mail_data) {
                    $message->to($mail_data['email'])->subject('Payment Details');
                });
            } catch (\Exception $e) {
                $message = 'Payment updated successfully. Please setup your <a href="setting/mail_setting">mail setting</a> to send mail.';
            }
        }
        return redirect('sale')->with('message', $message);
    }

    public function deletePayment(Request $request)
    {
        $ezpos_payment_data = Payment::find($request['id']);
        $ezpos_sale_data = Sale::where('id', $ezpos_payment_data->sale_id)->first();
        $ezpos_sale_data->paid_amount -= $ezpos_payment_data->amount;
        $balance = $ezpos_sale_data->grand_total - $ezpos_sale_data->paid_amount;
        if ($balance > 0 || $balance < 0)
            $ezpos_sale_data->payment_status = 2;
        elseif ($balance == 0)
            $ezpos_sale_data->payment_status = 4;
        $ezpos_sale_data->save();

        if ($ezpos_payment_data->paying_method == 'Gift Card') {
            $ezpos_payment_gift_card_data = PaymentWithGiftCard::where('payment_id', $request['id'])->first();
            $ezpos_gift_card_data = GiftCard::find($ezpos_payment_gift_card_data->gift_card_id);
            $ezpos_gift_card_data->expense -= $ezpos_payment_data->amount;
            $ezpos_gift_card_data->save();
            $ezpos_payment_gift_card_data->delete();
        } elseif ($ezpos_payment_data->paying_method == 'Credit Card') {
            $ezpos_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $request['id'])->first();
            $ezpos_pos_setting_data = PosSetting::find(1);
            Stripe::setApiKey($ezpos_pos_setting_data->stripe_secret_key);
            \Stripe\Refund::create(array(
                "charge" => $ezpos_payment_with_credit_card_data->charge_id,
            ));

            $ezpos_payment_with_credit_card_data->delete();
        } elseif ($ezpos_payment_data->paying_method == 'Cheque') {
            $ezpos_payment_cheque_data = PaymentWithCheque::where('payment_id', $request['id'])->first();
            $ezpos_payment_cheque_data->delete();
        } elseif ($ezpos_payment_data->paying_method == 'Paypal') {
            $ezpos_payment_paypal_data = PaymentWithPaypal::where('payment_id', $request['id'])->first();
            if ($ezpos_payment_paypal_data) {
                $provider = new ExpressCheckout;
                $response = $provider->refundTransaction($ezpos_payment_paypal_data->transaction_id);
                $ezpos_payment_paypal_data->delete();
            }
        } elseif ($ezpos_payment_data->paying_method == 'Deposit') {
            $ezpos_customer_data = Customer::find($ezpos_sale_data->customer_id);
            $ezpos_customer_data->expense -= $ezpos_payment_data->amount;
            $ezpos_customer_data->save();
        }
        $ezpos_payment_data->delete();
        return redirect('sale')->with('not_permitted', 'Payment deleted successfully');
    }

    public function posSale()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('sales-add')) {
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if (empty($all_permission))
                $all_permission[] = 'dummy text';

            $ezpos_customer_list = Customer::where('is_active', true)->get();
            $ezpos_customer_group_all = CustomerGroup::where('is_active', true)->get();
            $ezpos_store_list = Store::where('is_active', true)->get();
            $ezpos_tax_list = Tax::where('is_active', true)->get();
            $ezpos_product_list = Product::where([
                ['featured', 1],
                ['is_active', true]
            ])->get();
            $product_number = count($ezpos_product_list);
            $ezpos_pos_setting_data = PosSetting::latest()->first();
            $ezpos_brand_list = Brand::where('is_active', true)->get();
            $ezpos_category_list = Category::where('is_active', true)->get();
            $recent_sale = Sale::where('sale_status', 1)->orderBy('id', 'desc')->take(10)->get();
            $recent_draft = Sale::where('sale_status', 2)->orderBy('id', 'desc')->take(10)->get();
            $flag = 0;

            return view('sale.pos', compact('ezpos_customer_list', 'ezpos_customer_group_all', 'ezpos_store_list', 'ezpos_product_list', 'product_number', 'ezpos_tax_list', 'ezpos_pos_setting_data', 'ezpos_brand_list', 'ezpos_category_list', 'recent_sale', 'recent_draft', 'all_permission', 'flag'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function destroy($id)
    {
        $url = url()->previous();
        $ezpos_sale_data = Sale::find($id);
        if ($ezpos_sale_data->sale_status == 1)
            $message = 'Sale deleted successfully';
        else
            $message = 'Draft deleted successfully';
        $ezpos_product_sale_data = Product_Sale::where('sale_id', $id)->get();
        $ezpos_delivery_data = Delivery::where('sale_id', $id)->first();
        foreach ($ezpos_product_sale_data as $product_sale) {
            //adjust product quantity
            if ($ezpos_sale_data->sale_status == 1) {
                $ezpos_product_data = Product::find($product_sale->product_id);
                if ($ezpos_product_data->type == 'standard') {
                    $ezpos_product_store_data = Product_Store::where([
                        ['product_id', $ezpos_product_data->id],
                        ['store_id', $ezpos_sale_data->store_id]
                    ])->first();
                    $ezpos_product_data->qty += $product_sale->qty;
                    $ezpos_product_store_data->qty += $product_sale->qty;
                    $ezpos_product_data->save();
                    $ezpos_product_store_data->save();
                }
            }
            $product_sale->delete();
        }

        $ezpos_payment_data = Payment::where('sale_id', $id)->get();
        foreach ($ezpos_payment_data as $payment) {
            if ($payment->paying_method == 'Gift Card') {
                $ezpos_payment_with_gift_card_data = PaymentWithGiftCard::where('payment_id', $payment->id)->first();
                $ezpos_gift_card_data = GiftCard::find($ezpos_payment_with_gift_card_data->gift_card_id);
                $ezpos_gift_card_data->expense -= $payment->amount;
                $ezpos_gift_card_data->save();
                $ezpos_payment_with_gift_card_data->delete();
            } elseif ($payment->paying_method == 'Cheque') {
                $ezpos_payment_cheque_data = PaymentWithCheque::where('payment_id', $payment->id)->first();
                $ezpos_payment_cheque_data->delete();
            } elseif ($payment->paying_method == 'Credit Card') {
                $ezpos_payment_with_credit_card_data = PaymentWithCreditCard::where('payment_id', $payment->id)->first();
                $ezpos_payment_with_credit_card_data->delete();
            } elseif ($payment->paying_method == 'Paypal') {
                $ezpos_payment_paypal_data = PaymentWithPaypal::where('payment_id', $payment->id)->first();
                if ($ezpos_payment_paypal_data)
                    $ezpos_payment_paypal_data->delete();
            } elseif ($payment->paying_method == 'Deposit') {
                $ezpos_customer_data = Customer::find($ezpos_sale_data->customer_id);
                $ezpos_customer_data->expense -= $payment->amount;
                $ezpos_customer_data->save();
            }
            $payment->delete();
        }
        if ($ezpos_delivery_data)
            $ezpos_delivery_data->delete();
        $ezpos_sale_data->delete();
        return Redirect::to($url)->with('not_permitted', $message);
    }
}
