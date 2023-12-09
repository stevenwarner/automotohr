<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 10px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 0px solid #ddd;
    }

    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .invoice-fields {
        float: left;
        width: 100%;
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 5px;
        color: #000;
        padding: 0 5px;
        background-color: #eee;
    }

    .auto-height {
        height: auto !important;
    }
</style>


<div class="row">
    <?php
    $userPrefillInfo  = [];
    if ($document['form_input_data'] == '' || $document['form_input_data'] == null) {
            $userPrefillInfo = get_employee_profile_info_detail($document['user_sid'], $document['user_type']);
    }

    ?>

    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <h3>Written Employee Counseling Report Form</h3>
        <p><strong>Instructions:</strong>Use this form to document the need for improvement in job performance or to
            document disciplinary action for misconduct. This form is intended to be used as part of the
            progressive discipline process to help improve an employee’s performance or behavior in a formal
            and systematic manner. Before completing this form, review the Progressive Discipline Policy and any
            relevant supervisor guidelines</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <table class="table borderless">
            <tbody>
                <tr>
                    <td class="text-left" width="50%">
                        <label>Employee Name:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empName'];?>" name="short_textbox_0" id="short_textbox_0_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Employee Number:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empPhoneNumber'];?>" name="short_textbox_1" id="short_textbox_1_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Job Title:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empJobTitle'];?>" name="short_textbox_2" id="short_textbox_2_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Department:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empDepartment'];?>" name="short_textbox_3" id="short_textbox_3_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Location:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empLocationAddress'];?>" name="short_textbox_4" id="short_textbox_4_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Supervisor:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empSupervisor'];?>" name="short_textbox_5" id="short_textbox_5_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>Description of problem, including relevant dates and other people involved</label><br>
                        <textarea id="long_textbox_0_id" name="long_textbox_0" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_0_id_sec'></div>
                    </td>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Description of performance or behavior that is expected</label><br>
                        <textarea id="long_textbox_1_id" name="long_textbox_1" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_1_id_sec'></div>

                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Description of consequences for not meeting expectations</label><br>
                        <textarea id="long_textbox_2_id" name="long_textbox_2" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_2_id_sec'></div>

                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Dates and descriptions of prior discissions or warnings formal or informal, regardless this issue
                            or other relevant issues.
                        </label><br>
                        <textarea id="long_textbox_3_id" name="long_textbox_3" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_3_id_sec'></div>

                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Other information you would like to provide</label><br>
                        <textarea id="long_textbox_4_id" name="long_textbox_4" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_4_id_sec'></div>

                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Employee Signature:</label>
                        <?php if ($document['signature_base64'] != '' || $document['signature_base64'] != null) { ?>
                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $document['signature_base64']; ?>" />
                        <?php } else { ?>
                            <a class="btn btn-sm blue-button get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="" id="draw_upload_img" />
                        <?php } ?>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Date:</label>
                        <input class="invoice-fields date_picker short_textbox" type="text" value="" name="short_textbox_6" id="short_textbox_6_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>GM Signature:</label>
                        <?php if ($document['authorized_signature'] != '' || $document['authorized_signature'] != null) { ?>
                            <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $document['authorized_signature']; ?>" />
                        <?php } else { ?>
                            
                        <?php } ?>
                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>Date: Authorize Sign Date:</label>
                        <input class="invoice-fields sign_date short_textbox" type="text" value="" name="short_textbox_7" id="short_textbox_7_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
</div>

<script language="JavaScript" type="text/javascript" src="<?= base_url('assets') ?>/js/jquery.validate.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $('.sign_date').datepicker({
        dateFormat: 'mm-dd-yy',
        setDate: new Date(),
        maxDate: new Date,
        minDate: new Date()
    });

    $('.date_picker').datepicker({
        dateFormat: 'mm-dd-yy',
        changeMonth: true,
        changeYear: true,
        yearRange: "-100:+50",
    }).val();
</script>