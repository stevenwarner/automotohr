<div class="container-fluid">
    <div class="csPageBox csRadius5">
        <!-- Page Header -->
        <div class="csPageBoxHeader p10">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <h1 class="csF18">
                        <span id="jsReviewTitleHeader"></span>
                    </h1>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <span class="csBTNBox">
                        <button class="btn btn-black btn-lg dn  csF14 jsFinishLater"><i aria-hidden="true"
                                class="fa fa-edit csF14"></i> Finish
                            Later</button>
                        <a href="<?=purl('reviews');?>" class="btn btn-black btn-lg csF14"><i aria-hidden="true"
                                class="fa fa-long-arrow-left csF14"></i> All Reviews</a>
                    </span>

                </div>
            </div>
        </div>

        <!-- Page Body -->
        <div class="csPageBoxBody p10">
            <!-- Loader -->
            <div class="csIPLoader jsIPLoader" data-page="create_review"><i aria-hidden="true"
                    class="fa fa-circle-o-notch fa-spin"></i>
            </div>
            <div class="row">
                <!-- Left menu -->
                <div class="col-sm-2 col-xs-12 csSticky csStickyTop">
                    <ul class="csPageLeftMenu ma10">
                        <li class="jsReviewStep active csF14" data-to="templates">
                            <span class="csF14">Templates</span> <i aria-hidden="true"
                                class="fa fa-long-arrow-right"></i>
                        </li>
                        <li class="jsReviewStep csF14" data-to="schedule">
                            <span class="csF14">Name & Schedule</span>
                        </li>
                        <li class="jsReviewStep csF14" data-to="reviewees">
                            <span class="csF14">Reviewees</span>
                        </li>
                        <li class="jsReviewStep csF14" data-to="reviewers">
                            <span class="csF14">Reviewers</span>
                        </li>
                        <li class="jsReviewStep csF14" data-to="questions">
                            <span class="csF14">Questions</span>
                        </li>
                        <li class="jsReviewStep csF14" data-to="feedback">
                            <span class="csF14">Sharing Feedback</span>
                        </li>
                    </ul>
                </div>
                <!-- Content Area -->
                <div class="col-sm-10 col-xs-12">
                    <div class="csPageContent">
                        <!-- Template Section -->
                        <?php $this->load->view($pp.'reviews/partials/template'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
