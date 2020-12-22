<!DOCTYPE html>
<html>
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
    <!-- theme stylesheet-->

    <style type="text/css">
        #receipt-data { font-size: 14px; }
        @media print {
            @page { size: portrait; }
        }
    </style>

    <script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.min.js') ?>"></script>
    <script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
    </script>
  </head>
<body>
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center d-print-none"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div>
@endif
<div style="max-width: 300px; margin: 0 auto;">
    @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
    @else
        @php $url = url()->previous(); @endphp
    @endif
    <div class="row d-print-none">
        <span class="col-md-6">
            <a href="{{$url}}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a> 
        </span>
        <span class="col-md-6">
            <button onclick="window.print();" class="btn btn-block btn-primary"><i class="fa fa-print"></i> {{trans('file.Print')}}</button> 
        </span>
    </div>
        <div id="receipt-data" style="padding-top: 20px">
            <div class="text-center">
                @if($general_setting->site_logo)
                    <img src="{{url('public/logo', $general_setting->site_logo)}}" height="42" width="42" >
                @endif
                <h4 style="text-transform:uppercase;">{{$ezpos_user_data->company_name}}</h4>
                
                <p>{{$ezpos_store_data->address}}
                    <br>{{trans('file.Phone Number')}}: {{$ezpos_store_data->phone}}
                </p>
            </div>
            <p>{{trans('file.reference')}}: {{$ezpos_sale_data->reference_no}}<br>
                {{trans('file.Date')}}: {{$ezpos_sale_data->created_at}}<br>
                {{trans('file.customer')}}: {{$ezpos_customer_data->name}}
            </p>
            <table class="table table-condensed">
                <tbody>
                    @foreach($ezpos_product_sale_data as $product_sale_data)
                    @php $ezpos_product_data = \App\Product::find($product_sale_data->product_id) @endphp
                    <tr class="border-bottom">
                        <td>{{$ezpos_product_data->name}}</td>
                        <td class="text-center">{{$product_sale_data->qty}} x {{number_format((float)($product_sale_data->total / $product_sale_data->qty))}}</td>
                        <td class="text-right">{{number_format((float)$product_sale_data->total)}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">{{trans('file.Total')}}</th>
                        <th class="text-right">{{number_format((float)$ezpos_sale_data->total_price)}}</th>
                    </tr>
                    @if($ezpos_sale_data->order_tax)
                    <tr>
                        <th colspan="2">{{trans('file.Order Tax')}}</th>
                        <th class="text-right">{{number_format((float)$ezpos_sale_data->order_tax)}}</th>
                    </tr>
                    @endif
                    @if($ezpos_sale_data->order_discount)
                    <tr>
                        <th colspan="2">{{trans('file.Order Discount')}}</th>
                        <th class="text-right">{{number_format((float)$ezpos_sale_data->order_discount)}}</th>
                    </tr>
                    @endif
                    @if($ezpos_sale_data->coupon_discount)
                    <tr>
                        <th colspan="2">{{trans('file.Coupon Discount')}}</th>
                        <th class="text-right">{{number_format((float)$ezpos_sale_data->coupon_discount)}}</th>
                    </tr>
                    @endif
                    @if($ezpos_sale_data->shipping_cost)
                    <tr>
                        <th colspan="2">{{trans('file.Shipping Cost')}}</th>
                        <th class="text-right">{{number_format((float)$ezpos_sale_data->shipping_cost)}}</th>
                    </tr>
                    @endif
                    <tr>
                        <th colspan="2">{{trans('file.grand total')}}</th>
                        <th class="text-right">{{number_format((float)$ezpos_sale_data->grand_total)}}</th>
                    </tr>
                     @foreach($ezpos_payment_data as $payment_data)
                    <tr>
                        <th colspan="2">{{trans('file.Amount')}}</th>
                        <th class="text-right"> {{number_format((float)$payment_data->amount)}}</th>
                    </tr>
                     <tr>
                        <th colspan="2">{{trans('file.Change')}}</th>
                        <th class="text-right"> {{number_format((float)$payment_data->change)}}</th>
                    </tr>
                    @endforeach
                    <tr>
                        @if($general_setting->currency_position == 'prefix')
                        <th colspan="3" class="text-center">{{trans('file.In Words')}}: <span style="text-transform: uppercase">{{$general_setting->currency}}</span> <span style="text-transform: capitalize">{{str_replace("-"," ",$numberInWords)}} {{trans('file.dollar')}}</span></th>
                        @else
                        <th colspan="3" class="text-center">{{trans('file.In Words')}}: <span style="text-transform: capitalize">{{str_replace("-"," ",$numberInWords)}}</span> <span style="text-transform: capitalize">{{$general_setting->currency}}</span></th>
                        @endif
                    </tr>
                </tfoot>
            </table>
            <table class="table table-striped table-condensed">
                <tbody>
                    @foreach($ezpos_payment_data as $payment_data)
                    <tr>
                        <td>{{trans('file.Paid By')}}: {{$payment_data->paying_method}}</td>
                        <td colspan="2">{{trans('file.Amount')}}: {{number_format((float)$payment_data->amount)}}</td>
                        <td>{{trans('file.Change')}}: {{number_format((float)$payment_data->change)}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <p  class="text-center" style="font-size: 12px; padding-top:15px; ">
                {{trans('file.Invoice Generated By')}} <strong>{{$general_setting->site_title}}</strong>.
                {{trans('file.Developed By')}} <a style="text-decoration: none; color: #60bf62;" href="http://dubanproject.com"><strong>Duban Project</strong></a>
            </p>
        </div>
</div>

<script type="text/javascript">
    function auto_print() {     
        window.print()
    }
    setTimeout(auto_print, 1000);
</script>

</body>
</html>
