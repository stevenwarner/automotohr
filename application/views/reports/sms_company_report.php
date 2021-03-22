<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                                    <a href="<?php echo base_url('reports'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Back</a>
                                        <?php echo $title; ?>
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="hr-search-criteria">
                                        <strong>Click to modify search criteria</strong>
                                    </div>
                                    <div class="hr-search-main search-collapse-area">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label for="year">Start Date</label>
                                                    <?php $start_date = $this->uri->segment(5); ?>
                                                    <input type="text" name="start" value="<?= $start_date; ?>" class="invoice-fields" id="startdate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label for="month">End Date</label>
                                                    <?php $end_date = $this->uri->segment(6); ?>
                                                    <input type="text" name="end" value="<?= $end_date; ?>" class="invoice-fields" id="enddate" readonly>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="field-row">
                                                    <label for="month">&nbsp;</label>
                                                    <a href="" class="btn btn-equalizer btn-success btn-block" id="search_btn">Search</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hr-box">
                                <div class="hr-box-header bg-header-green">
                                    <span class="hr-registered">SMS Service History </span>
                                </div>
                                <div class="hr-box-body hr-innerpadding">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left">Receiver Name</th>
                                                            <th class="text-left">Sender Name</th>
                                                            <th class="text-center">Message</th>
                                                            <th class="text-center">Status</th>
                                                            <th class="text-center">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if(!empty($sms_data)) { ?>
                                                            <?php foreach($sms_data as $sms) { ?>
                                                                <tr>
                                                                    <td class="text-left">
                                                                        <?php echo $sms['receiver_name']; ?> <br />
                                                                        <?php echo $sms['receiver_phone_number']; ?>
                                                                    </td>
                                                                    <td class="text-left">
                                                                        <?php echo $sms['sender_name']; ?> <br />
                                                                        <?php echo $sms['sender_phone_number']; ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo $sms['message']; ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php echo $sms['read']; ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        $<?php echo $sms['amount']; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td colspan="5" class="text-center">
                                                                    <span class="no-data">No SMS Record Found</span>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td class="text-left" colspan="4">
                                                                <strong>Total</strong>
                                                            </td>
                                                            <td class="text-right">
                                                                <strong>$<?php echo $grand_total;?></strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
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
    $(document).ready(function () {
        function generate_search_url(){
            var start_date = $('#startdate').val();
            var end_date = $('#enddate').val();

            start_date = start_date != '' && start_date != null && start_date != undefined && start_date != 0 ? encodeURIComponent(start_date) : 'all';
            end_date = end_date != '' && end_date != null && end_date != undefined && end_date != 0 ? encodeURIComponent(end_date) : 'all';

            var myUrl = '<?php echo base_url("reports/company_sms_report")?>' + '/' + start_date + '/' + end_date;

            console.log(myUrl);
            $('#search_btn').attr('href', myUrl);
        }

    	$('#startdate').datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function (value) { //console.log(value);
                $('#enddate').datepicker('option', 'minDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'maxDate', $('#enddate').val());

        $('#enddate').datepicker({
            dateFormat: 'dd-mm-yy',
            onSelect: function (value) { //console.log(value);
                $('#startdate').datepicker('option', 'maxDate', value);
                generate_search_url();
            }
        }).datepicker('option', 'minDate', $('#startdate').val());

    });
</script>