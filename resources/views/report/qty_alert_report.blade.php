@extends('layout.main') @section('content')

<section>
	<h4 class="text-center">{{trans('file.product')}} {{trans('file.Quantity')}} {{trans('file.Alert')}}</h4>
	<div class="table-responsive">
        <table id="report-table" class="table table-hover">
            <thead>
                <tr>
                    <th class="not-exported"></th>
                    <th>{{trans('file.Image')}}</th>
                    <th>{{trans('file.product')}} {{trans('file.name')}}</th>
                    <th>{{trans('file.product')}} {{trans('file.Code')}}</th>
                    <th>{{trans('file.Quantity')}}</th>
                    <th>{{trans('file.Alert')}} {{trans('file.Quantity')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ezpos_product_data as $key=>$product)
                <tr>
                    <td>{{$key}}</td>
                    <td> <img src="{{url('public/images/product',$product->image)}}" height="80" width="80"> </td>
                    <td>{{$product->name}}</td>
                    <td>{{$product->code}}</td>
                    <td>{{number_format((float)($product->qty), 2, '.', '')}}</td>
                    <td>{{number_format((float)($product->alert_quantity), 2, '.', '')}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script type="text/javascript">
    $("ul#report").siblings('a').attr('aria-expanded','true');
    $("ul#report").addClass("show");
    $("ul#report #qty-alert-menu").addClass("active");

    $('#report-table').DataTable( {
        "order": [],
        'columnDefs': [
            {
                "orderable": false,
                'targets': 0
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
            },
            {
                extend: 'csv',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'print',
                exportOptions: {
                    columns: ':visible:Not(.not-exported)',
                    rows: ':visible'
                },
            },
            {
                extend: 'colvis',
                columns: ':gt(0)'
            }
        ],
    } );

</script>
@endsection