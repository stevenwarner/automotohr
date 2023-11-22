<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="applicant-profile-wrp">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $(".tab_content").hide();
                            $(".tab_content:first").show();
                            $("ul.tabs li").click(function() {
                                $("ul.tabs li").removeClass("active");
                                $(this).addClass("active");
                                $(".tab_content").hide();
                                var activeTab = $(this).attr("rel");
                                $("#" + activeTab).fadeIn();
                            });
                        });
                    </script>
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>
                    <div id="HorizontalTab" class="HorizontalTab">

                        <div class="resp-tabs-container hor_1">
                            <div id="tab1" class="tabs-content">
                                <div id="jsPrimaryEmployeeBox">

                                    <div class="universal-form-style-v2 info_edit">
                                        <form id="save_jobinfo" method="POST" enctype="multipart/form-data">
                                            <div class="form-title-section"><br>
                                                <h2>Add Job Compensation</h2>
                                                <div class="text-right">
                                                    <input type="button" value="Save" onclick="submitResult();" class="btn btn-success">
                                                    <a href="<?php echo base_url('job_info_edit/') .  $jobTitleData["sid"]; ?>" class="btn btn-danger">Cancel</a>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 form-group">
                                                    <p class="text-danger csF16">
                                                        <strong>
                                                            <em>
                                                                Note: The FLSA status for this compensation. Salaried ('Exempt') employees are paid a fixed salary every pay period. Salaried with overtime ('Salaried Nonexempt') employees are paid a fixed salary every pay period, and receive overtime pay when applicable. Hourly ('Nonexempt') employees are paid for the hours they work, and receive overtime pay when applicable. Owners ('Owner') are employees that own at least twenty percent of the company. </em>
                                                        </strong>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3 form-group">
                                                    <label>FLSA Status:</label>
                                                    <select class="invoice-fields" id="flsa" name="flsa">
                                                        <option value="Exempt">Exempt</option>
                                                        <option value="Salaried Nonexempt">Salaried Nonexempt</option>
                                                        <option value="Nonexempt">Nonexempt</option>
                                                        <option value="Owner">Owner</option>
                                                    </select>
                                                    <?php echo form_error('flsa'); ?>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <label class="csF16">Compensation Multiplier <strong class="text-danger">*</strong></label>
                                                    <div class="input-group">
                                                      
                                                        <input type="text" class="form-control input-lg rate" placeholder="0.0" value="<?php echo $compensationData['compensation_multiplier'] ?>" name="compensation_multiplier">
                                                    </div>
                                                    <?php echo form_error('compensation_multiplier'); ?>

                                                </div>

                                                <div class=" col-lg-2 col-md-2 col-xs-12 col-sm-2  form-group">
                                                    <label class="csF16">Per <strong class="text-danger">*</strong></label>
                                                    <select class="form-control input-lg rate_per" name="per" id="normal_per">
                                                        <option value="Hour">Per hour</option>
                                                        <option value="Week">Per week</option>
                                                        <option value="Month">Per month</option>
                                                        <option value="Year">Per year</option>
                                                        <option value="Paycheck">Per paycheck</option>
                                                    </select>
                                                    <?php echo form_error('per'); ?>
                                                </div>


                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <label>Effected Date: <strong class="text-danger">*</strong></label>
                                                    <?php
                                                    $effectedDate = $compensationData['effective_date'] != NULL && $compensationData['effective_date'] != '0000-00-00' ? DateTime::createFromFormat('Y-m-d', $compensationData['effective_date'])->format('m-d-Y') : '';
                                                    ?>
                                                    <input class="invoice-fields js-rehireDate" id="effective_date" readonly="" type="text" name="effective_date" value="<?php echo $effectedDate; ?>">
                                                    <?php echo form_error('effective_date'); ?>
                                                </div>


                                                <div class="col-lg-1 col-md-1 col-xs-12 col-sm-1 form-group">
                                                    <!--  --><br>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" name="is_primary" id="is_primary" value="1" <?= $compensationData['is_primary'] != 0 ? 'checked' : ''; ?> /> Primary?
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>

                                                <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2 form-group">
                                                    <!--  --><br>
                                                    <label class="control control--checkbox">
                                                        <input type="checkbox" name="is_active" value="1" <?= $compensationData['is_active'] != 0 ? 'checked' : ''; ?> /> Is Avtive?
                                                        <div class="control__indicator"></div>
                                                    </label>
                                                </div>
                                         
                                            </div>
                                                                           
                                        </form>
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


<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/additional-methods.min.js">
</script>
<!--file opener modal starts-->
<script language="JavaScript" type="text/javascript">
    var old_rehire_date = '<?php echo $rehireDate; ?>';
    //
    var timeOff = '<?= $timeOff ?>';
    $("#teams").select2();


    $('#js-policies').select2({
        closeOnSelect: false
    });

    $('#js_offdays').select2({
        closeOnSelect: false
    });


    //
    $('#flsa').val('<?php echo $compensationData['flsa_status'] ?>');
    $('#normal_per').val('<?php echo $compensationData['per'] ?>');



    <?php if ($access_level_plus == 1 && IS_PTO_ENABLED == 1) { ?>
        $('.js-shift-start-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            onShow: function(ct) {
                this.setOptions({
                    //maxTime: $('.js-shift-start-time').val() ? $('.js-shift-start-time').val() : false
                });
            }
        });
        $('.js-shift-end-time').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            onShow: function(ct) {
                time = $('.js-shift-start-time').val();
                if (time == '') return false;
                timeAr = time.split(":");
                last = parseInt(timeAr[1].substr(0, 2));
                if (last == 0)
                    last = "00";
                mm = timeAr[1].substr(2, 2);
                timeFinal = timeAr[0] + ":" + last + mm;
                this.setOptions({
                    //minTime: $('.js-shift-start-time').val() ? timeFinal : false
                })
            }
        });
    <?php } ?>


    function submitResult() {

        var myurl = "<?= base_url() ?>job_compensation_primary_check/<?php echo $jobTitleData['sid'] ?>";
        var isprimary = false;
        if ($("#is_primary").prop('checked') == true) {

            $.ajax({
                type: 'GET',
                url: myurl,
                success: function(res) {

                    if (res == 'yes') {

                        alertify.confirm('Notice', 'A Compensation marked as primary is already exist. do you wants to mark this Compensation as primary?', function() {
                           $('#save_jobinfo').submit();

                        }, function() {

                            $('#is_primary').prop('checked', false);

                           $('#save_jobinfo').submit();
                        });

                    } else {
                       $('#save_jobinfo').submit();
                    }

                },
                error: function() {

                }
            });

        } else {
           $('#save_jobinfo').submit();

        }


    }




    $('.js-rehireDate').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "<?php echo JOINING_DATE_LIMIT; ?>",
    }).val();



    //
    <?php if ($templateTitles) { ?>

        <?php if ($employer['job_title_type'] != '0') { ?>
            $('#temppate_job_title').show();
            $('#temppate_job_title').val('<?php echo $employer['job_title_type'] . '#' . $employer['job_title']; ?>');
            $('#job_title').hide();
        <?php } ?>

        $('.titleoption').click(function() {
            var titleOption = $(this).val();
            if (titleOption == 'dropdown') {
                $('#temppate_job_title').show();
                $('#temppate_job_title').val('<?php echo $employer['job_title_type'] == '0' ? '0' : $employer['job_title_type'] . '#' . $employer['job_title']; ?>');
                $('#job_title').hide();
            } else if (titleOption == 'manual') {
                $('#temppate_job_title').hide();
                $('#temppate_job_title').val('0');
                $('#job_title').show();
            }

        });
    <?php } ?>

    $('#temppate_job_title').on('change', function() {

        var jobtitle = this.value;
        let jobtitleAry = jobtitle.split("#");
        $('#job_title').val(jobtitleAry[1]);
    });



    //
    $('.rate').on('change', function() {

        var rate = this.value;
        if (rate < 1) {
            alertify.alert("Notice", "Multiplier always greater than 0!");
            this.value = 1;
        }
    });
</script>

<style>
    .select2-container--default .select2-selection--single {
        border: 2px solid #aaaaaa !important;
        background-color: #f7f7f7 !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        padding: 5px 20px 5px 8px !important;
    }

    .select2-container {
        width: 100%;
    }

    .select2-results__option {
        padding-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option:before {
        content: "";
        display: inline-block;
        position: relative;
        height: 20px;
        width: 20px;
        border: 2px solid #e9e9e9;
        border-radius: 4px;
        background-color: #fff;
        margin-right: 20px;
        vertical-align: middle;
    }

    .select2-results__option[aria-selected=true]:before {
        font-family: fontAwesome;
        content: "\f00c";
        color: #fff;
        background-color: #81b431;
        border: 0;
        display: inline-block;
        padding-left: 3px;
    }

    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #fff;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eaeaeb;
        color: #272727;
    }

    .select2-container--default .select2-selection--multiple {
        margin-bottom: 10px;
    }

    .select2-container--default.select2-container--open.select2-container--below .select2-selection--multiple {
        border-radius: 4px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #81b431;
        border-width: 2px;
    }

    .select2-container--default .select2-selection--multiple {
        border-width: 2px;
    }

    .select2-container--open .select2-dropdown--below {

        border-radius: 6px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

    }

    .select2-selection .select2-selection--multiple:after {
        content: 'hhghgh';
    }

    /* select with icons badges single*/
    .select-icon .select2-selection__placeholder .badge {
        display: none;
    }

    .select-icon .placeholder {
        display: none;
    }

    .select-icon .select2-results__option:before,
    .select-icon .select2-results__option[aria-selected=true]:before {
        display: none !important;
        /* content: "" !important; */
    }

    .select-icon .select2-search--dropdown {
        display: none;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        height: 25px !important;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 30px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #81b431;
        border-color: #81b431;
        color: #ffffff;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__rendered {
        height: 40px;
    }

    .select2-selection__choice__remove {
        color: #ffffff !important;
    }

    .select2-selection__rendered li {
        height: 24px !important;
    }

    .update_employee_info_container {
        height: 28px !important;
        font-size: 18px;
        padding-top: 0px;
    }

    @media screen and (max-width: 768px) {
        .shift_end {
            margin-top: 12px
        }

        .shift_div {
            padding-left: 0px;
            padding-right: 0px;
        }
    }
</style>