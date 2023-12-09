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


<?php
$userPrefillInfo  = [];
if ($document['form_input_data'] == '' || $document['form_input_data'] == null) {
        $userPrefillInfo = get_employee_profile_info_detail($document['user_sid'], $document['user_type']);
}

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
        <h3>Notice of Termination of Employment</h3>
        <p><strong>Instructions:</strong> Use this form to officially notify the Company that an employee who reports to you is
            now Terminated. This will insure that appropriate action is taken and that the employee receives their
            final check.
        </p>
        <p>After you have selected the employee that you are terminating, take care to record the details of this
            termination as accurately as possible.</p>
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
                        <label>Title:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empJobTitle'];?>" name="short_textbox_1" id="short_textbox_1_id" data-type='text' autocomplete="off"/>
                    </td>

                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Supervisor:</label>
                        <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empSupervisor'];?>" name="short_textbox_2" id="short_textbox_2_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Last day of work:</label>
                        <input class="invoice-fields short_textbox" type="text" value="" name="short_textbox_3" id="short_textbox_3_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>Is the termination Voluntary?</label><br>
                        <div class="card-fields-row">
                            <select class="invoice-fields js_select_document" name="select_0" id="select_0_id" data-type='text' required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </td>
                </tr>


                <tr>
                    <td width="50%">
                        <label>Summarize the notifications or warnings that were given to the employee prior to termination.
                            Include dates of each warning, whether the warning was documented, and where all
                            documentation is kept</label><br>
                    </td>
                </tr>

                <tr>
                    <td class="text-left" width="50%">
                        <label>All Dealership property has been returned? </label><br>
                        <div class="card-fields-row">
                            <select class="invoice-fields js_select_document" name="select_1" id="select_1_id" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="text-left" width="50%">
                        <label>Would you consider re-employing this person in your department? </label><br>
                        <div class="card-fields-row">
                            <select class="invoice-fields js_select_document" name="select_2" id="select_2_id" required=true>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>

</div>