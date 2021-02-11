<style>
    .cs-progress{ margin-bottom: 0; height: 25px; }
    .cs-progress div{ line-height: 25px; background-color: #81b431; }
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

                        <!-- Graphs -->
                        <div>
                            <!-- Performance Graph -->
                            <div class="col-sm-12">
                                <div id="jsPerformanceChart"></div>
                                <hr />
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 hidden">
                            <div class="panel panel-success">
                                <div class="panel-heading" style="background-color: #81b431; color: #ffffff;"><strong>Search Performance Review Detail</strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <div class="form-group full-width">
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="rev_status" id="rev_status">
                                                        <option value="0">Select Status</option>
                                                        <option value="started">Started</option>
                                                        <option value="ended">Ended</option>
                                                        <!-- <option value="archived">Archived</option> -->
                                                        <!-- <option value="draft">Draft</option> -->
                                                        <option value="pending">Pending</option>
                                                        <!-- <option value="cancelled">Cancelled</option> -->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <div class="form-group full-width">
                                                <div class="hr-select-dropdown">
                                                    <select class="invoice-fields" name="con_sid" id="con_sid">
                                                        <option value="0">Select Reviewer</option>
                                                        <?php if (!empty($active_employees)) { ?>
                                                            <?php foreach ($active_employees as $active_employee) { ?>
                                                                <option value="<?php echo $active_employee['sid']; ?>"><?php echo $active_employee['first_name'].' '.$active_employee['last_name'] ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <a class="form-btn" href="javascript:;" id="filter-btn" name="filter-btn">Filter</a>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6 custom-col">
                                            <a class="form-btn" href="<?php echo base_url('performance_review').'/'.$employer['sid']; ?>">Clear</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                            $reviewedT = $reviewed['completed'] * 100 / $reviewed['total'];
                            $feedbackT = $feedback['completed'] * 100 / $feedback['total'];
                        ?>

                        <div class="col-sm-6">
                            <h5><strong>Reviewed</strong></h5>
                            <div class="progress cs-progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                style="width: <?=$reviewedT;?>%;" aria-valuenow="<?=$reviewedT;?>" aria-valuemin="0" aria-valuemax="<?=$reviewed['total'];?>"><?=$feedback['completed'];?>%</div>
                            </div>
                            <p><strong><?=$reviewed['completed'];?></strong> out of <strong><?=$reviewed['total'];?></strong></p>
                        </div>
                        
                        <div class="col-sm-6">
                            <h5><strong>Feedback(s)</strong></h5>
                            <div class="progress cs-progress">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                style="width: <?=$feedbackT;?>%;" aria-valuenow="<?=$feedbackT;?>" aria-valuemin="0" aria-valuemax="<?=$feedback['total'];?>"><?=$feedback['completed'];?>%</div>
                            </div>
                            <p><strong><?=$feedback['completed'];?></strong> out of <strong><?=$feedback['total'];?></strong></p>
                        </div>
                        
                        
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <hr />
                            <div class="table-responsive table-outer">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <th>Reviewee Title</th>
                                        <th>Reviewer Name</th>
                                        <th>Status</th>
                                        <th class="col-sm-1">Actions</th>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($employee_review_detail)) { ?>
                                            <?php foreach ($employee_review_detail as $key => $review_info) {?>
                                                <tr>
                                                    <td><?php echo $review_title; ?></td>
                                                    <td><?php echo $review_info['reviewer_name']; ?></td>
                                                    <td><?php echo ucwords($review_info['review_status']); ?></td>
                                                    <td class="text-center">
                                                        <a href="<?php echo base_url('performance_review/review_question/').$review_sid.'/'.$employee_review_sid.'/'.$employer['sid'].'/'.$review_info['conductor_sid']; ?>" class="btn btn-success btn-xs" title="View Answers"><i class="fa fa-eye"></i></a>
                                                    </td>
                                                <tr>
                                            <?php } ?>
                                        <?php } else { ?> 
                                            <tr>
                                                <td colspan="4">
                                                    <p class="alert alert-info text-center">No detail found.</p>
                                                </td>
                                            </tr>
                                        <?php } ?>  
                                    </tbody>
                                </table>
                            </div>
                        </div>    
                    </div>
                </div>
                <?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<!-- Google chart script -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    //
    $('#con_sid').select2();
    //
    if(<?=json_encode($report);?>.total != 0){
        // Google chart load
        google.load("visualization", "1", {packages: ["corechart"]});
        google.setOnLoadCallback(drawPerformanceChart);

        //
        function drawPerformanceChart() {
            let titles = <?=json_encode($report['Rating']);?>;
            data = google.visualization.arrayToDataTable(titles);
            let options = {
                title: 'Score',
                is3D: true,
                legend: {position: 'top', maxLines: 3}
            };
            let chart = new google.visualization.BarChart(document.getElementById('jsPerformanceChart'));
            chart.draw(data, options);
        }
    }
</script>