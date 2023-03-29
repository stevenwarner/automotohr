<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_reporting'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Advanced Reports</a>
                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div id="listing">
                                    <div class="box-wrapper" id="listing">
                                        <div class="row">
                                            <div class="applicant-reg-date">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <!--                                                <div class="row">-->
                                                    <form id="form-filters" method="post" enctype="multipart/form-data" action="">

                                                        <div class="row">
                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="form-col-100">
                                                                    <label for="startdate">Keyword</label>
                                                                    <?php $keyword = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                                    <input type="text" id="keyword" class="invoice-fields" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>">
                                                                    <div class="video-link" style='font-style: italic;'><b>Hint:</b>
                                                                        Search by First Name, Last Name, Email or Phone number
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">

                                                                <label class="">Start Date</label>
                                                                <?php $start_date = $this->uri->segment(4) != 'all' && $this->uri->segment(4) != '' ? urldecode($this->uri->segment(4)) : date('m-01-Y'); ?>
                                                                <input class="invoice-fields" placeholder="<?php echo date('m-01-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">

                                                                <label class="">End Date</label>
                                                                <?php $end_date = $this->uri->segment(5) != 'all' && $this->uri->segment(5) != '' ? urldecode($this->uri->segment(5)) : date('m-d-Y'); ?>
                                                                <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />

                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                                                <div class="report-btns">
                                                                    <div class="row">
                                                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                            <a class=" btn-block btn btn-success" id="btn_apply_filters" href="javascript:;">Filter</a>
                                                                        </div>
                                                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                            <a class="btn btn-success btn-block" id="clear">Clear</a>
                                                                            <!--                                                                        <button class="form-btn" onclick="fClearDateFilters();">Clear</button>-->
                                                                        </div>
                                                                        <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>
                                                                            <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
                                                                                <div class="form-group">
                                                                                    <form method="post" id="export" name="export">
                                                                                        <input type="submit" name="submit" class="btn btn-block btn-success" value="Export" />
                                                                                    </form>
                                                                                </div>
                                                                            </div>

                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!--                                                </div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                            <?php if (isset($applicants) && sizeof($applicants) > 0) { ?>

                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <span class="pull-left">
                                                            <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $applicants_count ?></p>
                                                        </span>
                                                        <span class="pull-right">
                                                            <?php echo $page_links ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <hr>
                                                </div>
                                                <div class="page-header-area">
                                                    <span class="page-heading pull-right">
                                                        <b><?= 'Total number of applicants:    ' . $applicants_count ?></b>
                                                    </span>
                                                </div>
                                            <?php } ?>

                                            <div class="table-responsive table-outer" id="print_div">
                                                <div class="border-none mylistings-wrp">

                                                    <table class="table table-bordered table-stripped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Job Title</th>
                                                                <th>First Name</th>
                                                                <th>Last Name</th>
                                                                <th>Email</th>
                                                                <th>Phone Number</th>
                                                                <!--                                                                <th>Applicant Type</th>-->
                                                                <th>Application Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if (!empty($applicants)) { ?>
                                                                <?php foreach ($applicants as $applicant) { ?>
                                                                    <?php
                                                                    $state = $city = '';
                                                                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != null && $applicant['Location_City'] != '') $city = ' - ' . ucfirst($applicant['Location_City']);
                                                                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != null && $applicant['Location_State'] != '') $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                                                    $applicant['desired_job_title'] .= $city . $state;
                                                                    ?>
                                                                    <tr>
                                                                        <td style="color:<?php echo (($applicant['desired_job_title'] != '') ? 'green' : 'red'); ?>"><?php echo $applicant['desired_job_title'] != '' ? ucwords($applicant['desired_job_title']) : 'Job Not Provided'; ?></td>
                                                                        <td><?php echo ucwords($applicant['first_name']); ?></td>
                                                                        <td><?php echo ucwords($applicant['last_name']); ?></td>
                                                                        <td><?php echo $applicant['email']; ?></td>
                                                                        <td><?php echo $applicant['phone_number']; ?></td>
                                                                        <!--                                                                        <td>--><?php //echo $applicant['applicant_type']; 
                                                                                                                                                            ?>
                                                                        <!--</td>-->
                                                                        <td><?= reset_datetime(array('datetime' => $applicant['date_applied'], '_this' => $this)); ?></td>
                                                                        <td><a class="btn-success btn btn-sm questionnaire" data-time="<?php echo date_with_time($applicant['date_applied']); ?>" data-key='<?php echo json_encode($applicant) ?>' data-attr='<?php echo json_encode(unserialize($applicant['talent_and_fair_data'])) ?>' href="javascript:;">View Questionnaire</a></td>

                                                                    </tr>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <tr>
                                                                    <td colspan="12" class="text-center">No Applicants Found</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrapper" id="detail">

                                    <div class="row">
                                        <div class="applicant-reg-date">
                                            <div class="col-lg-12 mb-2">
                                                <a class="btn btn-success btn-sm" href="javascript:;" id="search-btn">Back To Search</a>
                                            </div>

                                            <div class="col-lg-12 mb-2">
                                                <div class="table-responsive table-outer" id="print_div">
                                                    <div class="border-none mylistings-wrp">

                                                        <table class="table table-bordered table-stripped table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>Job Title</th>
                                                                    <th>First Name</th>
                                                                    <th>Last Name</th>
                                                                    <th>Email</th>
                                                                    <th>Phone Number</th>
                                                                    <!--                                                            <th>Applicant Type</th>-->
                                                                    <th>Application Date</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="detail-table">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="panel-section">
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

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
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
        var keyword = $('#keyword').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();


        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        var url = '<?php echo base_url('reports/generate_job_fair_report'); ?>' + '/' + keyword + '/' + start_date_applied + '/' + end_date_applied;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {
        $('#detail').hide();
        var url = '<?php echo base_url(); ?>' + 'reports/generate_job_fair_report/';
        $('#clear').attr('href', url);


        $('#btn_apply_filters').on('click', function(e) {
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('.questionnaire').click(function() {
            var questionnaire = $(this).attr('data-attr');
            var general_data = $(this).attr('data-key');
            var date = $(this).attr('data-time');
            general_data = JSON.parse(general_data);
            var title = general_data.desired_job_title != '' && general_data.desired_job_title != null ? general_data.desired_job_title : 'Job Not Provided';
            var phone_number = general_data.phone_number != '' && general_data.phone_number != null ? general_data.phone_number : 'Not Provided';

            $('#detail-table').html('');
            $('#detail-table').append('<tr><td>' + title + '</td><td>' + general_data.first_name + '</td><td>' + general_data.last_name + '</td><td>' + general_data.email + '</td>' +
                '<td>' + phone_number + '</td>' +
                '<td>' + date + '</td>' +
                '</tr>'
            );
            if (questionnaire != 'false') {
                var questionnaire = JSON.parse(questionnaire);
                $('#panel-section').html('');
                $.each(questionnaire.questions, function(index, value) {
                    $('#panel-section').append('<div class="panel panel-default"><div class="panel-heading"><strong>' + index + '</strong></div> <div class="panel-body">' + value + '</div> </div>');
                });
            } else {
                $('#panel-section').html('');
                $('#panel-section').append('<div class="panel panel-default"><div class="panel-heading"><strong>Job Fair Data Not Found</strong></div> </div>');

            }
            $('#listing').hide();
            $('#detail').show();
        });

        $('#search-btn').click(function() {
            $('#listing').show();
            $('#detail').hide();
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
                //console.log(value);
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
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

    });

    function fApplyDateFilters() {
        var startDate = $('#startdate').val();
        var endDate = $('#enddate').val();
        var keyword = $('#keyword').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = '';
        var applicant_status = '';
        <?php if (isset($is_hired_report) && $is_hired_report == false) { ?>
            applicant_type = $('#applicant_type').val();
            applicant_status = $('#applicant_status').val();
        <?php } ?>

        var url = '';
        <?php if (isset($is_hired_report) && $is_hired_report == true) { ?>
            url = '<?php echo base_url(); ?>' + 'reports/generate_new_hires_report/';
        <?php } else { ?>
            url = '<?php echo base_url(); ?>' + 'reports/candidates_between_period/';
        <?php } ?>

        if (startDate != '' && endDate == '') {
            url += encodeURI(startDate) + '/end-of-days/';
        }

        if (endDate != '' && startDate == '') {
            url += 'beginning-of-time/' + encodeURI(endDate) + '/';
        }

        if ((startDate != '') && (endDate != '')) {
            url += encodeURI(startDate) + '/' + encodeURI(endDate) + '/';
        }

        if (keyword != '') {
            url += encodeURI(keyword) + '/';
        } else {
            url += encodeURI('all/');
        }

        if (job_sid != '' && job_sid != null) {
            url += encodeURI(job_sid) + '/';
        } else {
            url += encodeURI('all/');
        }

        if (applicant_type != '') {
            url += encodeURI(applicant_type) + '/';
        } else {
            url += encodeURI('all/');
        }

        if (applicant_status != '') {
            url += encodeURI(applicant_status);
        } else {
            url += encodeURI('all');
        }
        $('#form-filters').attr('action', url);

        $('#form-filters').submit();

    }

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