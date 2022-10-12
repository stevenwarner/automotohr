<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>.tab-content > .tab-pane{ display: block !important; }</style>
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
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">

                                    </div>
                                    <div class="tabs-outer">
                                        <ul class="nav nav-tabs">
                                            <li class="cards-tabs active" data-attr="active_cards" ><a  href="<?=base_url('manage_admin/companies/manage_complynet_new/'.$company_sid);?>">Add New</a></li>
                                            <li class="cards-tabs" data-attr="inactive_cards" ><a  href="<?=base_url('manage_admin/companies/manage_complynet/'.$company_sid);?>">Old</a></li>
                                            </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="js-tab-pane">
                                                    <div id="show_no_jobs" class="table-wrp">
                                                       <table class="table table-bordered table-hover table-striped table-condensed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-xs-3">AutomotoHR</th>
                                                                            <th class="col-xs-3">ComplyNet</th>
                                                                            <th class="col-xs-2">Created At</th>
                                                                            <th class="col-xs-2">Status</th>
                                                                            <th class="col-xs-2">Action</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <thead>
                                                                        <?php if (!empty($maped_company_info)) { ?>
                                                                                <tr>

                                                                                    <td><?php echo $maped_company_info->automotohr_name; ?></td>
                                                                                    <td><?php echo $maped_company_info->complynet_name; ?></td>
                                                                                    <td><?php echo DateTime::createfromformat('Y-m-d H:i:s', $maped_company_info->created_at)->format('M d Y, D H:i:s'); ?></td>
                                                                                    <td <?php echo  $maped_company_info->status ? 'style="color:green;"' : 'style="color:red;"'; ?>><?php  echo $maped_company_info->status ? 'Active' : 'In-Active'; ?></td>
                                                                                    <td>
                                                                                        <a class="btn btn-success btn-sm btn-block" title="View" href="javascript:;" onclick="getcompanydata('<?php echo $maped_company_info->automotohr_sid?>', '<?php echo $maped_company_info->automotohr_name?>')">View</a>
                          
                                                                                    </td>
                                                                                </tr>
                                                                        <?php } else { ?>
                                                                            <tr>
                                                                                <td colspan="7" class="text-center">
                                                                                    <span class="no-data">                                            <a class="btn btn-success btn-block btn-equalizer" title="Add Company" href="javascript:;" onclick="showmodel()">Add Company</a>
</span>
                                                                                </td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </thead>
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

                    <form id="frmmapcompany">
                        <div class="universal-form-style-v2">

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6" style="color:red; display: none;" id="companymapederror">
                                    Companies already Maped please select different companies
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <label>AutomotoHR</label>
                                    <div class="hr-select-dropdown">
                                        <select class="invoice-fields" name="automotohrcompany" id="automotohrcompany">
                                            <?php foreach ($active_companies as $automoto_row) { ?>
                                                <option value="<?php echo $automoto_row['sid']; ?>#<?php echo $automoto_row['CompanyName']; ?>#<?php echo $automoto_row['complynet_status']; ?>"><?php echo $automoto_row['CompanyName']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                    <label>ComplyNet</label>
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



<script type="text/javascript">
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

        $('#loader_text_div').text('Processing');
        $('#document_loader').show();
        $.ajax({
            'url': '<?php echo base_url('manage_admin/complynet/mapcompany'); ?>',
            'type': 'POST',
            'data': {
                'automotohrcompany': automotohrcompany,
                'complynetcompany': complynetcompany
            },
            success: function(data) {

                if (data == 'alradyexist') {
                    $('#companymapederror').show();
                    $('#loader_text_div').text('');
                    $('#document_loader').hide();

                } else {
                    location.reload();
                }

            }
        });


    }


    function getcompanydata(companyid, companyname) {
        $('#loader_text_div').text('Processing');
        $('#document_loader').show();

        $.ajax({
            'url': '<?php echo base_url('manage_admin/complynet/getcompanyemployees/'); ?>' + companyid,
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