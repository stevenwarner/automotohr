<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-list"></i>Apply Discount To Admin Invoice # <?php echo $invoice['invoice_number'];?></h1>
                                        <a href="<?php echo base_url('manage_admin/invoice/list_admin_invoices'); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Invoices</a>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $invoice['company_sid']);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>

                                    <div class="hr-edit-invoice">
                                        <form enctype="multipart/form-data" method="post" action="<?php echo base_url('manage_admin/invoice/apply_discount_admin_invoice') . '/' . $invoice['sid']; ?>">
                                            <input type="hidden" id="commission_invoice_sid" name="commission_invoice_sid" value="<?php echo $invoice['commission_invoice_sid']; ?>" />

                                            <ul class="invoice-form">
                                                <li id="">
                                                    <label>Invoice Amount:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-field">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">$</span>
                                                                <input type="text" readonly="readonly" value="<?php echo $invoice['value']; ?>" name="value" id="value" class="invoice-fields invoice-discount" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li></li>

                                                <li id="">
                                                    <label>Discount Amount:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-field">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">$</span>
                                                                <input type="text" value="<?php echo set_value('discount_amount', $invoice['discount_amount']); ?>" name="discount_amount" id="discount_amount" class="invoice-fields invoice-discount" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li id="">
                                                    <label>Discount:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-field">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">%</span>
                                                                <input type="text" value="<?php echo set_value('discount_percentage', $invoice['discount_percentage']); ?>" name="discount_percentage" id="discount_percentage" class="invoice-fields invoice-discount" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li id="">
                                                    <label>Total Amount:</label>
                                                    <div class="invoice-field-wrap">
                                                        <div class="hr-invoice-field">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">$</span>
                                                                <input type="text" readonly="readonly" value="<?php echo set_value('total_after_discount', $invoice['total_after_discount']); ?>" name="total_after_discount" id="total_after_discount" class="invoice-fields invoice-discount" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <input type="hidden" value="<?php echo $invoice['sid']; ?>" name="invoice_sid" id="invoice_sid" />
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                <input type="submit" value="Apply Discount" class="search-btn" />
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
    $('#discount_percentage').on('change, focusout, keyup', function () {
        var invoice_value =  parseFloat($('#value').val());
        var discount_percentage = parseFloat($(this).val());


        if(discount_percentage > 100){
            $(this).val(100);
        }

        discount_percentage = parseFloat($(this).val());


        var discount_amount = invoice_value * ( discount_percentage / 100 );

        discount_amount = Number((discount_amount).toFixed(0))

        var total_after_discount = invoice_value - discount_amount;

        if(isNaN(discount_amount)){
            $('#discount_amount').val(0);
            $('#total_after_discount').val(0);
            console.log(discount_amount);
        }else{
            $('#discount_amount').val(discount_amount);
            $('#total_after_discount').val(total_after_discount);
            console.log(discount_amount);
        }
    });

    $('#discount_amount').on('change, focusout, keyup', function () {
        var invoice_value =  parseFloat($('#value').val());

        var discount_amount = parseFloat($(this).val());

        if(discount_amount > invoice_value){
            $(this).val(invoice_value);
        }

        discount_amount = parseFloat($(this).val());

        var discount_percentage = ( discount_amount / invoice_value ) * 100;

        var total_after_discount = invoice_value - discount_amount;

        discount_percentage = Number((discount_percentage).toFixed(2));

        if(isNaN(discount_amount)){
            $('#discount_percentage').val(0);
            $('#total_after_discount').val(0);
            console.log(discount_amount);
        }else{
            $('#discount_percentage').val(discount_percentage);
            $('#total_after_discount').val(total_after_discount);
            console.log(discount_amount);
        }
    });

    function fValidateDiscount(){

    }

</script>