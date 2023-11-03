<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!--  -->
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <!-- Left column -->
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!-- Main content area -->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding jsExpandContent">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <!-- Title -->
                            <div class="heading-title page-title">
                                <h1 class="page-title">
                                    <i class="fa fa-clipboard"></i>
                                    Plans Management (<?php echo $benefitName; ?>) [<?php echo $categoryName; ?>]
                                </h1>
                                <div class="text-right">
                                    <a href="<?php echo base_url('sa/benefits'); ?>" class="btn black-btn csF16 pull-right">
                                        <i class="fa fa-long-arrow-left csF16" aria-hidden="true"></i>
                                        &nbsp;Back to Benefits
                                    </a>
                                </div>
                            </div>

                            <br />
                            <br />
                            <br />

                            <!--  -->
                            <div id="jsBenefitPlansBox"></div>

                            <?php $this->load->view('v1/loader', ['id' => 'jsPageLoader']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var benefitId = '<?php echo $benefitId; ?>';
    var categoryId = '<?php echo $categoryId; ?>';
</script>