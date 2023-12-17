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
        <h3>Oral Employee Counseling Report Form</h3>
        <p><strong>Instructions:</strong> Use this form to document the need for improvement in job performance or to document
            disciplinary action for misconduct. This form is intended to be used as part of a progressive
            disciplinary process to help improve an employee’s performance or behavior in a formal and
            systematic manner. Before completing this form, review the Progressive Discipline Policy and any
            relevant supervisor guidances.
        </p>
        <p>If the employee is being terminated, review the policy on Immediate termination and related
            guidelines, and use the Termination Without Notice Form to process.</p>
    </div>

</div>


<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <table class="table borderless">
            <tbody>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Employee name: <span class="staric">*</span></label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empName'];?>" name="short_textbox_0" id="short_textbox_0_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Department: <span class="staric">*</span></label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empDepartment'];?>" name="short_textbox_1" id="short_textbox_1_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Date of occurrence: <span class="staric">*</span></label>
                        <input class="invoice-fields short_textbox date_picker" type="text" value="" name="short_textbox_2" id="short_textbox_2_id" data-type='text' autocomplete="off" />

                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>Supervisor: <span class="staric">*</span></label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empSupervisor'];?>" name="short_textbox_3" id="short_textbox_3_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>The following counseling has taken place (check all boxes that apply) and provide details in the
                            summary section below: </label>
                    </td>
                </tr>


                <tr>
                    <td width="100%">
                        <table class="table table table-bordered  table-striped text-left">
                            <tbody>

                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Absence</label>

                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_1" id="checkbox_1_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Harassment</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_2" id="checkbox_2_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Tardiness</label>

                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_3" id="checkbox_3_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Dishonesty</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_4" id="checkbox_4_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Violation of company policies and/or
                                            procedures</label>
                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_5" id="checkbox_5_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Violation of safety rules</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_6" id="checkbox_6_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Horseplay</label>
                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_7" id="checkbox_7_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Leaving work without authorization</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_8" id="checkbox_8_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Smoking in unauthorized areas</label>
                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_9" id="checkbox_9_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Unsatisfactory job performance</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_10" id="checkbox_10_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Failure to follow instructions</label>
                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_11" id="checkbox_11_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Insubordination</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_12" id="checkbox_12_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Unauthorized use of equipment,materials</label>
                                    </td>
                                    <td class="" width="50%">
                                        <input type="checkbox" name="checkbox_13" id="checkbox_13_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Falsification of records</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <input type="checkbox" name="checkbox_14" id="checkbox_14_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Other</label>
                                    </td>

                                </tr>

                            </tbody>
                        </table>
                    </td>

                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>Summary of violation: <span class="staric">*</span></label>
                        <textarea id="long_textbox_0_id" name="long_textbox_0" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_0_id_sec'></div>
                    </td>

                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Summary of corrective plan: <span class="staric">*</span></label>
                        <textarea id="long_textbox_1_id" name="long_textbox_1" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                        <div id='long_textbox_1_id_sec'></div>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Follow up dates: <span class="staric">*</span></label>
                        <input class="invoice-fields short_textbox date_picker" type="text" value="" name="short_textbox_4" id="short_textbox_4_id" data-type='text' />

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