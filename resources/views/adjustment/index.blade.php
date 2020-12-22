@extends('layout.main')
@section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif

<section>
    <div class="container-fluid">
        <a href="{{route('qty_adjustment.create')}}" class="btn btn-info"><i class="fa fa-plus"></i> {{trans('file.add')}} {{trans('file.Adjustment')}} </a>
    </div>
    <div class="table-responsive">
        <table id="adjustment-table" class="table table-hover purchase-list">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Date')}}</th>
                    <th>{{trans('file.reference')}} No</th>
                    <th>{{trans('file.Store')}}</th>
                    <th>{{trans('file.product')}}s</th>
                    <th>{{trans('file.Note')}}</th>
                    <th class="not-exported">{{trans('file.action')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ezpos_adjustment_all as $key=>$adjustment)
                <tr>
                    <td>{{$key}}</td>
                    <td>{{ date('d-m-Y', strtotime($adjustment->date)) }}</td>
                    <td>{{ $adjustment->reference_no }}</td>
                    <?php $store = DB::table('stores')->find($adjustment->store_id) ?>
                    <td>{{ $store->name }}</td>
                    <td>
                    <?php
                    	$product_adjustment_data = DB::table('product_adjustments')->where('adjustment_id', $adjustment->id)->get();
                    	foreach ($product_adjustment_data as $key => $product_adjustment) {
                    	 	$product = DB::table('products')->find($product_adjustment->product_id);
                    	 	if($key)
                    	 		echo '<br>';
                    	 	echo $product->name;
                    	 } 
                    ?>
                    </td>
                    <td>{{$adjustment->note}}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{trans('file.action')}}<span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                                <li>
                                    <a href="{{ route('qty_adjustment.edit', ['id' => $adjustment->id]) }}" class="btn btn-link"><i class="fa fa-edit"></i> {{trans('file.edit')}}</a> 
                                </li>
                                <li class="divider"></li>
                                {{ Form::open(['route' => ['qty_adjustment.destroy', $adjustment->id], 'method' => 'DELETE'] ) }}
                                <li>
                                    <button type="submit" class="btn btn-link" onclick="return confirmDelete()"><i class="fa fa-trash"></i> {{trans('file.delete')}}</button>
                                </li>
                                {{ Form::close() }}
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
<script type="text/javascript">
    $("ul#product").siblings('a').attr('aria-expanded','true');
    $("ul#product").addClass("show");
    $("ul#product #adjustment-list-menu").addClass("active");

    function confirmDelete() {
        if (confirm("Are you sure want to delete?")) {
            return true;
        }
        return false;
    }

    $('#adjustment-table').DataTable( {
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
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                footer:true
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                footer:true
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
                footer:true
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            },
        ],
    } );
</script>
@endsection