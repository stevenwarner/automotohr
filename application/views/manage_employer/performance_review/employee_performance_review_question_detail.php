
<style>
<?php $this->load->view('manage_employer/PerformanceReview/main.css'); ?>
</style>
<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow">
                                    <a class="dashboard-link-btn" href="<?php echo $top_bar_link_url; ?>"><i class="fa fa-chevron-left"></i><?php echo $top_bar_link_title; ?></a>
                                    <?php echo $title; ?></span>
                            </div>
                        </div>
                         
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row hidden-print">
                                <div class="col-sm-8 col-xs-12">
                                    <p class="btn"><strong><?= $review_title; ?></strong></p>
                                </div>
                                <div class="col-sm-4 col-xs-12">
                                    <span class="pull-right">
                                        <a href="<?= base_url('performance/pd/print/' . ($review_sid . "/" . $conductor_sid) . "/" . $employee_sid . ''); ?>" class="btn btn-success ">Print</a>
                                        <a href="<?= base_url('performance/pd/download/' . ($review_sid . "/" . $conductor_sid) . "/" . $employee_sid . ''); ?>" class="btn btn-success ">Download</a>
                                    </span>
                                </div>
                            </div>
                            <hr />
                            <div class="A4" id="js-export-pdf">
                                <?php $answers = []; ?>
                                <?php foreach ($review['questions'] as $key => $question) { ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="cs-question-row">
                                                <h5>
                                                    <strong>
                                                        Q <?php echo $key + 1; ?>: <?php echo stripslashes($question['question']); ?>
                                                    </strong>
                                                </h5>
                                                <h5>
                                                    <?php echo stripslashes($question['description']); ?>
                                                </h5>
                                                <?php echo getQuestion($question, $answers); ?>
                                                <p style="margin-top: 10px;"><?=$conductor_name;?> <i class="fa fa-long-arrow-right"></i> <?=$employee_name;?></p>
                                                <div class="clearfix"></div>
                                                <hr />
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>    
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<script>
    $(window).load(function(){
        $('.cs-text-answer').attr('disabled', true);
    });
</script>