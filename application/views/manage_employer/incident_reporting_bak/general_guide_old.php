<?php if (!$load_view) { ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                <?php $this->load->view('main/employer_column_left_view'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                <div class="page-header-area margin-top">
                    <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                        <a href="<?php echo base_url('incident_reporting_system')?>" class="dashboard-link-btn"><i class="fa fa-angle-left"> </i> Incident Reporting System</a>
                        <?php echo $title; ?>
                    </span>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="btn-panel">
                            <a href="<?php echo base_url('incident_reporting_system/list_incidents')?>" class="btn btn-success"><i class="fa fa-heartbeat"></i> Reported Incidents</a>
                            <a href="<?php echo base_url('incident_reporting_system/assigned_incidents'); ?>" class="btn btn-success"><i class="fa fa-stethoscope "></i> Assigned  Incidents</a></a>
                            <a href="<?php echo base_url('incident_reporting_system/view_general_guide')?>" class="btn btn-success"><i class="fa fa-book"></i> Incident Guide </a>
                        </div>
                    </div>
                </div>

                <?php if (sizeof($gen_guide)>0) {
                    $i=1; ?>
                    <?php foreach($gen_guide as $guide){ ?>

                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                <b><?php echo $i . ". " . $guide['incident_name']?></b>
                            </table>
                        </div>
                    </div>

                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                <b>Instructions</b>
                                <tbody>
                                    <tr>
                                        <td><?= nl2br($guide['instructions']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="table-responsive table-outer">
                        <div class="table-wrp data-table">
                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                <b>Reasons</b>
                                <tbody>
                                    <tr>
                                        <td><?= nl2br($guide['reasons']) ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php $i++; }
                } else { ?>
                    <div id="show_no_jobs" class="table-wrp">
                        <span class="applicant-not-found">No Guide Presented Yet!</span>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<link rel="StyleSheet" type="text/css" href="<?= base_url(); ?>/assets/css/chosen.css"/>
<script language="JavaScript" type="text/javascript" src="<?= base_url(); ?>/assets/js/chosen.jquery.js"></script>

<script type="text/javascript">
    $("#inc-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            title: {
                required: true
            },
            nature_of_report: {
                required: true
            },
            who_did_inappropriate: {
                required: true
            },
            to_whom: {
                required: true
            },
            when_and_where: {
                required: true
            },
            was_it_isolated: {
                required: true
            },
            why_you_believe_above: {
                required: true
            },
            your_reaction: {
                required: true
            },
            any_witness: {
                required: true
            },
            spoken_to_anyone: {
                required: true
            },
            reported_to_supervisor: {
                required: true
            },
            avoid_future_incidents: {
                required: true
            }
        },
        messages: {
            Title: {
                required: 'Job title is required'
            },
            nature_of_report: {
                required: 'Nature of your report is required'
            },
            who_did_inappropriate: {
                required: 'This field is required'
            },
            to_whom: {
                required: 'This field is required'
            },
            when_and_where: {
                required: 'These details are required'
            },
            was_it_isolated: {
                required: 'These details are required'
            },
            why_you_believe_above: {
                required: 'These details are required'
            },
            your_reaction: {
                required: 'Your reaction is required'
            },
            any_witness: {
                required: 'Please provide this information'
            },
            spoken_to_anyone: {
                required: 'These details are required'
            },
            reported_to_supervisor: {
                required: 'Please provide this information'
            },
            avoid_future_incidents: {
                required: 'Please provide some suggestions'
            }
        }
    });

    $("#inc-form").submit(function(){
        var flag = 0;
        $(".multi-checkbox").each(function(index, element ){
            if($(this).attr('data-attr')!='0'){
                var key = "multi-list_"+$(this).attr('data-key');
                var name = "input:checkbox[name^='"+key+"']:checked";
                var checked = $(name).length;
                if(!checked){
                    alertify.error($(this).attr('data-value')+' is required');
                    flag = 1;
                    return false;
                }
            }
        });
        if(flag){
            return false;
        }
    });

    function check_file(val) {
        var fileName = $("#" + val).val();
        if (fileName.length > 0) {
            $('#name_' + val).html(fileName.substring(0, 45));
//            var ext = fileName.split('.').pop();
//            if (val == 'pictures') {
//                if (ext != "jpg" && ext != "jpeg" && ext != "png" && ext != "jpe" && ext != "JPG" && ext != "JPEG" && ext != "JPE" && ext != "PNG" && ext != "docs" && ext != "pdf") {
//                    $("#" + val).val(null);
//                    alertify.error("Please select a valid Image format.");
//                    $('#name_' + val).html('<p class="red">Only (.jpg .jpeg .png) allowed!</p>');
//                    return false;
//                } else
//                    return true;
//            }
        } else {
            $('#name_' + val).html('No file selected');
        }
    }
</script>
<?php } else { ?>
    <?php $this->load->view('manage_employer/incident_reporting/general_guide'); ?>
<?php } ?>