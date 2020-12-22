<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{url('public/logo', $general_setting->site_logo)}}" style="filter: brightness(0);" />
    <title>{{$general_setting->site_title}}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-datepicker.min.css') ?>" type="text/css">
    <link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap-select.min.css') ?>" type="text/css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/font-awesome/css/font-awesome.min.css') ?>" type="text/css">
    <!-- Fontastic Custom icon font-->
    <link rel="stylesheet" href="<?php echo asset('public/css/fontastic.css') ?>" type="text/css">
    <!-- Ion icon font-->
    <link rel="stylesheet" href="<?php echo asset('public/css/ionicons.min.css') ?>" type="text/css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="<?php echo asset('public/css/grasp_mobile_progress_circle-1.0.0.min.css') ?>" type="text/css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css') ?>" type="text/css">
    <!-- virtual keybord stylesheet-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/keyboard/css/keyboard.css') ?>" type="text/css">
    <!-- date range stylesheet-->
    <link rel="stylesheet" href="<?php echo asset('public/vendor/daterange/css/daterangepicker.min.css') ?>" type="text/css">
    <!-- table sorter stylesheet-->
    <link rel="stylesheet" type="text/css" href="<?php echo asset('public/vendor/datatable/dataTables.bootstrap4.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('public/vendor/datatable/buttons.bootstrap4.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('public/vendor/datatable/select.bootstrap4.min.css') ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo asset('public/vendor/datatable/dataTables.checkboxes.css') ?>">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?php echo asset('public/css/style.default.css') ?>" id="theme-stylesheet" type="text/css">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?php echo asset('public/css/custom.css') ?>" type="text/css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="img/favicon.ico">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

<script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/jquery/jquery-ui.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/jquery/bootstrap-datepicker.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/popper.js/umd/popper.min.js') ?>">
</script>
<script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/bootstrap/js/bootstrap-select.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/keyboard/js/jquery.keyboard.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/keyboard/js/jquery.keyboard.extension-autocomplete.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/js/grasp_mobile_progress_circle-1.0.0.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/jquery.cookie/jquery.cookie.js') ?>">
</script>
<script type="text/javascript" src="<?php echo asset('public/vendor/chart.js/Chart.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/jquery-validation/jquery.validate.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js')?>"></script>
<script type="text/javascript" src="<?php echo asset('public/js/charts-custom.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/js/front.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/moment.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/knockout-3.4.2.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/daterange/js/daterangepicker.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/tinymce/js/tinymce/tinymce.min.js') ?>"></script>

<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/pdfmake.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/vfs_fonts.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/jquery.dataTables.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.bootstrap4.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.buttons.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.bootstrap4.min.js') ?>">"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.colVis.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.html5.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/buttons.print.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.select.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/sum().js') ?>"></script>
<script type="text/javascript" src="<?php echo asset('public/vendor/datatable/dataTables.checkboxes.min.js') ?>"></script>

  </head>
  <body>
    <!-- Side Navbar -->
    <nav class="side-navbar">
      <div class="side-navbar-wrapper">
        <!-- Sidebar Header    -->
        <div class="sidenav-header d-flex align-items-center justify-content-center">
          <!-- User Info-->
          <div class="sidenav-header-inner text-center">
            <span class="brand-big text-center">@if($general_setting->site_logo)<img src="{{url('public/logo', $general_setting->site_logo)}}" height="80" width="80">&nbsp;&nbsp;@endif<strong>{{$general_setting->site_title}}</strong></span>
          </div>
          <!-- Small Brand information, appears on minimized sidebar-->
          <div class="sidenav-header-logo"><a href="index.html" class="brand-small text-center"> <strong>L</strong></a></div>
        </div>
        <!-- Sidebar Navigation Menus-->
        <div class="main-menu">
          <ul id="side-main-menu" class="side-menu list-unstyled">
            <li><a href="{{url('/')}}"> <i class="icon ion-ios-speedometer-outline"></i>{{ __('file.dashboard') }}</a></li>
            <?php
              $role = DB::table('roles')->find(Auth::user()->role_id);
              $index_permission = DB::table('permissions')->where('name', 'products-index')->first();
              $index_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $index_permission->id],
                      ['role_id', $role->id]
                  ])->first();
              $print_barcode_permission = DB::table('permissions')->where('name', 'print_barcode')->first();
              $print_barcode_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $print_barcode_permission->id],
                      ['role_id', $role->id]
                  ])->first();
              $stock_count_permission = DB::table('permissions')->where('name', 'stock_count')->first();
              $stock_count_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $stock_count_permission->id],
                      ['role_id', $role->id]
                  ])->first();
              $adjustment_permission = DB::table('permissions')->where('name', 'adjustment')->first();
              $adjustment_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $adjustment_permission->id],
                      ['role_id', $role->id]
                  ])->first();
            ?>
            <li><a href="#product" aria-expanded="false" data-toggle="collapse"> <i class="icon-list"></i>{{__('file.product')}}</a>
              <ul id="product" class="collapse list-unstyled ">
                <li id="category-menu"><a href="{{route('category.index')}}">{{__('file.category')}}</a></li>
                @if($index_permission_active)
                <li id="product-list-menu"><a href="{{route('products.index')}}">{{__('file.product_list')}}</a></li>
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'products-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li id="product-add-menu"><a href="{{route('products.create')}}">{{__('file.add_product')}}</a></li>
                @endif
                @endif
                @if($print_barcode_permission_active)
                <li id="print-barcode-menu"><a href="{{route('product.printBarcode')}}">{{__('file.print_barcode')}}</a></li>
                @endif
                @if($adjustment_permission_active)
                <li id="adjustment-list-menu"><a href="{{route('qty_adjustment.index')}}">{{trans('file.Adjustment')}} {{trans('file.List')}}</a></li>
                <li id="adjustment-add-menu"><a href="{{route('qty_adjustment.create')}}">{{trans('file.add')}} {{trans('file.Adjustment')}}</a></li>
                @endif
                @if($stock_count_permission_active)
                <li id="stock-count-menu"><a href="{{route('stock-count.index')}}">{{__('file.Stock Count')}}</a></li>
                @endif
              </ul>
            </li>
            <?php 
              $index_permission = DB::table('permissions')->where('name', 'purchases-index')->first();
                $index_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $index_permission->id],
                      ['role_id', $role->id]
                  ])->first();
            ?>
            @if($index_permission_active)
            <li><a href="#purchase" aria-expanded="false" data-toggle="collapse"> <i class="icon-bill"></i>{{trans('file.Purchase')}}</a>
              <ul id="purchase" class="collapse list-unstyled ">
                <li id="purchase-list-menu"><a href="{{route('purchase.index')}}">{{trans('file.Purchase')}} {{trans('file.List')}}</a></li>
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'purchases-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li id="purchase-add-menu"><a href="{{route('purchase.create')}}">{{trans('file.add')}} {{trans('file.Purchase')}}</a></li>
                @endif
              </ul>
            </li>
            @endif
            <?php 
              $index_permission = DB::table('permissions')->where('name', 'sales-index')->first();
              $index_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $index_permission->id],
                      ['role_id', $role->id]
                  ])->first();
            ?>
            @if($index_permission_active)
            <li><a href="#sale" aria-expanded="false" data-toggle="collapse"> <i class="icon ion-ios-cart-outline"></i>{{trans('file.Sale')}}</a>
              <ul id="sale" class="collapse list-unstyled ">
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'sales-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li id="pos-menu"><a href="{{route('sale.pos')}}">POS</a></li>
                @endif
                <li id="sale-list-menu"><a href="{{route('sale.index')}}">{{trans('file.Sale')}} {{trans('file.List')}}</a></li>
                <li id="gift-card-menu"><a href="{{route('gift_cards.index')}}">{{trans('file.Gift Card')}} {{trans('file.List')}}</a></li>
                <li id="delivery-menu"><a href="{{route('delivery.index')}}">{{trans('file.Delivery')}} {{trans('file.List')}}</a></li>
              </ul>
            </li>
            @endif
            <?php 
              $index_permission = DB::table('permissions')->where('name', 'expenses-index')->first();
              $index_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $index_permission->id],
                      ['role_id', $role->id]
                  ])->first();
            ?>
            @if($index_permission_active)
            <li><a href="#expense" aria-expanded="false" data-toggle="collapse"> <i class="icon ion-calculator"></i>{{trans('file.Expense')}}</a>
              <ul id="expense" class="collapse list-unstyled ">
                <li id="expense-category-menu"><a href="{{route('expense_categories.index')}}">{{trans('file.Expense Category')}}</a></li>
                <li id="expense-menu"><a href="{{route('expenses.index')}}">{{trans('file.Expense')}} {{trans('file.List')}}</a></li>
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'expenses-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li><a id="add-expense" href=""> {{trans('file.add')}} {{trans('file.Expense')}}</a></li>
                @endif
              </ul>
            </li>
            @endif
            <?php 
              $index_permission = DB::table('permissions')->where('name', 'transfers-index')->first();
              $index_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $index_permission->id],
                      ['role_id', $role->id]
                  ])->first();
            ?>
            @if($index_permission_active)
            <li><a href="#transfer" aria-expanded="false" data-toggle="collapse"> <i class="icon ion-share"></i>{{trans('file.Transfer')}}</a>
              <ul id="transfer" class="collapse list-unstyled ">
                <li id="transfer-list-menu"><a href="{{route('transfers.index')}}">{{trans('file.Transfer')}} {{trans('file.List')}}</a></li>
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'transfers-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li id="transfer-add-menu"><a href="{{route('transfers.create')}}">{{trans('file.add')}} {{trans('file.Transfer')}}</a></li>
                @endif
              </ul>
            </li>
            @endif
            <li><a href="#return" aria-expanded="false" data-toggle="collapse"> <i class="ion-arrow-return-left"></i> {{trans('file.return')}}</a>
              <ul id="return" class="collapse list-unstyled ">
                <?php 
                  $index_permission = DB::table('permissions')->where('name', 'returns-index')->first();
                  $index_permission_active = DB::table('role_has_permissions')->where([
                          ['permission_id', $index_permission->id],
                          ['role_id', $role->id]
                      ])->first();
                  $return_purchase_permission = DB::table('permissions')->where('name', 'return-purchase-index')->first();
                  $return_purchase_permission_active = DB::table('role_has_permissions')->where([
                          ['permission_id', $return_purchase_permission->id],
                          ['role_id', $role->id]
                      ])->first();
                ?>
                @if($index_permission_active)
                <li id="return-sale-menu"><a href="{{route('return-sale.index')}}">{{trans('file.Sale')}}</a></li>
                @endif
                @if($return_purchase_permission_active)
                <li id="return-purchase-menu"><a href="{{route('return-purchase.index')}}">{{trans('file.Purchase')}}</a></li>
                @endif
              </ul>
            </li>
            <li><a href="#people" aria-expanded="false" data-toggle="collapse"> <i class="icon-user"></i>{{trans('file.People')}}</a>
              <ul id="people" class="collapse list-unstyled ">
                <?php $index_permission_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'users-index'],
                        ['role_id', $role->id] ])->first();
                ?>
                @if($index_permission_active)
                <li id="user-list-menu"><a href="{{route('user.index')}}">{{trans('file.User')}} {{trans('file.List')}}</a></li>
                <?php $add_permission_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'users-add'],
                        ['role_id', $role->id] ])->first();
                ?>
                @if($add_permission_active)
                <li id="user-add-menu"><a href="{{route('user.create')}}">{{trans('file.add')}} {{trans('file.User')}}</a></li>
                @endif
                @endif
                <?php 
                  $index_permission = DB::table('permissions')->where('name', 'customers-index')->first();
                  $index_permission_active = DB::table('role_has_permissions')->where([
                          ['permission_id', $index_permission->id],
                          ['role_id', $role->id]
                      ])->first();
                ?>
                @if($index_permission_active)
                <li id="customer-list-menu"><a href="{{route('customer.index')}}">{{trans('file.customer')}} {{trans('file.List')}}</a></li>
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'customers-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li id="customer-add-menu"><a href="{{route('customer.create')}}">{{trans('file.add')}} {{trans('file.customer')}}</a></li>
                @endif
                @endif
                <?php 
                  $index_permission = DB::table('permissions')->where('name', 'suppliers-index')->first();
                  $index_permission_active = DB::table('role_has_permissions')->where([
                          ['permission_id', $index_permission->id],
                          ['role_id', $role->id]
                      ])->first();
                ?>
                @if($index_permission_active)
                <li id="supplier-list-menu"><a href="{{route('supplier.index')}}">{{trans('file.Supplier')}} {{trans('file.List')}}</a></li>
                <?php 
                  $add_permission = DB::table('permissions')->where('name', 'suppliers-add')->first();
                  $add_permission_active = DB::table('role_has_permissions')->where([
                      ['permission_id', $add_permission->id],
                      ['role_id', $role->id]
                  ])->first();
                ?>
                @if($add_permission_active)
                <li id="supplier-add-menu"><a href="{{route('supplier.create')}}">{{trans('file.add')}} {{trans('file.Supplier')}}</a></li>
                @endif
                @endif
              </ul>
            </li>
            <li><a href="#report" aria-expanded="false" data-toggle="collapse"> <i class="icon ion-ios-paper-outline"></i>{{trans('file.Report')}}</a>
              <?php
                $profit_loss_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'profit-loss'],
                        ['role_id', $role->id] ])->first();
                $best_seller_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'best-seller'],
                        ['role_id', $role->id] ])->first();
                $store_stock_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'store-stock-report'],
                        ['role_id', $role->id] ])->first();
                $product_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'product-report'],
                        ['role_id', $role->id] ])->first();
                $daily_sale_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'daily-sale'],
                        ['role_id', $role->id] ])->first();
                $monthly_sale_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'monthly-sale'],
                        ['role_id', $role->id]])->first();
                $daily_purchase_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'daily-purchase'],
                        ['role_id', $role->id] ])->first();
                $monthly_purchase_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'monthly-purchase'],
                        ['role_id', $role->id] ])->first();
                $purchase_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'purchase-report'],
                        ['role_id', $role->id] ])->first();
                $sale_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'sale-report'],
                        ['role_id', $role->id] ])->first();
                $payment_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'payment-report'],
                        ['role_id', $role->id] ])->first();
                $product_qty_alert_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'product-qty-alert'],
                        ['role_id', $role->id] ])->first();
                $customer_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'customer-report'],
                        ['role_id', $role->id] ])->first();
                $supplier_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'supplier-report'], 
                        ['role_id', $role->id] ])->first();
                $due_report_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'due-report'],
                        ['role_id', $role->id] ])->first();
              ?>
              <ul id="report" class="collapse list-unstyled ">
                @if($profit_loss_active)
                <li id="summary-menu">
                  {!! Form::open(['route' => 'report.profitLoss', 'method' => 'post', 'id' => 'profitLoss-report-form']) !!}
                  <input type="hidden" name="start_date" value="{{date('Y-m').'-'.'01'}}" />
                  <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />
                  <a id="profitLoss-link" href="">{{trans('file.Summary Report')}}</a>
                  {!! Form::close() !!}
                </li>
                @endif
                @if($best_seller_active)
                <li id="best-seller-menu">
                  <a href="{{url('report/best_seller')}}">{{trans('file.Best Seller')}}</a>
                </li>
                @endif

                @if($store_stock_report_active)
                <li id="store-stock-menu">
                  <a href="{{route('report.storeStock')}}">{{trans('file.Store')}} {{trans('file.Stock Chart')}}</a>
                </li>
                @endif
                @if($product_report_active)
                <li id="product-report-menu">
                  {!! Form::open(['route' => 'report.product', 'method' => 'post', 'id' => 'product-report-form']) !!}
                  <input type="hidden" name="start_date" value="1988-04-18" />
                  <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />
                  <input type="hidden" name="store_id" value="0" />
                  <a id="report-link" href="">{{trans('file.product')}} {{trans('file.Report')}} </a>
                  {!! Form::close() !!}
                </li>
                @endif
                @if($daily_sale_active)
                <li id="daily-sale-report-menu">
                  <a href="{{url('report/daily_sale/'.date('Y').'/'.date('m'))}}">{{trans('file.Daily Sale')}}</a>
                </li>
                @endif
                @if($monthly_sale_active)
                <li id="monthly-sale-report-menu">
                  <a href="{{url('report/monthly_sale/'.date('Y'))}}">{{trans('file.Monthly Sale')}}</a>
                </li>
                @endif
                @if($daily_purchase_active)
                <li id="daily-purchase-report-menu">
                  <a href="{{url('report/daily_purchase/'.date('Y').'/'.date('m'))}}">{{trans('file.Daily Purchase')}}</a>
                </li>
                @endif
                @if($monthly_purchase_active)
                <li id="monthly-purchase-report-menu">
                  <a href="{{url('report/monthly_purchase/'.date('Y'))}}">{{trans('file.Monthly Purchase')}}</a>
                </li>
                @endif
                @if($sale_report_active)
                <li id="sale-report-menu">
                  {!! Form::open(['route' => 'report.sale', 'method' => 'post', 'id' => 'sale-report-form']) !!}
                  <input type="hidden" name="start_date" value="1988-04-18" />
                  <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />
                  <input type="hidden" name="store_id" value="0" />
                  <a id="sale-report-link" href="">{{trans('file.Sale')}} {{trans('file.Report')}}</a>
                  {!! Form::close() !!}
                </li>
                @endif
                @if($payment_report_active)
                <li id="payment-report-menu">
                  {!! Form::open(['route' => 'report.paymentByDate', 'method' => 'post', 'id' => 'payment-report-form']) !!}
                  <input type="hidden" name="start_date" value="1988-04-18" />
                  <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />
                  <a id="payment-report-link" href="">{{trans('file.Payment')}} {{trans('file.Report')}}</a>
                  {!! Form::close() !!}
                </li>
                @endif
                @if($purchase_report_active)
                <li id="purchase-report-menu">
                  {!! Form::open(['route' => 'report.purchase', 'method' => 'post', 'id' => 'purchase-report-form']) !!}
                  <input type="hidden" name="start_date" value="1988-04-18" />
                  <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />
                  <input type="hidden" name="store_id" value="0" />
                  <a id="purchase-report-link" href="">{{trans('file.Purchase')}} {{trans('file.Report')}}</a>
                  {!! Form::close() !!}
                </li>
                @endif
                @if($product_qty_alert_active)
                <li id="qty-alert-menu">
                  <a href="{{route('report.qtyAlert')}}">{{trans('file.product')}} {{trans('file.Quantity')}} {{trans('file.Alert')}}</a>
                </li>
                @endif
                @if($customer_report_active)
                <li id="customer-report-menu">
                  <a id="customer-report-link" href="">{{trans('file.customer')}} {{trans('file.Report')}}</a>
                </li>
                @endif
                @if($supplier_report_active)
                <li id="supplier-report-menu">
                  <a id="supplier-report-link" href="">{{trans('file.Supplier')}} {{trans('file.Report')}}</a>
                </li>
                @endif
                @if($due_report_active)
                <li id="due-report-menu">
                  {!! Form::open(['route' => 'report.dueByDate', 'method' => 'post', 'id' => 'due-report-form']) !!}
                  <input type="hidden" name="start_date" value="1988-04-18" />
                  <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />
                  <a id="due-report-link" href="">{{trans('file.Due')}} {{trans('file.Report')}}</a>
                  {!! Form::close() !!}
                </li>
                @endif
              </ul>
            </li>
            <li><a href="#setting" aria-expanded="false" data-toggle="collapse"> <i class="icon ion-ios-gear-outline"></i>{{trans('file.settings')}}</a>
              <ul id="setting" class="collapse list-unstyled ">
                @if($role->id <= 2)
                <li id="role-menu"><a href="{{route('role.index')}}">{{trans('file.Role Permission')}}</a></li>
                @endif
                <li id="store-menu"><a href="{{route('store.index')}}">{{trans('file.Store')}}</a></li>
                <li id="customer-group-menu"><a href="{{route('customer_group.index')}}">{{trans('file.Customer Group')}}</a></li>
                <li id="brand-menu"><a href="{{route('brand.index')}}">{{trans('file.Brand')}}</a></li>
                <li id="tax-menu"><a href="{{route('tax.index')}}">{{trans('file.Tax')}}</a></li>
                <?php 
                    $general_setting_permission = DB::table('permissions')->where('name', 'general_setting')->first();
                    $general_setting_permission_active = DB::table('role_has_permissions')->where([
                                ['permission_id', $general_setting_permission->id],
                                ['role_id', $role->id]
                            ])->first();

                    $mail_setting_permission = DB::table('permissions')->where('name', 'mail_setting')->first();
                    $mail_setting_permission_active = DB::table('role_has_permissions')->where([
                        ['permission_id', $mail_setting_permission->id],
                        ['role_id', $role->id]
                    ])->first();

                    $pos_setting_permission = DB::table('permissions')->where('name', 'pos_setting')->first();
                    $pos_setting_permission_active = DB::table('role_has_permissions')->where([
                        ['permission_id', $pos_setting_permission->id],
                        ['role_id', $role->id]
                    ])->first();

                    $empty_database_permission = DB::table('permissions')->where('name', 'empty_database')->first();
                    $empty_database_permission_active = DB::table('role_has_permissions')->where([
                        ['permission_id', $empty_database_permission->id],
                        ['role_id', $role->id]
                    ])->first();
                ?>
                @if($general_setting_permission_active)
                <li id="general-setting-menu"><a href="{{route('setting.general')}}">{{trans('file.General Setting')}}</a></li>
                @endif
                @if($mail_setting_permission_active)
                <li id="mail-setting-menu"><a href="{{route('setting.mail')}}">{{trans('file.Mail Setting')}}</a></li>
                @endif
                <li id="profile-menu"><a href="{{route('user.profile', ['id' => Auth::id()])}}">{{trans('file.User')}} {{trans('file.profile')}}</a></li>
                @if($pos_setting_permission_active)
                <li id="pos-setting-menu"><a href="{{route('setting.pos')}}">POS {{trans('file.settings')}}</a></li>
                @endif
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="page">
      <!-- navbar-->
      <header class="header">
        <nav class="navbar">
          <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
              <span class=""><a class="float-right btn-pos white-text" href="{{route('sale.pos')}}"><i class="fa fa-th-large"></i>&nbsp; POS</a></span>
              <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                <li class="nav-item">
                  <a class="dropdown-item" href="{{url('/')}}"><i class="fa fa-dashboard"></i> {{ trans('file.dashboard') }}</a>
                </li>
                @if($alert_product > 0)
                <li class="nav-item">
                      <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i class="fa fa-bell"></i><span class="badge badge-danger">{{$alert_product}}</span>
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                      </a>
                      <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default notifications" user="menu">
                          <li class="notifications">
                            <a href="{{route('report.qtyAlert')}}" class="btn btn-link"> <i class="fa fa-exclamation-triangle"></i> {{$alert_product}} product exceeds alert quantity</a>
                          </li>
                      </ul>
                </li>
                @endif
                <li class="nav-item"> 
                  
                </li>
                <li class="nav-item">
                      <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i class="fa fa-language"></i> {{__('file.language')}} <i class="fa fa-angle-down"></i>
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                      </a>
                      <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                          <li>
                            <a href="{{ url('language_switch/en') }}" class="btn btn-link"> English</a>
                          </li>
                          <li>
                            <a href="{{ url('language_switch/id') }}" class="btn btn-link"> Indonesia</a>
                          </li>
                          
                      </ul>
                </li>
                <li class="nav-item">
                      <a rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-item"><i class="fa fa-user"></i> {{strtoupper(Auth::user()->name)}} <i class="fa fa-angle-down"></i>
                          <span class="caret"></span>
                          <span class="sr-only">Toggle Dropdown</span>
                      </a>
                      <ul class="dropdown-menu edit-options dropdown-menu-right dropdown-default" user="menu">
                          <li> 
                            <a href="{{route('user.profile', ['id' => Auth::id()])}}"><i class="fa fa-user"></i> {{trans('file.profile')}}</a>
                          </li>
                          @if($general_setting_permission_active)
                          <li> 
                            <a href="{{route('setting.general')}}"><i class="fa fa-cog"></i> {{trans('file.settings')}}</a>
                          </li>
                          @endif
                          @if($empty_database_permission_active)
                          <li>
                            <a onclick="return confirm('Are you sure want to delete? If you do this all of your data will be lost.')" href="{{route('setting.emptyDatabase')}}"><i class="fa fa-database"></i> {{trans('file.Empty Database')}}</a>
                          </li>
                          @endif
                          <li>
                            <a href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>
                                {{trans('file.logout')}}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                          </li>
                      </ul>
                </li> 
              </ul>
            </div>
          </div>
        </nav>
      </header>
      <!-- modal section -->
      <div id="expense-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.add')}} {{trans('file.Expense')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                  <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'expenses.store', 'method' => 'post']) !!}
                    <?php 
                      $ezpos_expense_category_list = DB::table('expense_categories')->where('is_active', true)->get();
                      $ezpos_store_list = DB::table('stores')->where('is_active', true)->get();
                    ?>
                      <div class="form-group">
                          <label><strong>{{trans('file.date')}} *</strong></label>
                          <input type="text" name="date" value="{{date('d-m-Y')}}" required class="form-control date">
                      </div>
                      <div class="form-group">
                          <label><strong>{{trans('file.Expense Category')}} *</strong></label>
                          <select name="expense_category_id" class="selectpicker form-control" required data-live-search="true" data-live-search-style="begins" title="Select Expense Category...">
                              @foreach($ezpos_expense_category_list as $expense_category)
                              <option value="{{$expense_category->id}}">{{$expense_category->name . ' (' . $expense_category->code. ')'}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label><strong>{{trans('file.Store')}} *</strong></label>
                          <select name="store_id" class="selectpicker form-control" required data-live-search="true" data-live-search-style="begins" title="Select store...">
                              @foreach($ezpos_store_list as $store)
                              <option value="{{$store->id}}">{{$store->name}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label><strong>{{trans('file.Amount')}} *</strong></label>
                          <input type="number" name="amount" step="any" required class="form-control">
                      </div>
                      <div class="form-group">
                          <label><strong>{{trans('file.Note')}}</strong></label>
                          <textarea name="note" rows="3" class="form-control"></textarea>
                      </div>
                      <div class="form-group">
                          <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                      </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
      </div>

      <div id="customer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.customer')}} {{trans('file.Report')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                  <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'report.customer', 'method' => 'post']) !!}
                    <?php 
                      $ezpos_customer_list = DB::table('customers')->where('is_active', true)->get();
                    ?>
                      <div class="form-group">
                          <label><strong>{{trans('file.customer')}} *</strong></label>
                          <select name="customer_id" class="selectpicker form-control" required data-live-search="true" id="customer-id" data-live-search-style="begins" title="Select customer...">
                              @foreach($ezpos_customer_list as $customer)
                              <option value="{{$customer->id}}">{{$customer->name . ' (' . $customer->phone_number. ')'}}</option>
                              @endforeach
                          </select>
                      </div>

                      <input type="hidden" name="start_date" value="1988-04-18" />
                      <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />

                      <div class="form-group">
                          <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                      </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
      </div>

      <div id="supplier-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="exampleModalLabel" class="modal-title">{{trans('file.Supplier')}} {{trans('file.Report')}}</h5>
                    <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                  <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                    {!! Form::open(['route' => 'report.supplier', 'method' => 'post']) !!}
                    <?php 
                      $ezpos_supplier_list = DB::table('suppliers')->where('is_active', true)->get();
                    ?>
                      <div class="form-group">
                          <label><strong>{{trans('file.Supplier')}} *</strong></label>
                          <select name="supplier_id" class="selectpicker form-control" required data-live-search="true" id="supplier-id" data-live-search-style="begins" title="Select Supplier...">
                              @foreach($ezpos_supplier_list as $supplier)
                              <option value="{{$supplier->id}}">{{$supplier->name . ' (' . $supplier->phone_number. ')'}}</option>
                              @endforeach
                          </select>
                      </div>

                      <input type="hidden" name="start_date" value="1988-04-18" />
                      <input type="hidden" name="end_date" value="{{date('Y-m-d')}}" />

                      <div class="form-group">
                          <button type="submit" class="btn btn-primary">{{trans('file.submit')}}</button>
                      </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
      </div>
      <!-- Counts Section -->
      
      <!-- Header Section-->
      
      <!-- Statistics Section-->
     
      <!-- Updates Section -->
      
      @yield('content')
      <footer class="main-footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <p>&copy; {{$general_setting->site_title}}</p>
            </div>
            <div class="col-sm-6 text-right">
              <p>{{trans('file.Developed')}} {{trans('file.By')}} <a href="http://lion-coders.com" class="external">Duban Project</a></p>
            </div>
          </div>
        </div>
      </footer>
    </div>
    @yield('scripts')
    <script type="text/javascript">

      var date = $('#date');
    date.datepicker({
     format: "dd-mm-yyyy",
     startDate: "<?php echo date('d-m-Y', strtotime('')); ?>",
     autoclose: true,
     todayHighlight: true
     });
      
      $("a#add-expense").click(function(e){
        e.preventDefault();
        $('#expense-modal').modal();
      });

      $("a#profitLoss-link").click(function(e){
        e.preventDefault();
        $("#profitLoss-report-form").submit();
      });

      $("a#report-link").click(function(e){
        e.preventDefault();
        $("#product-report-form").submit();
      });

      $("a#purchase-report-link").click(function(e){
        e.preventDefault();
        $("#purchase-report-form").submit();
      });

      $("a#sale-report-link").click(function(e){
        e.preventDefault();
        $("#sale-report-form").submit();
      });

      $("a#payment-report-link").click(function(e){
        e.preventDefault();
        $("#payment-report-form").submit();
      });

      $("a#customer-report-link").click(function(e){
        e.preventDefault();
        $('#customer-modal').modal();
      });

      $("a#supplier-report-link").click(function(e){
        e.preventDefault();
        $('#supplier-modal').modal();
      });

      $("a#due-report-link").click(function(e){
        e.preventDefault();
        $("#due-report-form").submit();
      });

      $('.selectpicker').selectpicker({
          style: 'btn-link',
      });
    </script>
  </body>
</html>