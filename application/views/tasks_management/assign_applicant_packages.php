<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>  
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php echo $title; ?></span>
                    </div>
                    <div class="multistep-progress-form">
                        <form class="msform" action="" method="POST" enctype="multipart/form-data">
                            <?php foreach ($primary_applicants_data as $applicants_data) { ?>
                                   <div class="col-xs-4 col-md-4 col-sm-4 col-lg-4">
                                        <label class="package_label" for="package_<?php echo $applicants_data['sid']; ?>">
                                            <div class="img-thumbnail text-center package-info-box">
                                                <figure>
                                                    <!-- <i class="fa fa-codepen" style="font-size: 150px"></i> -->
                                                    <img src="<?= base_url() ?>assets/manage_admin/images/img-packages.png" alt="Image Packages" />
                                                </figure>
                                                <div class="caption">
                                                    <h2><strong>$ <?php echo number_format($package['price'] , 2, '.', ',') ; ?></strong></h2>
                                                    <p><?php echo $applicants_data['first_name']; ?></p>
                                                </div>
                                                <input class="select-package" type="radio" id="package_<?php echo $package['sid']; ?>" name="package" value="<?php echo $package['sid']; ?>" />
                                            </div>
                                        </label>
                                    </div> 
                            <?php } ?>
                        </form>
                    </div>
                </div> 
            </div>          
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
<script>
    $(document).ready(function () {
        $(".chosen-select").chosen();
    });
</script>