-<?php
    $company_name = ucwords($session['company_detail']['CompanyName']);
?>
<?php if (!$load_view) { ?>

    <div class="main-content">
        <div class="dashboard-wrp">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                                <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                                <div class="page-header-area margin-top">
                                    <span class="page-heading down-arrow">
                                        <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>
                                        <?php echo $title; ?></span>
                                </div>
                                <div class="dashboard-conetnt-wrp">
                                    <div class="form-wrp full-width">
                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                            <div class="page-header">
                                                <h2 class="section-ttile">Form W-4 (<?php echo date('Y'); ?>)</h2>
                                                <div class="row mb-2">
                                                    <div class="col-lg-3 pull-right">
                                                        <form action="<?php echo current_url()?>" method="get">
                                                            <?php if( isset($pre_form['user_consent']) && $pre_form['user_consent'] == 1){ ?>
                                                                <input class="btn btn-success btn-block" id="download-pdf" value="Download PDF" name="submit" type="submit">
                                                            <?php }?>
                                                        </form>
                                                    </div>
                                                    <!--                        <div class="col-lg-3 pull-right">-->
                                                    <!--                            <a target="_blank" href="--><?php //echo base_url('form_w9/preview_w9form/' . $pre_form['user_type'] . '/' . $pre_form['user_sid'])?><!--" class="btn btn-success btn-block">Preview</a>-->
                                                    <!--                        </div>-->
                                                </div>
                                            </div>
                                            <iframe srcdoc="<?php echo $view; ?>" width="500px" height="700px"></iframe>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->load->view($left_navigation); ?>

                </div>
            </div>
        </div>
    </div>
    <?php $this->load->view('static-pages/e_signature_popup'); ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.debug.js" integrity="sha384-CchuzHs077vGtfhGYl9Qtc7Vx64rXBXdIAZIPbItbNyWIRTdG0oYAqki3Ry13Yzu" crossorigin="anonymous"></script>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>

<script language="JavaScript" type="text/javascript">
    $("#w9-form").validate({
        ignore: ":hidden:not(select)",
        rules: {
            w4_first_name: {
                required: true
            },
            w4_middle_name: {
                required: true
            },
            user_consent: {
                required: true
            }
        },
        messages: {
            w4_first_name: {
                required: 'First Name is required'
            },
            w4_middle_name: {
                required: 'Middle Name is required'
            },
            user_consent: {
                required: true
            }
        },
        submitHandler: function(form){
//            var flag = 0;
//            var name = "input:checkbox[name^='w4_federaltax_classification']:checked";
//            var checked = $(name).length;
//            var check_val = $(name).val();
//            if(!checked){
//                alertify.error('Federal Tax Classification is required');
//                flag = 1;
//                return false;
//            }
//            if(check_val=='llc' && $('#llc_desc').val() == ''){
//                alertify.error('Enter The Tax Classification (C=C Corporation, S=S Corporation, P=Partnership)');
//                flag = 1;
//                return false;
//            }
//            if(check_val=='other' && $('#other_desc').val() == ''){
//                alertify.error('Enter Other Federal Tax Classification');
//                flag = 1;
//                return false;
//            }
//            if(flag){
//                return false;
//            }
//            else{
//            }
                form.submit();
        }
    });
    $(document).ready(function(){
        $('input[name=w4_federaltax_classification]').on('change', function() {
            $('input[name=w4_federaltax_classification]').not(this).prop('checked', false);
        });


        $('#first_date_of_employment').datepicker({
            dateFormat: 'mm-dd-yy',
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+50",
        }).val();
    });
    function func_save_e_signature() {

        var is_signature_exist = $('#signature_bas64_image').val();
        if(is_signature_exist == ""){
            alertify.error('Please Add Your Signature!');
            return false;
        }

        if ($('#w4-form').validate()) {
            alertify.confirm(
                'Are you Sure?',
                'Are you sure you want to Consent And Accept Electronic Signature Agreement?',
                function () {
                    $('#w4-form').submit();
                },
                function () {
                    alertify.error('Cancelled!');
                }).set('labels', {ok: 'I Consent and Accept!', cancel: 'Cancel'});
        }
    }

</script>
<?php }  else if ($load_view == 'new') { ?>
    <?php $this->load->view('form_w4/index_ems'); ?>
<?php } ?>

