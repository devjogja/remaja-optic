@extends('layout.main') @section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{!! session()->get('message') !!}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section>
    <div class="container-fluid">
            @if(in_array("return-purchase-add", $all_permission))
            <a href="{{route('return-purchase.create')}}" class="btn btn-info"><i class="fa fa-plus"></i> {{trans('file.add')}} {{trans('file.return')}}</a>
            @endif
    </div>
    <div class="table-responsive">
        <table id="return-table" class="table table-striped return-list">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}}</th>
                    <th>{{trans('file.Store')}}</th>
                    <th>{{trans('file.Supplier')}}</th>
                    <th>{{trans('file.grand total')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ezpos_return_all as $key=>$return)
                <?php 
                    $supplier = DB::table('suppliers')->find($return->supplier_id);
                    if(!$supplier){
                        $supplier = new App\Supplier();
                        $supplier->name = $supplier->company_name = $supplier->email = $supplier->phone_number = $supplier->address = $supplier->city = '';
                    }
                    $store = DB::table('stores')->where('id', $return->store_id)->first();
                    $user = DB::table('users')->find($return->user_id);
                ?>
                <tr class="return-link" data-return='["{{date("d-m-Y", strtotime($return->created_at->toDateString()))}}", "{{$return->reference_no}}", "{{$store->name}}", "{{$store->phone}}", "{{$store->address}}", "{{$supplier->name}}", "{{$supplier->company_name}}","{{$supplier->email}}", "{{$supplier->phone_number}}", "{{$supplier->address}}", "{{$supplier->city}}", "{{$return->id}}", "{{$return->total_tax}}", "{{$return->total_discount}}", "{{$return->total_cost}}", "{{$return->order_tax}}", "{{$return->order_tax_rate}}", "{{$return->grand_total}}", "{{$return->return_note}}", "{{$return->staff_note}}", "{{$user->name}}", "{{$user->email}}"]'>
                    <td>{{$key}}</td>
                    <td>{{ date('d-m-Y', strtotime($return->created_at->toDateString())) . ' '. $return->created_at->toTimeString() }}</td>
                    <td>{{ $return->reference_no }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td class="grand-total">{{ $return->grand_total }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <button type="button" class="btn btn-link view"><i class="fa fa-eye"></i> {{trans('file.View')}}</button>
                                </li>
                                @if(in_array("return-purchase-edit", $all_permission))
                                <li>
                                    <a href="{{ route('return-purchase.edit', ['id' => $return->id]) }}" class="btn btn-link"><i class="fa fa-edit"></i> {{trans('file.edit')}}</a>
                                </li>
                                @endif
                                @if(in_array("return-purchase-delete", $all_permission))
                                {{ Form::open(['route' => ['return-purchase.destroy', $return->id], 'method' => 'DELETE'] ) }}
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
            </tfoot>
        </table>
    </div>
    <div id="return-details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
          <div class="modal-content">
            <div class="container mt-3 pb-2 border-bottom">
            <div class="row">
                <div class="col-md-3">
                    <button id="print-btn" type="button" class="btn btn-default btn-sm d-print-none"><i class="fa fa-print"></i> {{trans('file.Print')}}</button>
                </div>
                <div class="col-md-6">
                    <h3 id="exampleModalLabel" class="modal-title text-center container-fluid">{{$general_setting->site_title}}</h3>
                </div>
                <div class="col-md-3">
                    <button type="button" id="close-btn" data-dismiss="modal" aria-label="Close" class="close d-print-none"><span aria-hidden="true">×</span></button>
                </div>
                <div class="col-md-12 text-center">
                    <i style="font-size: 15px;">{{trans('file.Return Details')}}</i>
                </div>
            </div>
        </div>
                <div id="return-content" class="modal-body">
                </div>
                <br>
                <table class="table table-bordered product-return-list">
                    <thead>
                        <th>#</th>
                        <th>{{trans('file.product')}}</th>
                        <th>{{trans('file.qty')}}</th>
                        <th>{{trans('file.Unit Cost')}}</th>
                        <th>{{trans('file.Tax')}}</th>
                        <th>{{trans('file.Discount')}}</th>
                        <th>{{trans('file.Subtotal')}}</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <div id="return-footer" class="modal-body"></div>
          </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    $("ul#return").siblings('a').attr('aria-expanded','true');
    $("ul#return").addClass("show");
    $("ul#return #return-purchase-menu").addClass("active");

    var return_id = [];
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    $("tr.return-link td:not(:first-child, :last-child)").on("click", function(){
        var returns = $(this).parent().data('return');
        returnDetails(returns);
    });

    $(".view").on("click", function(){
        var returns = $(this).parent().parent().parent().parent().parent().data('return');
        returnDetails(returns);
    });

    $("#print-btn").on("click", function(){
          var divToPrint=document.getElementById('return-details');
          var newWin=window.open('','Print-Window');
          newWin.document.open();
          newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
          newWin.document.close();
          setTimeout(function(){newWin.close();},10);
    });

    $('#return-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': [0, 6]
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
                text: 'PDF',
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
                text: 'CSV',
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
                text: 'Print',
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
                text: 'Column visibility',
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

            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
        }
        else {
            $( dt_selector.column( 5 ).footer() ).html(dt_selector.cells( rows, 5, { page: 'current' } ).data().sum().toFixed(2));
        }
    }

    function returnDetails(returns){
        $('input[name="return_id"]').val(returns[11]);
        var htmltext = '<strong>{{trans("file.Date")}}: </strong>'+returns[0]+'<br><strong>{{trans("file.reference")}}: </strong>'+returns[1]+'<br><br><div class="row"><div class="col-md-6"><strong>{{trans("file.From")}}:</strong><br>'+returns[2]+'<br>'+returns[3]+'<br>'+returns[4]+'</div><div class="col-md-6"><div class="float-right"><strong>{{trans("file.To")}}:</strong><br>'+returns[5]+'<br>'+returns[6]+'<br>'+returns[7]+'<br>'+returns[8]+'<br>'+returns[9]+', '+returns[10]+'</div></div></div>';
        $.get('return-purchase/product_return/' + returns[11], function(data){
            $(".product-return-list tbody").remove();
            var name_code = data[0];
            var qty = data[1];
            var unit_code = data[2];
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
                if(unit_code[index] != 'null')
                    cols += '<td>' + qty[index] + ' ' + unit_code[index] + '</td>';
                else
                    cols += '<td>' + qty[index] + '</td>';
                cols += '<td>' + (subtotal[index] / qty[index]) + '</td>';
                cols += '<td>' + tax[index] + '(' + tax_rate[index] + '%)' + '</td>';
                cols += '<td>' + discount[index] + '</td>';
                cols += '<td>' + subtotal[index] + '</td>';
                newRow.append(cols);
                newBody.append(newRow);
            });

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=4><strong>{{trans("file.Total")}}:</strong></td>';
            cols += '<td>' + returns[12] + '</td>';
            cols += '<td>' + returns[13] + '</td>';
            cols += '<td>' + returns[14] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.Order Tax")}}:</strong></td>';
            cols += '<td>' + returns[15] + '(' + returns[16] + '%)' + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            var newRow = $("<tr>");
            cols = '';
            cols += '<td colspan=6><strong>{{trans("file.grand total")}}:</strong></td>';
            cols += '<td>' + returns[17] + '</td>';
            newRow.append(cols);
            newBody.append(newRow);

            $("table.product-return-list").append(newBody);
        });
        var htmlfooter = '<p><strong>{{trans("file.Return Note")}}:</strong> '+returns[18]+'</p><p><strong>{{trans("file.Staff Note")}}:</strong> '+returns[19]+'</p><strong>{{trans("file.Created By")}}:</strong><br>'+returns[20]+'<br>'+returns[21];
        $('#return-content').html(htmltext);
        $('#return-footer').html(htmlfooter);
        $('#return-details').modal('show');
    }

</script>
@endsection('content')