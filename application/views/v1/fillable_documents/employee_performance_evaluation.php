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

        <div style="text-align: left; padding-right: 20px; padding-left: 15px;">
            <h3>Eemployee Performance Evaluation </h3>
            <strong> How to complete the Employee Performance Evaluation Process:
                <p> <br><strong style="font-size: 16px;"> Section 1: </strong> This section will be completed by the immediate Manager of the employee. The Manager will complete this section ahead of the scheduled performance evaluation meeting with the employee.
                    <br> <em style="color: #ea0000;"> The manager must send their completed portion of the performance evaluation to human resources for review PRIOR to the meeting with the employee. Once HR has reviewed the Performance Evaluation and has sent this back to you, you can then meet with the employee.</em>
            </strong><br>
            </p>


            <strong><br><strong style="font-size: 16px;"> Section 2: </strong>
                The Manager will send Section 2 to the employee ahead of the scheduled performance evaluation meeting. The employee will complete this section on their own and hold onto this until their scheduled performance evaluation meeting with their manager.
            </strong><br>

            <strong><br><strong style="font-size: 16px;"> Section 3: </strong>
                The Manager will schedule the performance evaluation meeting with the employee. The Manager and the employee will complete section 3 together by providing any additional commitments, goals, and feedback.
            </strong><br>

            <strong><br><strong style="font-size: 16px;"> Section 4: </strong>
                Once the Manager and the employee have met and completed the performance evaluation process, they will both sign the completed form
            </strong><br>

            <strong><br><strong style="font-size: 16px;"> Section 5: </strong>
                The Manager may make a recommendation for salary changes. The form will then be sent to Human Resources for final approval and a review of the salary recommendation will be sent to the Director. The performance evaluation then becomes part of the employee’s personnel file.
            </strong><br>

            <strong><br><strong style="font-size: 16px;"> Tips: </strong>
                The employee should have the Manager’s undivided attention during the performance evaluation meeting. The Manager should articulate the employee’s strengths and, if there are any improvement opportunities, the Manager should propose suggestions on how the employee can improve. During the dialog with the employee, the Manager will review the employee’s feedback on the form they filled out and that of the Manager. <br>
                This performance evaluation discussion is intended to be a constructive exchange relative to the individual's past performance, improvement opportunities, and future expectations. It offers a chance for the employee to improve in areas that are needed. The dialogue should be a two-way conversation between the employee and the Manager.

            </strong><br>
        </div>




    </div>
</div>

<div class="row" style="text-align: left;">
    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">

        <section class="pdf-cover-page">

            <table class="table table-border-collapse">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;"><br><br>
                            <strong style="font-size: 14px;">Manager Section 1: Employee Year in Review Evaluation
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Name</strong>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Job Title</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">

                                            <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empName']; ?>" name="short_textbox_0" id="short_textbox_0_id" data-type='text' autocomplete="off" />


                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">

                                            <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empJobTitle']; ?>" name="short_textbox_1" id="short_textbox_1_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Department </strong>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Manager</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox" type="text" value="<?php echo $userPrefillInfo['empDepartment']; ?>" name="short_textbox_2" id="short_textbox_2_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox" type="text" value="<?= $formInputData['short_textbox_3'] ? $formInputData['short_textbox_3'] : '' ?>" name="short_textbox_3" id="short_textbox_3_id" data-type='text' autocomplete="off" />

                                        </td>
                                    </tr>


                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Hire Date with DeFOUW Automotive </strong>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Start Date in Current Position</strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker" type="text" value="<?= $formInputData['short_textbox_4'] ? $formInputData['short_textbox_4'] : '' ?>" name="short_textbox_4" id="short_textbox_4_id" data-type='text' autocomplete="off" />


                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker" type="text" value="<?= $formInputData['short_textbox_5'] ? $formInputData['short_textbox_5'] : '' ?>" name="short_textbox_5" id="short_textbox_5_id" data-type='text' autocomplete="off" />

                                        </td>
                                    </tr>



                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Review Period Start </strong>

                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong> Review Period End </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker" type="text" value="<?= $formInputData['short_textbox_6'] ? $formInputData['short_textbox_6'] : '' ?>" name="short_textbox_6" id="short_textbox_6_id" data-type='text' autocomplete="off" />


                                        </td>
                                        <td width="50%" style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker" type="text" value="<?= $formInputData['short_textbox_7'] ? $formInputData['short_textbox_7'] : '' ?>" name="short_textbox_7" id="short_textbox_7_id" data-type='text' autocomplete="off" />

                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">

            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Rate the employee in each area below. Comments are required for each section. </strong><br>

                            <strong style="font-size: 14px;"> POSITION KNOWLEDGE: </strong> To what level is this employee knowledgeable of the job duties of the position to include methods, procedures, standard practices, and techniques? This may have been acquired through formal training, education and/or experience.

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Knowledge is below the minimum requirements of the position. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Knowledge is sufficient to perform the requirements of the position.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is exceptionally well informed and competent in all aspects of the position..</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_0" id="checkbox_0_id" value="" class="counseling user_checkbox" data-type='checkbox'>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_1" id="checkbox_1_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_2" id="checkbox_2_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <textarea id="long_textbox_0_id" name="long_textbox_0" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_0_id_sec'> </div>

                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s position knowledge be improved?. <?= $formInputData['short_textbox_9'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> QUANTITY OF WORK: </strong> Evaluate the quantity of work produced.

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Output is below that required of the position. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Output meets that required of the position.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Output consistently exceeds that required of the position.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_3" id="checkbox_3_id" value="" class="counseling user_checkbox" data-type='checkbox'>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_4" id="checkbox_4_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_5" id="checkbox_5_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <textarea id="long_textbox_1_id" name="long_textbox_1" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_1_id_sec'> </div>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s quantity of work be improved?. <?= $formInputData['short_textbox_11'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> QUANTITY OF WORK: </strong> Evaluate the quality of work produced in accordance with requirements for accuracy, completeness, and attention to detail.

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Quality of work is frequently below position requirements. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Quality of work meets position requirements.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Quality of work consistently exceeds position requirements.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_6" id="checkbox_6_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_7" id="checkbox_7_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_8" id="checkbox_8_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>

                                            <textarea id="long_textbox_2_id" name="long_textbox_2" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_2_id_sec'> </div>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s quantity of work be improved?. <?= $formInputData['short_textbox_13'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> INTERPERSONAL RELATIONS: </strong> o what level does this individual demonstrate cooperative behavior and contribute to a supportive work environment?.

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is frequently non-supportive. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately contributes to supportive environment.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee consistently contributes to supportive work environment.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_9" id="checkbox_9_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_10" id="checkbox_10_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_11" id="checkbox_11_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>

                                            <textarea id="long_textbox_3_id" name="long_textbox_3" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_3_id_sec'> </div>

                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s interpersonal relations be improved?. <?= $formInputData['short_textbox_15'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> Mission: </strong> To what level does the employees work support the Mission of the organization; To what level does the employee make themselves available to respond to needs of others both internally and externally?

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Level of mission focus is often below the required/acceptable standard. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately contributes to high quality mission.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee consistently demonstrates exceptional commitment to the mission.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_12" id="checkbox_12_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_13" id="checkbox_13_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_14" id="checkbox_14_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>

                                            <textarea id="long_textbox_4_id" name="long_textbox_4" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_4_id_sec'> </div>

                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s customer service skills/delivery be improved?. <?= $formInputData['short_textbox_17'] ? $formInputData['short_textbox_9'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> DEPENDABILITY: </strong> To what level is the employee dependable; How often does the employee show up to work on time and complete their scheduled shifts? Can the employee be counted on to complete tasks and meet deadlines consistently?

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is late, absent, misses deadlines. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately attends work, rarely misses or late, meets deadlines.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee consistently on time, at work and completes deadlines ahead of schedule.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_15" id="checkbox_15_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_16" id="checkbox_16_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_17" id="checkbox_17_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>


                                            <textarea id="long_textbox_5_id" name="long_textbox_5" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_5_id_sec'> </div>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s dependability be improved?. <?= $formInputData['short_textbox_19'] ? $formInputData['short_textbox_19'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> ADHERENCE TO POLICY & PROCEDURE: </strong> To what level does the employee adhere to standard operating policies and procedures?

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is frequently coached on standard operating policies and procedures. Improvement is mandatory. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately adheres to standard operating policies and procedures with few reminders.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is consistently exceptional in following standard operating policies and procedures..</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_18" id="checkbox_18_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_19" id="checkbox_19_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_20" id="checkbox_20_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>


                                            <textarea id="long_textbox_6_id" name="long_textbox_6" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_6_id_sec'> </div>

                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">How may the employee’s adherence to policy and procedure be improved?. <?= $formInputData['short_textbox_21'] ? $formInputData['short_textbox_21'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> OTHER: </strong> <?= $formInputData['short_textbox_22'] ? $formInputData['short_textbox_22'] : '' ?>

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee frequently falls below acceptable standard as outlined above. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately meets standard as outlined above.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is consistently exceptional in meeting performance standard.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_21" id="checkbox_21_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_22" id="checkbox_22_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_23" id="checkbox_23_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <textarea id="long_textbox_7_id" name="long_textbox_7" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_7_id_sec'> </div>
                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse" style="margin-top: -10px;">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;"> How may employee’s performance in meeting this standard be improved? <?= $formInputData['short_textbox_24'] ? $formInputData['short_textbox_24'] : '' ?> </strong><br>

                            <strong style="font-size: 14px;"> OTHER: </strong> <?= $formInputData['short_textbox_25'] ? $formInputData['short_textbox_25'] : '' ?>

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee frequently falls below acceptable standard as outlined above. </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee adequately meets standard as outlined above.</strong>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee is consistently exceptional in meeting performance standard.</strong>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_24" id="checkbox_24_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_25" id="checkbox_25_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input type="checkbox" name="checkbox_26" id="checkbox_26_id" value="" class="counseling user_checkbox" data-type='checkbox'>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Comment:</strong>
                                            <textarea id="long_textbox_8_id" name="long_textbox_8" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_8_id_sec'> </div>

                                        </td>

                                    </tr>


                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>How may employee’s performance in meeting this standard be improved?</strong> <?= $formInputData['short_textbox_27'] ? $formInputData['short_textbox_27'] : '' ?>
                                        </td>

                                    </tr>


                                    <tr>
                                        <td colspan="3" width="50%" style="border: 1px solid; font-size: 14px;">
                                            <strong>Managers Additional Comments for the Review Period: </strong>
                                            <textarea id="long_textbox_9_id" name="long_textbox_9" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_9_id_sec'> </div>
                                        </td>

                                    </tr>


                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">Manager Section 2: The Year in Review
                                <br>
                                List 3-4 top accomplishments & add a reflection on overall performance for the year.
                            </strong>

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="10%" style="border: 1px solid; font-size: 14px;  width:10%">
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Accomplishment </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Comments/Reflection </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            1
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_26'] ? $formInputData['short_textbox_26'] : '' ?>" name="short_textbox_26" id="short_textbox_26_id" data-type='text' autocomplete="off" />

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_27'] ? $formInputData['short_textbox_27'] : '' ?>" name="short_textbox_27" id="short_textbox_27_id" data-type='text' autocomplete="off" />

                                        </td>
                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            2
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_28'] ? $formInputData['short_textbox_28'] : '' ?>" name="short_textbox_28" id="short_textbox_28_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_29'] ? $formInputData['short_textbox_29'] : '' ?>" name="short_textbox_29" id="short_textbox_29_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            3
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_30'] ? $formInputData['short_textbox_30'] : '' ?>" name="short_textbox_30" id="short_textbox_30_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_31'] ? $formInputData['short_textbox_31'] : '' ?>" name="short_textbox_31" id="short_textbox_31_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;"> 4
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_32'] ? $formInputData['short_textbox_32'] : '' ?>" name="short_textbox_32" id="short_textbox_32_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_33'] ? $formInputData['short_textbox_33'] : '' ?>" name="short_textbox_33" id="short_textbox_33_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">

            <table class="table table-border-collapse">
                <tbody>

                    <tr>
                        <td width="50%" style="border-top:0px;"><br><br>
                            <strong style="font-size: 14px;">
                                Opportunities for Improved Performance: List 2-4 areas of improvement & how you will work on these improvements over the coming year. </strong>

                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="10%" style="border: 1px solid; font-size: 14px;  width:10%">
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Opportunity for Improvement </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Comments </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            1
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_34'] ? $formInputData['short_textbox_34'] : '' ?>" name="short_textbox_34" id="short_textbox_34_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_35'] ? $formInputData['short_textbox_35'] : '' ?>" name="short_textbox_35" id="short_textbox_35_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            2
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_36'] ? $formInputData['short_textbox_36'] : '' ?>" name="short_textbox_36" id="short_textbox_36_id" data-type='text' autocomplete="off" />


                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_37'] ? $formInputData['short_textbox_37'] : '' ?>" name="short_textbox_37" id="short_textbox_37_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            3
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_38'] ? $formInputData['short_textbox_38'] : '' ?>" name="short_textbox_38" id="short_textbox_38_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_39'] ? $formInputData['short_textbox_39'] : '' ?>" name="short_textbox_26" id="short_textbox_39_id" data-type='text' autocomplete="off" />

                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;"> 4
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_40'] ? $formInputData['short_textbox_40'] : '' ?>" name="short_textbox_40" id="short_textbox_40_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_41'] ? $formInputData['short_textbox_41'] : '' ?>" name="short_textbox_41" id="short_textbox_41_id" data-type='text' autocomplete="off" />

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>



        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                List 2-3 goals for the upcoming year. One must reflect a personal development goal.
                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td width="10%" style="border: 1px solid; font-size: 14px;  width:10%">
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Goal </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> General comments and summary relating to the status of the goal, attainment, difficulty of goal, and impacting factors. </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            1
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_42'] ? $formInputData['short_textbox_42'] : '' ?>" name="short_textbox_42" id="short_textbox_42_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_43'] ? $formInputData['short_textbox_43'] : '' ?>" name="short_textbox_43" id="short_textbox_43_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            2
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_44'] ? $formInputData['short_textbox_44'] : '' ?>" name="short_textbox_44" id="short_textbox_44_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_45'] ? $formInputData['short_textbox_45'] : '' ?>" name="short_textbox_45" id="short_textbox_45_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            3
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_46'] ? $formInputData['short_textbox_46'] : '' ?>" name="short_textbox_46" id="short_textbox_46_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_47'] ? $formInputData['short_textbox_47'] : '' ?>" name="short_textbox_47" id="short_textbox_47_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;"> 4
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_48'] ? $formInputData['short_textbox_48'] : '' ?>" name="short_textbox_48" id="short_textbox_48_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_49'] ? $formInputData['short_textbox_49'] : '' ?>" name="short_textbox_49" id="short_textbox_49_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>


                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                            <strong> Have you and your manager reviewed your job description for this review period? </strong><br>
                                            <select class="invoice-fields js_select_document" name="select_0" id="select_0_id" data-type='text' required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                            <strong> 2.Do you have access to equipment and resources necessary to perform your job function?
                                                (If No, please list the equipment you deem necessary subject to Managers approval and budgeting) </strong><br>

                                            <select class="invoice-fields js_select_document" name="select_1" id="select_1_id" data-type='text' required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                            <strong> 3.Is there any additional support or training you feel would be helpful for DeFOUW Automotive to provide for you to help you succeed in your current role?</strong><br>
                                            <select class="invoice-fields js_select_document" name="select_2" id="select_2_id" data-type='text' required>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                    </tr>

                                    <tr>

                                        <td style="border: 1px solid; font-size: 14px;" colspan="3">
                                            <strong>Employee Additional Comments:</strong> <br>
                                            <textarea id="long_textbox_11_id" name="long_textbox_11" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                            <div id='long_textbox_11_id_sec'></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">
            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Section 3: The Year in Review </strong><br>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Additional Comments, Feedback - Managers Comments: <br>
                                <textarea id="long_textbox_12_id" name="long_textbox_12" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                <div id='long_textbox_12_id_sec'></div>

                            </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Additional Comments, Feedback - Employee Comments: <br>
                                <textarea id="long_textbox_13_id" name="long_textbox_13" class="invoice-fields auto-height long_textbox" rows="6" data-type='textarea'></textarea>
                                <div id='long_textbox_13_id_sec'></div>
                            </strong>
                        </td>
                    </tr>

                </tbody>
            </table>
        </section>


        <section class="pdf-cover-page">

            <table class="table table-border-collapse">
                <tbody>
                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <strong style="font-size: 14px;">
                                Section 4: Signatures </strong>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" style="border-top:0px;">
                            <table class="table" style="border: 1px solid;   border-collapse: collapse;">
                                <tbody>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Employee Date </strong>

                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Manager Date. </strong>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker " type="text" value="<?= $formInputData['short_textbox_50'] ? $formInputData['short_textbox_50'] : '' ?>" name="short_textbox_50" id="short_textbox_50_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker " type="text" value="<?= $formInputData['short_textbox_51'] ? $formInputData['short_textbox_51'] : '' ?>" name="short_textbox_51" id="short_textbox_51_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                    <tr>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Next Level Approval Date </strong>
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <strong> Human Resources Date. </strong>
                                        </td>
                                    </tr>

                                    <tr>

                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker " type="text" value="<?= $formInputData['short_textbox_52'] ? $formInputData['short_textbox_52'] : '' ?>" name="short_textbox_52" id="short_textbox_52_id" data-type='text' autocomplete="off" />
                                        </td>
                                        <td style="border: 1px solid; font-size: 14px;">
                                            <input class="invoice-fields short_textbox date_picker " type="text" value="<?= $formInputData['short_textbox_53'] ? $formInputData['short_textbox_53'] : '' ?>" name="short_textbox_53" id="short_textbox_53_id" data-type='text' autocomplete="off" />
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                    </tr>

                </tbody>
            </table>
        </section>

        <section class="pdf-cover-page">

        <table class="table table-border-collapse">
            <tbody>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">
                            Section 5: Salary Recommendation </strong>
                    </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Employees Current Pay Rate: </strong>
                        <input class="invoice-fields short_textbox " type="text" value="<?= $formInputData['short_textbox_54'] ? $formInputData['short_textbox_54'] : '' ?>" name="short_textbox_54" id="short_textbox_54_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Recommended Pay Increase: </strong>
                        <input class="invoice-fields short_textbox  " type="text" value="<?= $formInputData['short_textbox_55'] ? $formInputData['short_textbox_55'] : '' ?>" name="short_textbox_55" id="short_textbox_55_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Approved Amount:</strong>
                        <input class="invoice-fields short_textbox  " type="text" value="<?= $formInputData['short_textbox_56'] ? $formInputData['short_textbox_56'] : '' ?>" name="short_textbox_56" id="short_textbox_56_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Approved By:</strong>
                        <input class="invoice-fields short_textbox  " type="text" value="<?= $formInputData['short_textbox_57'] ? $formInputData['short_textbox_57'] : '' ?>" name="short_textbox_57" id="short_textbox_57_id" data-type='text' autocomplete="off" />
                    </td>
                </tr>
                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Approved Date:</strong>
                        <input class="invoice-fields short_textbox date_picker " type="text" value="<?= $formInputData['short_textbox_58'] ? $formInputData['short_textbox_58'] : '' ?>" name="short_textbox_58" id="short_textbox_58_id" data-type='text' autocomplete="off" />
                   </td>
                </tr>

                <tr>
                    <td width="50%" style="border-top:0px;">
                        <strong style="font-size: 14px;">Effective Date of Increase:</strong>
                        <input class="invoice-fields short_textbox date_picker " type="text" value="<?= $formInputData['short_textbox_59'] ? $formInputData['short_textbox_59'] : '' ?>" name="short_textbox_59" id="short_textbox_59_id" data-type='text' autocomplete="off" />
                   </td>
                </tr>

            </tbody>
        </table>
    </section>


    </div>

</div>

<?php //$this->load->view('static-pages/e_signature_popup'); 
?>
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