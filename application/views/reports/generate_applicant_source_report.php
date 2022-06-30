<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_company'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn"><i class="fa fa-chevron-left"></i>Back</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="hr-box">
                                            <div class="hr-box-header bg-header-green">
                                                <strong>Advanced Search Filters</strong>
                                            </div>
                                            <div class="hr-innerpadding">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <form id="form_export_csv" method="post" enctype="multipart/form-data" action="<?php echo current_url(); ?>">
                                                            <input type="hidden" id="perform_action" name="perform_action" value="export_csv" />
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <?php $applicant_name = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                                        <label>Applicant Name</label>
                                                                        <input placeholder="John Doe" class="invoice-fields" type="text" id="applicant_name" name="applicant_name" value="<?php echo set_value('applicant_name', $applicant_name); ?>" />
                                                                        <div class="video-link" style='font-style: italic;'><b>Hint:</b>
                                                                            Search by Applicant Name
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <?php $job_sid = $this->uri->segment(4) != 'all' ? $this->uri->segment(4) : ''; ?>
                                                                        <label>Job</label>
                                                                        <div class="hr-select-dropdown ">
                                                                            <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                                                                <?php if (!empty($jobOptions)) { ?>
                                                                                    <!--                                                                                    <option value="all" --><?php //if (in_array('all', $job_sid_array)) { 
                                                                                                                                                                                                    ?>
                                                                                    <!-- selected="selected" --><?php //} 
                                                                                                                ?>
                                                                                    <!-->All Jobs</option>-->
                                                                                    <?php foreach ($jobOptions as $key => $value) { ?>
                                                                                        <option value="<?= $key ?>" <?php if (in_array($key, $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                                                            <?php echo $value; ?>
                                                                                        </option>
                                                                                        <!--                                                                                        <option --><?php //echo set_select('job_sid', $key, $job_sid == $key); 
                                                                                                                                                                                                ?>
                                                                                        <!-- value="--><?php //echo $key; 
                                                                                                        ?>
                                                                                        <!--">--><?php //echo $value; 
                                                                                                    ?>
                                                                                        <!--</option>-->
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <option value="">No jobs found</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <div class="video-link" style='font-style: italic;'><b>Hint:</b>
                                                                                Please leave blank to search all jobs
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <label>Source</label>
                                                                        <?php $source = $this->uri->segment(8) != 'all' ? urldecode($this->uri->segment(8)) : ''; ?>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" name="source" id="source">
                                                                                <option value="all">Please Select</option>
                                                                                <option value="automotosocial" <?php if ($source == 'automotosocial') { ?> selected="selected" <?php } ?>>Automoto Social</option>
                                                                                <option value="jobs2careers" <?php if ($source == 'jobs2careers') { ?> selected="selected" <?php } ?>>Jobs2Careers</option>
                                                                                <option value="ziprecruiter" <?php if ($source == 'ziprecruiter') { ?> selected="selected" <?php } ?>>ZipRecruiter</option>
                                                                                <option value="glassdoor" <?php if ($source == 'glassdoor') { ?> selected="selected" <?php } ?>>Glassdoor</option>
                                                                                <option value="indeed" <?php if ($source == 'indeed') { ?> selected="selected" <?php } ?>>Indeed</option>
                                                                                <option value="juju" <?php if ($source == 'juju') { ?> selected="selected" <?php } ?>>Juju</option>
                                                                                <option value="career_website" <?php if ($source == 'career_website') { ?> selected="selected" <?php } ?>>Career Website</option>
                                                                                <option value="others" <?php if ($source == 'others') { ?> selected="selected" <?php } ?>>Others</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="row">

                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <label>Applicant Type</label>
                                                                        <?php $applicant_type = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" name="applicant_type" id="applicant_type">
                                                                                <?php if (!empty($applicant_types)) { ?>
                                                                                    <option value="all">All</option>
                                                                                    <?php foreach ($applicant_types as $type) { ?>
                                                                                        <option <?php echo set_select('applicant_type', $type, $applicant_type == $type); ?> value="<?php echo $type; ?>"><?php echo $type; ?></option>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <option value="all">No Applicant Types found</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <label class="">Start Date</label>
                                                                        <?php $start_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                    <div class="field-row">
                                                                        <label class="">End Date</label>
                                                                        <?php $end_date = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-d-Y'); ?>
                                                                        <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                    <div class="field-row">
                                                                        <label>Applicant Status</label>
                                                                        <?php $applicant_status = $this->uri->segment(9) != 'all' ? urldecode($this->uri->segment(9)) : ''; ?>
                                                                        <div class="hr-select-dropdown">
                                                                            <select class="invoice-fields" name="applicant_status" id="applicant_status">
                                                                                <?php if (!empty($applicant_statuses)) { ?>
                                                                                    <option value="all">All</option>
                                                                                    <?php foreach ($applicant_statuses as $status) { ?>
                                                                                        <option <?php echo set_select('applicant_status', $status['name'], $applicant_status == $status['name']); ?> value="<?php echo $status['name']; ?>"><?php echo $status['name']; ?></option>
                                                                                    <?php } ?>
                                                                                <?php } else { ?>
                                                                                    <option value="all">No Status found</option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                                    <div class="field-row">
                                                                        <label class="">&nbsp;</label>
                                                                        <a id="btn_apply_filters" class="btn btn-success btn-block" href="#">Apply Filters</a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                                    <div class="field-row">
                                                                        <label class="">&nbsp;</label>
                                                                        <a id="btn_reset_filters" class="btn btn-success btn-block" href="<?php echo base_url('reports/applicant_source_report'); ?>">Reset Filters</a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
                                                                    <div class="field-row">
                                                                        <label class="">&nbsp;</label>
                                                                        <button type="submit" id="btn_export" class="btn btn-success btn-block">Export</button>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <hr />

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <span class="pull-left">
                                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                        </span>
                                                        <?php if (!empty($page_links)) { ?>
                                                            <span class="pull-right">
                                                                <?php echo $page_links ?>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <hr />

                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-hover table-condensed" id="example">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-1">Date Applied</th>
                                                                        <th class="col-xs-1">Applicant Type</th>
                                                                        <th class="col-xs-2">Applicant Status</th>
                                                                        <th class="col-xs-2">Name</th>
                                                                        <th class="col-xs-3">Job Title</th>
                                                                        <th class="col-xs-1">IP Address</th>
                                                                        <th class="col-xs-2">Applicant Source</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php if (!empty($applicants)) { ?>
                                                                        <?php foreach ($applicants as $applicant) { ?>
                                                                            <tr>
                                                                                <!-- <td><?php //echo DateTime::createFromFormat('Y-m-d H:i:s', $applicant['date_applied'])->format('M j, Y h:i A'); 
                                                                                            ?></td> -->
                                                                                <td><?= reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this)); ?></td>
                                                                                <td><?php echo $applicant['applicant_type']; ?></td>
                                                                                <td><?php echo $applicant['status']; ?></td>
                                                                                <td><?php echo ucwords($applicant['first_name'] . ' ' . $applicant['last_name']); ?></td>
                                                                                <td><?php echo ucwords($applicant['Title']); ?></td>
                                                                                <td><?php echo $applicant['ip_address']; ?></td>
                                                                                <td>
                                                                                    <div class="table-responsive applicant_source_link_in_table">
                                                                                        <?php echo $applicant['applicant_source']; ?>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td class="text-center" colspan="6">
                                                                                No applicants found
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr />
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                        <span class="pull-left">
                                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                        </span>
                                                        <?php if (!empty($page_links)) { ?>
                                                            <span class="pull-right">
                                                                <?php echo $page_links ?>
                                                            </span>
                                                        <?php } ?>
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
            </div>
        </div>
    </div>
</div>

<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css" />
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>
<script>
    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        var keyword = $('#applicant_name').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = $('#applicant_type').val();
        var applicant_status = $('#applicant_status').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        var source = $('#source').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        source = source != '' && source != null && source != undefined && source != 0 ? encodeURIComponent(source) : 'all';

        var url = '<?php echo base_url('reports/applicant_source_report'); ?>' + '/' + keyword + '/' + job_sid + '/' + applicant_type + '/' + start_date_applied + '/' + end_date_applied + '/' + source + '/' + applicant_status;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {

        $('.chosen-select').selectize({
            plugins: ['remove_button'],
            delimiter: ',',
            persist: true,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });
        $('.collapse').on('shown.bs.collapse', function() {
            $(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
        }).on('hidden.bs.collapse', function() {
            $(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
        });

        $('#job_sid').on('change', function(value) {
            generate_search_url();
        });

        $('#applicant_type').on('change', function(value) {
            generate_search_url();
        });


        $('#applicant_status').on('change', function(value) {
            generate_search_url();
        });

        $('#source').on('change', function(value) {
            generate_search_url();
        });

        $('#applicant_name').on('keyup', function() {
            generate_search_url();
        });

        $('#applicant_name').trigger('keyup');

        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });


        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                $('#end_date_applied').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function(value) {
                $('#start_date_applied').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());
    });

    function print_page(elem) {

        var data = ($(elem).html());
        var mywindow = window.open('', 'Print Report', 'height=800,width=1200');

        mywindow.document.write('<html><head><title>' + '<?php echo $title; ?>' + '</title>');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/style.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome-animation.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/bootstrap.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/font-awesome.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/responsive.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/jquery-ui.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/jquery.datetimepicker.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/images/favi-icon.png'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/alertify.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/alertifyjs/css/themes/default.min.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/select2.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/manage_admin/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('<link rel="stylesheet" href="<?php echo site_url('assets/css/chosen.css'); ?>" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');
        mywindow.document.write('<scr' + 'ipt src="<?php echo site_url('assets/manage_admin/js/jquery-1.11.3.min.js'); ?>"></scr' + 'ipt>');
        mywindow.document.write('<scr' + 'ipt type="text/javascript">$(window).load(function() { window.print(); window.close(); });</scr' + 'ipt>');
        mywindow.document.close();
        mywindow.focus();

    }
</script>