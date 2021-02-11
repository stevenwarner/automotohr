<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_pto_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow">Export Time off</span>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <form id="form_export_employees" class="form-wrp" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <input type="hidden" id="perform_action" name="perform_action" value="export_timeoff" />
                                        <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_sid; ?>" />
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Employee(s)</label>
                                                    <select class="form-control" name="app_sid" id="app_sid">
                                                        <?php if (!empty($allEmp)) { ?>
                                                            <option value="all" <?php if (in_array('all', $allEmp)) { ?> selected="selected" <?php } ?>>All Employees</option>
                                                            <?php foreach ($allEmp as $value) { ?>
                                                                <option value="<?= $value['sid'] ?>">
                                                                    <?php echo remakeEmployeeName($value, true); ?>
                                                                </option>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <option value="">No employee found</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Status</label>
                                                    <select class="form-control" multiple="multiple" name="status[]" id="status">
                                                        <option value="all" selected>All</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                        <option value="cancelled">Canceled</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label>Archive</label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="form-control" name="archive" id="archive">
                                                            <option value="0">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label class="">Date From</label>
                                                    <?php $start_date = $this->uri->segment(6) != 'all' && $this->uri->segment(6) != '' ? urldecode($this->uri->segment(6)) : date('m-d-Y');?>
                                                    <input class="form-control"
                                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                                           type="text"
                                                           name="start_date_applied"
                                                           id="start_date_applied"
                                                           value="<?php echo set_value('start_date_applied', $start_date); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label class="">Date To</label>
                                                    <?php $end_date = $this->uri->segment(7) != 'all' && $this->uri->segment(7) != '' ? urldecode($this->uri->segment(7)) : date('m-d-Y');?>
                                                    <input class="form-control"
                                                           placeholder="<?php echo date('m-d-Y'); ?>"
                                                           type="text"
                                                           name="end_date_applied"
                                                           id="end_date_applied"
                                                           value="<?php echo set_value('end_date_applied', $end_date); ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 col-xs-3 col-sm-3 col-sm-offset-9">
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
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>assets/js/chosen.jquery.js"></script>
<script>
//    $(document).ready(function () {
//        $('#form_export_employees').validate();
//    });

    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url(){
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

    $(document).ready(function () {

        $('#app_sid').select2();
        $('#status').select2();

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        $('#job_sid').on('change',function (value){
            generate_search_url();
        });
        $('#applicant_type').on('change',function (value) {
            generate_search_url();
        });
        $('#applicant_status').on('change',function (value) {
            generate_search_url();
        });

        $('#keyword').on('keyup', function () {
            generate_search_url();
        });

        $('#keyword').trigger('keyup');

        $('.datepicker').datepicker({dateFormat: 'mm-dd-yy'}).val();

        $('#start_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
            onSelect: function (value) {
                //console.log(value);
                $('#end_date_applied').datepicker('option', 'minDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date_applied').val());

        $('#end_date_applied').datepicker({
            dateFormat: 'mm-dd-yy',
            changeYear: true,
            changeMonth: true,
            onSelect: function (value) {
                //console.log(value);
                $('#start_date_applied').datepicker('option', 'maxDate', value);

                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#start_date_applied').val());

    });
</script>