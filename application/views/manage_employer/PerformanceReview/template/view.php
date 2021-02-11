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
                            'Text' => 'Performance Review - Template(s)'
                        ]); ?>

                        <!-- Search Form -->
                        <div class="hr-search-main">
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

                        <div class="cs-prpage">
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
                                        <th>Template Name</th>
                                        <th>Status</th>
                                        <th>Created By</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </thead>
                                    <tbody>

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
<script>
    <?php $this->load->view("manage_employer/PerformanceReview/Pagination.js"); ?>
    <?php $this->load->view('manage_employer/PerformanceReview/template/scripts/view'); ?>
</script>