<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <!-- Header -->
                        <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                            'Link' => [
                                base_url('dashboard'),
                                'Dashboard',
                            ],
                            'Text' => 'Performance Review - Review(s)'
                        ]); ?>



                        <!-- Table -->

                        <div class="cs-prpage">
                            <!-- Loader -->
                            <div class="cs-loader">
                                <i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>
                            <!-- Pagination -->
                            <!-- Question Area -->
                            <div>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="btn btn-success js-save-answers"> Save</a>
                                        <a class="btn btn-default" href="<?=base_url("performance/assigned/view");?>"> Cancel</a>
                                    </div>
                                </div>
                                <hr /> 
                                <?php $answers = []; ?>
                                <?php foreach ($review['questions'] as $k => $question) { ?>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="cs-question-row">
                                                <h5><strong>Q <?=$k + 1?>:  <?= stripslashes($question['question']); ?></strong></h5>
                                                <h5><?= stripslashes($question['description']); ?></h5>
                                                <?php echo getQuestion($question, $answers); ?>
                                                <div class="clearfix"></div>
                                                <hr />
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="btn btn-success js-save-answers"> Save</a>
                                        <a href="<?=base_url("performance/assigned/view");?>" class="btn btn-default"> Cancel</a>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    <?php $this->load->view("manage_employer/PerformanceReview/Pagination.css"); ?><?php $this->load->view("manage_employer/PerformanceReview/main.css"); ?>
</style>
<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script>
    <?php $this->load->view("manage_employer/PerformanceReview/Pagination.js"); ?>
    <?php $this->load->view('manage_employer/PerformanceReview/assigned/scripts/single_view', ['answers' => $answers]); ?>
</script>