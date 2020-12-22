@extends('layout.main')
@section('content')
<section>
	<div class="col-md-12">
			{{ Form::open(['route' => 'report.storeStock', 'method' => 'post', 'id' => 'report-form']) }}
			<input type="hidden" name="store_id_hidden" value="{{$store_id}}">
			<h3 class="text-center">{{trans('file.Stock Chart')}} &nbsp;
			<select class="selectpicker" id="store_id" name="store_id">
				<option value="0">{{trans('file.All')}} {{trans('file.Store')}}</option>
				@foreach($ezpos_store_list as $store)
				<option value="{{$store->id}}">{{$store->name}}</option>
				@endforeach
			</select>
			</h3>
			{{ Form::close() }}
		
		<div class="col-md-6 offset-md-3 mt-3">
			<div class="row">
				<div class="col-md-6">
					<div class="colored-box green-bg">
						<i class="fa fa-star"></i>
						<h4>Total {{trans('file.Items')}}</h4>
						<p class="text-center"><strong>{{number_format((float)$total_item, 2, '.', '') }}</strong></p>
					</div>
				</div>
				<div class="col-md-6">
					<div class="colored-box orange-bg">
						<i class="fa fa-star"></i>
						<h4>Total {{trans('file.Quantity')}}</h4>
						<p class="text-center"><strong>{{number_format((float)$total_qty, 2, '.', '') }}</strong></p>
					</div>
				</div>	
			</div>		
		</div>
			
		<div class="col-md-6 offset-md-3 mt-3">
			<div class="pie-chart">
		      <canvas id="pieChart" data-price={{$total_price}} data-cost={{$total_cost}} width="100" height="100"> </canvas>
		    </div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$("ul#report").siblings('a').attr('aria-expanded','true');
    $("ul#report").addClass("show");
    $("ul#report #store-stock-menu").addClass("active");

	$('#store_id').val($('input[name="store_id_hidden"]').val());
	$('.selectpicker').selectpicker('refresh');

	$('#store_id').on("change", function(){
		$('#report-form').submit();
	});
</script>
@endsection