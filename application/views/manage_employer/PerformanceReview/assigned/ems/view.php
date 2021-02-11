<div class="main">
    <div class="">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    <a href="<?=base_url('employee_management_system');?>" class="btn btn-info btn-block">
                        <i class="fa fa-arrow-left"></i> Dashboard
                    </a>
                </div>
                <?php if(strtolower($session['employer_detail']['access_level']) != 'employee') { ?>
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                    <a href="<?=base_url('performance/review/view');?>" class="btn btn-info btn-block">
                        <i class="fa fa-th-list"></i> Reviews
                    </a>
                </div>
                <?php } ?>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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
                    </div>
                </div>
                <br />
                        <!-- Table -->
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                        <div class="page-header">
                            <h2 class="section-ttile">Assigned Review(s)</h2>
                        </div>
                        <div class="cs-">
                            <!-- Loader -->
                            <div class="cs-loader">
                                <i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>
                            <!-- Pagination -->
                            <div class="cs-pagination-area"></div>
                            <br />
                            <div class="clearfix"></div>
                            <div class="table-responsive table-outer">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th class="col-sm-3">Actions</th>
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
    <?php $this->load->view('manage_employer/PerformanceReview/assigned/ems/scripts/view'); ?>
</script>