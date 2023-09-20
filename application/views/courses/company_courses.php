<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view($left_navigation); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="dashboard-conetnt-wrp">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>

                    <!--  -->
                    <div style="position: relative;">
                        <!-- Loader -->
                        <?php $this->load->view('loader_new', ['id' => 'jsPageLoader']); ?>
                        <!-- Main area -->
                        <div id="jsDefaultCoursesView"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .csRP {
        padding: 0px;
    }

    .csQuestionBox:nth-child(even) {
        background-color: #f1f1f1 !important;
    }

    .csQuestionBox {
        display: block;
        padding: 20px 10px;
    }

    .text-white {
        color: #fff;
    }

    .csQuestionBox:nth-child(odd) {
        background-color: #fff;
    }

    h3,h4 {
        color: #fff;
    }

    strong {
        color: #000;
    }

    thead {
        background: #000;
    }

    .success-block {
        background: #28a745 !important;
    }

    .error-block {
        background: #dc3545 !important;
    }

    .post-block {
        background: #007bff !important;
    }
</style> 


<script>
    var apiURL = "<?php echo $apiURL; ?>";
    var companySid = "<?php echo $company_sid; ?>";
    var apiAccessToken = "<?php echo $apiAccessToken; ?>";
</script>