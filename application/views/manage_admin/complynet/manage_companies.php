<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <h1 class="page-title">ComplyNet<br></h1><br>
                                        <a class="btn btn-success btn-block btn-equalizer" title="Add Company" href="javascript:;" onclick="showmodel()">Add Company</a>

                                    </div>
                                    <br />
                                    <br />

                                    <div class="row">
                                        <div class="col-xs-12">
                                            <div class="hr-box">
                                                <div class="hr-innerpadding">
                                                    <?php if (!empty($links)) { ?>
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
                                                                            <th class="col-xs-3">AutomotoHR</th>
                                                                            <th class="col-xs-3">Comply Net</th>
                                                                            <th class="col-xs-2">Created At</th>
                                                                            <th class="col-xs-2">Status</th>
                                                                            <th class="col-xs-2">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>
                                                                        <?php if (!empty($company_records)) { ?>
                                                                            <?php foreach ($company_records as $record) { ?>
                                                                                <tr>

                                                                                    <td><?php echo $record['automotohr_name']; ?></td>
                                                                                    <td><?php echo $record['complynet_name']; ?></td>
                                                                                    <td><?php //echo $record['created_at']; 
                                                                                        ?><?php echo DateTime::createfromformat('Y-m-d H:i:s', $record['created_at'])->format('M d Y, D H:i:s'); ?></td>
                                                                                    <td <?php echo  $record['status'] ? 'style="color:green;"' : 'style="color:red;"'; ?>><?php echo  $record['status'] ? 'Active' : 'In-Active'; ?></td>
                                                                                    <td>
                                                                                        <a class="btn btn-success btn-sm btn-block" title="View" href="javascript:;" onclick="showmodel()">View</a>
                                                                                        <?php if ($record['status']) { ?><a class="btn btn-success btn-sm btn-block" title="View" href="javascript:;" onclick="changestatus('0#'+'<?php echo $record['automotohr_sid'] ?>')">Disable</a><?php } else { ?>
                                                                                            <a class="btn btn-success btn-sm btn-block" title="View" href="javascript:;" onclick="changestatus('1#'+'<?php echo $record['automotohr_sid'] ?>')">Enable</a>

                                                                                        <?php } ?>
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
                                                    <?php if (!empty($links)) { ?>
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


<div id="bulk_email_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Company</h4>
            </div>
            <div class="modal-body">
                <div class="compose-message">

                    <form action="<?= base_url('manage_admin/complynet/mapcompany'); ?>" id="frmmapcompany" method="POST">
                        <div class="universal-form-style-v2">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <label>AutomotoHR</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="automotohrcompany" id="automotohrcompany">
                                            <option selected="selected" value="">--Please select a company--</option>
                                            <?php foreach ($active_companies as $automoto_row) { ?>
                                                <option value="<?php echo $automoto_row['sid']; ?>#<?php echo $automoto_row['CompanyName']; ?>#<?php echo $automoto_row['complynet_status']; ?>"><?php echo $automoto_row['CompanyName']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <label>Comply Net</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="complynetcompany" id="complynetcompany">
                                            <option selected="selected" value="">--Please select a company--</option>
                                            <option value="110#Test Company1">Test Company1</option>
                                            <option value="111#Test Company2">Test Company2</option>
                                            <option value="112#Test Company3">Test Company3</option>
                                            <option value="113#Test Company4">Test Company4</option>
                                            <option value="114#Test Company5">Test Company5</option>
                                        </select>
                                    </div>
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6"></div>
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="float: right;">
                                        <a class="btn btn-success btn-block btn-equalizer" title="Add Company" href="javascript:;" onclick="showmodel()">Cancel</a>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3" style="float: right;">
                                        <a class="btn btn-success btn-block btn-equalizer" title="Add Company" href="javascript:;" onclick="savecompany()">Save</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>

<!-- Loader Start -->
<div id="document_loader" class="text-center my_loader" style="display: none; z-index: 1234;">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i aria-hidden="true" class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text" id="loader_text_div" style="display:block; margin-top: 35px;"></div>
    </div>
</div>
<!-- Loader End -->


<script>
    $(document).ready(function() {
        $("#company_sid").val('<?php echo $companySid; ?>');
    });

    //
    function showmodel() {
        $('#bulk_email_modal').modal("toggle");
    }
    //
    function savecompany() {
        event.preventDefault();
        var automotohrcompany = $('#automotohrcompany').val();
        var complynetcompany = $('#complynetcompany').val();

        if (complynetcompany == '') {
            alertify.alert('Error!', 'Please select a comply net company.', function() {
                return
            });
            return;
        }
        if (automotohrcompany == '') {
            alertify.alert('Error!', 'Please select a automotoHR company.', function() {
                return;
            });
            return;
        }

        $("#frmmapcompany").submit();


    }


//
    function changestatus(companydata) {
        const companydataarray = companydata.split("#");
        $('#loader_text_div').text('Processing');
        $('#document_loader').show();
        $.ajax({
            'url': '<?php echo base_url('manage_admin/complynet/changecomplynetstatus'); ?>',
            'type': 'POST',
            'data': {
                'automotohr_sid': companydataarray[1],
                'complynet_status': companydataarray[0]
            },
            success: function(urls) {

                location.reload();

                $('#loader_text_div').text('');
                $('#document_loader').hide();

            }
        });



    }





    function getcompanydata(companyid, companyname) {
        $('#loader_text_div').text('Processing');
        $('#document_loader').show();

        $.ajax({
            'url': '<?php echo base_url('manage_admin/2022/reports/complynet/getcompanyemployees/'); ?>' + companyid,
            'type': 'GET',

            success: function(urls) {

                $('.modal-body').html(urls);
                $('#document_modal .modal-footer').html('footer_content');
                $('.modal-title').html('Company Name: ' + companyname);
                $('#bulk_email_modal').modal("toggle");
                $('#loader_text_div').text('');
                $('#document_loader').hide();

            }
        });


    }



    function func_apply_filters() {
        var company_sid = $('#company_sid').val();
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        var base_url = '<?php echo base_url('manage_admin/2022/reports/complynet'); ?>';

        company_sid = company_sid == '' || company_sid == undefined || company_sid == null ? 'all' : encodeURIComponent(company_sid);
        date_start = date_start == '' || date_start == undefined || date_start == null ? 'all' : encodeURIComponent(date_start);
        date_end = date_end == '' || date_end == undefined || date_end == null ? 'all' : encodeURIComponent(date_end);

        var url = base_url + '/' + company_sid + '/' + date_start + '/' + date_end;

        window.location = url;
    }
</script>


<style>
    .modal-backdrop {
        z-index: 1;
    }

    .universal-form-style-v2 ul li label,
    .universal-form-style-v2 form label {
        float: none !important;
    }
</style>