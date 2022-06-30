<?php
$eDoc = [];
//
if (isset($document_info) && count($document_info)) $eDoc = $document_info;
?>

<style>
    .nopaddingleft {
        padding-left: 0;
    }

    .nopaddingright {
        padding-right: 0;
    }

    .select2-container {
        width: 100% !important;
        min-width: 0 !important;
    }

    .select2-container--default .select2-selection--single {
        background-color: #fff !important;
        border: 1px solid #aaa !important;
    }
</style>

<!-- Panel for send documents DMWYC -->
<div class="panel panel-default <?= isset($dwmc) && $userType == 'applicant' ? 'hidden' : ''; ?>">
    <div class="panel-heading"><b>Assign & Send Document</b></div>
    <div class="panel-body">
        <!--  -->
        <div class="row">
            <!-- Selection row -->
            <div class="col-sm-12">
                <!-- None -->
                <label class="control control--radio">
                    None &nbsp;&nbsp;
                    <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="none" />
                    <div class="control__indicator"></div>
                </label>
                <!-- Daily -->
                <label class="control control--radio">
                    Daily &nbsp;&nbsp;
                    <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="daily" />
                    <div class="control__indicator"></div>
                </label>
                <!-- Weekly -->
                <label class="control control--radio">
                    Weekly &nbsp;&nbsp;
                    <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="weekly" />
                    <div class="control__indicator"></div>
                </label>
                <!-- Monthly -->
                <label class="control control--radio">
                    Monthly &nbsp;&nbsp;
                    <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="monthly" />
                    <div class="control__indicator"></div>
                </label>
                <!-- Yearly -->
                <label class="control control--radio">
                    Yearly &nbsp;&nbsp;
                    <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="yearly" />
                    <div class="control__indicator"></div>
                </label>
                <!-- Custom -->
                <label class="control control--radio">
                    Custom &nbsp;&nbsp;
                    <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="custom" />
                    <div class="control__indicator"></div>
                </label>
                <!--  -->
            </div>

            <!-- Custom date row -->
            <div class="col-sm-12 jsCustomDateRow">
                <br />
                <label id="jsCustomLabel">Select a date & time</label>
                <div class="row">
                    <div class="col-sm-4" id="jsCustomDate">
                        <input type="text" class="form-control jsDatePicker" name="assignAndSendCustomDate" readonly="true" />
                    </div>
                    <div class="col-sm-4" id="jsCustomDay">
                        <select name="assignAndSendCustomDay" id="jsCustomDaySLT">
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="7">Sunday</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control jsTimePicker" name="assignAndSendCustomTime" readonly="true" />
                    </div>
                </div>
            </div>

            <?php if (isset($dwmc)) { ?>
                <!-- Against Selected Employees -->
                <div class="col-sm-12 hidden">
                    <label>Employee(s)</label>
                    <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                        <option value="<?= $userSid; ?>" selected="true"></option>
                    </select>
                </div>
            <?php } else { ?>
                <div class="col-sm-12">
                    <hr />
                </div>
                <!-- Against Selected Employees -->
                <div class="col-sm-12">
                    <label>Employee(s)</label>
                    <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                        <option value="-1">All</option>
                        <?php foreach ($employeesList as $key => $employee) { ?>
                            <option value="<?= $employee['sid']; ?>"><?= remakeEmployeeName($employee); ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>

        </div>
    </div>
</div>


<script>
    $(function() {
        //
        let eDoc = <?= json_encode($eDoc); ?>;
        //
        $('.assignSelectedEmployees').select2({
            closeOnSelect: false
        });
        //
        $('#jsCustomDaySLT').select2();
        //
        $('.assignAndSendDocument').change(function() {
            //
            $('.jsCustomDateRow').show();
            $('#jsCustomDay').hide();
            $('#jsCustomLabel').text('Select a date & time');
            $('#jsCustomDate').show();
            $('.jsDatePicker').datepicker('option', {
                changeMonth: true
            });
            //
            if ($(this).val().toLowerCase() == 'daily') {
                $('#jsCustomLabel').text('Select time');
                $('#jsCustomDate').hide();
            } else if ($(this).val().toLowerCase() == 'monthly') {
                $('#jsCustomLabel').text('Select a date & time');
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'dd'
                });
                $('.jsDatePicker').datepicker('option', {
                    changeMonth: false
                });
            } else if ($(this).val().toLowerCase() == 'weekly') {
                $('#jsCustomDate').hide();
                $('#jsCustomDay').show();
                $('#jsCustomLabel').text('Select day & time');
            } else if ($(this).val().toLowerCase() == 'yearly' || $(this).val().toLowerCase() == 'custom') {
                $('.jsDatePicker').datepicker('option', {
                    dateFormat: 'mm/dd'
                });
            } else if ($(this).val().toLowerCase() == 'none') {
                $('.jsCustomDateRow').hide();
            }
        });

        //
        $('.jsDatePicker').datepicker({
            changeMonth: true,
            dateFormat: 'mm/dd',
            changeYear: true,
            yearRange: "<?php echo DOB_LIMIT; ?>"
        });

        //
        $('.jsTimePicker').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });

        //
        if (eDoc.sid !== undefined) {
            let SE = null;
            //
            if (eDoc.assigned_employee_list != null && eDoc.assigned_employee_list != '' && eDoc.assigned_employee_list == 'all') {
                SE = ['-1'];
            }
            if (eDoc.assigned_employee_list != null && eDoc.assigned_employee_list != '' && eDoc.assigned_employee_list != 'all') {
                SE = JSON.parse(eDoc.assigned_employee_list);
            }
            //
            $(`.assignAndSendDocument[value="${eDoc.assign_type}"]`).prop('checked', true).trigger('change');
            $('.assignSelectedEmployees').select2('val', SE);
            $('.jsDatePicker').val(eDoc.assign_date)
            if (eDoc.assign_type == 'weekly') $('#jsCustomDaySLT').select2('val', eDoc.assign_date);
            $('.jsTimePicker').val(eDoc.assign_time)
        } else $('.assignAndSendDocument[value="none"]').prop('checked', true);
    });
</script>