<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-money"></i>Recurring payments</h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/recurring_payments'); ?>"><i class="fa fa-long-arrow-left"></i> Back to Payments</a>
                                    </div>
                                    <div class="form-main-wrp">
                                        <?php if(!empty($company_detail)) { ?>
                                            <h3 class="company-name">Company Name: <?php echo ucwords($company_detail[0]['CompanyName']); ?></h3>
                                        <?php } ?>
                                        <form action="" method="post">
                                            <input type="hidden" name="rec_sid" id="rec_sid" value="<?php echo (!empty($rec_payment)? $rec_payment['sid'] : ''); ?>" />

                                                        <?php if(!empty($rec_payment)) { ?>
                                                            <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_detail[0]['sid']; ?>" />
                                                        <?php } else { ?>
                                                            <div class="form-row">
                                                                <div class="row">

                                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                        <label>Company <span class="hr-required">*</span></label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                                            <div class="hr-select-dropdown">
                                                                                <select id="company_sid" name="company_sid" class="invoice-fields">
                                                                                    <option value="">Please Choose Company</option>
                                                                                    <?php foreach($companies as $company) { ?>
                                                                                        <?php
                                                                                            $is_default = false;
                                                                                            if($company['sid'] == $rec_payment['company_sid']){
                                                                                                $is_default = true;
                                                                                            }
                                                                                        ?>

                                                                                        <option <?php echo set_select('company_sid', $company['sid'], $is_default); ?> value="<?php echo $company['sid']; ?>"><?php echo $company['CompanyName']; ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                                <?php echo form_error('company_sid');?>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php } ?>

                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Packages <span class="hr-required">*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="invoice-fields autoheight">
                                                            <?php $last_key = end($packages); ?>
                                                        <?php foreach($packages as  $key => $package) { ?>
                                                            <?php
                                                                $is_default = false;
                                                                $selected = '';
                                                                if(!empty($rec_payment['items'])){
                                                                    foreach($rec_payment['items'] as $item){
                                                                        if($item == $package['sid']){
                                                                            $is_default = true;
                                                                        }
                                                                    }
                                                                }

                                                            ?>
                                                            <?php $remainder = ($key + 1) % 3; ?>
                                                            <?php if($key == 0) { ?>
                                                                <div class="row">
                                                            <?php } ?>
                                                            <div class="col-xs-12 col-md-4 col-sm-6 col-lg-4">
                                                                <label id="item-<?php echo $key?>" class="package_label">
                                                                    <div class="img-thumbnail text-center package-info-box">
                                                                        <div class="caption">
                                                                            <h2><strong>$ <?php echo number_format($package['price'], 2, '.', ','); ?></strong></h2>
                                                                            <p><?php echo $package['name']; ?></p>
                                                                        </div>
                                                                        <input <?php echo set_radio('items[]', $package['sid'], $is_default);?> type="radio" name="items[]" id="item-<?php echo $key?>"  class="select-package item"  value="<?php echo $package['sid']; ?>" data-price="<?php echo $package['price']; ?>"/>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                            <?php if ( $remainder == 0 && $key != 0) { ?>
                                                                </div>
                                                                <div class="row">
                                                            <?php } elseif ($key == count($packages) - 1) { ?>
                                                                </div>
                                                            <?php } elseif( $key == $last_key) { ?>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                        </div>
                                                        </div>
                                                    <?php echo form_error('items[]'); ?>
                                                    </div>
                                                </div>

                                            </div>




                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Addons <span class="hr-required">*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="invoice-fields autoheight">
                                                            <div class="row">
                                                                <?php foreach($addons as $key => $addon) { ?>
                                                                    <?php
                                                                    $is_default = false;
                                                                    $selected = '';
                                                                    if(!empty($rec_payment['items'])){
                                                                        foreach($rec_payment['items'] as $item){
                                                                            if($item == $addon['sid']){
                                                                                $is_default = true;
                                                                            }
                                                                        }
                                                                    }

                                                                    ?>

                                                                    <div class="col-xs-12 col-md-4 col-sm-6 col-lg-4">
                                                                        <label id="addon-<?php echo $key?>" class="package_label">
                                                                            <div class="img-thumbnail text-center package-info-box">
                                                                                <div class="caption">
                                                                                    <h2><strong>$ <?php echo number_format($addon['price'], 2, '.', ','); ?></strong></h2>
                                                                                    <p><?php echo $addon['name']; ?></p>
                                                                                </div>
                                                                                <input <?php echo set_checkbox('items[]', $addon['sid'], $is_default); ?> type="checkbox" name="items[]" id="addon-<?php echo $key?>" class="select-package item" value="<?php echo $addon['sid']; ?>" data-price="<?php echo $addon['price']; ?>"/>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <?php echo form_error('items[]'); ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Payment Day <span class="hr-required">*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="hr-select-dropdown">
                                                            <select id="payment_day" name="payment_day" class="invoice-fields">
                                                                <?php for($count = 1; $count <= 28; $count++) {?>
                                                                    <?php
                                                                        $is_default = false;
                                                                        $selected = '';
                                                                        if(!empty($rec_payment)){
                                                                            if($count == $rec_payment['payment_day']){
                                                                                $selected = $count;
                                                                                $is_default = true;
                                                                            }
                                                                        }
                                                                    ?>

                                                                    <option <?php echo set_select('payment_day', $selected, $is_default); ?> value="<?php echo $count?>" ><?php echo $count?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Sub Total Amount</label> 
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="amountfield">
                                                            <input type="text" name="sub_total_amount" id="sub_total_amount" class="invoice-fields" readonly="readonly" value="<?php echo set_value('sub_total_amount'); ?>" />
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>

                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>No. Of Rooftops <span class="hr-required">*</span></label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <?php
                                                        $selected = '';
                                                        if(!empty($rec_payment)){
                                                            $selected = $rec_payment['number_of_rooftops'];
                                                                }else{
                                                            $selected = 1;
                                                        }
                                                        ?>
                                                        <input type="text" name="number_of_rooftops" id="number_of_rooftops" class="invoice-fields" value="<?php echo set_value('number_of_rooftops', $selected); ?>" />
                                                        <?php echo form_error('number_of_rooftops'); ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Rooftops Total</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="amountfield">
                                                            <input id="rooftops_total" name="rooftops_total" type="text" class="invoice-fields" readonly="" value="<?php echo set_value('rooftops_total'); ?>" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Discount Amount <span class="hr-required">*</span></label> 
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="amountfield">
                                                            <?php
                                                            $selected = '';
                                                            if(!empty($rec_payment)){
                                                                $selected = $rec_payment['discount_amount'];
                                                            }else{
                                                                $selected = 0;
                                                            }
                                                            ?>

                                                            <input type="text" name="discount_amount" id="discount_amount" class="invoice-fields" value="<?php echo set_value('discount_amount', $selected); ?>" >
                                                            <?php echo form_error('discount_amount'); ?>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Total After Discount</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <div class="amountfield">
                                                            <input id="total_after_discount" name="total_after_discount" type="text" class="invoice-fields" readonly="" value="<?php echo set_value('total_after_discount'); ?>" >
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>

                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                        <label>Status</label>
                                                    </div>
                                                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                        <?php
                                                        $selected = '';
                                                        $active = false;
                                                        $in_active = false;
                                                        if(!empty($rec_payment)){
                                                            $selected = $rec_payment['status'];
                                                            if($selected == 'active'){
                                                                $active = true;
                                                                $in_active = false;
                                                            }else{
                                                                $active = false;
                                                                $in_active = true;
                                                            }
                                                        }
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="invoice-field autoheight">
                                                                    <input id="status_active" name="status" type="radio" class="" value="active" <?php echo set_radio('status', $selected, $active); ?> >&nbsp;<label for="status_active">Active</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                                <div class="invoice-field autoheight">
                                                                    <input id="status_inactive" name="status" type="radio" class="" value="in-active" <?php echo set_radio('status', $selected, $in_active); ?> >&nbsp;<label for="status_inactive">In Active</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <button class="site-btn lineheight pull-right" type="submit" style="margin-top:15px;">
                                                        <?php if(empty($rec_payment)) { ?>
                                                            Create
                                                        <?php } else { ?>
                                                            Update
                                                        <?php } ?>
                                                        </button>
                                                    </div>
                                                </div> 
                                            </div>
                                            
                                        </form>
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
<script>

    $(document).ready(function () {
        fCalculateTotal();
        $('.item').each(function () {
            $(this).on('click', function () {
                fCalculateTotal();
            });
        });


        $('#discount_amount').on('keyup', function () {
            fUpdateTotalAfterDiscount();
        });


        $('#number_of_rooftops').on('keyup', function () {
            fUpdateRooftopsTotal();
        });


        $('.select-package').click(function () {
            $('.select-package:not(:checked)').parent().removeClass("selected-package");
            $('.select-package:checked').parent().addClass("selected-package");
        });


        $('.select-package:not(:checked)').parent().removeClass("selected-package");
        $('.select-package:checked').parent().addClass("selected-package");
    });


    function fUpdateRooftopsTotal(){
        var rooftops = parseFloat($('#number_of_rooftops').val());
        var sub_total = parseFloat($('#sub_total_amount').val());
        var rooftops_total = 0;

        if(rooftops > 0){
            rooftops_total = rooftops * sub_total;
        }

        $('#rooftops_total').val(rooftops_total);

        fUpdateTotalAfterDiscount();
    }

    function fUpdateTotalAfterDiscount(){
        var discount = parseFloat($('#discount_amount').val());
        var sub_total = parseFloat($('#rooftops_total').val());


        if(!isNaN(discount) || discount == undefined || discount == null) {
            if (sub_total < discount) {
                $('#discount_amount').val(sub_total);
            }

            discount = parseFloat($('#discount_amount').val());

            var balance = sub_total - discount;

            $('#total_after_discount').val(balance);
        }else{
            $('#discount_amount').val(0);
            $('#total_after_discount').val(sub_total);
        }
    }

    function fCalculateTotal(){
        var items = $('.item:checked');

        var total =  0;
        for(var iCount = 0; iCount < items.length; iCount++){
            total += parseFloat($(items[iCount]).attr('data-price'));
        }

        $('#sub_total_amount').val(total);

        //update discount value
        fUpdateTotalAfterDiscount();

        //Update Rooftops Total
        fUpdateRooftopsTotal();
    }
</script>