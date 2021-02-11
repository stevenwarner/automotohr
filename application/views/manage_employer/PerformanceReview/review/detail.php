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
                            'Link2' => [
                                base_url('performance/review/view/' . ($id) . ''),
                                'BACK',
                            ],
                            'Text' => 'Performance Review - Review(s)'
                        ]); ?>

                        <!-- Search Form -->
                        <div class="hr-search-main hidden">
                            <form method="GET" action="#" id="js-search-filter">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Title</label>
                                            <input class="invoice-fields" id="js-title" />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                        <div class="field-row">
                                            <label class="">Status</label>
                                            <select class="invoice-fields" id="js-status" value="-1">
                                                <option value="-1">All</option>
                                                <option value="1">Active</option>
                                                <option value="0">In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <div class="field-row">
                                            <label class="">&nbsp;</label>
                                            <a class="btn btn-success btn-block js-apply-filter-btn" href="#">Apply Filters</a>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                        <div class="field-row">
                                            <label class="">&nbsp;</label>
                                            <a class="btn btn-success btn-block js-reset-filter-btn" href="#">Reset Filters</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Table -->

                        <div class="cs">
                            <!-- Loader -->
                            <div class="cs-loader">
                                <i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>
                            <!-- Pagination -->
                            <div class="cs-pagination-area"></div>
                            <div class="table-responsive table-outer">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>ID</th>
                                        <th>Reviewee Name</th>
                                        <th>Reviewer(s)</th>
                                        <th>Status</th>
                                        <th class="col-sm-1">Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php if (count($review['reviewees'])) { ?>
                                            <?php foreach ($review['reviewees'] as $reviewee) {
                                                if ($reviewee['employee_sid'] != $cId) continue; ?>
                                                <tr data-id="<?= $reviewee['sid']; ?>">
                                                    <td><?= $reviewee['sid']; ?></td>
                                                    <td><?= remakeEmployeeName([
                                                            'first_name' => $reviewee['efirst_name'],
                                                            'last_name' => $reviewee['elast_name'],
                                                            'pay_plan_flag' => $reviewee['epay_plan_flag'],
                                                            'access_level' => $reviewee['eaccess_level'],
                                                            'access_level_plus' => $reviewee['eaccess_level_plus'],
                                                            'job_title' => $reviewee['ejob_title'],
                                                        ], true); ?></td>
                                                    <td>
                                                        <?= remakeEmployeeName($reviewee); ?>
                                                    </td>
                                                    <td><?= $reviewee['is_completed'] == 1 ? "Completed" : "Pending"; ?></td>
                                                    <td>
                                                        <a href="<?= base_url('performance/review/answers') . '/' . $review['main']['sid'] . '/' . $reviewee['conductor_sid'] . '/' . $reviewee['employee_sid']; ?>" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td colspan="5">
                                                    <p class="alert alert-info text-center">No records found.</p>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="cs-pagination-area"></div>
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
<?php $this->load->view("manage_employer/PerformanceReview/scripts/common.php"); ?>
<script>
    <?php $this->load->view("manage_employer/PerformanceReview/Pagination.js"); ?>
    <?php $this->load->view('manage_employer/PerformanceReview/review/scripts/employee_view'); ?>
</script>