<style>
.cs-nav-tabs>li>a {
    color: #81b431 !important;
}

.cs-nav-tabs>li.active>a,
.nav-tabs>li.active>a:hover,
.nav-tabs>li.active>a:focus {
    background-color: #81b431 !important;
    color: #ffffff;
}
</style>

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
                            'Text' => 'Performance Feedback'
                        ]); ?>

                        <div class="clearfix"></div>

                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <!-- Search Form -->
                                <div class="hr-search-main ">
                                    <form method="GET" action="#" id="js-search-filter">
                                        <div class="row">

                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                <div class="field-row">
                                                    <label class="">Review Title</label>
                                                    <input class="invoice-fields" id="js-title" />
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 hidden">
                                                <div class="field-row">
                                                    <label class="">Reviewee</label>
                                                    <select class="invoice-fields" id="js-reviewee" multiple="true">
                                                        <option value="-1">All</option>
                                                        <?php if(count($employees['All'])) { ?>
                                                        <?php   foreach($employees['All'] as $employee){ ?>
                                                        <option value="<?=$employee['sid'];?>"><?=remakeEmployeeName($employee);?></option>
                                                        <?php   } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 hidden">
                                                <div class="field-row">
                                                    <label class="">Status</label>
                                                    <select class="invoice-fields" id="js-status">
                                                        <option value="-1">All</option>
                                                        <option value="0">Pending</option>
                                                        <option value="1">Completed</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <div class="field-row">
                                                    <label class="">&nbsp;</label>
                                                    <a class="btn btn-success btn-block js-apply-filter-btn"
                                                        href="#">Apply Filters</a>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                <div class="field-row">
                                                    <label class="">&nbsp;</label>
                                                    <a class="btn btn-success btn-block js-reset-filter-btn"
                                                        href="#">Reset Filters</a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <br />

                        <div class="cs-prpage">
                            <div>
                                <!-- Pagination  -->
                                <div class="cs-pagination-area"></div>
                                <!-- Data Table -->
                                <div class="table-responsive table-outer">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Review Title</th>
                                                <th>Feedback Progress</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jsDataArea"></tbody>
                                    </table>
                                </div>
                                <!-- Pagination  -->
                                <div class="cs-pagination-area"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<style>
?><?php $this->load->view("manage_employer/PerformanceReview/main.css");
?>
</style>
<script src="<?= base_url('assets/js/moment.min.js'); ?>"></script>
<script>
<?php $this->load->view('manage_employer/PerformanceReview/Feedback/scripts/view'); ?>
</script>