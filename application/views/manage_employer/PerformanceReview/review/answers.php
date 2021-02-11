<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4 hidden-print">
                    <?php $this->load->view('manage_employer/PerformanceReview/sidebar'); ?>
                </div>

                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="right-content">
                        <div class=" hidden-print">
                            <!-- Header -->
                            <?php $this->load->view('manage_employer/PerformanceReview/headerBar', [
                                'Link' => [
                                    base_url('dashboard'),
                                    'Dashboard',
                                ],
                                'Link2' => [
                                    base_url("performance/review/detail/" . ($review['main']['sid']) . "/" . $eId . ""),
                                    'BACK',
                                ],
                                'Text' => 'Performance Review - Answer(s)'
                            ]); ?>


                        </div>
                        <!-- Table -->

                        <div class="cs">
                            <!-- Pagination -->
                            <!-- Question Area -->
                            <div>
                                <!--  -->
                                <div class="row hidden-print">
                                    <div class="col-sm-6">
                                        <h3><?= $review['main']['title']; ?></h3>
                                    </div>
                                    <div class="col-sm-6">
                                        <span class="pull-right">
                                            <a href="<?= base_url('performance/pd/print/' . ($review['main']['sid'] . "/" . $cId) . "/" . $eId . ''); ?>" class="btn btn-success ">Print</a>
                                            <a href="<?= base_url('performance/pd/download/' . ($review['main']['sid'] . "/" . $cId) . "/" . $eId . ''); ?>" class="btn btn-success ">Export</a>
                                        </span>
                                    </div>
                                </div>
                                <hr />
                                <?php 
                                    $ReviewerName =  getReviewName($review['reviewees'], $cId, 'reviewer'); 
                                    $RevieweeName =  getReviewName($review['reviewees'], $eId, 'reviewee');
                                ?>
                                <div class="A4" id="js-export-pdf">
                                    <?php $answers = []; ?>
                                    <?php foreach ($review['questions'] as $k => $question) {?>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="cs-question-row">
                                                    <h5><strong>Q <?= $k + 1 ?>: <?= stripslashes($question['question']); ?></strong></h5>
                                                    <h5><?= stripslashes($question['description']); ?></h5>
                                                    <?php echo getQuestion($question, $answers); ?>
                                                    <div class="clearfix"></div>
                                                    <div>
                                                        <br />
                                                        <p><?=$ReviewerName;?> <i class="fa fa-long-arrow-right"></i> <?=$RevieweeName;?></p>
                                                    </div>
                                                    <hr />
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <!--  -->
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a href="<?= base_url("performance/review/detail/" . ($review['main']['sid']) . "/" . $eId . ""); ?>" class="btn btn-default"> Back</a>
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
    <?php $this->load->view("manage_employer/PerformanceReview/main.css"); ?>
</style>
<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
