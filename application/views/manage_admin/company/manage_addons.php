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
                                        <h1 class="page-title"><i class="fa fa-cog"></i>Manage Account Addons</h1>
                                        <a href="<?php echo base_url('manage_admin/companies/manage_company/' . $company_sid);?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Manage Company</a>
                                    </div>

                                    <div class="add-new-company">
                                        <div class="row">
                                            <div class="col-xs-10 col-sm-12 col-md-12 col-lg-12">
                                                <div class="heading-title page-title">
                                                    <h1 class="page-title">Addons</h1>
                                                </div>

                                                <form id="form_process_package" name="form_process_package" enctype="multipart/form-data" method="post" action="<?php base_url('manage_admin/companies/manage_addons'); ?>">
                                                    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                                    <input type="hidden" id="created_by" name="created_by" value="<?php echo $current_admin; ?>" />
                                                    <!--<input type="hidden" id="number_of_rooftops" name="number_of_rooftops" value="1" />-->
                                                    <div class="row">
                                                        <?php foreach($packages as $package) { ?>

                                                                <div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
                                                                    <label class="package_label" for="package_<?php echo $package['sid']; ?>">
                                                                        <div class="img-thumbnail text-center package-info-box">
                                                                            <figure>
                                                                                <!-- <i class="fa fa-codepen" style="font-size: 150px"></i> -->
                                                                                <img src="<?= base_url() ?>assets/manage_admin/images/img-addon.png" alt="Image Packages" />
                                                                            </figure>
                                                                            <div class="caption">
                                                                                <h2><strong>$ <?php echo number_format($package['price'] , 2, '.', ','); ?></strong></h2>
                                                                                <p><?php echo $package['name']; ?></p>

                                                                            </div>
                                                                            <input class="select-package" type="checkbox" id="package_<?php echo $package['sid']; ?>" name="packages[]" value="<?php echo $package['sid']; ?>" />
                                                                        </div>
                                                                    </label>
                                                                </div>

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
        alertify.confirm(
            'Are you Sure?',
            'Are you Sure you want to proceed? <br /> This action will automatically generate an invoice against this company!',
            function () {
                $('#form_process_package').submit();
            }, function () {
                //Cancel
            });
    }

    $(document).ready(function () {
        $('.select-package').click(function () {
            $('.select-package:not(:checked)').parent().removeClass("selected-package");
            $('.select-package:checked').parent().addClass("selected-package");
        });
    });
</script>