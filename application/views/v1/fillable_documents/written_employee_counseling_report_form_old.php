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

<div class="container">
    <div class="row">
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
                        <td class="" width="50%">
                            <label>Employee Name:</label>
                            <input class="invoice-fields" type="text" value="" name="employee_name" />
                        </td>
                    </tr>

                    <tr>
                        <td class="" width="50%">
                            <label>Employee Number:</label>
                            <input class="invoice-fields" type="text" value="" name="employee_number" />
                        </td>
                    </tr>

                    <tr>
                        <td class="" width="50%">
                            <label>Job Title:</label>
                            <input class="invoice-fields" type="text" value="" name="job_title" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Department:</label>
                            <input class="invoice-fields" type="text" value="" name="department" />
                        </td>

                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Location:</label>
                            <input class="invoice-fields" type="text" value="" name="location" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Supervisor:</label>
                            <input class="invoice-fields" type="text" value="" name="supervisor" />
                        </td>
                    </tr>


                    <tr>
                        <td width="50%">
                            <label>Description of problem, including relevant dates and other people involved</label><br>
                            <textarea id="" name="description_problem" class="invoice-fields auto-height" rows="6"></textarea>
                        </td>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Description of performance or behavior that is expected</label><br>
                            <textarea id="" name="description_performance" class="invoice-fields auto-height" rows="6"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Description of consequences for not meeting expectations</label><br>
                            <textarea id="" name="description_consequences" class="invoice-fields auto-height" rows="6"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Dates and descriptions of prior discissions or warnings formal or informal, regardless this issue
                                or other relevant issues.
                            </label><br>
                            <textarea id="" name="description_warnings" class="invoice-fields auto-height" rows="6"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Other information you would like to provide</label><br>
                            <textarea id="" name="other_information" class="invoice-fields auto-height" rows="6"></textarea>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Employee Signature:</label>
                            <?php if ($signed_flag == true) { ?>
                                <img style="max-height: <?= SIGNATURE_MAX_HEIGHT ?>;" src="<?php echo $pre_form['signature_bas64_image']; ?>" />
                            <?php } else { ?>
                                <!-- the below loaded view add e-signature -->
                                <?php $this->load->view('static-pages/e_signature_button'); ?>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Date:</label>
                            <input class="invoice-fields date_picker" type="text" value="" name="document_date" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%">
                            <label>Date: Authorize Sign Date:</label>
                            <input class="invoice-fields sign_date" type="text" value="" name="authorize_sign_date" />
                        </td>
                    </tr>

                    <tr>
                        <td width="50%" class="text-right">
                            <button type="button" class="btn blue-button break-word-text">Save</button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>

<?php $this->load->view('static-pages/e_signature_popup'); ?>
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