@extends('layout.main')

@section('content')
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.add')}} {{trans('file.product')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'products.store', 'method' => 'post', 'files' => true, 'id' => 'product-form']) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>{{trans('file.product')}} {{trans('file.Image')}}</strong> </label>
                                    <input type="file" name="image" class="form-control">
                                    @if($errors->has('image'))
                                        <span>
                                           <strong>{{ $errors->first('image') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
	                            <div class="form-group">
	                                <label><strong>{{trans('file.product')}} {{trans('file.name')}} *</strong> </label>
	                                <input type="text" name="name" required class="form-control">
                                    @if($errors->has('name'))
                                    <span>
                                       <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    @endif
	                            </div>
                            </div>
                            <div class="col-md-6">
                        		<div class="form-group">
                        			<label><strong>{{trans('file.product')}} {{trans('file.Code')}} *</strong> </label>
                        			<div class="input-group">
		                                <input type="text" name="code" required class="form-control">
		                                <div class="input-group-append">
				                            <button id="genbutton" type="button" class="btn btn-default">{{trans('file.Generate')}}</button>
				                        </div>
                                        @if($errors->has('code'))
                                        <span>
                                           <strong>{{ $errors->first('code') }}</strong>
                                        </span>
                                        @endif
                        			</div>
	                            </div>
                            </div>
                        	<div class="col-md-6">
                        		<div class="form-group">
                        			<label><strong>{{trans('file.product')}} {{trans('file.Type')}} *</strong> </label>
                        			<div class="input-group">
		                                <select name="type" required class="form-control selectpicker">
		                                	<option value="standard">Standard</option>
                                            <option value="digital">Digital</option>
		                                </select>
                        			</div>
	                            </div>
                        	</div>
                            <div class="col-md-6">
                        		<div class="form-group">
                        			<label><strong>{{trans('file.Barcode Symbology')}} *</strong> </label>
                        			<div class="input-group">
		                                <select name="barcode_symbology" required class="form-control selectpicker">
		                                	<option value="C128">Code 128</option>
		                                	<option value="C39">Code 39</option>
		                                	<option value="UPCA">UPC-A</option>
		                                	<option value="UPCE">UPC-E</option>
		                                	<option value="EAN8">EAN-8</option>
		                                	<option value="EAN13">EAN-13</option>
		                                </select>
                        			</div>
	                            </div>
                            </div>
                            <div id="digital" class="col-md-6">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Attach File')}} *</strong> </label>
                                    <div class="input-group">
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                </div>                                
                            </div>
                            <div class="col-md-6">
							    <div class="form-group">
							    	<label><strong>{{trans('file.Brand')}}</strong> </label>
							    	<div class="input-group">
								      <select name="brand_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Brand...">
								      	@foreach($ezpos_brand_list as $brand)
								      		<option value="{{$brand->id}}">{{$brand->title}}</option>
								      	@endforeach
								      </select>
								  </div>
							    </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>{{trans('file.category')}} *</strong> </label>
                                    <div class="input-group">
                                      <select name="category_id" required class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Category...">
                                        @foreach($ezpos_category_list as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                      </select>
                                  </div>
                                </div>
                            </div>
                            <div id="unit" class="col-md-12">
                                <div class="row ">
                                    <div class="col-md-6">
                                            <label><strong>{{trans('file.product')}} {{trans('file.Unit')}}</strong> </label>
                                            <div class="input-group">
                                              <input type="text" name="unit" class="form-control">
                                          </div>
                                    </div> 
                                    <div id="alert-qty" class="col-md-6">
                                        <label><strong>{{trans('file.Alert')}} {{trans('file.Quantity')}}</strong> </label>
                                        <input type="number" name="alert_quantity" class="form-control" step="any">
                                    </div>                              
                                </div>                                
                            </div>
                            <div id="cost" class="col-md-6">
                                 <div class="form-group">
                                    <label><strong>{{trans('file.product')}} {{trans('file.Cost')}} *</strong> </label>
                                    <input type="number" name="cost" required class="form-control" step="any">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>{{trans('file.product')}} {{trans('file.Price')}} *</strong> </label>
                                    <input type="number" name="price" required class="form-control" step="any">
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="qty" value="0.00">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mt-3">
                                    <label><strong>{{trans('file.Featured')}}</strong></label>&nbsp;
                                    <input type="checkbox" name="featured" value="1">
                                    <p class="italic">Featured product will be displayed in POS</p>
                                </div> 
                            </div>
                            <div class="col-md-6 mt-3">
                                <label><strong>{{trans('file.add')}} {{trans('file.Promotional Price')}}</strong></label>&nbsp;
                                <input name="promotion" type="checkbox" id="promotion" value="1">
                            </div>
                            <div class="col-md-6" id="promotion_price">
                                <label><strong>{{trans('file.Promotional Price')}}</strong></label>
                                <input type="number" name="promotion_price" class="form-control" step="any" />
                            </div>
                            <div class="col-md-6" id="start_date">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Promotion Starts')}}</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                                        </div>
                                        <input type="text" name="starting_date" id="starting_date" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6" id="last_date">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Promotion Ends')}}</strong></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                                        </div>
                                        <input type="text" name="last_date" id="ending_date" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>{{trans('file.Tax')}} {{trans('file.Method')}}</strong> </label>
                                    <select name="tax_method" class="form-control selectpicker">
                                        <option value="1">Exclusive</option>
                                        <option value="2">Inclusive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label><strong>{{trans('file.product')}} {{trans('file.Tax')}}</strong> </label>
                                    <select name="tax_id" class="form-control selectpicker">
                                        <option value="">No Tax</option>
                                        @foreach($ezpos_tax_list as $tax)
                                            <option value="{{$tax->id}}">{{$tax->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label><strong>{{trans('file.product')}} {{trans('file.Details')}}</strong></label>
                                    <textarea name="product_details" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                        </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #product-add-menu").addClass("active");

    $("#digital").hide();
    $("#promotion_price").hide();
    $("#start_date").hide();
    $("#last_date").hide();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$('#genbutton').on("click", function(){
      $.get('gencode', function(data){
        $("input[name='code']").val(data);
      });
    });

    $('.selectpicker').selectpicker({
	  style: 'btn-link',
	});

    tinymce.init({
      selector: 'textarea',
      height: 200,
      plugins: [
        'advlist autolink lists link image charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime media table contextmenu paste code wordcount'
      ],
      toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat',
      branding:false
    });

    $('select[name="type"]').on('change', function() {
        if($(this).val() == 'digital'){
            $("input[name='cost']").prop('required',false);
            $("select[name='unit_id']").prop('required',false);
            $("input[name='file']").prop('required',true);
            $("#cost").hide();
            $("#unit").hide();
            $("#alert-qty").hide();
            $("#digital").show();
        }
        else if($(this).val() == 'standard'){
            $("input[name='cost']").prop('required',true);
            $("select[name='unit_id']").prop('required',true);
            $("input[name='file']").prop('required',false);
            $("#cost").show();
            $("#unit").show();
            $("#alert-qty").show();
            $("#digital").hide();
        }
    });


    $( "#promotion" ).on( "change", function() {
        if ($(this).is(':checked')) {
            $("#starting_date").val($.datepicker.formatDate('dd-mm-yy', new Date()));
            $("#promotion_price").show();
            $("#start_date").show();
            $("#last_date").show();
        } 
        else {
            $("#promotion_price").hide();
            $("#start_date").hide();
            $("#last_date").hide();
        }
    });

    var starting_date = $('#starting_date');
    starting_date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    var ending_date = $('#ending_date');
    ending_date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y'); ?>",
     autoclose: true,
     todayHighlight: true
     });

    $('#product-form').on('submit',function(e){
        var product_code = $("input[name='code']").val();
        var barcode_symbology = $('select[name="barcode_symbology"]').val();
        var exp = /^\d+$/;

        if(!(product_code.match(exp)) && (barcode_symbology == 'UPCA' || barcode_symbology == 'UPCE' || barcode_symbology == 'EAN8' || barcode_symbology == 'EAN13') ) {
            alert('Product code must be numeric.');
            e.preventDefault();
        }
        else if(product_code.match(exp)) {
            if(barcode_symbology == 'UPCA' && product_code.length > 11){
                alert('Product code length must be less than 12');
                e.preventDefault();
            }
            else if(barcode_symbology == 'EAN8' && product_code.length > 7){
                alert('Product code length must be less than 8');
                e.preventDefault();
            }
            else if(barcode_symbology == 'EAN13' && product_code.length > 12){
                alert('Product code length must be less than 13');
                e.preventDefault();
            }
        }
    });

</script>
@endsection
