@extends('layout.main')
@section('content')
@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
      <div class="row">
        <div class="container-fluid">
          <div class="col-md-12">
            <div class="brand-text float-left mt-4">
                <h3>{{trans('file.welcome')}} <span>{{Auth::user()->name}}</span> </h3>
            </div>
            <ul class="filter-toggle">
              <li><button class="btn btn-primary btn-sm date-btn" data-start_date="{{date('Y-m-d')}}" data-end_date="{{date('Y-m-d')}}">{{trans('file.Today')}}</button></li>
              <li><button class="btn btn-primary btn-sm date-btn" data-start_date="{{date('Y-m-d', strtotime(' -7 day'))}}" data-end_date="{{date('Y-m-d')}}">{{trans('file.Last 7 Days')}}</button></li>
              <li><button class="btn btn-primary btn-sm date-btn active" data-start_date="{{date('Y').'-'.date('m').'-'.'01'}}" data-end_date="{{date('Y-m-d')}}">{{trans('file.This Month')}}</button></li>
              <li><button class="btn btn-primary btn-sm date-btn" data-start_date="{{date('Y').'-01'.'-01'}}" data-end_date="{{date('Y').'-12'.'-31'}}">{{trans('file.This Year')}}</button></li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Counts Section -->
      <section class="dashboard-counts">
        <div class="container-fluid">
          <div class="row">
            <!-- Count item widget-->
            <div class="col-md-3">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="ion-connection-bars"></i></div>
                <div class="name">{{ trans('file.revenue') }}
                  <div class="count-number revenue-data">{{number_format((float)$revenue)}}</div>
                </div>
              </div>
            </div>
            <!-- Count item widget-->
            <div class="col-md-3">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="ion-arrow-return-left"></i></div>
                <div class="name">{{trans('file.Sale Return')}}
                  <div class="count-number return-data">{{number_format((float)$return)}}</div>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="fa fa-shopping-cart"></i></div>
                <div class="name">{{trans('file.Purchase Return')}}
                  <div class="count-number sale-data">{{number_format((float)$purchase_return)}}</div>
                </div>
              </div>
            </div>
            <!-- Count item widget-->
            <div class="col-md-3">
              <div class="wrapper count-title d-flex">
                <div class="icon"><i class="ion-pie-graph"></i></div>
                <div class="name">{{trans('file.profit')}}
                  <div class="count-number profit-data">{{number_format((float)$profit)}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="mb-30px">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h4>{{trans('file.yearly report')}}</h4>
                </div>
                <div class="card-body">
                  <canvas id="saleChart" data-sale_chart_value = "{{json_encode($yearly_sale_amount)}}" data-purchase_chart_value = "{{json_encode($yearly_purchase_amount)}}"></canvas>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>{{trans('file.Recent Transaction')}}</h4>
                  <div class="right-column">
                    <div class="badge badge-primary">{{trans('file.latest')}} 5</div>
                  </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" href="#sale-latest" role="tab" data-toggle="tab">{{trans('file.Sale')}}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#purchase-latest" role="tab" data-toggle="tab">{{trans('file.Purchase')}}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#return-latest" role="tab" data-toggle="tab">{{trans('file.Sale Return')}}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="#payment-latest" role="tab" data-toggle="tab">{{trans('file.Payment')}}</a>
                  </li>
                </ul>

                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane show active" id="sale-latest">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>{{trans('file.date')}}</th>
                              <th>{{trans('file.reference')}}</th>
                              <th>{{trans('file.customer')}}</th>
                              <th>{{trans('file.status')}}</th>
                              <th>{{trans('file.grand total')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($recent_sale as $sale)
                            <?php $customer = DB::table('customers')->find($sale->customer_id); ?>
                            <tr>
                              <td>{{date('d-m-Y', strtotime($sale->date))}}</td>
                              <td>{{$sale->reference_no}}</td>
                              <td>{{$customer->name}}</td>
                              @if($sale->sale_status == 1)
                              <td><div class="badge badge-success">Completed</div></td>
                              @else
                              <td><div class="badge badge-warning">Draft</div></td>
                              @endif
                              <td>{{$sale->grand_total}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="purchase-latest">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>{{trans('file.date')}}</th>
                              <th>{{trans('file.reference')}}</th>
                              <th>{{trans('file.Supplier')}}</th>
                              <th>{{trans('file.status')}}</th>
                              <th>{{trans('file.grand total')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($recent_purchase as $purchase)
                            <?php $supplier = DB::table('suppliers')->find($purchase->supplier_id); ?>
                            <tr>
                              <td>{{date('d-m-Y', strtotime($purchase->date))}}</td>
                              <td>{{$purchase->reference_no}}</td>
                              @if($supplier)
                                <td>{{$supplier->name}}</td>
                              @else
                                <td>N/A</td>
                              @endif
                              @if($purchase->status == 1)
                              <td><div class="badge badge-success">Recieved</div></td>
                              @elseif($purchase->status == 2)
                              <td><div class="badge badge-success">Partial</div></td>
                              @elseif($purchase->status == 3)
                              <td><div class="badge badge-danger">Pending</div></td>
                              @else
                              <td><div class="badge badge-danger">Ordered</div></td>
                              @endif
                              <td>{{$purchase->grand_total}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="return-latest">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>{{trans('file.date')}}</th>
                              <th>{{trans('file.reference')}}</th>
                              <th>{{trans('file.customer')}}</th>
                              <th>{{trans('file.grand total')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($recent_return as $return)
                            <?php $customer = DB::table('customers')->find($return->customer_id); ?>
                            <tr>
                              <td>{{date('d-m-Y', strtotime($return->date))}}</td>
                              <td>{{$return->reference_no}}</td>
                              <td>{{$customer->name}}</td>
                              <td>{{$return->grand_total}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
                  <div role="tabpanel" class="tab-pane fade" id="payment-latest">
                      <div class="table-responsive">
                        <table class="table table-striped">
                          <thead>
                            <tr>
                              <th>{{trans('file.date')}}</th>
                              <th>{{trans('file.reference')}}</th>
                              <th>{{trans('file.Amount')}}</th>
                              <th>{{trans('file.Paid')}} {{trans('file.By')}}</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($recent_payment as $payment)
                            <tr>
                              <td>{{date('d-m-Y', strtotime($payment->date))}}</td>
                              <td>{{$payment->payment_reference}}</td>
                              <td>{{$payment->amount}}</td>
                              <td>{{$payment->paying_method}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-5">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>{{trans('file.best selling product').' '.date('F')}}</h4>
                  <div class="right-column">
                    <div class="badge badge-primary">{{trans('file.top')}} 5</div>
                  </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>SL No</th>
                          <th>{{trans('file.product details')}}</th>
                          <th>{{trans('file.qty')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($best_selling_qty as $key=>$sale)
                        <?php $product = DB::table('products')->find($sale->product_id); ?>
                        <tr>
                          <td>{{$key + 1}}</td>
                          <td>{{$product->name}}<br>[{{$product->code}}]</td>
                          <td>{{$sale->sold_qty}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>{{trans('file.best selling product').' '.date('Y'). '('.trans('file.qty').')'}}</h4>
                  <div class="right-column">
                    <div class="badge badge-primary">{{trans('file.top')}} 5</div>
                  </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>SL No</th>
                          <th>{{trans('file.product details')}}</th>
                          <th>{{trans('file.qty')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($yearly_best_selling_qty as $key => $sale)
                        <?php $product = DB::table('products')->find($sale->product_id); ?>
                        <tr>
                          <td>{{$key + 1}}</td>
                          <td>{{$product->name}}<br>[{{$product->code}}]</td>
                          <td>{{$sale->sold_qty}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4>{{trans('file.best selling product').' '.date('Y') . '('.trans('file.price').')'}}</h4>
                  <div class="right-column">
                    <div class="badge badge-primary">{{trans('file.top')}} 5</div>
                  </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                      <thead>
                        <tr>
                          <th>SL No</th>
                          <th>{{trans('file.product details')}}</th>
                          <th>{{trans('file.grand total')}}</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($yearly_best_selling_price as $key => $sale)
                        <?php $product = DB::table('products')->find($sale->product_id); ?>
                        <tr>
                          <td>{{$key + 1}}</td>
                          <td>{{$product->name}}<br>[{{$product->code}}]</td>
                          <td>{{number_format((float)$sale->total_price, 2, '.', '')}}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </section>
<script type="text/javascript">

    $(".date-btn").on("click", function() {
        var index = $(this).parent('li').index();
        var start_date = $(this).data('start_date');
        var end_date = $(this).data('end_date');
        $.get('dashboard-filter/' + start_date + '/' + end_date, function(data) {
            $(".date-btn").removeClass("active");
            $("ul.filter-toggle li:eq("+index+") .date-btn").addClass("active");
            dashboardFilter(data);
        });
    });

    function dashboardFilter(data){
        $('.revenue-data').hide();
        $('.revenue-data').html(parseFloat(data[0]));
        $('.revenue-data').show(500);

        $('.return-data').hide();
        $('.return-data').html(parseFloat(data[1]));
        $('.return-data').show(500);

        $('.profit-data').hide();
        $('.profit-data').html(parseFloat(data[3]));
        $('.profit-data').show(500);

        $('.sale-data').hide();
        $('.sale-data').html(parseFloat(data[2]));
        $('.sale-data').show(500);
    }
</script>
@endsection

