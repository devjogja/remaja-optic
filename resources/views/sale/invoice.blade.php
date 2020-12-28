<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

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
        #receipt-data {
            font-size: 14px;
        }

        .table td,
        .table th {
            padding: 0.20rem;
        }

        @media print {
            @page {
                size: portrait;
                margin: 0 10mm;
            }
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
    <div style="max-width: 297mm; margin: 0 auto; padding: 5px;">
        @if(preg_match('~[0-9]~', url()->previous()))
        @php $url = '../../pos'; @endphp
        @else
        @php $url = url()->previous(); @endphp
        @endif
        <div class="row d-print-none" style='width:50%; margin:0 auto;'>
            <span class="col-md-6">
                <a href="{{$url}}" class="btn btn-block btn-info"><i class="fa fa-arrow-left"></i> {{trans('file.Back')}}</a>
            </span>
            <span class="col-md-6">
                <button onclick="window.print();" class="btn btn-block btn-primary"><i class="fa fa-print"></i> {{trans('file.Print')}}</button>
            </span>
        </div>
        <div id="receipt-data" style="padding-top: 20px;">
            <div style='border-bottom: 1.8px dotted black'>
                <table style='width: 100%;'>
                    <tr>
                        <td style='width: 15%; text-align:center;'>
                            @if($general_setting->site_logo)
                            <img src="{{url('public/logo', $general_setting->site_logo)}}" width="70">
                            @endif
                        </td>
                        <td>
                            <span style="text-transform: uppercase; font-weight: bold; font-size: 18pt;">{{$ezpos_store_data->name}}</span><br />
                            <span style="font-weight: bold; font-size: 10pt;">Optical House & Lens Center - Soft Lens</span>
                        </td>
                        <td class='text-right' style=' vertical-align: top;'>
                            No.Nota : {{$ezpos_sale_data->reference_no}}<br/>
                            Tanggal : {{date('d/m/Y', strtotime($ezpos_sale_data->created_at))}}
                        </td>
                    </tr>
                    <tr>
                        <td colspan='3'><i>Store : {{$ezpos_store_data->address}}, No.Telp : {{$ezpos_store_data->phone}}</i></td>
                    </tr>
                </table>
            </div>
            <div>
                <table style='width: 100%;'>
                    <tr>
                        <td style='width: 70%'>
                            <table>
                                <tr>
                                    <td style='width: 40%'>Nama</td>
                                    <td>: {{$ezpos_customer_data->name}}</td>
                                </tr>
                                <tr>
                                    <td>Alamat</td>
                                    <td>: {{$ezpos_customer_data->address}} {{$ezpos_customer_data->city}}</td>
                                </tr>
                                <tr>
                                    <td>No.Telp</td>
                                    <td>: {{$ezpos_customer_data->phone_number}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
            <div style='margin-top: 8px;'>
                <table class="table table-condensed">
                    <tbody>
                        @foreach($ezpos_product_sale_data as $product_sale_data)
                        @php $ezpos_product_data = \App\Product::find($product_sale_data->product_id) @endphp
                        @php $ezpos_product_category = \App\Category::find($ezpos_product_data->category_id) @endphp
                        <tr class="border-bottom">
                            <td style='width: 15%'>{{$ezpos_product_category->name}}</td>
                            <td>: [{{$ezpos_product_data->code}}] {{$ezpos_product_data->name}}</td>
                            <td class="text-center">{{$product_sale_data->qty}} x {{number_format((float)($product_sale_data->total / $product_sale_data->qty))}}</td>
                            <td class="text-right" style='width: 15%'>{{number_format((float)$product_sale_data->total)}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Total')}}</th>
                            <th class="text-right">{{number_format((float)$ezpos_sale_data->total_price)}}</th>
                        </tr>
                        @if($ezpos_sale_data->order_tax)
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Order Tax')}}</th>
                            <th class="text-right">{{number_format((float)$ezpos_sale_data->order_tax)}}</th>
                        </tr>
                        @endif
                        @if($ezpos_sale_data->order_discount)
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Order Discount')}}</th>
                            <th class="text-right">{{number_format((float)$ezpos_sale_data->order_discount)}}</th>
                        </tr>
                        @endif
                        @if($ezpos_sale_data->coupon_discount)
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Coupon Discount')}}</th>
                            <th class="text-right">{{number_format((float)$ezpos_sale_data->coupon_discount)}}</th>
                        </tr>
                        @endif
                        @if($ezpos_sale_data->shipping_cost)
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Shipping Cost')}}</th>
                            <th class="text-right">{{number_format((float)$ezpos_sale_data->shipping_cost)}}</th>
                        </tr>
                        @endif
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.grand total')}}</th>
                            <th class="text-right">{{number_format((float)$ezpos_sale_data->grand_total)}}</th>
                        </tr>
                        @foreach($ezpos_payment_data as $payment_data)
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Amount')}}</th>
                            <th class="text-right"> {{number_format((float)$payment_data->amount)}}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class='text-right'>{{trans('file.Change')}}</th>
                            <th class="text-right"> {{number_format((float)$payment_data->change)}}</th>
                        </tr>
                        @endforeach
                    </tfoot>
                </table>
            </div>
            <div>
                <table style='width: 100%'>
                    <tr>
                        <td style='width: 50%'>
                            <table class='table table-bordered'>
                                <tr>
                                    <td></td>
                                    <th class='text-center'>SPH</th>
                                    <th class='text-center'>CYL</th>
                                    <th class='text-center'>AXIS</th>
                                    <th class='text-center'>ADD</th>
                                </tr>
                                <tr>
                                    <th class='text-center'>R</th>
                                    <td>{{$hasil_refraksi->sphr}}</td>
                                    <td>{{$hasil_refraksi->cylr}}</td>
                                    <td>{{$hasil_refraksi->axisr}}</td>
                                    <td>{{$hasil_refraksi->addr}}</td>
                                </tr>
                                <tr>
                                    <th class='text-center'>L</th>
                                    <td>{{$hasil_refraksi->sphl}}</td>
                                    <td>{{$hasil_refraksi->cyll}}</td>
                                    <td>{{$hasil_refraksi->axisl}}</td>
                                    <td>{{$hasil_refraksi->addl}}</td>
                                </tr>
                                <tr>
                                    <th>PD</th>
                                    <td colspan="4">{{$hasil_refraksi->pdd}}</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style='width:100%'>
                                <tr>
                                    <td style='width:50%' class='text-center'><br><br><br><br>({{$ezpos_customer_data->name}})</td>
                                    <td class='text-center'><br><br><br><br>({{$ezpos_user_data->name}})</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
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