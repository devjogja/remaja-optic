@extends('layout.main')
@section('content')
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.Group Permission')}}</h4>
                    </div>
                    {!! Form::open(['route' => 'role.setPermission', 'method' => 'post']) !!}
                    <div class="card-body">
                    	<input type="hidden" name="role_id" value="{{$ezpos_role_data->id}}" />
						<div class="table-responsive">
						    <table class="table table-bordered table-striped reports-table">
						        <thead>
						        <tr>
						            <th colspan="5" class="text-center">{{$ezpos_role_data->name}} {{trans('file.Group Permission')}}</th>
						        </tr>
						        <tr>
						            <th rowspan="2" class="text-center">Module Name                                    </th>
						            <th colspan="4" class="text-center">{{trans('file.Permissions')}}&nbsp;&nbsp; <input type="checkbox" id="select_all" class="checkbox"></th>
						        </tr>
						        <tr>
						            <th class="text-center">{{trans('file.View')}}</th>
						            <th class="text-center">{{trans('file.add')}}</th>
						            <th class="text-center">{{trans('file.edit')}}</th>
						            <th class="text-center">{{trans('file.delete')}}</th>
						        </tr>
						        </thead>
						        <tbody>
						        <tr>
						            <td>{{trans('file.product')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("products-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="products-index" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="products-index" />
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("products-add", $all_permission))
						               	<input type="checkbox" value="1" class="checkbox" name="products-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="products-add">
						                @endif
						                </div>

						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("products-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="products-edit" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="products-edit" />
						                @endif
						                </div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("products-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="products-delete" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="products-delete" />
						                @endif
						                </div>
						            </td>
						        </tr>

						        <tr>
						            <td>{{trans('file.Purchase')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("purchases-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="purchases-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="purchases-index">
						                @endif
						                </div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("purchases-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="purchases-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="purchases-add">
						                @endif
						                </div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("purchases-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="purchases-edit" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="purchases-edit">
						                @endif
						                </div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("purchases-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="purchases-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="purchases-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>

						        <tr>
						            <td>{{trans('file.Sale')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("sales-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="sales-index" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="sales-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("sales-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="sales-add" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="sales-add">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("sales-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="sales-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="sales-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("sales-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="sales-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="sales-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>

						        <tr>
						            <td>{{trans('file.Expense')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("expenses-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="expenses-index" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="expenses-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("expenses-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="expenses-add" checked />
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="expenses-add">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("expenses-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="expenses-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="expenses-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("expenses-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="expenses-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="expenses-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>

						        <tr>
						            <td>{{trans('file.Transfer')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("transfers-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="transfers-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="transfers-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("transfers-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="transfers-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="transfers-add">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("transfers-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="transfers-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="transfers-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("transfers-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="transfers-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="transfers-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>

						        <tr>
						            <td>{{trans('file.Sale Return')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("returns-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="returns-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="returns-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("returns-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="returns-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="returns-add">
						                @endif
						                </div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("returns-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="returns-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="returns-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("returns-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="returns-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="returns-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>

						        <tr>
						            <td>{{trans('file.Purchase Return')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("return-purchase-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("return-purchase-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-add">
						                @endif
						                </div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("return-purchase-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("return-purchase-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="return-purchase-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>
						        <tr>
						            <td>{{trans('file.User')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("users-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="users-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="users-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("users-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="users-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="users-add">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("users-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="users-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="users-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("users-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="users-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="users-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>
						        <tr>
						            <td>{{trans('file.customer')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("customers-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="customers-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="customers-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("customers-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="customers-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="customers-add">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue checked" aria-checked="false" aria-disabled="false">
						                @if(in_array("customers-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="customers-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="customers-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("customers-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="customers-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="customers-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>
						        <tr>
						            <td>{{trans('file.Supplier')}}</td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("suppliers-index", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-index" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-index">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("suppliers-add", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-add" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-add">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("suppliers-edit", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-edit" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-edit">
						                @endif
						            	</div>
						            </td>
						            <td class="text-center">
						                <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false">
						                @if(in_array("suppliers-delete", $all_permission))
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-delete" checked>
						                @else
						                <input type="checkbox" value="1" class="checkbox" name="suppliers-delete">
						                @endif
						            	</div>
						            </td>
						        </tr>
						        <tr>
						            <td>{{trans('file.Report')}}</td>
						            <td colspan="5">
						            	<span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("profit-loss", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="profit-loss" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="profit-loss">
					                    	@endif
						                    </div>
						                    <label for="profit-loss" class="padding05">{{trans('file.Summary Report')}}&nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("best-seller", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="best-seller" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="best-seller">
					                    	@endif
						                    </div>
						                    <label for="best-seller" class="padding05">{{trans('file.Best Seller')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("store-stock-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="store-stock-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="store-stock-report">
					                    	@endif
						                    </div>
						                    <label for="store-stock-report" class="padding05">{{trans('file.Store')}} {{trans('file.Stock Chart')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("daily-sale", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="daily-sale" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="daily-sale">
					                    	@endif
						                    </div>
						                    <label for="daily-sale" class="padding05">{{trans('file.Daily Sale')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("monthly-sale", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="monthly-sale" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="monthly-sale">
					                    	@endif
						                    </div>
						                    <label for="monthly-sale" class="padding05">{{trans('file.Monthly Sale')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("daily-purchase", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="daily-purchase" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="daily-purchase">
					                    	@endif
						                    </div>
						                    <label for="daily-purchase" class="padding05">{{trans('file.Daily Purchase')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("monthly-purchase", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="monthly-purchase" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="monthly-purchase">
					                    	@endif
						                    </div>
						                    <label for="monthly-purchase" class="padding05">{{trans('file.Monthly Purchase')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("product-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="product-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="product-report">
					                    	@endif
						                    </div>
						                    <label for="product-report" class="padding05">{{trans('file.product')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("payment-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="payment-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="payment-report">
					                    	@endif
						                    </div>
						                    <label for="payment-report" class="padding05">{{trans('file.Payment')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("purchase-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="purchase-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="purchase-report">
					                    	@endif
						                    </div>
						                    <label for="purchase-report" class="padding05"> {{trans('file.Purchase')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("sale-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="sale-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="sale-report">
					                    	@endif
						                    </div>
						                    <label for="sale-report" class="padding05">{{trans('file.Sale')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>

						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("product-qty-alert", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="product-qty-alert" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="product-qty-alert">
					                    	@endif
						                    </div>
						                    <label for="product-qty-alert" class="padding05">{{trans('file.product')}} {{trans('file.Quantity')}} {{trans('file.Alert')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("customer-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="customer-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="customer-report">
					                    	@endif
						                    </div>
						                    <label for="customer-report" class="padding05">{{trans('file.customer')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("supplier-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="supplier-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="supplier-report">
					                    	@endif
						                    </div>
						                    <label for="Supplier-report" class="padding05">{{trans('file.Supplier')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("due-report", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="due-report" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="due-report">
					                    	@endif
						                    </div>
						                    <label for="due-report" class="padding05">{{trans('file.Due')}} {{trans('file.Report')}} &nbsp;&nbsp;</label>
						                </span>
						            </td>
						        </tr>
						        <tr>
						            <td>{{trans('file.settings')}}</td>
						            <td colspan="5">
						            	<span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("general_setting", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="general_setting" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="general_setting">
					                    	@endif
						                    </div>
						                    <label for="general_setting" class="padding05">{{trans('file.General Setting')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("mail_setting", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="mail_setting" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="mail_setting">
					                    	@endif
						                    </div>
						                    <label for="mail_setting" class="padding05">{{trans('file.Mail Setting')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("pos_setting", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="pos_setting" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="pos_setting">
					                    	@endif
						                    </div>
						                    <label for="pos_setting" class="padding05">{{trans('file.POS Setting')}} &nbsp;&nbsp;</label>
						                </span>
						            </td>
						        </tr>
						        <tr>
						            <td>{{trans('file.Miscellaneous')}}</td>
						            <td colspan="5">
						            	<span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("stock_count", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="stock_count" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="stock_count">
					                    	@endif
						                    </div>
						                    <label for="stock_count" class="padding05">{{trans('file.Stock Count')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("adjustment", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="adjustment" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="adjustment">
					                    	@endif
						                    </div>
						                    <label for="adjustment" class="padding05">{{trans('file.Adjustment')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("print_barcode", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="print_barcode" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="print_barcode">
					                    	@endif
						                    </div>
						                    <label for="print_barcode" class="padding05">{{trans('file.print_barcode')}} &nbsp;&nbsp;</label>
						                </span>
						                <span style="display:inline-block;">
						                    <div class="text-center" aria-checked="false" aria-disabled="false">
					                    	@if(in_array("empty_database", $all_permission))
					                    	<input type="checkbox" value="1" class="checkbox" name="empty_database" checked>
					                    	@else
					                    	<input type="checkbox" value="1" class="checkbox" name="empty_database">
					                    	@endif
						                    </div>
						                    <label for="empty_database" class="padding05">{{trans('file.Empty Database')}} &nbsp;&nbsp;</label>
						                </span>
						            </td>
						        </tr>
						        </tbody>
						    </table>
						</div>
						<div class="form-group">
	                        <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
	                    </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">

	$("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting li").eq(0).addClass("active");

	$("#select_all").on( "change", function() {
	    if ($(this).is(':checked')) {
	        $("tbody input[type='checkbox']").prop('checked', true);
	    } 
	    else {
	        $("tbody input[type='checkbox']").prop('checked', false);
	    }
	});
</script>
@endsection