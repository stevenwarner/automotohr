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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main" style="display: block;" >
                                    <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $fromEmail = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                    <label>From Email / name</label>
                                                    <input type="text" class="invoice-fields" name="from_email_or_name" id="from_email_or_name" value="<?php echo set_value('keyword', $fromEmail); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $email = $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>
                                                    <label>To Email</label>
                                                    <input type="text" class="invoice-fields" name="email" id="email" value="<?php echo set_value('keyword', $email); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label class="">Date From</label>
                                                    <?php $start_date = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                    <input class="invoice-fields"
                                                           placeholder=""
                                                           type="text"
                                                           name="start_date"
                                                           id="start_date"
                                                           value="<?php echo set_value('start_date', $start_date); ?>"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label class="">Date To</label>
                                                    <?php $end_date = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                                    <input class="invoice-fields"
                                                           placeholder=""
                                                           type="text"
                                                           name="end_date"
                                                           id="end_date"
                                                           value="<?php echo set_value('end_dat', $end_date); ?>"/>
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a id="btn_apply_filters" class="btn btn-success" href="#" >Apply Filters</a>
                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/notification_email_log'); ?>">Reset Filters</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered"><?php echo $page_title; ?></h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo $total_records; ?></h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records; ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-2 text-center">Date</th>
                                                                    <th class="col-xs-3 text-center">To Email</th>
                                                                    <th class="col-xs-4 text-center">Subject</th>
                                                                    <th class="col-xs-3 text-center" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($logs)) { ?>
                                                                    <?php foreach ($logs as $log) { ?>
                                                                        <tr>
                                                                            <td><?php echo date_with_time($log['sent_date']); ?></td>
                                                                            <td>
                                                                                <?php echo $log['receiver']; ?>
                                                                            </td>
                                                                            <td><?php echo $log['subject']; ?></td>
                                                                            <td class="col-xs-1"><a class="btn btn-success btn-sm btn-block" title="View" href="<?php echo base_url('manage_admin/notification_email_log_view'); ?>/<?php echo $log["sid"]; ?>">View</a></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <tr>
                                                                        <td class="text-center" colspan="5">
                                                                            <span class="no-data">No Email Found</span>
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
                                                    <span class="pull-left">
                                                        <p>Showing <?php echo $from_records; ?> to <?php echo $to_records; ?> out of <?php echo $total_records ?></p>
                                                    </span>
                                                    <span>
                                                        <?php echo $links; ?>
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
    </div>
</div>

<script>
    function resend(id) {
        alertify.dialog('confirm')
                .set({
                    'title ': 'Confirmation',
                    'labels': {ok: 'Yes', cancel: 'No'},
                    'message': 'Are you sure you want to Resend this Email?',
                    'onok': function () {
                        url = "<?php echo base_url('manage_admin/resend_email'); ?>" + '/' + id;
                        window.location.href = url;
                    },
                    'oncancel': function () {
                        alertify.error('Cancelled!');
                    }
                }).show();
    }

    function generate_url() {
        var fromEmailOrName = $('#from_email_or_name').val();
        var toEmail = $('#email').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();

        fromEmailOrName = fromEmailOrName != '' && fromEmailOrName != null && fromEmailOrName != undefined && fromEmailOrName != 0 ? encodeURIComponent(fromEmailOrName) : 'all';
        toEmail = toEmail != '' && toEmail != null && toEmail != undefined && toEmail != 0 ? encodeURIComponent(toEmail) : 'all';
        start_date = start_date != '' && start_date != null && start_date != undefined && start_date != 0 ? encodeURIComponent(start_date) : 'all';
        end_date = end_date != '' && end_date != null && end_date != undefined && end_date != 0 ? encodeURIComponent(end_date) : 'all';
        var url = '<?php echo base_url('manage_admin/notification_email_log'); ?>' + '/' + fromEmailOrName + '/' + toEmail + '/' + start_date + '/' + end_date;
        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {
        $('#btn_apply_filters').click(function () {
            generate_url();
        });

        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function (value) {
                $('#end_date').datepicker('option', 'minDate', value);
                generate_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date').val());

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>",
            onSelect: function (value) {
                $('#start_date').datepicker('option', 'maxDate', value);
                generate_url();
            }
        }).datepicker('option', 'minDate', $('#start_date').val());


        $('#keyword').trigger('keyup');
    });

</script>