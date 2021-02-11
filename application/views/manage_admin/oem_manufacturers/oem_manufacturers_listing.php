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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <?php if(check_access_permissions_for_view($security_details, 'add_oem_manufacturer')) { ?>
                                                <div class="add-new-promotions">
                                                    <a class="site-btn" href="<?php echo base_url() . 'manage_admin/oem_manufacturers/add_oem_manufacturer' ?>">Add New</a>
                                                </div>
                                    <?php } ?>
                                    <div class="table-responsive hr-promotions">
                                        <div class="hr-displayResultsTable">
                                            <br>
                                            <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th class="col-xs-5">Name</th>
                                                            <th class="col-xs-5">Website</th>
                                                            <?php $function_names = array('manage_oem_manufacturer', 'add_oem_manufacturer'); ?>
                                                            <?php if(check_access_permissions_for_view($security_details, $function_names)) { ?>
                                                                    <th class="text-center col-xs-2" colspan="2">Actions</th>
                                                            <?php } ?>
                                                        </tr> 
                                                    </thead>
                                                    <tbody>
                                                        <?php if(sizeof($oem_brands) > 0) { ?>
                                                        <?php foreach ($oem_brands as $oem_brand) { ?>
                                                            <tr id='<?php echo $oem_brand['sid']; ?>'>
                                                                <td><?php echo $oem_brand['oem_brand_name']; ?></td>
                                                                <td><?php echo $oem_brand['brand_website']; ?></td>
                                                                <?php if(check_access_permissions_for_view($security_details, 'manage_oem_manufacturer')) { ?>
                                                                        <td>
                                                                            <a class="hr-edit-btn" href="<?php echo base_url() . 'manage_admin/oem_manufacturers/manage_oem_manufacturer/' . $oem_brand['sid']; ?>">
                                                                                Manage
                                                                            </a>
                                                                        </td>
                                                                <?php } ?>
                                                                <?php if(check_access_permissions_for_view($security_details, 'add_oem_manufacturer')) { ?>
                                                                        <td>
                                                                            <a class="hr-delete-btn" href="javascript:;" id='delete' name='delete' onclick='delete_oem_manufacturer(<?php echo $oem_brand['sid']; ?>);'>
                                                                                Delete
                                                                            </a>
                                                                        </td>
                                                                <?php } ?>
                                                            </tr>
                                                        <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan='3'>No OEM, Independent, Vendor found.</td>
                                                            </tr>
                                                        <?php } ?>
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
</div>
<?php if(check_access_permissions_for_view($security_details, 'add_oem_manufacturer')) { ?>
<script type="text/javascript">
    function delete_oem_manufacturer(oem_sid) {
        alertify.confirm("Delete OEM, Independent, Vendor", "Are you sure you want to delete this OEM, Independent, Vendor?",
                function () {
                    var myUrl = "<?= base_url('manage_admin/oem_manufacturers/oem_manufacturer_delete_ajax') ?>";

                    var myRequest;
                    myRequest = $.ajax({
                        url: myUrl,
                        type: 'post',
                        data: {oem_sid: oem_sid}
                    });

                    myRequest.done(function (response) {
                        if (response) {
                            $('#'+oem_sid).hide();
                            alertify.notify('OEM, Independent, Vendor deleted successfully.', 'success');
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
<?php }