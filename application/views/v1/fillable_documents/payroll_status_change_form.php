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
        <h3>Payroll/Status Change Form</h3>
        <p><strong>Instructions:</strong> Use this form to change an employee’s status and/or payroll information.
        </p>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <table class="table borderless">
            <tbody>

                <tr>
                    <td class="text-left" width="50%">
                        <h3>General Information:</h3>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Employee name:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empName'];?>" name="short_textbox_0" id="short_textbox_0_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Effective Date:</label>
                        <input class="invoice-fields short_textbox date_picker" type="text" value="" name="short_textbox_1" id="short_textbox_1_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Department:</label>
                        <input class="invoice-fields short_textbox " type="text" value="<?php echo $userPrefillInfo['empDepartment'];?>" name="short_textbox_2" id="short_textbox_2_id" data-type='text' autocomplete="off" />

                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Supervisor:</label>
                        <input class="invoice-fields short_textbox " type="text" value="<?php echo $userPrefillInfo['empSupervisor'];?>" name="short_textbox_3" id="short_textbox_3_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <h3>Indicate All Changes</h3>
                    </td>
                </tr>

                <tr>
                    <td class="" width="50%">
                        <table class="table" style="margin-bottom:0px; padding-left: 0px;">
                            <tbody>
                                <tr>
                                    <td class="text-left" width="20%">
                                        &nbsp; <label> Rate From:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"> $ </div>
                                            <input class="invoice-fields short_textbox " type="text" value="" name="short_textbox_4" id="short_textbox_4_id" data-type='text' autocomplete="off" />

                                        </div>
                                    </td>
                                    <td class="text-left" width="20%">
                                        &nbsp; <label> Rate To:</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"> $ </div>
                                            <input class="invoice-fields short_textbox " type="text" value="" name="short_textbox_5" id="short_textbox_5_id" data-type='text' autocomplete="off" />

                                        </div>
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Job:</label>
                        <input class="invoice-fields short_textbox " type="text" value="<?php echo $userPrefillInfo['empJobTitle'];?>" name="short_textbox_6" id="short_textbox_6_id" data-type='text' autocomplete="off" />

                    </td>

                </tr>
                <tr>
                    <td class="text-left" width="50%">
                        <label>Department:</label>
                        <input class="invoice-fields short_textbox " type="text" value="<?php echo $userPrefillInfo['empDepartment'];?>" name="short_textbox_7" id="short_textbox_7_id" data-type='text' autocomplete="off" />

                    </td>
                </tr>
                <tr>
                    <td class="text-left" width="50%">
                        <label>Location:</label>
                        <input class="invoice-fields short_textbox " type="text" value="<?php echo $userPrefillInfo['empLocationAddress'];?>" name="short_textbox_8" id="short_textbox_8_id" data-type='text' autocomplete="off"/>
                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <label>Shift:</label>
                        <input class="invoice-fields short_textbox " type="text" value="" name="short_textbox_9" id="short_textbox_9_id" data-type='text' autocomplete="off"/>

                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Other:</label>
                        <input class="invoice-fields short_textbox " type="text" value="" name="short_textbox_10" id="short_textbox_10_id" data-type='text' autocomplete="off"/>

                    </td>
                </tr>


                <tr>
                    <td class="text-left" width="50%">
                        <h3>Indicate All Reasons for Changes below</h3>
                    </td>
                </tr>


                <tr>
                    <td width="100%" class="text-left">
                        <table class="table table table-bordered  table-striped">
                            <tbody>

                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox'>

                                        &nbsp; <label> Seniority increase</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_1" id="checkbox_1_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Retirement</label>
                                    </td>

                                    <td>
                                        <input type="checkbox" name="checkbox_2" id="checkbox_2_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Layoff</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkbox_3" id="checkbox_3_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Contract change</label>

                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_4" id="checkbox_4_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Resignation</label>
                                    </td>

                                    <td>
                                        <input type="checkbox" name="checkbox_5" id="checkbox_5_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Discharge</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkbox_6" id="checkbox_6_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Re-evaluation</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_7" id="checkbox_7_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Demotion</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_8" id="checkbox_8_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Leave of absence</label>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkbox_9" id="checkbox_9_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Transfer</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_10" id="checkbox_10_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Promotion</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_11" id="checkbox_11_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Merit Increase</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="checkbox_12" id="checkbox_12_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Probation period end</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_13" id="checkbox_13_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Re-hired</label>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="checkbox_14" id="checkbox_14_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        &nbsp; <label> Hired</label>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
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