 <?php $__env->startSection('content'); ?>
<?php if($errors->has('phone_number')): ?>
<div class="alert alert-danger alert-dismissible text-center">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e($errors->first('phone_number')); ?></div>
<?php endif; ?> 
<?php if(session()->has('message')): ?>
    <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo session()->get('message'); ?></div>
<?php endif; ?>
<?php if(session()->has('not_permitted')): ?>
    <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><?php echo e(session()->get('not_permitted')); ?></div> 
<?php endif; ?>
<section class="forms pos-section">
    <div class="row">
        <div class="col-md-7 pr-0">
            <div class="card">
                <div class="card-body">
                    <?php echo Form::open(['route' => 'sale.store', 'method' => 'post', 'files' => true, 'class' => 'payment-form']); ?>

                    <?php
                        if($ezpos_pos_setting_data)
                            $keybord_active = $ezpos_pos_setting_data->keybord_active;
                        else
                            $keybord_active = 0;

                        $customer_active = DB::table('permissions')
                      ->join('role_has_permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                      ->where([
                        ['permissions.name', 'customers-add'],
                        ['role_id', \Auth::user()->role_id] ])->first();
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6 date">
                                    <div class="form-group">
                                        <input type="text" id="date" name="date" value="<?php echo e(date('d-m-Y')); ?>" class="form-control pos-text" required />
                                    </div>
                                </div>
                                <div class="col-md-6 store_id">
                                    <div class="form-group">
                                        <?php if($ezpos_pos_setting_data): ?>
                                        <input type="hidden" name="store_id_hidden" value="<?php echo e($ezpos_pos_setting_data->store_id); ?>">
                                        <?php endif; ?>
                                        <select required id="store_id" name="store_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select store...">
                                            <?php $__currentLoopData = $ezpos_store_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($store->id); ?>"><?php echo e($store->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php if($ezpos_pos_setting_data): ?>
                                        <input type="hidden" name="customer_id_hidden" value="<?php echo e($ezpos_pos_setting_data->customer_id); ?>">
                                        <?php endif; ?>
                                        <?php if($customer_active): ?>
                                        <div class="input-group pos">
                                            <select required name="customer_id" id="customer_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select customer...">
                                            <?php $deposit = [];?>
                                            <?php $__currentLoopData = $ezpos_customer_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $deposit[$customer->id] = $customer->deposit - $customer->expense; ?>
                                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name . ' (' . $customer->phone_number . ')'); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addCustomer" title="Add Customer"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <?php else: ?>
                                        <div class="input-group pos">
                                            <select required name="customer_id" id="customer_id" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select customer...">
                                            <?php $__currentLoopData = $ezpos_customer_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $deposit[$customer->id] = $customer->deposit - $customer->expense; ?>
                                                <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name . ' (' . $customer->phone_number . ')'); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="search-box form-group">
                                        <input type="text" name="product_code_name" id="ezpos_productcodeSearch" placeholder="Scan/Search product by name/code" class="form-control pos-text" autofocus />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-1">
                                <div class="table-responsive">
                                    <table id="myTable" class="table table-hover order-list table-fixed">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-4"><?php echo e(trans('file.product')); ?></th>
                                                <th class="col-sm-2"><?php echo e(trans('file.Price')); ?></th>
                                                <th class="col-sm-3"><?php echo e(trans('file.Quantity')); ?></th>
                                                <th class="col-sm-2"><?php echo e(trans('file.Subtotal')); ?></th>
                                                <th class="col-sm-1"><i class="fa fa-trash"></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                        <tfoot class="tfoot active">
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <table class="table table-bordered table-condensed totals">
                                    <tr>
                                    <td style="width:15%; padding: 0 0 0 10px; color: #000;"><strong><?php echo e(trans('file.Items')); ?></strong><br>
                                    <span id="item">0</span>
                                    </td>
                                    <td style="width:15%; padding: 0 0 0 10px; color: #000;"><strong><?php echo e(trans('file.Total')); ?></strong><br>
                                    <span id="subtotal">0.00</span>
                                    </td>
                                    <td style="width:18%; padding: 0 0 0 10px; color: #000;"><strong><?php echo e(trans('file.Discount')); ?></strong>
                                        <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#order-discount"> <i class="fa fa-edit"></i></button><br>
                                        <span id="discount">0.00</span>
                                    </td>
                                    <td style="width:18%; padding: 0 0 0 10px; color: #000;"><strong><?php echo e(trans('file.Tax')); ?></strong>
                                    <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#order-tax"><i class="fa fa-edit"></i></button><br>
                                    <span id="tax">0.00</span>
                                    </td>
                                    <td style="width:18%; padding: 0 0 0 10px; color: #000;"><strong><?php echo e(trans('file.Shipping')); ?></strong>
                                        <button type="button" class="btn btn-link btn-sm" data-toggle="modal" data-target="#shipping-cost-modal"><i class="fa fa-edit"></i></button><br>
                                        <span id="shipping-cost">0.00</span>
                                    </td>
                                    <td style="width:15%; padding: 0 0 0 10px; color: #000;"><strong><?php echo e(trans('file.grand total')); ?></strong><br>
                                    <span id="grand-total">0.00</span>
                                    </td>
                                </tr>
                                </table>
                            </div>
                            
                            <div class="column-5">
                                <button style="background: #47d147" type="button" class="btn btn-custom" data-toggle="modal" data-target="#add-payment" id="cash-btn"><i class="fa fa-money"></i> Cash</button>
                            </div>
                            
                            <div class="column-5">
                                <button style="background-color: #e28d02" type="button" class="btn btn-custom" id="draft-btn"><i class="ion-android-drafts"></i> Draft</button>  
                            </div>
                            <div class="column-5">
                                <button style="background-color: #6666ff" type="button" class="btn btn-custom" data-toggle="modal" data-target="#add-payment" id="paypal-btn"><i class="fa fa-paypal"></i> OVO</button>
                            </div>
                            
                            <div class="column-5">
                                <button style="background-color: #800080" type="button" class="btn btn-custom" data-toggle="modal" data-target="#add-payment" id="gift-card-btn"><i class="ion-card"></i> GiftCard</button>
                            </div>
                            
                            <div class="column-5">
                                <button style="background-color: #cc0000;" type="button" class="btn btn-custom" id="cancel-btn" onclick="return confirmCancel()"><i class="ion-android-cancel"></i> Cancel</button>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_qty" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_discount" value="0.00" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_tax" value="0.00"/>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="total_price" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="item" />
                                        <input type="hidden" name="order_tax" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="grand_total" />
                                        <input type="hidden" name="sale_status" value="1" />
                                        <input type="hidden" name="draft" value="0" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
        </div>
        <!-- order_discount modal -->
        <div id="order-discount" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(trans('file.Order')); ?> <?php echo e(trans('file.Discount')); ?></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="order_discount" class="form-control numkey" step="any">
                        </div>
                        <button type="button" name="order_discount_btn" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('file.submit')); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- order_tax modal -->
        <div id="order-tax" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(trans('file.Order')); ?> <?php echo e(trans('file.Tax')); ?></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <select class="form-control" name="order_tax_rate">
                                <option value="0">No Tax</option>
                                <?php $__currentLoopData = $ezpos_tax_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($tax->rate); ?>"><?php echo e($tax->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="button" name="order_tax_btn" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('file.submit')); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- shipping_cost modal -->
        <div id="shipping-cost-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo e(trans('file.Shipping Cost')); ?></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="shipping_cost" class="form-control numkey" step="any">
                        </div>
                        <button type="button" name="shipping_cost_btn" class="btn btn-primary" data-dismiss="modal"><?php echo e(trans('file.submit')); ?></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- payment modal -->
        <div id="add-payment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="exampleModalLabel" class="modal-title"><?php echo e(trans('file.Finalize')); ?> <?php echo e(trans('file.Sale')); ?></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label><strong><?php echo e(trans('file.Paying Amount')); ?> *</strong></label>
                                <input type="text" name="paying_amount" class="form-control numkey" required step="any">
                            </div>
                            <div class="col-md-6">
                                <label><strong><?php echo e(trans('file.Payable Amount')); ?> *</strong></label>
                                <input type="text" name="paid_amount" class="form-control numkey"  step="any">
                            </div>
                            <div class="col-md-6 mt-1">
                                <label><strong><?php echo e(trans('file.Change')); ?> : </strong></label>
                                <p id="change" class="ml-2">0.00</p>
                            </div>
                            <div class="col-md-6 mt-1">
                                <label><strong><?php echo e(trans('file.Paid By')); ?></strong></label>
                                <select name="paid_by_id" class="form-control">
                                    <option value="1">Cash</option>
                                    <option value="2">Gift Card</option>
                                 <!--   <option value="3">Credit Card</option> -->
                                 <!--   <option value="4">Cheque</option> -->
                                    <option value="5">OVO</option>
                                <!--     <option value="6">Deposit</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="card-element form-control">
                            </div>
                            <div class="card-errors" role="alert"></div>
                        </div>
                        <div class="form-group" id="gift-card">
                            <label><strong> <?php echo e(trans('file.Gift Card')); ?></strong></label>
                            <input type="hidden" name="gift_card_id">
                            <select id="gift_card_id_select" name="gift_card_id_select" class="selectpicker form-control" data-live-search="true" data-live-search-style="begins" title="Select Gift Card..."></select>
                        </div>
                        <div id="cheque">
                            <div class="form-group">
                                <label><strong><?php echo e(trans('file.Cheque')); ?> No</strong></label>
                                <input type="text" name="cheque_no" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label><strong><?php echo e(trans('file.Payment')); ?> <?php echo e(trans('file.Note')); ?></strong></label>
                            <textarea id="payment_note" rows="2" class="form-control" name="payment_note"></textarea>
                        </div>
                        <div class="row">
                           <div class="col-md-6 form-group">
                                <label><strong><?php echo e(trans('file.Sale')); ?> <?php echo e(trans('file.Note')); ?></strong></label>
                                <textarea rows="3" class="form-control" name="sale_note"></textarea>
                            </div>
                            <div class="col-md-6 form-group">
                                <label><strong><?php echo e(trans('file.Staff')); ?> <?php echo e(trans('file.Note')); ?></strong></label>
                                <textarea rows="3" class="form-control" name="staff_note"></textarea>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button id="submit-btn" type="submit" class="btn btn-primary"><?php echo e(trans('file.submit')); ?></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Form::close(); ?>

        <!-- product list -->
        <div class="col-md-5">
            <div class="filter-window">
                <div class="category mt-3">
                    <div class="row ml-2">
                        <?php $__currentLoopData = $ezpos_category_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 category-img" data-category="<?php echo e($category->id); ?>">
                            <img  src="<?php echo e(url('public/images/product/zummXD2dvAtI.png')); ?>" />
                            <p class="text-center"><?php echo e($category->name); ?></p>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <div class="brand mt-3">
                    <div class="row ml-2">
                        <?php $__currentLoopData = $ezpos_brand_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($brand->image): ?>
                            <div class="col-md-3 brand-img" data-brand="<?php echo e($brand->id); ?>">
                                <img  src="<?php echo e(url('public/images/brand',$brand->image)); ?>" />
                                <p class="text-center"><?php echo e($brand->title); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="col-md-3 brand-img" data-brand="<?php echo e($brand->id); ?>">
                                <img  src="<?php echo e(url('public/images/product/zummXD2dvAtI.png')); ?>" />
                                <p class="text-center"><?php echo e($brand->title); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-block btn-primary" id="category-filter"><?php echo e(trans('file.category')); ?></button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-block btn-info" id="brand-filter"><?php echo e(trans('file.Brand')); ?></button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-block btn-danger" id="featured-filter"><?php echo e(trans('file.Featured')); ?></button>
                        </div>
                        <div class="col-md-12 mt-1 table-container">
                            <table id="product-table" class="table product-list">
                                <thead class="d-none">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php for($i=0; $i < ceil($product_number/5); $i++): ?>
                                    <tr>
                                        <td class="product-img" title="<?php echo e($ezpos_product_list[0+$i*5]->name); ?>" data-product ="<?php echo e($ezpos_product_list[0+$i*5]->code . ' (' . $ezpos_product_list[0+$i*5]->name . ')'); ?>"><img  src="<?php echo e(url('public/images/product',$ezpos_product_list[0+$i*5]->image)); ?>" width="100%" />
                                            <p><?php echo e($ezpos_product_list[0+$i*5]->name); ?></p>
                                        </td>
                                        <?php if(!empty($ezpos_product_list[1+$i*5])): ?>
                                        <td class="product-img" title="<?php echo e($ezpos_product_list[1+$i*5]->name); ?>" data-product ="<?php echo e($ezpos_product_list[1+$i*5]->code . ' (' . $ezpos_product_list[1+$i*5]->name . ')'); ?>"><img  src="<?php echo e(url('public/images/product',$ezpos_product_list[1+$i*5]->image)); ?>" width="100%" />
                                            <p><?php echo e($ezpos_product_list[1+$i*5]->name); ?></p>
                                        </td>
                                        <?php else: ?>
                                        <td style="border:none;"></td>
                                        <?php endif; ?>
                                        <?php if(!empty($ezpos_product_list[2+$i*5])): ?>
                                        <td class="product-img" title="<?php echo e($ezpos_product_list[2+$i*5]->name); ?>" data-product ="<?php echo e($ezpos_product_list[2+$i*5]->code . ' (' . $ezpos_product_list[2+$i*5]->name . ')'); ?>"><img  src="<?php echo e(url('public/images/product',$ezpos_product_list[2+$i*5]->image)); ?>" width="100%" />
                                            <p><?php echo e($ezpos_product_list[2+$i*5]->name); ?></p>
                                        </td>
                                        <?php else: ?>
                                        <td style="border:none;"></td>
                                        <?php endif; ?>
                                        <?php if(!empty($ezpos_product_list[3+$i*5])): ?>
                                        <td class="product-img" title="<?php echo e($ezpos_product_list[3+$i*5]->name); ?>" data-product ="<?php echo e($ezpos_product_list[3+$i*5]->code . ' (' . $ezpos_product_list[3+$i*5]->name . ')'); ?>"><img  src="<?php echo e(url('public/images/product',$ezpos_product_list[3+$i*5]->image)); ?>" width="100%" />
                                            <p><?php echo e($ezpos_product_list[3+$i*5]->name); ?></p>
                                        </td>
                                        <?php else: ?>
                                        <td style="border:none;"></td>
                                        <?php endif; ?>
                                        <?php if(!empty($ezpos_product_list[4+$i*5])): ?>
                                        <td class="product-img" title="<?php echo e($ezpos_product_list[4+$i*5]->name); ?>" data-product ="<?php echo e($ezpos_product_list[4+$i*5]->code . ' (' . $ezpos_product_list[4+$i*5]->name . ')'); ?>"><img  src="<?php echo e(url('public/images/product',$ezpos_product_list[4+$i*5]->image)); ?>" width="100%" />
                                            <p><?php echo e($ezpos_product_list[4+$i*5]->name); ?></p>
                                        </td>
                                        <?php else: ?>
                                        <td style="border:none;"></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-header d-flex justify-content-between align-items-center">
                              <h4><?php echo e(trans('file.Recent Transaction')); ?></h4>
                              <div class="right-column">
                                <div class="badge badge-primary"><?php echo e(trans('file.latest')); ?> 10</div>
                              </div>
                              <button class="btn btn-default btn-sm transaction-btn-plus" type="button" data-toggle="collapse" data-target="#transaction" aria-expanded="false" aria-controls="transaction"><i class="ion-plus-circled"></i></button>
                              <button class="btn btn-default btn-sm transaction-btn-close d-none" type="button" data-toggle="collapse" data-target="#transaction" aria-expanded="false" aria-controls="transaction"><i class="ion-close-circled"></i></button>
                            </div>
                            <div class="collapse" id="transaction">
                                <div class="card card-body">
                                    <ul class="nav nav-tabs" role="tablist">
                                      <li class="nav-item">
                                        <a class="nav-link active" href="#sale-latest" role="tab" data-toggle="tab"><?php echo e(trans('file.Sale')); ?></a>
                                      </li>
                                      <li class="nav-item">
                                        <a class="nav-link" href="#draft-latest" role="tab" data-toggle="tab"><?php echo e(trans('file.Draft')); ?></a>
                                      </li>
                                    </ul>
                                    <div class="tab-content">
                                      <div role="tabpanel" class="tab-pane show active" id="sale-latest">
                                          <div class="table-responsive">
                                            <table class="table table-striped">
                                              <thead>
                                                <tr>
                                                  <th><?php echo e(trans('file.date')); ?></th>
                                                  <th><?php echo e(trans('file.reference')); ?></th>
                                                  <th><?php echo e(trans('file.customer')); ?></th>
                                                  <th><?php echo e(trans('file.grand total')); ?></th>
                                                  <th><?php echo e(trans('file.action')); ?></th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php $__currentLoopData = $recent_sale; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $customer = DB::table('customers')->find($sale->customer_id); ?>
                                                <tr>
                                                  <td><?php echo e(date('d-m-Y', strtotime($sale->date))); ?></td>
                                                  <td><?php echo e($sale->reference_no); ?></td>
                                                  <td><?php echo e($customer->name); ?></td>
                                                  <td><?php echo e($sale->grand_total); ?></td>
                                                  <td>
                                                    <div class="btn-group">
                                                        <?php if(in_array("sales-edit", $all_permission)): ?>
                                                        <a href="<?php echo e(route('sale.edit', ['id' => $sale->id])); ?>" class="btn btn-success btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                        <?php endif; ?>
                                                        <?php if(in_array("sales-delete", $all_permission)): ?>
                                                        <?php echo e(Form::open(['route' => ['sale.destroy', $sale->id], 'method' => 'DELETE'] )); ?>

                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDelete()" title="Delete"><i class="fa fa-trash"></i></button>
                                                        <?php echo e(Form::close()); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                  </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              </tbody>
                                            </table>
                                          </div>
                                      </div>
                                      <div role="tabpanel" class="tab-pane fade" id="draft-latest">
                                          <div class="table-responsive">
                                            <table class="table table-striped">
                                              <thead>
                                                <tr>
                                                  <th><?php echo e(trans('file.date')); ?></th>
                                                  <th><?php echo e(trans('file.reference')); ?></th>
                                                  <th><?php echo e(trans('file.customer')); ?></th>
                                                  <th><?php echo e(trans('file.grand total')); ?></th>
                                                  <th><?php echo e(trans('file.action')); ?></th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <?php $__currentLoopData = $recent_draft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $draft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $customer = DB::table('customers')->find($draft->customer_id); ?>
                                                <tr>
                                                  <td><?php echo e(date('d-m-Y', strtotime($draft->date))); ?></td>
                                                  <td><?php echo e($draft->reference_no); ?></td>
                                                  <td><?php echo e($customer->name); ?></td>
                                                  <td><?php echo e($draft->grand_total); ?></td>
                                                  <td>
                                                    <div class="btn-group">
                                                        <?php if(in_array("sales-edit", $all_permission)): ?>
                                                        <a href="<?php echo e(url('sale/'.$draft->id.'/create')); ?>" class="btn btn-success btn-sm" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;
                                                        <?php endif; ?>
                                                        <?php if(in_array("sales-delete", $all_permission)): ?>
                                                        <?php echo e(Form::open(['route' => ['sale.destroy', $draft->id], 'method' => 'DELETE'] )); ?>

                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirmDelete()" title="Delete"><i class="fa fa-trash"></i></button>
                                                        <?php echo e(Form::close()); ?>

                                                        <?php endif; ?>
                                                    </div>
                                                  </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                              </tbody>
                                            </table>
                                          </div>
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- product edit modal -->
        <div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 id="modal_header" class="modal-title"></h5>
                        <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label><strong><?php echo e(trans('file.Quantity')); ?></strong></label>
                                <input type="text" name="edit_qty" class="form-control numkey">
                            </div>
                            <div class="form-group">
                                <label><strong><?php echo e(trans('file.Unit')); ?> <?php echo e(trans('file.Discount')); ?></strong></label>
                                <input type="text" name="edit_discount" class="form-control numkey">
                            </div>
                            <div class="form-group">
                                <label><strong><?php echo e(trans('file.Unit Price')); ?></strong></label>
                                <input type="text" name="edit_unit_price" class="form-control numkey" step="any">
                            </div>
                            <?php
                    $tax_name_all[] = 'No Tax';
                    $tax_rate_all[] = 0;
                    foreach($ezpos_tax_list as $tax) {
                        $tax_name_all[] = $tax->name;
                        $tax_rate_all[] = $tax->rate;
                    }
                ?>
                                <div class="form-group">
                                    <label><strong><?php echo e(trans('file.Tax')); ?> <?php echo e(trans('file.Rate')); ?></strong></label>
                                    <select name="edit_tax_rate" class="form-control">
                                        <?php $__currentLoopData = $tax_name_all; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                                <button type="button" name="update_btn" class="btn btn-primary"><?php echo e(trans('file.update')); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- add customer modal -->
        <div id="addCustomer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
            <div role="document" class="modal-dialog">
              <div class="modal-content">
                <?php echo Form::open(['route' => 'customer.store', 'method' => 'post', 'files' => true]); ?>

                <div class="modal-header">
                  <h5 id="exampleModalLabel" class="modal-title"><?php echo e(trans('file.add')); ?> <?php echo e(trans('file.customer')); ?></h5>
                  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                  <p class="italic"><small><?php echo e(trans('file.The field labels marked with * are required input fields')); ?>.</small></p>
                    <div class="form-group">
                        <label><strong><?php echo e(trans('file.Customer Group')); ?> *</strong> </label>
                        <select required class="form-control selectpicker" name="customer_group_id">
                            <?php $__currentLoopData = $ezpos_customer_group_all; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($customer_group->id); ?>"><?php echo e($customer_group->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><strong><?php echo e(trans('file.name')); ?> *</strong> </label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong><?php echo e(trans('file.Email')); ?></strong></label>
                        <input type="text" name="email" placeholder="example@example.com" class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong><?php echo e(trans('file.Phone Number')); ?> *</strong></label>
                        <input type="text" name="phone_number" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong><?php echo e(trans('file.Address')); ?> *</strong></label>
                        <input type="text" name="address" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label><strong><?php echo e(trans('file.City')); ?> *</strong></label>
                        <input type="text" name="city" required class="form-control">
                    </div>
                    <div class="form-group">
                    <input type="hidden" name="pos" value="1">      
                      <input type="submit" value="<?php echo e(trans('file.submit')); ?>" class="btn btn-primary">
                    </div>
                </div>
                <?php echo e(Form::close()); ?>

              </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    
    var public_key = <?php echo json_encode($ezpos_pos_setting_data->stripe_public_key); ?>;
    var product_row_number = <?php echo json_encode($ezpos_pos_setting_data->product_number); ?>;

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
var product_type = [];

// array data with selection
var product_price = [];
var product_discount = [];
var tax_rate = [];
var tax_name = [];
var tax_method = [];
var unit_name = [];
var unit_operator = [];
var unit_operation_value = [];
var gift_card_amount = [];
var gift_card_expense = [];

// temporary array
var temp_unit_name = [];
var temp_unit_operator = [];
var temp_unit_operation_value = [];

var deposit = <?php echo json_encode($deposit) ?>;
var rowindex;
var customer_group_rate;
var row_product_price;
var pos;
var keyboard_active = <?php echo json_encode($keybord_active); ?>;

var role_id = <?php echo json_encode(\Auth::user()->role_id) ?>;
var store_id = <?php echo json_encode(\Auth::user()->store_id) ?>;
$('.selectpicker').selectpicker({
    style: 'btn-link',
});

if(keyboard_active==1){

    $("input.numkey:text").keyboard({
        usePreview: false,
        layout: 'custom',
        display: {
        'accept'  : '&#10004;',
        'cancel'  : '&#10006;'
        },
        customLayout : {
          'normal' : ['1 2 3', '4 5 6', '7 8 9','0 {dec} {bksp}','{clear} {cancel} {accept}']
        },
        restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in
        preventPaste : true,  // prevent ctrl-v and right click
        autoAccept : true,
        css: {
            // input & preview
            // keyboard container
            container: 'center-block dropdown-menu', // jumbotron
            // default state
            buttonDefault: 'btn btn-default',
            // hovered button
            buttonHover: 'btn-primary',
            // Action keys (e.g. Accept, Cancel, Tab, etc);
            // this replaces "actionClass" option
            buttonAction: 'active'
        },
    });

    $('input[type="text"]:not(#date)').keyboard({
        usePreview: false,
        autoAccept: true,
        autoAcceptOnEsc: true,
        css: {
            // input & preview
            // keyboard container
            container: 'center-block dropdown-menu', // jumbotron
            // default state
            buttonDefault: 'btn btn-default',
            // hovered button
            buttonHover: 'btn-primary',
            // Action keys (e.g. Accept, Cancel, Tab, etc);
            // this replaces "actionClass" option
            buttonAction: 'active',
            // used when disabling the decimal button {dec}
            // when a decimal exists in the input area
            buttonDisabled: 'disabled'
        },
        change: function(e, keyboard) {
                keyboard.$el.val(keyboard.$preview.val())
                keyboard.$el.trigger('propertychange')        
              }
    });

    $('textarea').keyboard({
        usePreview: false,
        autoAccept: true,
        autoAcceptOnEsc: true,
        css: {
            // input & preview
            // keyboard container
            container: 'center-block dropdown-menu', // jumbotron
            // default state
            buttonDefault: 'btn btn-default',
            // hovered button
            buttonHover: 'btn-primary',
            // Action keys (e.g. Accept, Cancel, Tab, etc);
            // this replaces "actionClass" option
            buttonAction: 'active',
            // used when disabling the decimal button {dec}
            // when a decimal exists in the input area
            buttonDisabled: 'disabled'
        },
        change: function(e, keyboard) {
                keyboard.$el.val(keyboard.$preview.val())
                keyboard.$el.trigger('propertychange')        
              }
    });

    $('#ezpos_productcodeSearch').keyboard().autocomplete().addAutocomplete({
        // add autocomplete window positioning
        // options here (using position utility)
        position: {
          of: '#ezpos_productcodeSearch',
          my: 'top+18px',
          at: 'center',
          collision: 'flip'
        }
    });
}

$('select[name=customer_id]').val($("input[name='customer_id_hidden']").val());

if(role_id > 2){
    $('.date').addClass('d-none');
    $('.store_id').addClass('d-none');
    $('select[name=store_id]').val(store_id);
}
else
    $('select[name=store_id]').val($("input[name='store_id_hidden']").val());

$('.selectpicker').selectpicker('refresh');

var id = $('select[name="customer_id"]').val();
$.get('sale/getcustomergroup/' + id, function(data) {
    customer_group_rate = (data / 100);
});

var id = $('select[name="store_id"]').val();
$.get('sale/getproduct/' + id, function(data) {
    ezpos_product_array = [];
    product_code = data[0];
    product_name = data[1];
    product_qty = data[2];
    product_type = data[3];
    $.each(product_code, function(index) {
        ezpos_product_array.push(product_code[index] + ' -- (' + product_name[index] + ')');
    });
});

if(keyboard_active==1){
    $('#ezpos_productcodeSearch').bind('keyboardChange', function (e, keyboard, el) {
        var customer_id = $('#customer_id').val();
        var store_id = $('select[name="store_id"]').val();
        temp_data = $('#ezpos_productcodeSearch').val();
        if(!customer_id){
            $('#ezpos_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
            alert('Please select Customer!');
        }
        else if(!store_id){
            $('#ezpos_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
            alert('Please select store!');
        }
    });
}
else{
    $('#ezpos_productcodeSearch').on('input', function(){
        var customer_id = $('#customer_id').val();
        var store_id = $('#store_id').val();
        temp_data = $('#ezpos_productcodeSearch').val();
        if(!customer_id){
            $('#ezpos_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
            alert('Please select Customer!');
        }
        else if(!store_id){
            $('#ezpos_productcodeSearch').val(temp_data.substring(0, temp_data.length - 1));
            alert('Please select store!');
        }

    });
}

$('body').on('click', function(e){
    $('.filter-window').hide('slide', {direction: 'right'}, 'fast');
});

$('#category-filter').on('click', function(e){
    e.stopPropagation();
    $('.filter-window').show('slide', {direction: 'right'}, 'fast');
    $('.category').show();
    $('.brand').hide();
});

$('.category-img').on('click', function(){
    var category_id = $(this).data('category');
    var brand_id = 0;

    $(".table-container").children().remove();
    $.get('sale/getproduct/' + category_id + '/' + brand_id, function(data) {
        var tableData = '<table id="product-table" class="table product-list"> <thead class="d-none"> <tr> <th></th> <th></th> <th></th> <th></th> <th></th> </tr></thead> <tbody><tr>';
        if (Object.keys(data).length != 0) {
            $.each(data['name'], function(index) {
                var product_info = data['code'][index]+' (' + data['name'][index] + ')';
                if(index % 5 == 0 && index != 0){
                    tableData += '</tr><tr><td class="product-img" title="'+data['name'][index]+'" data-product = "'+product_info+'"><img  src="public/images/product/'+data['image'][index]+'" width="100%" /><p>'+data['name'][index]+'</p></td>';
                }
                else
                    tableData += '<td class="product-img" title="'+data['name'][index]+'" data-product = "'+product_info+'"><img  src="public/images/product/'+data['image'][index]+'" width="100%" /><p>'+data['name'][index]+'</p></td>';
            });

            if(data['name'].length % 5){
                var number = 5 - (data['name'].length % 5);
                while(number > 0)
                {
                    tableData += '<td style="border:none;"></td>';
                    number--;
                }
            }

            tableData += '</tr></tbody></table>';
            $(".table-container").html(tableData);
            $('#product-table').DataTable( {
            "order": [],
            'pageLength': product_row_number,
             'language': {
                'paginate': {
                    'previous': '<i class="fa fa-angle-left"></i>',
                    'next': '<i class="fa fa-angle-right"></i>'
                }
            },
            dom: 'tp'
            });
            $('table.product-list').hide();
            $('table.product-list').show(500);
        }
        else{
            tableData += '<td class="text-center">No data avaialable</td></tr></tbody></table>'
            $(".table-container").html(tableData);
        }
    });
});

$('#brand-filter').on('click', function(e){
    e.stopPropagation();
    $('.filter-window').show('slide', {direction: 'right'}, 'fast');
    $('.brand').show();
    $('.category').hide();
});

$('.brand-img').on('click', function(){
    var brand_id = $(this).data('brand');
    var category_id = 0;

    $(".table-container").children().remove();
    $.get('sale/getproduct/' + category_id + '/' + brand_id, function(data) {
        var tableData = '<table id="product-table" class="table product-list"> <thead class="d-none"> <tr> <th></th> <th></th> <th></th> <th></th> <th></th> </tr></thead> <tbody><tr>';
        if (Object.keys(data).length != 0) {
            $.each(data['name'], function(index) {
                var product_info = data['code'][index]+' (' + data['name'][index] + ')';
                if(index % 5 == 0 && index != 0){
                    tableData += '</tr><tr><td class="product-img" title="'+data['name'][index]+'" data-product = "'+product_info+'"><img  src="public/images/product/'+data['image'][index]+'" width="100%" /><p>'+data['name'][index]+'</p></td>';
                }
                else
                    tableData += '<td class="product-img" title="'+data['name'][index]+'" data-product = "'+product_info+'"><img  src="public/images/product/'+data['image'][index]+'" width="100%" /><p>'+data['name'][index]+'</p></td>';
            });

            if(data['name'].length % 5){
                var number = 5 - (data['name'].length % 5);
                while(number > 0)
                {
                    tableData += '<td style="border:none;"></td>';
                    number--;
                }
            }

            tableData += '</tr></tbody></table>';
            $(".table-container").html(tableData);
            $('#product-table').DataTable( {
            "order": [],
            'pageLength': product_row_number,
             'language': {
                'paginate': {
                    'previous': '<i class="fa fa-angle-left"></i>',
                    'next': '<i class="fa fa-angle-right"></i>'
                }
            },
            dom: 'tp'
            });
            $('table.product-list').hide();
            $('table.product-list').show(500);
        }
        else{
            tableData += '<td class="text-center">No data avaialable</td></tr></tbody></table>'
            $(".table-container").html(tableData);
        }
    });
});

$('#featured-filter').on('click', function(){
    $(".table-container").children().remove();
    $.get('sale/getfeatured', function(data) {
        var tableData = '<table id="product-table" class="table product-list"> <thead class="d-none"> <tr> <th></th> <th></th> <th></th> <th></th> <th></th> </tr></thead> <tbody><tr>';
        if (Object.keys(data).length != 0) {
            $.each(data['name'], function(index) {
                var product_info = data['code'][index]+' (' + data['name'][index] + ')';
                if(index % 5 == 0 && index != 0){
                    tableData += '</tr><tr><td class="product-img" title="'+data['name'][index]+'" data-product = "'+product_info+'"><img  src="public/images/product/'+data['image'][index]+'" width="100%" /><p>'+data['name'][index]+'</p></td>';
                }
                else
                    tableData += '<td class="product-img" title="'+data['name'][index]+'" data-product = "'+product_info+'"><img  src="public/images/product/'+data['image'][index]+'" width="100%" /><p>'+data['name'][index]+'</p></td>';
            });

            if(data['name'].length % 5){
                var number = 5 - (data['name'].length % 5);
                while(number > 0)
                {
                    tableData += '<td style="border:none;"></td>';
                    number--;
                }
            }

            tableData += '</tr></tbody></table>';
            $(".table-container").html(tableData);
            $('#product-table').DataTable( {
            "order": [],
            'pageLength': product_row_number,
             'language': {
                'paginate': {
                    'previous': '<i class="fa fa-angle-left"></i>',
                    'next': '<i class="fa fa-angle-right"></i>'
                }
            },
            dom: 'tp'
            });
            $('table.product-list').hide();
            $('table.product-list').show(500);
        }
        else{
            tableData += '<td class="text-center">No data avaialable</td></tr></tbody></table>'
            $(".table-container").html(tableData);
        }
    });
});

$("#print-btn").on("click", function(){
      var divToPrint=document.getElementById('sale-details');
      var newWin=window.open('','Print-Window');
      newWin.document.open();
      newWin.document.write('<link rel="stylesheet" href="<?php echo asset('public/vendor/bootstrap/css/bootstrap.min.css') ?>" type="text/css"><style type="text/css">@media  print {.modal-dialog { max-width: 1000px;} }</style><body onload="window.print()">'+divToPrint.innerHTML+'</body>');
      newWin.document.close();
      setTimeout(function(){newWin.close();},10);
});

$('select[name="customer_id"]').on('change', function() {
    var id = $(this).val();
    $.get('sale/getcustomergroup/' + id, function(data) {
        customer_group_rate = (data / 100);
    });
});

$('select[name="store_id"]').on('change', function() {
    var id = $(this).val();
    $.get('sale/getproduct/' + id, function(data) {
        ezpos_product_array = [];
        product_code = data[0];
        product_name = data[1];
        product_qty = data[2];
        product_type = data[3];
        $.each(product_code, function(index) {
            ezpos_product_array.push(product_code[index] + ' -- (' + product_name[index] + ')');
        });
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

$('#myTable').keyboard({
        accepted : function(event, keyboard, el) {
            checkQuantity(el.value, true);
      }
    });

//Change quantity
$("#myTable").on('input', '.qty', function() {
    rowindex = $(this).closest('tr').index();
    checkQuantity($(this).val(), true);
});

$("#myTable").on('click', '.plus', function() {
    rowindex = $(this).closest('tr').index();
    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
    checkQuantity(String(qty), true);
});

$("#myTable").on('click', '.minus', function() {
    rowindex = $(this).closest('tr').index();
    var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) - 1;
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
    checkQuantity(String(qty), true);
});

$("#myTable").on('click', '.qty', function() {
    rowindex = $(this).closest('tr').index();
});


$(document).on('click', '.product-img', function(){
    var customer_id = $('#customer_id').val();
    var store_id = $('select[name="store_id"]').val();
    if(!customer_id)
        alert('Please select Customer!');
    else if(!store_id)
        alert('Please select store!');
    else{
        var data = $(this).data('product');
        data = data.split(" ");
        pos = product_code.indexOf(data[0]);
        if(pos < 0)
            alert('Product is not avaialable in the selected store');
        else{
           $.ajax({
                type: 'GET',
                url: 'sale/ezpos_product_search',
                data: {
                    data: data[0]
                },
                success: function(data) {
                    var flag = 1;
                    $(".product-code").each(function(i) {
                        if ($(this).val() == data[1]) {
                            rowindex = i;
                            var qty = parseFloat($('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val()) + 1;
                            $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ') .qty').val(qty);
                            flag = 0;
                            checkQuantity(String(qty), true);
                        }
                    });
                    $("input[name='product_code_name']").val('');
                    if(flag){
                        addNewProduct(data);
                    }
                }
           }); 
        }
    }
});
//Delete product
$("table.order-list tbody").on("click", ".ibtnDel", function(event) {
    rowindex = $(this).closest('tr').index();
    product_price.splice(rowindex, 1);
    product_discount.splice(rowindex, 1);
    tax_rate.splice(rowindex, 1);
    tax_name.splice(rowindex, 1);
    tax_method.splice(rowindex, 1);
    $(this).closest("tr").remove();
    calculateTotal();
});

//Edit product
$("table.order-list").on("click", ".edit-product", function() {
    rowindex = $(this).closest('tr').index();
    edit();
});

//Update product
$('button[name="update_btn"]').on("click", function() {
    var edit_discount = $('input[name="edit_discount"]').val();
    var edit_qty = $('input[name="edit_qty"]').val();
    var edit_unit_price = $('input[name="edit_unit_price"]').val();

    if (parseFloat(edit_discount) > parseFloat(edit_unit_price)) {
        alert('Invalid Discount Input!');
        return;
    }

    var tax_rate_all = <?php echo json_encode($tax_rate_all) ?>;

    tax_rate[rowindex] = parseFloat(tax_rate_all[$('select[name="edit_tax_rate"]').val()]);
    tax_name[rowindex] = $('select[name="edit_tax_rate"] option:selected').text();

    product_discount[rowindex] = $('input[name="edit_discount"]').val();
    product_price[rowindex] = $('input[name="edit_unit_price"]').val();
    checkQuantity(edit_qty, false);
});

$('button[name="order_discount_btn"]').on("click", function() {
    calculateGrandTotal();
});

$('button[name="shipping_cost_btn"]').on("click", function() {
    calculateGrandTotal();
});

$('button[name="order_tax_btn"]').on("click", function() {
    calculateGrandTotal();
});

$("#draft-btn").on("click",function(){
    $('input[name="sale_status"]').val(2);
    $('input[name="paying_amount"]').val(0);
    $('input[name="paid_amount"]').val(0);
    $('input[name="paid_amount"]').prop('required',false);
    $('input[name="paying_amount"]').prop('required',false);
    var rownumber = $('table.order-list tbody tr:last').index();
    if (rownumber < 0) {
        alert("Please insert product to order table!")
    }
    else
        $('.payment-form').submit();
});

$("#gift-card-btn").on("click",function(){
    $('select[name="paid_by_id"]').val(2);
    $('input[name="paid_amount"]').val($("#grand-total").text());
    $('input[name="paying_amount"]').val($("#grand-total").text());
    giftCard();
});

$("#credit-card-btn").on("click",function(){
    $('select[name="paid_by_id"]').val(3);
    $('input[name="paid_amount"]').val($("#grand-total").text());
    $('input[name="paying_amount"]').val($("#grand-total").text());
    creditCard();
});

$("#cheque-btn").on("click",function(){
    $('select[name="paid_by_id"]').val(4);
    $('input[name="paid_amount"]').val($("#grand-total").text());
    $('input[name="paying_amount"]').val($("#grand-total").text());
    cheque();
});

$("#cash-btn").on("click",function(){
    $('select[name="paid_by_id"]').val(1);
    $('input[name="paid_amount"]').val($("#grand-total").text());
    $('input[name="paying_amount"]').val($("#grand-total").text());
    hide();
});

$("#paypal-btn").on("click",function(){
    $('select[name="paid_by_id"]').val(5);
    $('input[name="paid_amount"]').val($("#grand-total").text());
    $('input[name="paying_amount"]').val($("#grand-total").text());
    hide();
});

$("#deposit-btn").on("click",function(){
    $('select[name="paid_by_id"]').val(6);
    $('input[name="paid_amount"]').val($("#grand-total").text());
    $('input[name="paying_amount"]').val($("#grand-total").text());
    hide();
    deposits();
});

$('select[name="paid_by_id"]').on("change", function() {       
    var id = $(this).val();
    $(".payment-form").off("submit");
    if(id == 2) {
        giftCard();
    }
    else if (id == 3) {
        creditCard();
    } else if (id == 4) {
        cheque();
    } else {
        hide();
        if (id == 6){
            deposits();
        }
    }
});

$('#add-payment select[name="gift_card_id_select"]').on("change", function() {
    var balance = gift_card_amount[$(this).val()] - gift_card_expense[$(this).val()];
    $('#add-payment input[name="gift_card_id"]').val($(this).val());
    if($('input[name="paid_amount"]').val() > balance){
        alert('Amount exceeds card balance! Gift Card balance: '+ balance);
    }
});

$('input[name="paid_amount"]').on("input", function() {
    var change = $('input[name="paying_amount"]').val() - $(this).val();
    $("#change").text(parseFloat(change).toFixed(2));
    var id = $('select[name="paid_by_id"]').val();
    if(id == 2){
        var balance = gift_card_amount[$("#gift_card_id_select").val()] - gift_card_expense[$("#gift_card_id_select").val()];
        if($(this).val() > balance)
            alert('Amount exceeds card balance! Gift Card balance: '+ balance);
    }
    else if (id == 6){
        if($('input[name="paid_amount"]').val() > deposit[$('#customer_id').val()]){
            alert('Amount exceeds customer deposit! Customer deposit : '+ deposit[$('#customer_id').val()]);
        }
    }
});

$('input[name="paying_amount"]').on("input", function() {
    var change = $(this).val() - $('input[name="paid_amount"]').val();
    $("#change").text(parseFloat(change).toFixed(2));
});

$('.transaction-btn-plus').on("click", function() {
    $(this).addClass('d-none');
    $('.transaction-btn-close').removeClass('d-none');
});

$('.transaction-btn-close').on("click", function() {
    $(this).addClass('d-none');
    $('.transaction-btn-plus').removeClass('d-none');
});

function productSearch(data){
    $.ajax({
        type: 'GET',
        url: 'sale/ezpos_product_search',
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
                    checkQuantity(String(qty), true);
                }
            });
            $("input[name='product_code_name']").val('');
            if(flag){
                addNewProduct(data);
            }
        }
    });
}
function confirmDelete() {
    if (confirm("Are you sure want to delete?")) {
        return true;
    }
    return false;
}

function addNewProduct(data){
    var newRow = $("<tr>");
    var cols = '';
    cols += '<td class="col-sm-4 product-title"><strong>' + data[0] + '</strong> [' + data[1] + ']<button type="button" class="edit-product btn btn-link" data-toggle="modal" data-target="#editModal"> <i class="fa fa-edit"></i></button></td>';
    cols += '<td class="col-sm-2 product-price"></td>';
    cols += '<td class="col-sm-3"><div class="input-group"><span class="input-group-btn"><button type="button" class="btn btn-default minus"><span class="fa fa-minus"></span></button></span><input type="text" name="qty[]" class="form-control qty numkey input-number" value="1" step="any" required><span class="input-group-btn"><button type="button" class="btn btn-default plus"><span class="fa fa-plus"></span></button></span></div></td>';
    cols += '<td class="col-sm-2 sub-total"></td>';
    cols += '<td class="col-sm-1"><button type="button" class="ibtnDel btn btn-danger btn-sm">X</button></td>';
    cols += '<input type="hidden" class="product-code" name="product_code[]" value="' + data[1] + '"/>';
    cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[7] + '"/>';
    cols += '<input type="hidden" class="sale-unit" name="sale_unit[]" value="' + data[6] + '"/>';
    cols += '<input type="hidden" class="net_unit_price" name="net_unit_price[]" />';
    cols += '<input type="hidden" class="discount-value" name="discount[]" />';
    cols += '<input type="hidden" class="tax-rate" name="tax_rate[]" value="' + data[3] + '"/>';
    cols += '<input type="hidden" class="tax-value" name="tax[]" />';
    cols += '<input type="hidden" class="subtotal-value" name="subtotal[]" />';

    newRow.append(cols);
    if(keyboard_active==1){
        $("table.order-list tbody").append(newRow).find('.qty').keyboard({usePreview: false, layout: 'custom', display: { 'accept'  : '&#10004;', 'cancel'  : '&#10006;' }, customLayout : {
          'normal' : ['1 2 3', '4 5 6', '7 8 9','0 {dec} {bksp}','{clear} {cancel} {accept}']}, restrictInput : true, preventPaste : true, autoAccept : true, css: { container: 'center-block dropdown-menu', buttonDefault: 'btn btn-default', buttonHover: 'btn-primary',buttonAction: 'active', buttonDisabled: 'disabled'},});
    }
    else
        $("table.order-list tbody").append(newRow);

    product_price.push(parseFloat(data[2]) + parseFloat(data[2] * customer_group_rate));
    product_discount.push('0.00');
    tax_rate.push(parseFloat(data[3]));
    tax_name.push(data[4]);
    tax_method.push(data[5]);
    rowindex = newRow.index();
    checkQuantity(1, true);
}

function edit(){
    var row_product_name_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(1)').text();
    $('#modal_header').text(row_product_name_code);

    var qty = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val();
    $('input[name="edit_qty"]').val(qty);

    $('input[name="edit_discount"]').val(parseFloat(product_discount[rowindex]).toFixed(2));

    var tax_name_all = <?php echo json_encode($tax_name_all) ?>;
    pos = tax_name_all.indexOf(tax_name[rowindex]);
    $('select[name="edit_tax_rate"]').val(pos);

    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-code').val();
    pos = product_code.indexOf(row_product_code);
    row_product_price = product_price[rowindex];
    $('input[name="edit_unit_price"]').val(row_product_price.toFixed(2));
}

function checkQuantity(sale_qty, flag) {
    var row_product_code = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.product-code').val();
    pos = product_code.indexOf(row_product_code);
    if(product_type[pos] == 'standard'){
        if (parseFloat(sale_qty) > product_qty[pos]) {
            alert('Quantity exceeds stock quantity! ' + flag);
            if (flag) {
                sale_qty = sale_qty.substring(0, sale_qty.length - 1);
                $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(sale_qty);
                checkQuantity(sale_qty, true);
            } else {
                edit();
                return;
            }
        }
    }

    $('#editModal').modal('hide');
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.qty').val(sale_qty);
    calculateRowProductData(sale_qty);

}

function calculateRowProductData(quantity) {
    row_product_price = product_price[rowindex];

    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.discount-value').val((product_discount[rowindex] * quantity).toFixed(2));
    $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-rate').val(tax_rate[rowindex].toFixed(2));

    if (tax_method[rowindex] == 1) {
        var net_unit_price = row_product_price - product_discount[rowindex];
        var tax = net_unit_price * quantity * (tax_rate[rowindex] / 100);
        var sub_total = (net_unit_price * quantity) + tax;
        if(quantity)
            var sub_total_unit = sub_total / quantity;
        else
            var sub_total_unit = sub_total;

        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').val(net_unit_price.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text(sub_total_unit.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(4)').text(sub_total.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total.toFixed(2));
    } else {
        var sub_total_unit = row_product_price - product_discount[rowindex];
        var net_unit_price = (100 / (100 + tax_rate[rowindex])) * sub_total_unit;
        var tax = (sub_total_unit - net_unit_price) * quantity;
        var sub_total = sub_total_unit * quantity;

        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.net_unit_price').val(net_unit_price.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.tax-value').val(tax.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(2)').text(sub_total_unit.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('td:nth-child(4)').text(sub_total.toFixed(2));
        $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')').find('.subtotal-value').val(sub_total.toFixed(2));
    }
    calculateTotal();
}

function calculateTotal() {
    //Sum of quantity
    var total_qty = 0;
    $("table.order-list tbody .qty").each(function(index) {
        if ($(this).val() == '') {
            total_qty += 0;
        } else {
            total_qty += parseFloat($(this).val());
        }
    });
    $('input[name="total_qty"]').val(total_qty);

    //Sum of discount
    var total_discount = 0;
    $("table.order-list tbody .discount-value").each(function() {
        total_discount += parseFloat($(this).val());
    });

    $('input[name="total_discount"]').val(total_discount.toFixed(2));

    //Sum of tax
    var total_tax = 0;
    $(".tax-value").each(function() {
        total_tax += parseFloat($(this).val());
    });

    $('input[name="total_tax"]').val(total_tax.toFixed(2));

    //Sum of subtotal
    var total = 0;
    $(".sub-total").each(function() {
        total += parseFloat($(this).text());
    });
    $('input[name="total_price"]').val(total.toFixed(2));

    calculateGrandTotal();
}

function calculateGrandTotal() {

    var item = $('table.order-list tbody tr:last').index();

    var total_qty = parseFloat($('input[name="total_qty"]').val());
    var subtotal = parseFloat($('input[name="total_price"]').val());
    var order_tax = parseFloat($('select[name="order_tax_rate"]').val());
    var order_discount = parseFloat($('input[name="order_discount"]').val());
    if (!order_discount)
        order_discount = 0.00;
    $("#discount").text(order_discount.toFixed(2));

    var shipping_cost = parseFloat($('input[name="shipping_cost"]').val());
    if (!shipping_cost)
        shipping_cost = 0.00;

    item = ++item + '(' + total_qty + ')';
    order_tax = (subtotal - order_discount) * (order_tax / 100);
    var grand_total = (subtotal + order_tax + shipping_cost) - order_discount;

    $('#item').text(item);
    $('input[name="item"]').val($('table.order-list tbody tr:last').index() + 1);
    $('#subtotal').text(subtotal.toFixed(2));
    $('#tax').text(order_tax.toFixed(2));
    $('input[name="order_tax"]').val(order_tax.toFixed(2));
    $('#shipping-cost').text(shipping_cost.toFixed(2));
    $('#grand-total').text(grand_total.toFixed(2));
    $('input[name="grand_total"]').val(grand_total.toFixed(2));
}

function hide() {
    $(".card-element").hide();
    $(".card-errors").hide();
    $("#cheque").hide();
    $("#gift-card").hide();
}

function giftCard() {
    $("#gift-card").show();
    $.ajax({
        url: 'sale/get_gift_card',
        type: "GET",
        dataType: "json",
        success:function(data) {
            $('#add-payment select[name="gift_card_id_select"]').empty();
            $.each(data, function(index) {
                gift_card_amount[data[index]['id']] = data[index]['amount'];
                gift_card_expense[data[index]['id']] = data[index]['expense'];
                $('#add-payment select[name="gift_card_id_select"]').append('<option value="'+ data[index]['id'] +'">'+ data[index]['card_no'] +'</option>');
            });
            $('.selectpicker').selectpicker('refresh');
            $('.selectpicker').selectpicker();
        }
    });
    $(".card-element").hide();
    $(".card-errors").hide();
    $("#cheque").hide();
}

function cheque() {
    $("#cheque").show();
    $(".card-element").hide();
    $(".card-errors").hide();
    $("#gift-card").hide();
}

function creditCard() {
    $.getScript( "public/vendor/stripe/checkout.js" );
    $(".card-element").show();
    $(".card-errors").show();
    $("#cheque").hide();
    $("#gift-card").hide();
}

function deposits() {
    if($('input[name="paid_amount"]').val() > deposit[$('#customer_id').val()]){
        alert('Amount exceeds customer deposit! Customer deposit : '+ deposit[$('#customer_id').val()]);
    }
}

function cancel(rownumber) {
    while(rownumber >= 0) {
        product_price.pop();
        product_discount.pop();
        tax_rate.pop();
        tax_name.pop();
        tax_method.pop();
        $('table.order-list tbody tr:last').remove();
        rownumber--;
    }
    $('input[name="shipping_cost"]').val('');
    $('input[name="order_discount"]').val('');
    $('select[name="order_tax_rate"]').val(0);
    calculateTotal();
}

function confirmCancel() {
    if (confirm("Are you sure want to cancel?")){
        cancel($('table.order-list tbody tr:last').index());
    }
    return false;
}



$(document).on('submit', '.payment-form', function(e) {
    var rownumber = $('table.order-list tbody tr:last').index();
    if (rownumber < 0) {
        alert("Please insert product to order table!")
        e.preventDefault();
    }
});

$('#product-table').DataTable( {
    "order": [],
    'pageLength': product_row_number,
    'language': {
        'paginate': {
            'previous': '<i class="fa fa-angle-left"></i>',
            'next': '<i class="fa fa-angle-right"></i>'
        }
    },
    dom: 'tp'
});
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.top-head', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>