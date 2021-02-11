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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a class="black-btn pull-right" href="<?php echo base_url('manage_admin/oem_manufacturers'); ?>"><i class="fa fa-long-arrow-left"></i> Back to OEM, Independent, Vendor</a>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <h1 class="page-title">OEM, Independent, Vendor Information</h1>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <article class="information-box">
                                                    <header class="hr-box-header">OEM, Independent, Vendor Details <a href="<?php echo base_url('manage_admin/oem_manufacturers/edit_oem_manufacturer') . '/' . $oem_brand['sid']; ?>" class="site-btn pull-right">Edit</a></header>
                                                    <div class="table-outer">
                                                        <div class="info-row">
                                                            <ul>
                                                                <li>
                                                                    <label>Name</label>
                                                                    <div class="text">
                                                                        <?php echo $oem_brand['oem_brand_name']; ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <label>Website</label>
                                                                    <div class="text">
                                                                        <?php echo $oem_brand['brand_website']; ?>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <header class="hr-box-header hr-box-footer"></header>
                                                </article>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="add-new-company">
                                        <div class="heading-title">
                                            <h1 class="page-title">OEM, Independent, Vendor Companies Information</h1>
                                            <a href="<?php echo base_url('manage_admin/oem_manufacturers/add_manufacturer_company') . '/' . $oem_brand['sid']; ?>" class="site-btn pull-right">Add Company</a>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                <!-- ******************************** -->
                                                <div class="table-responsive hr-promotions">
                                                    <div class="hr-displayResultsTable">
                                                        <br>
                                                        <form name="multiple_actions" id="multiple_actions_company" method="POST">
                                                            <table class="table table-bordered table-hover table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Company Name</th>
                                                                        <th class="text-center">Action</th>
                                                                    </tr> 
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (sizeof($brand_companies) == 0) { ?>
                                                                        <tr><td colspan='3'>No companies found.</td></tr>
                                                                    <?php } else { ?>
                                                                        <?php foreach ($brand_companies as $brand_company) { ?>
                                                                            <tr id='<?php echo $brand_company['sid']; ?>'>
                                                                                <td><?php echo $brand_company['company_name']; ?></td>
                                                                                <td class="text-center">
                                                                                    <a class="hr-delete-btn" href="javascript:;" id='remove_brand_company' name='remove_brand_company' onclick='remove_brand_company(<?php echo $brand_company['sid']; ?>);'>Remove</a>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- ******************************** -->
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
<script type="text/javascript">
    function remove_brand_company(sid)
    {
        alertify.confirm("Remove OEM, Independent, Vendor Company", "Are you sure you want to remove this company from OEM, Independent, Vendor?",
                function () {
                    var myUrl = "<?= base_url('manage_admin/oem_manufacturers/remove_oem_company_ajax') ?>";

                    var myRequest;
                    myRequest = $.ajax({
                        url: myUrl,
                        type: 'post',
                        data: {sid: sid}
                    });

                    myRequest.done(function (response) {
                        if (response)
                        {
                            $('#' + sid).hide();
                            alertify.notify('OEM, Independent, Vendor company removed successfully.', 'success');
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