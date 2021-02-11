<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title"><i class="fa fa-cog"></i>Manage Packages</h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>

                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-10 col-sm-12 col-md-12 col-lg-12">

                                                <div class="heading-title page-title">
                                                    <h1 class="page-title">Company Account Package</h1>
                                                </div>
                                                <div>
                                                    <?php echo form_error('package'); ?>
                                                    <?php //echo form_error('number_of_rooftops'); ?>
                                                </div>
                                                <form id="form_process_package" name="form_process_package" enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/manage_packages'); ?>">
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                    <input type="hidden" id="created_by" name="created_by" value="<?php echo $current_admin; ?>" />
                                                    <div class="row">
                                                        <?php foreach($packages as $package) { ?>
                                                            <?php if($package['name'] != 'Deluxe Theme' && $package['name'] != 'Development Fee' ) { ?>
                                                                <div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
                                                                    <label class="package_label" for="package_<?php echo $package['sid']; ?>">
                                                                        <div class="img-thumbnail text-center package-info-box">
                                                                            <figure>
                                                                                <!-- <i class="fa fa-codepen" style="font-size: 150px"></i> -->
                                                                                <img src="<?= base_url() ?>assets/manage_admin/images/img-packages.png" alt="Image Packages" />
                                                                            </figure>
                                                                            <div class="caption">
                                                                                <h2><strong>$ <?php echo number_format($package['price'] , 2, '.', ',') ; ?></strong></h2>
                                                                                <p><?php echo $package['name']; ?></p>

                                                                            </div>
                                                                            <input class="select-package" type="radio" data-price="<?= $package['price']; ?>" data-cost="<?= $package['cost_price']; ?>" id="package_<?php echo $package['sid']; ?>" name="package" value="<?php echo $package['sid']; ?>" />
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="edit-email-template">
                                                            <div class="edit-template-from-main">
                                                                <ul>
                                                                    <li>
                                                                        <label for="CompanyName">Number of Rooftops <span class="hr-required">*</span></label>                                                    <div class="hr-fields-wrap">
                                                                            <input type="number" class="hr-form-fileds" value="1" name="number_of_rooftops" id="number_of_rooftops"  />
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div id="custom-fields-div" style="display: none;">

                                                        <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                                            <div class="edit-email-template">
                                                                <div class="edit-template-from-main">
                                                                    <ul>
                                                                        <li>
                                                                            <label for="custom_price">Custom Price<span class="hr-required">*</span></label>                                                    <div class="hr-fields-wrap">
                                                                                <input type="number" class="hr-form-fileds" value="" name="custom_price" id="custom_price"  />
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-xs-6 col-sm-12 col-md-6 col-lg-6">
                                                            <div class="edit-email-template">
                                                                <div class="edit-template-from-main">
                                                                    <ul>
                                                                        <li>
                                                                            <label for="cost_price">Cost Price<span class="hr-required">*</span></label>                                                    <div class="hr-fields-wrap">
                                                                                <input type="number" class="hr-form-fileds" value="" name="cost_price" id="cost_price"  />
                                                                            </div>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <!--<input type="hidden" class="hr-form-fileds" value="1" name="number_of_rooftops" id="number_of_rooftops"  />-->
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 text-center hr-btn-panel">
                                                        <button type="button" class="search-btn" onclick="fProcessInvoice();">Process Invoice</button>
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
    </div>
</div>

<script>

    function fProcessInvoice() {
        var value = $('.select-package:checked').val();
        var price = $('.select-package:checked').attr('data-price');
        var cost = $('.select-package:checked').attr('data-cost');

        if(value != null){
            alertify.confirm(
                'Are you Sure?',
                'Are you Sure you want to proceed? <br /> This action will automatically generate an invoice against this company!',
                function () {
                    $('#form_process_package').submit();
                }, function () {
                    //Cancel
                });
        } else{
            alertify.error('Please Select Package');
            return false;
        }

//        if(value==24){
//             $('#custom-fields-div').show();
            var custom_price = $('#custom_price').val();
            var cost_price = $('#cost_price').val();
            
            if(custom_price == '' || cost_price == '') {
                alertify.error('Please Provide Custom Price And Cost Price');
                return false;
//            }
        }
    }
    
    $(document).ready(function () {
        $('.select-package').click(function () {
            $('.select-package:not(:checked)').parent().removeClass("selected-package");
            $('.select-package:checked').parent().addClass("selected-package");
            var value = $(this).val();
            var price = $(this).attr('data-price');
            var cost = $(this).attr('data-cost');
//            if(value==24){
                $('#custom-fields-div').show();
                $('#custom_price').val(price);
                $('#cost_price').val(cost);
//            } else{
//                $('#custom-fields-div').hide();
//                $('#custom_price').val('');
//                $('#cost_price').val('');
//            }
        });    
    });
</script>