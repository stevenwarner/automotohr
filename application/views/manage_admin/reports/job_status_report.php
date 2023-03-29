<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>

                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                        <div class="hr-search-main" style='display:block'>
                                        <form method="GET" action="<?php echo base_url('manage_admin/reports/job_status_report'); ?>" name="search" id="search">
                                            <div class="row">
                                                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 field-row">
                                                    <label>Company:</label>
                                                    <div class="hr-select-dropdown">
                                                        <?php if (sizeof($companies) > 0) { ?>
                                                            <select class="invoice-fields" name="company_sid" id="company_sid">
                                                                <option value="all">All Companies</option>
                                                                <?php foreach ($companies as $active_company) { ?>
                                                                    <option <?php  if($this->uri->segment(5) != 'all' && urldecode($this->uri->segment(5)) == $active_company['sid']) { ?> selected="selected" <?php } ?> value="<?php echo $active_company['sid']; ?>">
                                                                        <?php echo $active_company['CompanyName']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        <?php } else { ?>
                                                            <p>No company found.</p>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 field-row">
                                                    <label class="transparent-label">empty</label>
                                                    <div>
                                                        <a id="btn_apply_filters" class="btn btn-success" href="#" >Apply Filter</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>


                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                    <p>Total: <strong class="messagesCounter"><?php echo $references_count;?></strong> <?php echo $card_type;?> Jobs</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-12">
                                        <?php   echo $links; ?>
                                    </div>
                                    <div class="tabs-outer">
                                        <ul class="nav nav-tabs" id="tabs">
                                            <li class="cards-tabs" data-attr="active_organic_jobs" <?php echo $active == 'active_organic_jobs' ? 'class="active"' : '' ?>><a data-toggle="tab" href="#active_organic_jobs">Active & Organic Jobs</a></li>
                                            <li class="cards-tabs" data-attr="active_jobs" <?php echo $active == 'active_jobs' ? 'class="active"' : '' ?>><a data-toggle="tab" href="#active_jobs">Active Jobs</a></li>
                                            <li class="cards-tabs" data-attr="inactive_jobs" <?php echo $active == 'inactive_jobs' ? 'class="active"' : '' ?>><a data-toggle="tab" href="#inactive_jobs">In-Active Jobs</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div id="active_organic_jobs" class="tab-pane fade <?php echo $active == 'active_organic_jobs' ? 'in active' : '' ?>">
                                                <?php if ($active_organic_jobs) { ?>
                                                    <div class="table-responsive table-outer">
                                                        <div class="table-wrp data-table">
                                                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                                <thead>
                                                                <tr>
                                                                    <th class="col-xs-2">ID</th>
                                                                    <th class="col-xs-2">Company Name</th>
                                                                    <th class="col-xs-2">Job Title</th>
                                                                    <th class="col-xs-3">Activation Date</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php   foreach ($active_organic_jobs as $job) {?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($job['sid']); ?></td>
                                                                        <td><?php echo ucwords($job['CompanyName']!='' ? $job['CompanyName'] : 'N/A'); ?></td>
                                                                        <?php
                                                                            $city = '';
                                                                            $state='';
                                                                            if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                                                                                $city = ' - '.ucfirst($job['Location_City']);
                                                                            }
                                                                            if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                                                                                $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                                                                            }
                                                                         ?>
                                                                        <td><?php echo $job['Title'].$city.$state; ?></td>
                                                                        <td><?php echo date_with_time($job['activation_date']); ?></td>
                                                                    </tr>
                                                                <?php   } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="show_no_jobs" class="table-wrp">
                                                        <span class="applicant-not-found">No Active & Organic Jobs Found!</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div id="active_jobs" class="tab-pane fade <?php echo $active == 'active_jobs' ? 'in active' : '' ?>">
                                                <?php if (is_array($active_jobs) && count($active_jobs) > 0) { ?>
                                                    <div class="table-responsive table-outer">
                                                        <div class="table-wrp data-table">
                                                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-2">ID</th>
                                                                        <th class="col-xs-2">Company Name</th>
                                                                        <th class="col-xs-2">Job Title</th>
                                                                        <th class="col-xs-3">Activation Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php   foreach ($active_jobs as $job) { ?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($job['sid']); ?></td>
                                                                        <td><?php echo ucwords($job['CompanyName']!='' ? $job['CompanyName'] : 'N/A'); ?></td>
                                                                         <?php
                                                                            $city = '';
                                                                            $state='';
                                                                            if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                                                                                $city = ' - '.ucfirst($job['Location_City']);
                                                                            }
                                                                            if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                                                                                $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                                                                            }
                                                                         ?>
                                                                        <td><?php echo $job['Title'].$city.$state; ?></td>
                                                                        <td><?php echo date_with_time($job['activation_date']); ?></td>
                                                                    </tr>
                                                                <?php   } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="show_no_jobs" class="table-wrp">
                                                        <span class="applicant-not-found">No Active Jobs Found!</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div id="inactive_jobs" class="tab-pane fade <?php echo $active == 'inactive_jobs' ? 'in active' : '' ?>">
                                                <?php if ($inactive_jobs) { ?>
                                                    <div class="table-responsive table-outer">
                                                        <div class="table-wrp data-table">
                                                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                                <thead>
                                                                <tr>
                                                                    <th class="col-xs-2">ID</th>
                                                                    <th class="col-xs-2">Company Name</th>
                                                                    <th class="col-xs-2">Job Title</th>
                                                                    <th class="col-xs-3">De-Activation Date</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                <?php   foreach ($inactive_jobs as $job) { ?>
                                                                    <tr>
                                                                        <td><?php echo ucwords($job['sid']); ?></td>
                                                                        <td><?php echo ucwords($job['CompanyName']!='' ? $job['CompanyName'] : 'N/A'); ?></td>
                                                                         <?php
                                                                            $city = '';
                                                                            $state='';
                                                                            if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                                                                                $city = ' - '.ucfirst($job['Location_City']);
                                                                            }
                                                                            if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                                                                                $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                                                                            }
                                                                         ?>
                                                                        <td><?php echo $job['Title'].$city.$state; ?></td>
                                                                        <td><?php echo $job['deactivation_date']!=NULL ? date_with_time($job['deactivation_date']) : 'N/A'; ?></td>
                                                                    </tr>
                                                                <?php   } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="show_no_jobs" class="table-wrp">
                                                        <span class="applicant-not-found">No In-Active Jobs Found!</span>
                                                    </div>
                                                <?php } ?>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?php   echo $links; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {

        //On tabs click
        $('.cards-tabs').click(function () {
            var attr = $(this).attr('data-attr');
            var start_date_applied = $('#start_date_applied').val();
            var end_date_applied = $('#end_date_applied').val();
            var company_sid = $('#company_sid').val();
            window.location.href = '<?php echo base_url('manage_admin/reports/job_status_report/') ?>' + '/' + attr + '/' + company_sid ;
        });

        //On Date Button Click
        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            var attr = $('#tabs').find('.active').attr('data-attr') == null ? 'active_organic_jobs':$('#tabs').find('.active').attr('data-attr');
            var start_date_applied = $('#start_date_applied').val();
            var end_date_applied = $('#end_date_applied').val();
            var company_sid = $('#company_sid').val();
            window.location.href = '<?php echo base_url('manage_admin/reports/job_status_report/') ?>' + '/' + attr + '/' + company_sid ;
        });

        $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) {
                //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());
    });
</script>