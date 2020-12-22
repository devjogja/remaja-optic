@extends('layout.main')
@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.update')}} {{trans('file.Adjustment')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => ['qty_adjustment.update', $ezpos_adjustment_data->id], 'method' => 'put', 'files' => true]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>{{trans('file.date')}}</strong></label>
                                            <input type="text" id="date" name="date" value="{{date('d-m-Y', strtotime($ezpos_adjustment_data->date)) }}" class="form-control" required />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>{{trans('file.reference')}} No</strong></label>
                                            <p><strong>{{$ezpos_adjustment_data->reference_no}}</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>{{trans('file.Store')}} *</strong></label>
                                            <select required id="store_id" name="store_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select store...">
                                                @foreach($ezpos_store_list as $store)
                                                <option value="{{$store->id}}">{{$store->name}}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="store_id_hidden" value="{{$ezpos_adjustment_data->store_id}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>{{trans('file.Attach Document')}}</strong></label>
                                            <input type="file" name="document" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label><strong>{{trans('file.Select Product')}}</strong></label>
                                        <div class="search-box input-group">
                                            <button type="button" class="btn btn-secondary btn-lg"><i class="fa fa-barcode"></i></button>
                                            <input type="text" name="product_code_name" id="ezpos_productcodeSearch" placeholder="Please type product code and select..." class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <h5>{{trans('file.Order Table')}} *</h5>
                                        <div class="table-responsive mt-3">
                                            <table id="myTable" class="table table-hover order-list">
                                                <thead>
                                                    <tr>
                                                        <th>{{trans('file.name')}}</th>
                                                        <th>{{trans('file.Code')}}</th>
                                                        <th>{{trans('file.Quantity')}}</th>
                                                        <th>{{trans('file.action')}}</th>
                                                        <th><i class="fa fa-trash"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                	@foreach($ezpos_product_adjustment_data as $product_adjustment_data)
                                                	<tr>
                                                	<?php 
                                                	$product = DB::table('products')->find($product_adjustment_data->product_id);
                                                	?>
                                                	<td>{{$product->name}}</td>
                                                	<td>{{$product->code}}</td>
                                                	<td><input type="number" class="form-control qty" name="qty[]" value="{{$product_adjustment_data->qty}}" required></td>
                                                	<td class="action">
                                                		<select name="action[]" class="form-control act-val">
                                                			@if($product_adjustment_data->action == '+')
                                                			<option value="+">{{trans("file.Addition")}}</option>
                                                			<option value="-">{{trans("file.Subtraction")}}</option>
                                                			@else
                                                			<option value="-">{{trans("file.Subtraction")}}</option><option value="+">{{trans("file.Addition")}}</option>
                                                			@endif
                                                		</select>
                                                	</td>
                                                	<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{trans("file.delete")}}</button>
                                                	<input type="hidden" class="product-code" value="{{$product->code}}" />
                                                	<input type="hidden" class="product-id" name="product_id[]" value="{{$product->id}}" />
                                                	</td>
                                                	@endforeach
                                                	</tr>
                                                </tbody>
                                                <tfoot class="tfoot active">
                                                    <th colspan="2">{{trans('file.Total')}}</th>
                                                    <th id="total-qty" colspan="2">0</th>
                                                    <th><i class="fa fa-trash"></i></th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="hidden" name="total_qty" />
                                            <input type="hidden" name="item" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label><strong>{{trans('file.Note')}}</strong></label>
                                            <textarea rows="5" class="form-control" name="note">{{$ezpos_adjustment_data->note}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary" id="submit-button">
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>   
</section>
<script type="text/javascript">
    $("ul#adjustment").siblings('a').attr('aria-expanded','true');
    $("ul#adjustment").addClass("show");

    var date = $('#date');
    date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y', strtotime('')); ?>",
     autoclose: true,
     todayHighlight: true
     });
// array data depend on store
var ezpos_product_array = [];
var product_code = [];
var product_name = [];
var product_qty = [];

	$('.selectpicker').selectpicker({
	    style: 'btn-link',
	});
	//assigning value
	$('select[name="store_id"]').val($('input[name="store_id_hidden"]').val());
	$('.selectpicker').selectpicker('refresh');
	calculateTotal();

	$('#ezpos_productcodeSearch').on('input', function(){
	    var store_id = $('#store_id').val();
	    temp_data = $('#ezpos_productcodeSearch').val();

	    if(!store_id){
	        $('#ezpos_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
	        alert('Please select store!');
	    }

	});

	var id = $('#store_id').val();
    $.get('../getproduct/' + id, function(data) {
        ezpos_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        $.each(product_code, function(index) {
            ezpos_product_array.push(product_code[index] + ' -- (' + product_name[index] + ')');
        });
    });

	var ezpos_productcodeSearch = $('#ezpos_productcodeSearch');

	ezpos_productcodeSearch.autocomplete({
	    source: function(request, response) {
	        var matcher = new RegExp(".?" + $.ui.autocomplete.escapeRegex(request.term), "i");
	        response($.grep(ezpos_product_array, function(item) {
	            return matcher.test(item);
	        }));
	    },
        response: function(event, ui) {
            if (ui.content.length == 1) {
                var data = ui.content[0].value;
                $(this).autocomplete( "close" );
                productSearch(data);
            };
        },
	    select: function(event, ui) {
	        var data = ui.item.value;
	        productSearch(data);
	    }
	});

	$('select[name="store_id"]').on('change', function() {
	    var id = $('#store_id').val();
	    $.get('../getproduct/' + id, function(data) {
	        ezpos_product_array = [];
	        product_code = data[0];
	        product_name = data[1];
	        product_qty = data[2];
	        $.each(product_code, function(index) {
	            ezpos_product_array.push(product_code[index] + ' -- (' + product_name[index] + ')');
	        });
	    });
	});

	$("#myTable").on('input', '.qty', function() {
	    rowindex = $(this).closest('tr').index();
	    checkQuantity($(this).val(), true);
	});

	$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
	    rowindex = $(this).closest('tr').index();
	    $(this).closest("tr").remove();
	    calculateTotal();
	});

    function productSearch(data){
        $.ajax({
            type: 'GET',
            url: '../ezpos_product_search',
            data: {
                data: data
            },
            success: function(data) {
                var flag = 1;
                $(".product-code").each(function(i) {
                    if ($(this).val() == data[1]) {
                        rowindex = i;
                        var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
                        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                        flag = 0;
                        calculateTotal();
                    }
                });
                $("input[name='product_code_name']").val('');
                if(flag){
                    var newRow = $("<tr>");
                    var cols = '';
                    cols += '<td>' + data[0] + '</td>';
                    cols += '<td>' + data[1] + '</td>';
                    cols += '<td><input type="number" class="form-control qty" name="qty[]" value="1" required /></td>';
                    cols += '<td class="action"><select name="action[]" class="form-control act-val"><option value="-">{{trans("file.Subtraction")}}</option><option value="+">{{trans("file.Addition")}}</option></select></td>';
                    cols += '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{trans("file.delete")}}</button></td>';
                    cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
                    cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[2] + '"/>';

                    newRow.append(cols);
                    $("table.order-list tbody").append(newRow);
                    rowindex = newRow.index();
                    calculateTotal();
                }  
            }
        });
    }

	function checkQuantity(qty) {
	    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text();
	    var pos = product_code.indexOf(row_product_code);
	    var action = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.act-val').val();
        if (parseFloat(qty) > product_qty[pos] && action == '-') {
	        alert('Quantity exceeds stock quantity!');
            var row_qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
            row_qty = row_qty.substring(0, row_qty.length - 1);
            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(row_qty);
	    }
	    else {
	        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(qty);
	        calculateTotal();
	    }
	}

	function calculateTotal() {
	    var total_qty = 0;
	    $(".qty").each(function() {

	        if ($(this).val() == '') {
	            total_qty += 0;
	        } else {
	            total_qty += parseFloat($(this).val());
	        }
	    });
	    $("#total-qty").text(total_qty);
	    $('input[name="total_qty"]').val(total_qty);
	    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
	}

$(window).keydown(function(e){
    if (e.which == 13) {
        var $targ = $(e.target);
        if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
            var focusNext = false;
            $(this).find(":input:visible:not([disabled],[readonly]), a").each(function(){
                if (this === e.target) {
                    focusNext = true;
                }
                else if (focusNext){
                    $(this).focus();
                    return false;
                }
            });
            return false;
        }
    }
});
</script>
@endsection