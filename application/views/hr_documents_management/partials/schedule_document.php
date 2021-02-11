<style>
    .csModalLoader{
        position: absolute;
        right: 0;
        left: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        background: #ffffff;
        z-index: 1;
    }
    .csModalLoader i{
        position: relative;
        top: 50%;
        left: 50%;
        font-size: 30px;
        transform: translate(-50%, -50%);
        color: #81b431;
    }
</style>

<!-- Modal -->
<div class="modal fade" id="jsScheduleModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #81b431; color: #fff;">
                <h5 class="modal-title"><span></span></h5>
            </div>
            <div class="modal-body">
                <div class="csModalLoader jsModalLoader"><i class="fa fa-spin fa-circle-o-notch"></i></div>
                <!--  -->
                <div class="row">
                    <!-- Selection row -->
                    <div class="col-sm-12">
                        <!-- None -->
                        <label class="control control--radio">
                            None &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="none"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Daily -->
                        <label class="control control--radio">
                            Daily &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="daily"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Weekly -->
                        <label class="control control--radio">
                            Weekly &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="weekly"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Monthly -->
                        <label class="control control--radio">
                            Monthly &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="monthly"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Yearly -->
                        <label class="control control--radio">
                            Yearly &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="yearly"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!-- Custom -->
                        <label class="control control--radio">
                            Custom &nbsp;&nbsp;
                            <input type="radio" name="assignAndSendDocument" class="assignAndSendDocument" value="custom"/>
                            <div class="control__indicator"></div>
                        </label>
                        <!--  -->
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <!-- Custom date row -->
                    <div class="col-sm-12 jsCustomDateRow">
                        <br />
                        <label id="jsCustomLabel">Select a date & time</label>
                        <div class="row">
                            <div class="col-sm-4" id="jsCustomDate">
                                <input type="text" class="form-control jsDatePicker" name="assignAndSendCustomDate" readonly="true" />
                            </div>
                            <div class="col-sm-3" id="jsCustomDay">
                                <select id="jsCustomDaySLT">
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">Wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                                    <option value="7">Sunday</option>
                                </select>
                            </div>
                            <div class="col-sm-4 nopaddingleft">
                                <input type="text" class="form-control jsTimePicker" name="assignAndSendCustomTime" readonly="true" />
                            </div>
                        </div>
                    </div>
                </div>
                <!--  -->
                <div class="row">
                    <div class="col-sm-12"><hr /></div>
                </div>
                <!--  -->
                <div class="row">
                    <!-- Against Selected Employees -->
                    <div class="col-sm-12">
                        <label>Employee(s)</label>
                        <select multiple="true" name="assignAdnSendSelectedEmployees[]" class="assignSelectedEmployees">
                            <option value="-1">All</option>
                            <?php foreach($employeesList as $key => $employee) { ?>
                                <option value="<?=$employee['sid'];?>"><?=remakeEmployeeName($employee);?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success jsModalSaveBTN">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function(){
        //
        let 
        selectedDocument = {},
        allDocuments = <?=json_encode($allDocuments);?>;
        //
        $('#jsCustomDaySLT').select2();

        //
        $('.jsScheduleDocument').click(function(e){
            //
            e.preventDefault();
            //
            $('.jsModalLoader').show();
            $('.jsModalSaveBTN').hide();
            $('#jsScheduleModal').find('.modal-header span').text($(this).data('title'));
            $('#jsScheduleModal').modal('show');
            //
            checkAndGetScheduleDocument($(this).data('id'));
        });

        //
        $('.jsModalSaveBTN').click((e) => {
            //
            e.preventDefault();
            //
            let obj = {};
            obj.assignAndSendDocument = $('.assignAndSendDocument:checked').val();
            obj.assignAdnSendSelectedEmployees = $('.assignSelectedEmployees').val();
            obj.assignAndSendCustomDate = $('input[name="assignAndSendCustomDate"]').val();
            obj.assignAndSendCustomDay = $('#jsCustomDaySLT').val();
            obj.assignAndSendCustomTime = $('input[name="assignAndSendCustomTime"]').val();
            obj.documentSid = selectedDocument.sid;
            //
            $('.jsModalLoader').show();
            $('.jsModalSaveBTN').hide();
            //
            $.post(
                "<?=base_url('hr_documents_management/set_schedule_document');?>",
                obj,
                (resp) => {
                    //
                    if(resp == 'success'){
                        $('#jsScheduleModal').modal('hide');
                        alertify.alert('SUCCESS!', 'You have successfully updated the document.', () => {
                            window.location.reload();
                        });
                        return;
                    }
                    $('.jsModalLoader').hide();
                    $('.jsModalSaveBTN').show();
                    alertify.alert('ERROR!', 'Something went wrong while updating the document. Please, try again in a few moments.', () => {});
                }
            );

        });

        //
        function checkAndGetScheduleDocument(
            documentId
        ){
            //
            if(
                allDocuments[documentId] === undefined
            ){
                alertify.alert('ERROR!', 'The system in unable to verify this document.', () => {});
                return;
            }
            //
            selectedDocument = allDocuments[documentId];
            console.log(selectedDocument);
            //
            let SE = null;
            //
            if(selectedDocument.assigned_employee_list != null && selectedDocument.assigned_employee_list != '' && selectedDocument.assigned_employee_list == 'all'){
                SE = ['-1'];
            }
            if(selectedDocument.assigned_employee_list != null && selectedDocument.assigned_employee_list != '' && selectedDocument.assigned_employee_list != 'all'){
                SE = JSON.parse(selectedDocument.assigned_employee_list);
            }
            //
            $(`.assignAndSendDocument[value="${selectedDocument.assign_type}"]`).prop('checked', true).trigger('change');
            $('.assignSelectedEmployees').select2('val', SE);
            $('#jsCustomDaySLT').select2('val', selectedDocument.assign_date);
            $('.jsDatePicker').val(selectedDocument.assign_date);
            $('.jsTimePicker').val(selectedDocument.assign_time);
            //
            $('.jsModalLoader').hide();
            $('.jsModalSaveBTN').show();
        }

        //
        $('.assignSelectedEmployees').select2({ closeOnSelect: false });
        //
        $('.assignAndSendDocument[value="none"]').prop('checked', true);
        
        //
        $('.assignAndSendDocument').change(function(){
            //
            $('.jsCustomDateRow').show();
            $('#jsCustomDay').hide();
            $('#jsCustomLabel').text('Select a date & time');
            $('#jsCustomDate').show();
            $('.jsDatePicker').datepicker('option', { changeMonth: true });
            //
            if($(this).val().toLowerCase() == 'daily'){
                $('#jsCustomLabel').text('Select time');
                $('#jsCustomDate').hide();
            } else if($(this).val().toLowerCase() == 'monthly'){
                $('#jsCustomLabel').text('Select a date & time');
                $('.jsDatePicker').datepicker('option', { dateFormat: 'dd' });
                $('.jsDatePicker').datepicker('option', { changeMonth: false });
            } else if($(this).val().toLowerCase() == 'weekly'){
                $('#jsCustomDate').hide();
                $('#jsCustomDay').show();
                $('#jsCustomLabel').text('Select day & time');
            } else if($(this).val().toLowerCase() == 'yearly' || $(this).val().toLowerCase() == 'custom'){
                $('.jsDatePicker').datepicker('option', { dateFormat: 'mm/dd' });
            } else if($(this).val().toLowerCase() == 'none'){
                $('.jsCustomDateRow').hide();
            }
        });
        
        //
        $('.jsDatePicker').datepicker({
            changeMonth: true,
            dateFormat: 'mm/dd'
        });

        //
        $('.jsTimePicker').datetimepicker({
            datepicker: false,
            format: 'g:i A',
            formatTime: 'g:i A',
            step: 15
        });
    });
</script>