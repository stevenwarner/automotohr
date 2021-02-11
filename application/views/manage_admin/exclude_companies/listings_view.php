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
                                        <h1 class="page-title"><i class="fa fa-file-code-o"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="add-new-promotions">
                                        <p>You can exclude all Demo / Test companies that are created for testing purpose. It can include Demo / Test companies by Dev team or even by AHR Administrator.
                                        We have specially designed this module for giving you full control on your revenue related reports so that test orders of these demo companies are excluded from AHR Revenue Reports</p>
                                        <?php if(check_access_permissions_for_view($security_details, 'add_exclude_company')){ ?>
                                            <a class="site-btn" href="<?php echo site_url('manage_admin/exclude_companies/add_exclude_company'); ?>">Add Company</a>
                                        <?php } ?>
                                    </div>
                                    <div class="hr-promotions table-responsive">
                                        <form method="post">
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th>Company Name</th>
                                                        <?php if(check_access_permissions_for_view($security_details, 'add_exclude_company')){ ?>
                                                            <th class="text-center">Actions</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php   if(count($excluded_companies)>0){
                                                            foreach ($excluded_companies as $key => $value) { ?>
                                                                <tr id="parent_<?= $value['sid'] ?>">
                                                                    <td><?php echo $value['company_name']; ?></td>
                                                                    <?php if(check_access_permissions_for_view($security_details, 'add_exclude_company')){ ?>
                                                                        <td class="text-center">
<!--                                                                            <input class="hr-delete-btn" type="button"  value="Delete" id="<?= $value['sid'] ?>" onclick="return todo('delete', this.id)" name="button">-->
                                                                            <a class="hr-delete-btn" href="javascript:;" id='remove_excluded_company' name='remove_excluded_company' onclick='remove_excluded_company(<?php echo $value['sid']; ?>);'>Remove</a>
                                                                        </td>
                                                                    <?php } ?>
                                                                </tr>
                                                <?php       } ?>
                                                <?php   } else { ?>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        No Company found!
                                                                    </td>
                                                                </tr>    
                                                <?php   } ?>
                                                </tbody>
                                            </table>
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
//    function todo(action, id) {
//        url = "<?= base_url() ?>manage_admin/promotions/promotion_task";
//        alertify.confirm('Confirmation', "Are you sure you want to " + action + " this Promotion?",
//                function () {
//                    $.post(url, {action: action, sid: id})
//                    .done(function (data) {
//
//                        if (action == "delete") {
//                            alertify.success('Selected promotion have been ' + action + 'd.');
//                            $("#parent_" + id).remove();
//                        }
//                        else {
//                            location.reload();
//                        }
//                    });
//                },
//                function () {
//                    alertify.error('Canceled');
//                });
//    }

function remove_excluded_company(sid)
    { 
        alertify.confirm("Remove Excluded Company", "Are you sure you want to remove this excluded company?",
                function () {
                    var myUrl = "<?= base_url('manage_admin/exclude_companies/remove_excluded_company_ajax') ?>";

                    var myRequest;
                    myRequest = $.ajax({
                        url: myUrl,
                        type: 'post',
                        data: {sid: sid}
                    });
                    
                    myRequest.done(function (response) {
                        if (response)
                        {
                            $('#parent_' + sid).hide();
                            alertify.notify('Excluded company removed successfully.', 'success');
                        }
                        else
                        {
                            alertify.error('An unknown error occurred. Please try again.');
                        }
                    });
                },
                function () {
                    alertify.error('Cancelled');
                });
    }
</script>