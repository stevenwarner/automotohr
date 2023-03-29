<!-- Main page -->
<div class="csPage">
    <!--  -->
    <?php $this->load->view('es/partials/navbar'); ?>
    <!--  -->
    <div class="_csMt10">
        <div class="container-fluid">
            <!--  -->
            <div class="row">
                 <!--  -->
                <div class="col-md-12 col-sm-12">
                    <!--  -->
                    <div class="row">

                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url("employee/surveys/create"); ?>" class="btn _csB4 _csF2 _csR5  _csF16"><i class="fa fa-plus _csF16" aria-hidden="true"></i>&nbsp;Create Survey</a>
                        </div>
                    </div>

                    <div class="panel panel panel-default _csMt10">
                        <!--  -->
                        <div class="panel-body">
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class=" _csF1 _csF16">
                                        <b> Title </b> &nbsp;
                                        <a class="btn _csB3 _csF2 _csF16 _csR5" href="#">Started</a>
                                    </h5>
                                </div>
                            </div>
                            <hr>
                            <!--  -->
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <h5 class=" _csF1 _csF16">
                                        <b>10% Completed</b>
                                    </h5>
                                    <h5 class=" _csF1 _csF14">
                                        3 out of 12 survey(s) submitted their feedback.
                                    </h5>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <h5 class="_csF1 _csF16">
                                        <b> 5% Completed </b>
                                    </h5>
                                    <h5 class="_csF1 _csF14">
                                        3 out of 20 reporting manager(s) submitted their feedback.
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-theme">
                        <div class="panel-body">
                            <table class="table table-striped table-condensed">
                                <caption></caption>
                                <thead>
                                    <tr>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Employee</th>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Progress</th>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Period Cycle</th>
                                        <th scope="col" class="_csF16 _csB1 _csF2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr data-review_id="<?= $review['sid']; ?>" data-id="<?= $reviewee['reviewee_sid']; ?>">
                                        <td style="vertical-align: middle">
                                            <p class="_csF14">
                                                <b>survey1</b>
                                            </p>
                                            <p class="_csF14"> asmin aaa </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <a class="_csF14" title="Click to view employees" placement="top">
                                                <b> 2 Reviewer(s) Added </b>
                                            </a>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <p class="_csF14"> sss </p>
                                        </td>
                                        <td style="vertical-align: middle">
                                            <a class="btn _csB4 _csF2 _csR5  _csF16" title="View Reviewers" placement="top" href="<?= base_url("employee/surveys/surveys/12/11/22"); ?>">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <?php
                                            if ($reviewee['is_started']) {
                                            ?>
                                                <button class="btn _csB4 _csF2 _csR5  _csF16" title="Stop Review" placement="top">
                                                    <i class="fa fa-stop" aria-hidden="true"></i>
                                                </button>
                                            <?php
                                            } else {
                                            ?>
                                                <button class="btn _csB4 _csF2 _csR5  _csF16" title="Start Review" placement="top">
                                                    <i class="fa fa-play" aria-hidden="true"></i>
                                                </button>
                                            <?php
                                            }
                                            ?>
                                            <button class="btn _csB4 _csF2 _csR5  _csF16" title="Manage" placement="top">
                                                <i class="fa fa-cogs" aria-hidden="true"></i>
                                            </button>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>