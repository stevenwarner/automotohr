<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <form action="<?= base_url('employee_profile_data_report'); ?>">

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                        <div class="heading-title page-title">
                                            <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        </div>
                                        <div class="hr-search-criteria <?= $flag ? 'opened' : "" ?>">
                                            <strong>Click to modify search criteria</strong>
                                        </div>
                                        <div class="hr-search-main" <?= $flag ? "style='display:block'" : "" ?>>
                                            <div class="row">
                                                <div class="col-xs-12">


                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>Start Date </label>
                                                            <?php $start_date = $this->uri->segment(2) != 'all' && $this->uri->segment(2) != '' ? urldecode($this->uri->segment(2)) : ''; ?>
                                                            <input class="invoice-fields" type="text" readonly
                                                                name="start_date_applied" id="start_date_applied"
                                                                value="<?php echo set_value('start_date_applied', $start_date); ?>" />
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                        <div class="field-row">
                                                            <label>End Date</label>
                                                            <?php $end_date = $this->uri->segment(3) != 'all' && $this->uri->segment(3) != '' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                            <input class="invoice-fields" type="text" readonly
                                                                name="end_date_applied" id="end_date_applied"
                                                                value="<?php echo set_value('end_date_applied', $end_date); ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8 field-row">
                                                    </div>

                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <button class="btn btn-success btn-block" style="padding: 9px;" id="btn_apply_filters">Search</button>
                                                    </div>
                                                    <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 field-row">
                                                        <label>&nbsp;</label>
                                                        <a href="<?php echo base_url('employee_profile_data_report'); ?>" class="btn black-btn btn-block" style="padding: 9px;">Reset Search</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </form>








                            <div class="hr-box" id="print_div">
                                <div class="hr-box-header bg-header-green">
                                    <span class="pull-left">
                                        <h1 class="hr-registered">Employee Profile Update</h1>
                                    </span>
                                    <span class="pull-right">
                                        <h1 class="hr-registered">Total Records Found :
                                            <?php echo $applicants_count; ?>
                                        </h1>
                                    </span>
                                </div>
                                <div class="hr-innerpadding">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <span class="pull-left">
                                                <p>Showing <?php echo $from_records; ?> to
                                                    <?php echo $to_records; ?> out of
                                                    <?php echo $applicants_count ?>
                                                </p>
                                            </span>
                                            <span class="pull-right"
                                                style="margin-top: 20px; margin-bottom: 20px;">
                                                <?php echo $page_links ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="table-responsive hr-innerpadding">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Company</th>
                                                            <th>Employee</th>
                                                            <th>Column</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($reportData)) { ?>
                                                            <?php foreach ($reportData as $record) {
                                                                $profileData = json_decode($record['profile_data'], true);
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $record['CompanyName']; ?></td>
                                                                    <td>
                                                                        <strong><?= remakeEmployeeName($record); ?></strong>
                                                                        <br>
                                                                        <br>
                                                                        <p><?= formatDateToDB($record['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME); ?></p>
                                                                    </td>
                                                                    <td>
                                                                        <table class="table table-striped table-bordered">
                                                                            <thead>
                                                                                <th>Type</th>
                                                                                <th>Old Value</th>
                                                                                <th>New Value</th>
                                                                            </thead>
                                                                            <?php if ($profileData['job_title']) : ?>
                                                                                <tr>
                                                                                    <th>Job Title</th>
                                                                                    <td class="bg-danger"><?= $profileData['job_title']['old']; ?></td>
                                                                                    <td class="bg-success"><?= $profileData['job_title']['new']; ?></td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                            <?php if ($profileData['email']) : ?>
                                                                                <tr>
                                                                                    <th>Email</th>
                                                                                    <td class="bg-danger"><?= $profileData['email']['old']; ?></td>
                                                                                    <td class="bg-success"><?= $profileData['email']['new']; ?></td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="6">
                                                                    No data found <?php echo $source; ?>
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
                                        <div class="col-xs-12">
                                            <span class="pull-right">
                                                <?php echo $page_links ?>
                                            </span>
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

<script>
    function generate_search_url() {
        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        //
        var url = '<?php echo base_url('employee_profile_data_report/'); ?>' + start_date_applied + '/' + end_date_applied;
        //
        $('#btn_apply_filters').attr('href', url);
    }


    function generate_search_url_export() {

        var start_date_applied = $('#start_date_applied').val();
        var end_date_applied = $('#end_date_applied').val();
        //
        start_date_applied = start_date_applied != '' && start_date_applied != null && start_date_applied != undefined && start_date_applied != 0 ? encodeURIComponent(start_date_applied) : 'all';
        end_date_applied = end_date_applied != '' && end_date_applied != null && end_date_applied != undefined && end_date_applied != 0 ? encodeURIComponent(end_date_applied) : 'all';
        //
        var url = '<?php echo base_url('employee_profile_data_report/export/'); ?>' + start_date_applied + '/' + end_date_applied;
        //
        $('#btn_apply_filters_export').attr('href', url);
    }

    //
    $(document).ready(function() {
        $('.btn-link').hover(function() {
            $(this).popover('show');
        }, function() {
            $(this).popover('hide');
        });
        $('#btn_apply_filters').click(function(e) {
            var company_sid = $('#company_sid').val();
            //
            if (company_sid == '') {
                alertify.error('Please select the company');
                return false;
            }
            //
            e.preventDefault();
            generate_search_url();
            window.location = $(this).attr('href').toString();
        });


        $('#btn_apply_filters_export').click(function(e) {
            e.preventDefault();
            generate_search_url_export();
            window.location = $(this).attr('href').toString();
        });


        $('#status').on('change', function(value) {
            generate_search_url();
        });

        $('#company_sid').on('change', function(value) {
            generate_search_url();
        });

        $('#keyword').on('keyup', function() {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('#indeed_ats_id').on('keyup', function() {
            generate_search_url();
        });

        $('#indeed_ats_id').trigger('keyup');

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


        // Search Area Toggle Function
        jQuery('.hr-search-criteria').click(function() {
            jQuery(this).next().slideToggle('1000');
            jQuery(this).toggleClass("opened");
        });

        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        }).val();
    });
</script>