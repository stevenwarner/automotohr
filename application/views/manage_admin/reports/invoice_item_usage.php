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
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $title; ?></h1>
                                    </div>
                                    <br />
                                    <br />
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-search-criteria opened">
                                                <strong>Click to modify search criteria</strong>
                                            </div>
                                            <div class="hr-search-main search-collapse-area" style="display: block;" >
                                                <div class="row">
                                                    <div class="col-xs-4">
                                                        <?php $company_sid = $this->uri->segment(4); ?>
                                                        <div class="field-row">
                                                            <label>Company</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields" id="company_sid" name="company_sid">
                                                                    <option value="all">All Companies</option>
                                                                    <?php foreach($companies as $company) { ?>
                                                                        <option <?php echo $company_sid == $company['sid'] ? 'selected="selected"' : ''; ?> value="<?php echo $company['sid']?>"><?php echo ucwords($company['CompanyName']); ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <?php $start = $this->uri->segment(5); ?>
                                                        <?php $start = empty($start) || is_null($start) ?  date('m-01-Y') : $start; ?>
                                                        <div class="field-row">
                                                            <label>Date From:</label>
                                                            <input class="invoice-fields datepicker" id="date_start" name="date_start" value="<?php echo $start; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-3">
                                                        <?php $end = $this->uri->segment(6); ?>
                                                        <?php $end = empty($end) || is_null($end) ?  date('m-t-Y') : $end; ?>
                                                        <div class="field-row">
                                                            <label>Date To:</label>
                                                            <input class="invoice-fields datepicker" id="date_end" name="date_end" value="<?php echo $end; ?>" />
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-2">
                                                        <div class="field-row">
                                                            <label>&nbsp;</label>
                                                            <button type="button" class="btn btn-success btn-block btn-equalizer" onclick="func_apply_filters();">Search</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <?php if(!empty($links)) { ?>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php echo $links; ?>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                    <?php } ?>
                                                    <div class="row">
                                                        <div class="col-xs-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover table-striped table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-2">Invoice</th>
                                                                            <th class="col-xs-3">Product</th>
                                                                            <th class="col-xs-2">Bought By</th>
                                                                            <th class="col-xs-1">Used By</th>
                                                                            <th class="col-xs-4">Used Against Job</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>
                                                                    <?php if(!empty($item_track_records)) { ?>
                                                                        <?php foreach($item_track_records as $record) { ?>
                                                                            <tr>
                                                                                <td>
                                                                                    <a target="_blank" href="<?php echo base_url('manage_admin/invoice/edit_invoice/' . $record['invoice_sid']); ?>"><?php echo 'MP-' . str_pad($record['invoice_sid'],6,0,STR_PAD_LEFT);?></a>
                                                                                    <div class="text-muted">
                                                                                        <small><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $record['date_purchased'])->format('m-d-Y h:i A'); ?></small>
                                                                                    </div>
                                                                                </td>
                                                                                <td><?php echo $record['product_name']; ?></td>
                                                                                <td>
                                                                                    <div class="text-success"><?php echo ucwords($record['bought_by_first_name'] . ' ' . $record['bought_by_last_name']); ?></div>
                                                                                    <div class="text-muted"><small><?php echo ucwords($record['company_name']); ?></small></div>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo ucwords($record['used_by_first_name'] . ' ' . $record['used_by_last_name']); ?>
                                                                                </td>
                                                                                <td>
                                                                                    <?php echo $record['job_title']; ?>
                                                                                    <div class="text-muted"><small><?php echo DateTime::createFromFormat('Y-m-d H:i:s', $record['used_date'])->format('m-d-Y  h:i A'); ?></small></div>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    <?php } else { ?>
                                                                        <tr>
                                                                            <td colspan="7" class="text-center">
                                                                                <span class="no-data">No Records</span>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                    </thead>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if(!empty($links)) { ?>
                                                        <hr />
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <?php echo $links; ?>
                                                            </div>
                                                        </div>
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
<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
                changeYear: true,
                yearRange: "<?php echo DOB_LIMIT; ?>"
        });
    });

    function func_apply_filters()
    {
        var company_sid = $('#company_sid').val();
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var base_url = '<?php echo base_url('manage_admin/reports/invoice_item_usage/'); ?>';

        company_sid = company_sid == '' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        date_start = date_start == '' || date_start == undefined || date_start == null ? 'all' : encodeURIComponent(date_start);
        date_end = date_end == '' || date_end == undefined || date_end == null ? 'all' : encodeURIComponent(date_end);

        var url = base_url + '/'+ company_sid + '/' + date_start + '/' + date_end ;

        window.location = url;
    }
</script>