@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('not_permitted') !!}</div> 
@endif

<section>
    <div class="container-fluid">
        @if(in_array("sales-add", $all_permission))
            <a href="{{url('/pos')}}" class="btn btn-info"><i class="fa fa-plus"></i> {{trans('file.add')}} {{trans('file.Sale')}}</a>
        @endif
    </div>
    <div class="table-responsive">
        <table id="sale-table" class="table table-hover sale-list">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}} No</th>
                    <th>{{trans('file.customer')}}</th>
                    <th>{{trans('file.Sale')}} {{trans('file.status')}}</th>
                    <th>{{trans('file.Payment')}} {{trans('file.status')}}</th>
                    <th>{{trans('file.grand total')}}</th>
                    <th>{{trans('file.Paid')}}</th>
                    <th>{{trans('file.Due')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ezpos_sale_all as $key => $sale)
                <?php 
                    $customer = DB::table('customers')->find($sale->customer_id);
                    $date = date('d-m-Y', strtotime($sale->date));
                    $user = DB::table('users')->find($sale->user_id);
                    $store = DB::table('stores')->find($sale->store_id);
                    if($sale->sale_status == 1)
                        $sale_status = 'Completed';
                    else
                        $sale_status = 'Draft';
                    
                    $replace = Array(
                        '\\' => '',
                        '"' => '\"'
                    );

                    $sale->sale_note = str_replace(array_keys($replace), $replace, $sale->sale_note);
                    $sale->sale_note = preg_replace('/\r\n+/', "<br>", $sale->sale_note);
                    $sale->staff_note = str_replace(array_keys($replace), $replace, $sale->staff_note);
                    $sale->staff_note = preg_replace('/\r\n+/', "<br>", $sale->staff_note);
                ?>
                <tr class="sale-link" data-sale='["{{$date}}", "{{$sale->reference_no}}", "{{$sale_status}}", "{{$customer->name}}", "{{$customer->phone_number}}", "{{$customer->address}}", "{{$customer->city}}", "{{$sale->id}}", "{{$sale->total_tax}}", "{{$sale->total_discount}}", "{{$sale->total_price}}", "{{$sale->order_tax}}", "{{$sale->order_tax_rate}}", "{{$sale->order_discount}}", "{{$sale->shipping_cost}}", "{{$sale->grand_total}}", "{{$sale->paid_amount}}", "{{$sale->sale_note}}", "{{$sale->staff_note}}", "{{$user->name}}", "{{$user->email}}", "{{$store->name}}"]'>
                    <td>{{$key}}</td>
                    <td>{{$date}}</td>
                    <td>{{ $sale->reference_no }}</td>
                    <td>
                        {{ $customer->name }}
                        <input type="hidden" class="deposit" value="{{$customer->deposit - $customer->expense}}">
                    </td>
                    @if($sale_status == 'Completed')
                    <td><div class="badge badge-success">{{$sale_status}}</div></td>
                    @else
                    <td><div class="badge badge-warning">{{$sale_status}}</div></td>
                    @endif
                    @if($sale->payment_status == 2)
                        <td><div class="badge badge-danger">Due</div></td>
                    @else
                        <td><div class="badge badge-success">Paid</div></td>
                    @endif
                    <td class="grand-total">{{ $sale->grand_total }}</td>
                    @if($sale->paid_amount)
                    <td class="paid-amount">{{ $sale->paid_amount }}</td>
                    @else
                    <td>0.00</td>
                    @endif
                    @if($sale->grand_total - $sale->paid_amount)
                    <td class="balance">{{ $sale->grand_total - $sale->paid_amount }}</td>
                    @else
                    <td>0.00</td>
                    @endif
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <a href="{{route('sale.invoice', ['id' => $sale->id])}}" class="btn btn-link"><i class="fa fa-copy"></i> {{trans('file.Generate')}} {{trans('file.Invoice')}}</a>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i> {{trans('file.View')}}</button>
                                </li>
                                @if(in_array("sales-edit", $all_permission))
                                <li>
                                    @if($sale->sale_status == 1)
                                        <a href="{{ route('sale.edit', ['id' => $sale->id]) }}" class="btn btn-link"><i class="fa fa-edit"></i> {{trans('file.edit')}}</a>
                                    @else
                                        <a href="{{ url('sale/'.$sale->id.'/create') }}" class="btn btn-link"><i class="fa fa-edit"></i> {{trans('file.edit')}}</a>
                                    @endif
                                </li>
                                @endif
                                <li>
                                    <button type="button" class="add-payment btn btn-link" data-id = "{{$sale->id}}" data-toggle="modal" data-target="#add-payment"><i class="fa fa-plus"></i> {{trans('file.add')}} {{trans('file.Payment')}}</button>
                                </li>
                                <li>
                                    <button type="button" class="get-payment btn btn-link" data-id = "{{$sale->id}}"><i class="fa fa-money"></i> {{trans('file.View')}} {{trans('file.Payment')}}</button>
                                </li>
                                <li>
                                    <button type="button" class="add-delivery btn btn-link" data-id = "{{$sale->id}}"><i class="fa fa-truck"></i> {{trans('file.add')}} {{trans('file.Delivery')}}</button>
                                </li>
                                @if(in_array("sales-delete", $all_permission))
                                {{ Form::open(['route' => ['sale.destroy', $sale->id], 'method' => 'DELETE'] ) }}
                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="fa fa-trash"></i> {{trans('file.delete')}}</button>
                                </li>
                                {{ Form::close() }}
                                @endif
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="tfoot active">
                <th></th>
                <th>{{trans('file.Total')}}</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tfoot>
        </table>
    </div>
</section>

<div id="sale-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
      <div class="modal-content">
        <div class="container mt-3 pb-2 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="fa fa-print"></i> {{trans('file.Print')}}</button>
                </div>
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid" style="color: #7c5cc4;">{{$general_setting->site_title}}</h3>
                </div>
                <div class="col-md-3">
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true">×</span></button>
                </div>
                <div class="col-md-12 text-center">
                    <i style="font-size: 15px;">{{trans('file.Sale')}} {{trans('file.Details')}}</i>
                </div>
            </div>
        </div>
            <div id="sale-content" class="modal-body">
            </div>
            <br>
            <table class="table table-bordered product-sale-list">
                <thead>
                    <th>#</th>
                    <th>{{trans('file.product')}}</th>
                    <th>Qty</th>
                    <th>{{trans('file.Unit Price')}}</th>
                    <th>{{trans('file.Tax')}}</th>
                    <th>{{trans('file.Discount')}}</th>
                    <th>{{trans('file.Subtotal')}}</th>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="sale-footer" class="modal-body"></div>
      </div>
    </div>
</div>

<div id="view-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.All')}} {{trans('file.Payment')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover payment-list">
                    <thead>
                        <tr>
                            <th>{{trans('file.date')}}</th>
                            <th>{{trans('file.reference')}} No</th>
                            <th>{{trans('file.Amount')}}</th>
                            <th>{{trans('file.Paid')}} {{trans('file.By')}}</th>
                            <th>{{trans('file.action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.add')}} {{trans('file.Payment')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'sale.add-payment', 'method' => 'post', 'files' => true, 'class' => 'payment-form' ]) !!}
                    <div class="form-group">
                        <label><strong>{{trans('file.date')}}</strong></label>
                        <input type="text" name="date" class="form-control date" value="{{date('d-m-Y')}}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label><strong>{{trans('file.Paying Amount')}} *</strong></label>
                            <input type="number" name="paying_amount" class="form-control"  step="any" required>
                        </div>
                        <div class="col-md-6">
                            <label><strong>{{trans('file.Payable Amount')}} *</strong></label>
                            <input type="number" id="amount" name="amount" class="form-control"  step="any" required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label><strong>{{trans('file.Change')}} : </strong></label>
                            <p class="change ml-2">0.00</p>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label><strong>{{trans('file.Paid By')}}</strong></label>
                            <select name="paid_by_id" class="form-control">
                                <option value="1">Cash</option>
                                <option value="2">Gift Card</option>
                                <option value="3">Credit Card</option>
                                <option value="4">Cheque</option>
                                <option value="5">Paypal</option>
                                <option value="6">Deposit</option>
                            </select>
                        </div>
                    </div>
                    <div class="gift-card form-group">
                        <label><strong> {{trans('file.Gift Card')}}</strong></label>
                        <select id="gift_card_id" name="gift_card_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Gift Card...">
                            @php 
                                $balance = [];
                                $expired_date = [];
                            @endphp
                            @foreach($ezpos_gift_card_list as $gift_card)
                            <?php 
                                $balance[$gift_card->id] = $gift_card->amount - $gift_card->expense;
                                $expired_date[$gift_card->id] = $gift_card->expired_date;
                            ?>
                                <option value="{{$gift_card->id}}">{{$gift_card->card_no}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="card-element" class="form-control">
                        </div>
                        <div class="card-errors" role="alert"></div>
                    </div>
                    <div id="cheque">
                        <div class="form-group">
                            <label><strong>{{trans('file.Cheque')}} No</strong></label>
                            <input type="text" name="cheque_no" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><strong>{{trans('file.Payment')}} {{trans('file.Note')}}</strong></label>
                        <textarea rows="3" class="form-control" name="payment_note"></textarea>
                    </div>
                    <input type="hidden" name="sale_id">
                    <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="edit-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.update')}} {{trans('file.Payment')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'sale.update-payment', 'method' => 'post', 'class' => 'payment-form' ]) !!}
                    <div class="form-group">
                        <label><strong>{{trans('file.date')}}</strong></label>
                        <input type="text" name="date" class="form-control date" value="{{date('d-m-Y')}}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label><strong>{{trans('file.Paying Amount')}} *</strong></label>
                            <input type="text" name="edit_paying_amount" class="form-control"  step="any" required>
                        </div>
                        <div class="col-md-6">
                            <label><strong>{{trans('file.Payable Amount')}} *</strong></label>
                            <input type="text" name="edit_amount" class="form-control"  step="any" required>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label><strong>{{trans('file.Change')}} : </strong></label>
                            <p class="change ml-2">0.00</p>
                        </div>
                        <div class="col-md-6 mt-1">
                            <label><strong>{{trans('file.Paid By')}}</strong></label>
                            <select name="edit_paid_by_id" class="form-control selectpicker">
                                <option value="1">Cash</option>
                                <option value="2">Gift Card</option>
                                <option value="3">Credit Card</option>
                                <option value="4">Cheque</option>
                                <option value="5">Paypal</option>
                                <option value="6">Deposit</option>
                            </select>
                        </div>
                    </div>
                    <div class="gift-card form-group">
                        <label><strong> {{trans('file.Gift Card')}}</strong></label>
                        <select id="gift_card_id" name="gift_card_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Gift Card...">
                            @foreach($ezpos_gift_card_list as $gift_card)
                                <option value="{{$gift_card->id}}">{{$gift_card->card_no}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mt-2">
                        <div class="card-element" class="form-control">
                        </div>
                        <div class="card-errors" role="alert"></div>
                    </div>
                    <div id="edit-cheque">
                        <div class="form-group">
                            <label><strong>{{trans('file.Cheque')}} No</strong></label>
                            <input type="text" name="edit_cheque_no" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><strong>{{trans('file.Payment')}} {{trans('file.Note')}}</strong></label>
                        <textarea rows="3" class="form-control" name="edit_payment_note"></textarea>
                    </div>
                    <input type="hidden" name="payment_id">
                    <button type="submit" class="btn btn-primary">{{trans('file.update')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div id="add-delivery" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
    <div role="document" class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="exampleModalLabel" class="modal-title">{{trans('file.add')}} {{trans('file.Delivery')}}</h5>
                <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                {!! Form::open(['route' => 'delivery.store', 'method' => 'post', 'files' => true]) !!}
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.date')}}</strong></label>
                        <input type="text" name="date" class="form-control date" value="" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Delivery')}} {{trans('file.reference')}}</strong></label>
                        <p id="dr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Sale')}} {{trans('file.reference')}}</strong></label>
                        <p id="sr"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Status')}} *</strong></label>
                        <select name="status" required class="form-control selectpicker">
                            <option value="packing">Packing</option>
                            <option value="delivering">Delivering</option>
                            <option value="delivered">Delivered</option>
                        </select>
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label><strong>{{trans('file.Delivered')}} {{trans('file.By')}}</strong></label>
                        <input type="text" name="delivered_by" class="form-control">
                    </div>
                    <div class="col-md-6 mt-2 form-group">
                        <label><strong>{{trans('file.Recieved')}} {{trans('file.By')}}</strong></label>
                        <input type="text" name="recieved_by" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.customer')}} *</strong></label>
                        <p id="customer"></p>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Attach File')}}</strong></label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Address')}} *</strong></label>
                        <textarea rows="3" name="address" class="form-control" required></textarea>
                    </div>
                    <div class="col-md-6 form-group">
                        <label><strong>{{trans('file.Note')}}</strong></label>
                        <textarea rows="3" name="note" class="form-control"></textarea>
                    </div>
                </div>
                <input type="hidden" name="reference_no">
                <input type="hidden" name="sale_id">
                <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    var public_key = <?php echo json_encode($ezpos_pos_setting_data->stripe_public_key); ?>;

    $("ul#sale").siblings('a').attr('aria-expanded','true');
    $("ul#sale").addClass("show");
    $("ul#sale #sale-list-menu").addClass("active");

    var date = $('.date');
    date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y', strtotime('')); ?>",
     autoclose: true,
     todayHighlight: true
     });

    var balance = <?php echo json_encode($balance) ?>;
    var expired_date = <?php echo json_encode($expired_date) ?>;
    var current_date = <?php echo json_encode(date("Y-m-d")) ?>;
    var payment_date = [];
    var payment_reference = [];
    var paid_amount = [];
    var paying_method = [];
    var payment_id = [];
    var payment_note = [];
    var deposit;

    $(".gift-card").hide();
    $(".card-element").hide();
    $("#cheque").hide();
    $('#view-payment').modal('hide');

    $("tr.sale-link td:not(:first-child, :last-child)").on("click", function(){
        var sale = $(this).parent().data('sale');
        saleDetails(sale);
    });

    $(".view").on("click", function(){
        var sale = $(this).parent().parent().parent().parent().parent().data('sale');
        saleDetails(sale);
    });

$("#print-btn").on("click", function(){
      var divToPrint=document.getElementById('sale-details');
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
      newWin.document.close();
      setTimeout(function(){newWin.close();},10);
});

    $("table.sale-list tbody").on("click", ".add-payment", function(event) {
        $("#cheque").hide();
        $(".gift-card").hide();
        $(".card-element").hide();
        $('select[name="paid_by_id"]').val(1);
        $('.selectpicker').selectpicker('refresh');
        rowindex = $(this).closest('tr').index();
        deposit = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.deposit').val();
        var sale_id = $(this).data('id').toString();
        var balance = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(9)').text();
        $('input[name="amount"]').val(balance);
        $('input[name="paying_amount"]').val(balance);
        $('input[name="sale_id"]').val(sale_id);
    });

    $("table.sale-list tbody").on("click", ".get-payment", function(event) {
        rowindex = $(this).closest('tr').index();
        deposit = $('table.sale-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.deposit').val();
        var id = $(this).data('id').toString();
        $.get('sale/getpayment/' + id, function(data) {
            $(".payment-list tbody").remove();
            var newBody = $("<tbody>");
            payment_date  = data[0];
            payment_reference = data[1];
            paid_amount = data[2];
            paying_method = data[3];
            payment_id = data[4];
            payment_note = data[5];
            cheque_no = data[6];
            gift_card_id = data[7];
            change = data[8];
            paying_amount = data[9];

            $.each(payment_date, function(index){
                var newRow = $("<tr>");
                var cols = '';

                cols += '<td>' + payment_date[index] + '</td>';
                cols += '<td>' + payment_reference[index] + '</td>';
                cols += '<td>' + paid_amount[index] + '</td>';
                cols += '<td>' + paying_method[index] + '</td>';
                if(paying_method[index] != 'Paypal')
                    cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu"><li><button type="button" class="btn btn-link edit-btn" data-id="' + payment_id[index] +'" data-clicked=false data-toggle="modal" data-target="#edit-payment"><i class="fa fa-edit"></i> {{trans("file.edit")}}</button></li><li class="divider"></li>{{ Form::open(['route' => 'sale.delete-payment', 'method' => 'post'] ) }}<li><input type="hidden" name="id" value="' + payment_id[index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmPaymentDelete()"><i class="fa fa-trash"></i> {{trans("file.delete")}}</button></li>{{ Form::close() }}</ul></div></td>';
                else
                    cols += '<td><div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans("file.action")}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">{{ Form::open(['route' => 'sale.delete-payment', 'method' => 'post'] ) }}<li><input type="hidden" name="id" value="' + payment_id[index] + '" /> <button type="submit" class="btn btn-link" onclick="return confirmPaymentDelete()"><i class="fa fa-trash"></i> {{trans("file.delete")}}</button></li>{{ Form::close() }}</ul></div></td>';

                newRow.append(cols);
                newBody.append(newRow);
                $("table.payment-list").append(newBody);
            });
            $('#view-payment').modal('show');
        });
    });
    
    $("table.payment-list").on("click", ".edit-btn", function(event) {
        $(".edit-btn").attr('data-clicked', true);        
        $(".card-element").hide();
        $("#edit-cheque").hide();
        $('.gift-card').hide();
        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
        var id = $(this).data('id').toString();
        $.each(payment_id, function(index){
            if(payment_id[index] == parseFloat(id)){
                $('#edit-payment input[name="date"]').val(payment_date[index]);
                $('input[name="payment_id"]').val(payment_id[index]);
                if(paying_method[index] == 'Cash')
                    $('select[name="edit_paid_by_id"]').val(1);
                else if(paying_method[index] == 'Gift Card'){
                    $('select[name="edit_paid_by_id"]').val(2);
                    $('#edit-payment select[name="gift_card_id"]').val(gift_card_id[index]);
                    $('.gift-card').show();
                    $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                }
                else if(paying_method[index] == 'Credit Card'){
                    $('select[name="edit_paid_by_id"]').val(3);
                    $.getScript( "public/vendor/stripe/checkout.js" );
                    $(".card-element").show();
                    $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', true);
                }
                else if(paying_method[index] == 'Cheque'){
                    $('select[name="edit_paid_by_id"]').val(4);
                    $("#edit-cheque").show();
                    $('input[name="edit_cheque_no"]').val(cheque_no[index]);
                }
                else
                    $('select[name="edit_paid_by_id"]').val(6);

                $('.selectpicker').selectpicker('refresh');
                $('input[name="edit_paying_amount"]').val(paying_amount[index]);
                $('#edit-payment .change').text(change[index]);
                $('input[name="edit_amount"]').val(paid_amount[index]);
                $('textarea[name="edit_payment_note"]').val(payment_note[index]);
                return false;
            }
        });
        $('#view-payment').modal('hide');
    });

    $('select[name="paid_by_id"]').on("change", function() {       
        var id = $(this).val();
        $(".payment-form").off("submit");
        if(id == 2){
            $(".gift-card").show();
            $(".card-element").hide();
            $("#cheque").hide();
        }
        else if (id == 3) {
            $.getScript( "public/vendor/stripe/checkout.js" );
            $(".card-element").show();
            $(".gift-card").hide();
            $("#cheque").hide();
        } else if (id == 4) {
            $("#cheque").show();
            $(".gift-card").hide();
            $(".card-element").hide();
        } else if (id == 5) {
            $(".card-element").hide();
            $(".gift-card").hide();
            $("#cheque").hide();
        } else {
            $(".card-element").hide();
            $(".gift-card").hide();
            $("#cheque").hide();
            if(id == 6){
                if($('#add-payment input[name="amount"]').val() > parseFloat(deposit))
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
            }
        }
    });
    
    $('#add-payment select[name="gift_card_id"]').on("change", function() {
        var id = $(this).val();
        if(expired_date[id] < current_date)
            alert('This card is expired!');
        else if($('#add-payment input[name="amount"]').val() > balance[id]){
            alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
        }
    });

    $('input[name="amount"]').on("input", function() {
        $(".change").text(parseFloat($('input[name="paying_amount"]').val() - $(this).val()).toFixed(2));
        var id = $('#add-payment select[name="paid_by_id"]').val();
        var amount = $(this).val();
        if(id == 2){
            id = $('#add-payment select[name="gift_card_id"]').val();
            if(amount > balance[id])
                alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
        }
        else if(id == 6){
            if(amount > parseFloat(deposit))
                alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
        }
    });

    $('input[name="paying_amount"]').on("input", function() {
        $(".change").text(parseFloat( $(this).val() - $('#amount').val() ).toFixed(2));
    });

    $('select[name="edit_paid_by_id"]').on("change", function() {        
        var id = $(this).val();
        $(".payment-form").off("submit");
        if(id == 2){
            $(".card-element").hide();
            $("#edit-cheque").hide();
            $('.gift-card').show();
        }
        else if (id == 3) {
            $(".edit-btn").attr('data-clicked', true);
            $.getScript( "public/vendor/stripe/checkout.js" );
            $(".card-element").show();
            $("#edit-cheque").hide();
            $('.gift-card').hide();
        } else if (id == 4) {
            $("#edit-cheque").show();
            $(".card-element").hide();
            $('.gift-card').hide();
        } else {
            $(".card-element").hide();
            $("#edit-cheque").hide();
            $('.gift-card').hide();
            if(id == 6){
                if($('input[name="edit_amount"]').val() > parseFloat(deposit))
                    alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
            }
        }
    });

    $('#edit-payment select[name="gift_card_id"]').on("change", function() {
        var id = $(this).val();
        if(expired_date[id] < current_date)
            alert('This card is expired!');
        else if($('#edit-payment input[name="edit_amount"]').val() > balance[id])
            alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
    });

    $('input[name="edit_amount"]').on("input", function() {
        $("#edit-payment .change").text(parseFloat($('input[name="edit_paying_amount"]').val() - $(this).val()).toFixed(2));
        var amount = $(this).val();
        var id = $('#edit-payment select[name="gift_card_id"]').val();
        if(amount > balance[id]){
            alert('Amount exceeds card balance! Gift Card balance: '+ balance[id]);
        }
        var id = $('#edit-payment select[name="edit_paid_by_id"]').val();
        if(id == 6){
            if(amount > parseFloat(deposit))
                alert('Amount exceeds customer deposit! Customer deposit : ' + deposit);
        }
    });

    $('input[name="edit_paying_amount"]').on("input", function() {
        $("#edit-payment .change").text(parseFloat( $(this).val() - $('input[name="edit_amount"]').val() ).toFixed(2));
    });

    $("table.sale-list tbody").on("click", ".add-delivery", function(event) {
        var id = $(this).data('id').toString();
        $.get('delivery/create/'+id, function(data) {
            $('#dr').text(data[0]);
            $('#sr').text(data[1]);
            if(data[2]){
                $('select[name="status"]').val(data[2]);
                $('.selectpicker').selectpicker('refresh');
            }
            $('input[name="delivered_by"]').val(data[3]);
            $('input[name="recieved_by"]').val(data[4]);
            $('#customer').text(data[5]);
            $('textarea[name="address"]').val(data[6]);
            $('textarea[name="note"]').val(data[7]);
            $('#add-delivery input[name="date"]').val(data[8]);
            $('input[name="reference_no"]').val(data[0]);
            $('input[name="sale_id"]').val(id);
            $('#add-delivery').modal('show');
        });
    });

    $('#sale-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 9]
            },
            {
                'checkboxes': {
                   'selectRow': true
                },
                'targets': 0
            }
        ],
        'select': { style: 'multi',  selector: 'td:first-child'},
        'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: 'Blfrtip',
        buttons: [
            {
                extend: 'pdf',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                action: function(e, dt, button, config) {
                    datatable_sum(dt, true);
                    $.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
                    datatable_sum(dt, false);
                },
                footer:true
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            },
        ],
        drawCallback: function () {
            var api = this.api();
            datatable_sum(api, false);
        }
    } );

    function datatable_sum(dt_selector, is_calling_first) {
        if (dt_selector.rows( '.selected' ).any() && is_calling_first) {
            var rows = dt_selector.rows( '.selected' ).indexes();

            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.cells( rows, 8, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 6 ).footer() ).html(dt_selector.cells( rows, 6, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 7 ).footer() ).html(dt_selector.cells( rows, 7, { page: 'current' } ).data().sum().toFixed(2));
            $( dt_selector.column( 8 ).footer() ).html(dt_selector.cells( rows, 8, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    function saleDetails(sale){
        var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+sale[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+sale[1]+'<br><strong>{{trans("file.Store")}}: </strong>'+sale[21]+'<br><strong>{{trans("file.Sale")}} {{trans("file.Status")}}: </strong>'+sale[2]+'<br><br><div class="row"><div class="col-md-6"><strong>{{trans("file.To")}}:</strong><br>'+sale[3]+'<br>'+sale[4]+'<br>'+sale[5]+'<br>'+sale[6]+'</div></div>';
        $.get('sale/product_sale/' + sale[7], function(data){
            $(".product-sale-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var unit = data[2];
            var tax = data[3];
            var tax_rate = data[4];
            var discount = data[5];
            var subtotal = data[6];
            var newBody = $("<tbody>");
            $.each(name_code, function(index){
                var newRow = $("<tr>");
                var cols = '';
                cols += '<td><strong>' + (index+1) + '</strong></td>';
                cols += '<td>' + name_code[index] + '</td>';
                cols += '<td>' + qty[index] + ' ' + unit[index] + '</td>';
                cols += '<td>' + parseFloat(subtotal[index] / qty[index]).toFixed(2) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + discount[index] + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Total")}}:</strong></td>';
            cols += '<td>' + sale[8] + '</td>';
            cols += '<td>' + sale[9] + '</td>';
            cols += '<td>' + sale[10] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order")}} {{trans("file.Tax")}}:</strong></td>';
            cols += '<td>' + sale[11] + '(' + sale[12] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order")}} {{trans("file.Discount")}}:</strong></td>';
            cols += '<td>' + sale[13] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Shipping Cost")}}:</strong></td>';
            cols += '<td>' + sale[14] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + sale[15] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Paid")}} {{trans("file.Amount")}}:</strong></td>';
            cols += '<td>' + sale[16] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Due")}}:</strong></td>';
            cols += '<td>' + parseFloat(sale[15] - sale[16]).toFixed(2) + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-sale-list").append(newBody);
        });
        var htmlfooter = '<p><strong>{{trans("file.Sale")}} {{trans("file.Note")}}:</strong> '+sale[17]+'</p><p><strong>{{trans("file.Staff")}} {{trans("file.Note")}}:</strong> '+sale[18]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+sale[19]+'<br>'+sale[20];
        $('#sale-content').html(htmltext);
        $('#sale-footer').html(htmlfooter);
        $('#sale-details').modal('show');
    }

    $('.payment-form').on('submit',function(){
        $('#edit-payment select[name="edit_paid_by_id"]').prop('disabled', false);
    });

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    function confirmPaymentDelete() {
        if (confirm("Are you sure want to delete? If you delete this money will be refunded.")) {
            return true;
        }
        return false;
    }

</script>
@endsection

@section('scripts')
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

@endsection