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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Call Logs</h1>
                                    </div>
                                    <div class="hr-search-criteria opened">
                                        <strong>Click to modify search criteria</strong>
                                    </div>


                                    <div class="hr-search-main" style="display: block;">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $user_name = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                    <label>Table Name</label>
                                                    <select class="invoice-fields" name="table_name" id="logTableName">
                                                        <option value="payroll_calls">payroll_calls</option>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>Post Type</label>
                                                    <select class="invoice-fields" name="post_type" id="postType">
                                                        <option value="GET">GET</option>
                                                        <option value="POST">POST</option>

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <?php $name_search = $this->uri->segment(7) != 'all' ? urldecode($this->uri->segment(7)) : ''; ?>
                                                    <label>Response Code</label>
                                                    <input type="text" class="invoice-fields" name="response_code" id="responseCode" value="<?php echo set_value('name_search', $name_search); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label class="">Date From</label>
                                                    <?php $start_date = $this->uri->segment(5) != 'all' ? urldecode($this->uri->segment(5)) : ''; ?>
                                                    <input class="invoice-fields" placeholder="" type="text" name="start_date" id="start_date" value="<?php echo set_value('start_date', $start_date); ?>" />
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <div class="field-row">
                                                    <label class="">Date To</label>
                                                    <?php $end_date = $this->uri->segment(6) != 'all' ? urldecode($this->uri->segment(6)) : ''; ?>
                                                    <input class="invoice-fields" placeholder="" type="text" name="end_date" id="end_date" value="<?php echo set_value('end_dat', $end_date); ?>" />
                                                </div>
                                            </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a id="btn_apply_filters" class="btn btn-success" href="#">Apply Filters</a>
                                                <a class="btn btn-success" href="<?php echo base_url('manage_admin/call_logs'); ?>">Reset Filters</a>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Call Logs</h1>
                                            </span>
                                            <span class="pull-right">
                                                <h1 class="hr-registered">Total Records Found : <?php echo $total_records; ?></h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
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
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th class="col-xs-1 text-center">Request Method</th>
                                                                    <th class="col-xs-2 text-center">Request URL</th>
                                                                    <th class="col-xs-3 text-center">Response Code</th>
                                                                    <th class="col-xs-4 text-center">Created At</th>
                                                                    <th class="col-xs-2 text-center" colspan="2">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php if (!empty($logs)) { ?>
                                                                    <?php foreach ($logs as $log) { ?>
                                                                        <tr>
                                                                            <td><?= date_with_time($log['date']); ?></td>
                                                                            <td>
                                                                                <?= $log['username'] ?>
                                                                                <?php if ($log['resend_flag']) {
                                                                                    echo '<br> <b>Resent On: </b>' . date_with_time($log['resend_date']);
                                                                                } ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php $length_start = strpos($log['message'], 'Dear');
                                                                                $add_start_length = 4;

                                                                                if (!$length_start) {
                                                                                    $length_start = strpos($log['message'], 'Hi');
                                                                                    $add_start_length = 2;
                                                                                }

                                                                                if ($length_start) {
                                                                                    $message_start = substr($log['message'], $length_start + $add_start_length);

                                                                                    if ($add_start_length == 4 || $add_start_length == 2) {
                                                                                        $length_end = strpos($message_start, ',');
                                                                                    }

                                                                                    if (!$length_end) {
                                                                                        $length_end = strpos($message_start, '<');
                                                                                    }

                                                                                    $message_name = substr($message_start, 0, $length_end);
                                                                                    echo '<b>' . strip_tags($message_name) . '</b><br>';
                                                                                } ?>
                                                                                <?php echo $log['email']; ?>
                                                                            </td>
                                                                            <td><?= $log['subject'] ?></td>
                                                                            <td class="col-xs-1"><a class="btn btn-success btn-sm btn-block" title="View" href="<?= base_url('manage_admin/call_logs') ?>/<?= $log["sid"] ?>">View</a></td>
                                                                            <td class="col-xs-1"><a class="btn btn-success btn-sm btn-block" title="Resend" id="<?= $log["sid"] ?>" onclick="return resend(this.id);">Resend</a></td>
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
    
    $("#logTableName").select2();
    $("#postType").select2();
 
    function generate_url() {
        var logTableName = $('#logTableName').val();
        var postType = $('#postType').val();
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        var responseCode = $('#responseCode').val();

        logTableName = logTableName != '' && logTableName != null && logTableName != undefined && logTableName != 0 ? encodeURIComponent(logTableName) : 'all';
        postType = postType != '' && postType != null && postType != undefined && postType != 0 ? encodeURIComponent(postType) : 'all';
        start_date = start_date != '' && start_date != null && start_date != undefined && start_date != 0 ? encodeURIComponent(start_date) : 'all';
        end_date = end_date != '' && end_date != null && end_date != undefined && end_date != 0 ? encodeURIComponent(end_date) : 'all';
        responseCode = responseCode != '' && responseCode != null && responseCode != undefined && responseCode != 0 ? encodeURIComponent(responseCode) : 'all';

        var url = '<?php echo base_url('manage_admin/call_logs'); ?>' + '/' + logTableName + '/' + postType + '/' + start_date + '/' + end_date + '/' + responseCode;
        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function() {
        $('#btn_apply_filters').click(function() {
            generate_url();
        });

        $('#start_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function(value) {
                $('#end_date').datepicker('option', 'minDate', value);
                generate_url();
            }
        }).datepicker('option', 'maxDate', $('#end_date').val());

        $('#end_date').datepicker({
            dateFormat: 'mm-dd-yy',
            onSelect: function(value) {
                $('#start_date').datepicker('option', 'maxDate', value);
                generate_url();
            }
        }).datepicker('option', 'minDate', $('#start_date').val());
       
        $('#keyword').trigger('keyup');
    });
</script>