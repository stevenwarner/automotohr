<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <form id="form_export_employees" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="export_applicants" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <?php $keyword = $this->uri->segment(2) != 'all' ? urldecode($this->uri->segment(2)) : ''; ?>
                                                    <label>Keyword</label>
                                                    <input placeholder="" class="invoice-fields" type="text" id="keyword" name="keyword" value="<?php echo set_value('keyword', $keyword); ?>" />
                                                </div>
                                            </div>
                                            <!-- jobs div -->
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <?php $job_sid = $this->uri->segment(3) != 'all' ? $this->uri->segment(3) : ''; ?>
                                                    <label>Job</label>
                                                    <!--<div class="hr-select-dropdown ">-->
                                                    <select class="chosen-select" multiple="multiple" name="job_sid[]" id="job_sid">
                                                        <?php if (!empty($jobOptions)) { ?>
                                                            <option value="all" <?php if (in_array('all', $job_sid_array)) { ?> selected="selected" <?php } ?>>All Jobs</option>
                                                            <?php foreach ($jobOptions as $key => $value) { ?>
                                                                <option value="<?= $key ?>" <?php if (in_array($key, $job_sid_array)) { ?> selected="selected" <?php } ?>>
                                                                    <?php echo $value; ?>
                                                                </option>
                                                                <!--                                                                    <option --><?php //echo set_select('job_sid', $key, $job_sid == $key); 
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
                                                    <!--</div>-->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label>Applicant Type</label>
                                                    <?php $applicant_type = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
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
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label>Applicant Status</label>
                                                    <?php $applicant_status = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="applicant_status" id="applicant_status">
                                                            <?php if (!empty($applicant_statuses)) { ?>
                                                                <option value="all">All</option>
                                                                <?php foreach ($applicant_statuses as $status) { ?>
                                                                    <option <?php echo set_select('applicant_status', $status['name'], $applicant_status == $status['name']); ?> value="<?php echo $status['name']; ?>"><?php echo $status['name']; ?></option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option <?php echo $applicant_status == 'all' ? 'selected="selected"' : ''; ?> value="all">All</option>
                                                                <option <?php echo $applicant_status == 'Not Contacted Yet' ? 'selected="selected"' : ''; ?> value="Not Contacted Yet">Not Contacted Yet</option>
                                                                <option <?php echo $applicant_status == 'Left Message' ? 'selected="selected"' : ''; ?> value="Left Message">Left Message</option>
                                                                <option <?php echo $applicant_status == 'Contacted' ? 'selected="selected"' : ''; ?> value="Contacted">Contacted</option>
                                                                <option <?php echo $applicant_status == 'Candidate Responded' ? 'selected="selected"' : ''; ?> value="Candidate Responded">Candidate Responded</option>
                                                                <option <?php echo $applicant_status == 'Interviewing' ? 'selected="selected"' : ''; ?> value="Interviewing">Interviewing</option>
                                                                <option <?php echo $applicant_status == 'Submitted' ? 'selected="selected"' : ''; ?> value="Submitted">Submitted</option>
                                                                <option <?php echo $applicant_status == 'Qualifying' ? 'selected="selected"' : ''; ?> value="Qualifying">Qualifying</option>
                                                                <option <?php echo $applicant_status == 'Ready to Hire' ? 'selected="selected"' : ''; ?> value="Ready to Hire">Ready to Hire</option>
                                                                <option <?php echo $applicant_status == 'Offered Job' ? 'selected="selected"' : ''; ?> value="Offered Job">Offered Job</option>
                                                                <option <?php echo $applicant_status == 'Client Declined' ? 'selected="selected"' : ''; ?> value="Client Declined">Client Declined</option>
                                                                <option <?php echo $applicant_status == 'Not In Consideration' ? 'selected="selected"' : ''; ?> value="Not In Consideration">Not In Consideration</option>
                                                                <option <?php echo $applicant_status == 'Future Opportunity' ? 'selected="selected"' : ''; ?> value="Future Opportunity">Future Opportunity</option>
                                                            <?php } ?>
                                                        </select>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label class="">Date From</label>
                                                    <?php $start_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y'); ?>
                                                    <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="start_date_applied" id="start_date_applied" value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label class="">Date To</label>
                                                    <?php $end_date = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-d-Y'); ?>
                                                    <input class="invoice-fields" placeholder="<?php echo date('m-d-Y'); ?>" type="text" name="end_date_applied" id="end_date_applied" value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                <!--                                                <label>Applicant Type:</label>-->
                                                <!--                                                <div class="hr-select-dropdown autoheight">-->
                                                <!--                                                    <select data-rule-required="true" class="invoice-fields" name="applicant_type" id="applicant_type">-->
                                                <!--                                                        <option value="">Please Select</option>-->
                                                <!--                                                        <option value="all">All Applicants</option>-->
                                                <!--                                                        <option value="active">Active Applicants</option>-->
                                                <!--                                                        <option value="archived">Archived Applicants</option>-->
                                                <!--                                                    </select>-->
                                                <!--                                                </div>-->
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-block btn-success">Export</button>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </form>
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
    //    $(document).ready(function () {
    //        $('#form_export_employees').validate();
    //    });

    $(document).keypress(function(e) {
        if (e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url() {
        var keyword = $('#keyword').val();
        var job_sid = $('#job_sid').val();
        var applicant_type = $('#applicant_type').val();
        var applicant_status = $('#applicant_status').val();
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();

        keyword = keyword != '' && keyword != null && keyword != undefined && keyword != 0 ? encodeURIComponent(keyword) : 'all';
        job_sid = job_sid != '' && job_sid != null && job_sid != undefined && job_sid != 0 ? encodeURIComponent(job_sid) : 'all';
        applicant_type = applicant_type != '' && applicant_type != null && applicant_type != undefined && applicant_type != 0 ? encodeURIComponent(applicant_type) : 'all';
        applicant_status = applicant_status != '' && applicant_status != null && applicant_status != undefined && applicant_status != 0 ? encodeURIComponent(applicant_status) : 'all';
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';


        var url = '<?php echo base_url('export_applicants_csv/'); ?>' + '/' + keyword + '/' + job_sid + '/' + applicant_type + '/' + applicant_status + '/' + start_date_applied + '/' + end_date_applied;

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

        $('#job_sid').on('change', function(value) {
            generate_search_url();
        });
        $('#applicant_type').on('change', function(value) {
            generate_search_url();
        });
        $('#applicant_status').on('change', function(value) {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy'
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
</script>