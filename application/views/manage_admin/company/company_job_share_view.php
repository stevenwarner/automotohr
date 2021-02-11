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
                                        <span class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></span>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/companies/manage_company/'.$company_sid); ?>"><i class="fa fa-long-arrow-left"></i> Back</a>
                                    </div>
                                    <div class="add-new-company">
                                        <?php if(!empty($corporate_companies)) { ?>
                                            <div class="row">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="heading-title">
                                                        <span class="page-title">Corporate Company</span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th class="text-left col-xs-4">Corporate Company Name</th>
                                                                <th class="text-left col-xs-4">Corporate Company URL</th>
                                                                <th class="text-center col-xs-2">Has Access</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach($corporate_companies as $corporate_company) {
                                                                if ($corporate_company['sid'] != $company_sid) { ?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($corporate_company['CompanyName']); ?></td>
                                                                        <td><?php echo $corporate_company['sub_domain']; ?></td>
                                                                        <td class="exec-admin-access text-center">
                                                                            <label id="lbl_is_registers_in_ahr"
                                                                                   class="control control--checkbox">
                                                                                <?php $corporate_company_sid = $corporate_company['sid']; ?>
                                                                                <input <?php echo set_checkbox('has_corporate_company_access', 1, in_array($corporate_company['sid'], $configured_companies)); ?>
                                                                                    class="has_access"
                                                                                    id="has_access_<?php echo $corporate_company_sid; ?>"
                                                                                    data-company-sid="<?php echo $corporate_company_sid; ?>"
                                                                                    data-admin-company-sid="<?php echo $company_sid; ?>"
                                                                                    name="has_access" value="1"
                                                                                    type="checkbox">

                                                                                <div class="control__indicator"></div>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                            }?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if(!empty($standard_companies)) { ?>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <div class="heading-title">
                                                    <h1 class="page-title">Regular Company</h1>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="table-responsive">
                                                    <table class="table table-stripped table-hover table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="text-left col-xs-4">Corporate Company Name</th>
                                                            <th class="text-left col-xs-4">Corporate Company URL</th>
                                                            <th class="text-center col-xs-2">Has Access</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($standard_companies as $company) {
                                                            if ($company['sid'] != $company_sid) { ?>
                                                            <tr>
                                                                <td><?php echo ucwords($company['CompanyName']); ?></td>
                                                                <td><?php echo $company['sub_domain']; ?></td>
                                                                <td class="exec-admin-access text-center">

                                                                    <label id="lbl_is_registers_in_ahr" class="control control--checkbox">
                                                                        <?php $standard_company_sid = $company['sid']; ?>
                                                                        <input <?php echo set_checkbox('is_registered_in_ahr', 1, in_array($company['sid'], $configured_companies)); ?>
                                                                            class="has_access"
                                                                            id="has_access_<?php echo $corporate_company_sid; ?>"
                                                                            data-company-sid="<?php echo $standard_company_sid; ?>"
                                                                            data-admin-company-sid="<?php echo $company_sid; ?>"
                                                                            name="has_access" value="1" type="checkbox">

                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </td>
                                                            </tr>
                                                            <?php }
                                                            } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
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
<script type="text/javascript">

    $(document).ready(function () {


        $('#corporate_password').on('keyup', function () {
            var password_val = $(this).val();

            if(password_val != ''){

            } else {

            }
        });

        $('#access_corp_web_yes').on('click', function () {
            console.log($(this).val());
            if($(this).prop('checked')) {
                $('#corporate_username').prop('disabled', false);
                $('#corporate_password').prop('disabled', false);
            }

        });

        $('#access_corp_web_no').on('click', function () {
            console.log($(this).val());
            if($(this).prop('checked')) {
                $('#corporate_username').attr('disabled', 'disabled');
                $('#corporate_password').attr('disabled', 'disabled');
            }
        });


//        $('.has_access').each(function () {
            $('.has_access').on('click', function () {
                var is_checked = $(this).prop('checked');
                var company_sid = $(this).attr('data-company-sid');
                var admin_company_sid = $(this).attr('data-admin-company-sid');
                //console.log(company_sid);
                var myUrl = '<?php echo current_url(); ?>';
                var myRequest = $.ajax({
                    url: myUrl,
                    type: 'POST',
                    data: {
                        is_ajax_request: 1,
                        'company_sid': company_sid,
                        'admin_company_sid': admin_company_sid,
                        'perform_action': (is_checked == true ? 'enable_company_access' : 'disable_company_access')
                    }
                });

                myRequest.done(function (response) {
                    if (response == 'success') {
                        alertify.success('Success: Re-assigning Job Access Rights Successfully Updated!');
                    } else {
                        alertify.success('Error: something went wrong!');
                    }
                });
            });
//        });

        $('input[type=radio]:checked').trigger('click');
    });


    function remove_company(sid, logged_in_sid) {
        alertify.confirm("Remove Admin Company", "Are you sure you want to remove this company from administrator?",
            function () {
                var myUrl = "<?= base_url('manage_admin/companies/executive_admin_company_remove_ajax') ?>";
                var myRequest;
                myRequest = $.ajax({
                    url: myUrl,
                    type: 'post',
                    data: {sid: sid, logged_in_sid: logged_in_sid}
                });

                myRequest.done(function (response) {
                    if (response) {
                        $('#' + sid).hide();
                        alertify.notify('Administrator company removed successfully.', 'success');
                    } else {
                        alertify.error('An unknown error occurred. Please try again.');
                    }
                });
            },
            function () {
                alertify.error('Cancelled');
            });
    }
</script>