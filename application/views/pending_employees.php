<style>
.popover-content p {
    padding: 10px;
}

.popover-content p:nth-child(odd) {
    background: #eee;
}

</style>


<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


        <?php
    if ( isset($employees) && count($employees) > 0) {
        ?>
        <div class="table-responsive">
            <h3>Employees with Pending Document Actions
                <span class="pull-right">
                    <button class="btn btn-success jsSendEmailReminder">
                        Send Email Reminder
                    </button>
                </span>
            </h3>
            <div class="hr-document-list">
                <table class="hr-doc-list-table">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">
                                <label class="control control--checkbox">
                                    <input type="checkbox" id="jsSelectAll" />
                                    <div class="control__indicator"></div>
                                </label>
                            </th>
                            <th scope="col">Employee Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Document(s)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                    function dateSorter($a, $b){
                        return $a['AssignedOn'] < $b['AssignedOn'];
                    }
                    foreach ($employees as $employee) {
                        $icount = sizeof($employee['Documents']);
                        $itext = '';
                        if(sizeof($employee['Documents'])){
                            //
                            usort($employee['Documents'], 'dateSorter');
                            foreach ($employee['Documents'] as $ke => $v) {
                                //
                                $assignedByText = '';
                                //
                                if(isset($v['AssignedBy'])){
                                    $assignedBy = getUserNameBySID($v['AssignedBy']);
                                    $assignedByText = '<br /> <em>Assigned By: '.( $assignedBy ).'</em>';

                                }
                                $itext .= '<p>';
                                $itext .= ' <strong>'.( $v['Title'] ).'</strong> ('.($v['Type']).')';
                                $itext .= ' <br /> <em>Assigned On: '.($v['AssignedOn']).'';
                                if(!empty($v['Days'])){
                                    $itext .= ' ('.$v['Days'].' day'.($v['Days'] == 1 ? '' : 's').' ago)';
                                }
                                $itext .= ' </em>';
                                $itext .= $assignedByText;
                                $itext .= '</p>';
                            }
                        }
                        ?>
                        <tr data-id="<?=$employee['sid'];?>">
                            <td>
                                <label class="control control--checkbox">
                                    <input type="checkbox" class="jsSelectSingle" />
                                    <div class="control__indicator"></div>
                                </label>
                            </td>
                            <td><?=remakeEmployeeName($employee);?></td>
                            <td><?php echo $employee['email']; ?></td>
                            <td style="cursor: pointer" data-container="body" data-toggle="cpopover"
                                data-placement="left" data-title="<?=$icount;?> Document(s)"
                                data-content="<?=$itext;?>">
                                <strong><?=$icount;?></strong> Document(s)
                            </td>
                        </tr>
                        <?php }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="table-responsive">
            <h3>Employees with Pending Document Actions</h3>
            <div class="hr-document-list">
                <table class="hr-doc-list-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Email</th>
                            <th style="text-align: right">View Document(s)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center">
                            <td colspan="3">No employee with pending document(s)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }?>


    </div>
</div>

<script>
     $(function(){
        $('[data-toggle="cpopover"]').popover({
            trigger: 'hover click',
            placement: 'left auto',
            html: true
        });
        //
        var tmpEmployeeHolder = {};
        //
        var current = 1;
        //
        var total = 0;
        //
        var employeeWithDocuments = <?=json_encode($employees);?>;
        //
        var selectedEmployees = {};
        //
        $('#jsSelectAll').click(function(){
            //
            selectedEmployees = {};
            //
            $('.jsSelectSingle').prop('checked', false);
            //
            if($('#jsSelectAll').prop('checked')){
                selectedEmployees[-1] = true;
                $('.jsSelectSingle').prop('checked', true);
            }
        });
        //
        $('.jsSelectSingle').click(function(){
            useSelect();
        });
        
        //
        $('.jsSendEmailReminder').click(function(event){
            //
            event.preventDefault();
            //
            var ids = Object.keys(selectedEmployees);
            //
            if(ids.length === 0){
                alertify.alert('Error!', 'Please select at least one employee.', function(){
                    return;
                });
                //
                return;
            }
            //
            var senderList = [];
            //
            if(selectedEmployees[-1] !== undefined){
                senderList = employeeWithDocuments;
            } else{
                ids.map(function(id){
                    senderList.push(getSingleEmployee(id));
                });
            }
            //
            alertify.confirm('Do you really want to send email reminders to the selected employees?', function(){
                //
                current = 1;
                //
                total = senderList.length;
                //
                startSendEmailProcess(senderList);
            });
        });

        //
        function useSelect(){
            //
            var single = $('.jsSelectSingle:checked');
            //
            selectedEmployees = {};
            //
            if($('.jsSelectSingle:checked').length != $('.jsSelectSingle').length){
                $('#jsSelectAll').prop('checked', false);
            } else{
                selectedEmployees[-1] = true;
                $('#jsSelectAll').prop('checked', true);
            }
            //
            if(single){
                single.map(function(e){
                    selectedEmployees[$(this).closest('tr').data('id')] = true;
                });
            }
        }

        //
        function getSingleEmployee(employeeId){
            //
            if(tmpEmployeeHolder[employeeId] !== undefined){
                return tmpEmployeeHolder[employeeId];
            }
            //
            var i = 0,
            il = employeeWithDocuments.length;
            //
            for(i; i<il;i++){
                //
                tmpEmployeeHolder[employeeWithDocuments[i]['sid']] = employeeWithDocuments[i];
                //
                if(employeeWithDocuments[i]['sid'] == employeeId){
                    return employeeWithDocuments[i];
                }
            }
            //
            return;
        }

        //
        function startSendEmailProcess(list){
            //
            var index = current;
            //
            var employee = list[--index];
            //
            if(employee === undefined){
                //
                loader(false);
                //
                alertify.alert('Success!','You have successfully sent an email reminder to selected employees', function(){ return; });
                //
                return;
            }
            //
            var text = '<p>Please wait, while we are sending email to <b>'+( employee.first_name +' '+employee.last_name )+'</b></p><p>'+(current)+' of '+(total)+'</p>';
            //
            loader(true, text);
            //
            $.post("<?=base_url('send_manual_reminder_email_to_employee');?>", {
                first_name: employee.first_name,
                last_name: employee.last_name,
                email: employee.email,
                company_sid: "<?=$company['sid'];?>",
                company_name: "<?=$company['CompanyName'];?>"
            }).done(function(){
                //
                current++;
                //
                startSendEmailProcess(list);
            });
        }

        //
        function loader(doShow, text){
            //
            if(doShow){
                $('#jsEmployeeEmailLoader').show(0);
                $('#jsEmployeeEmailLoader .jsLoaderText').html(text);
            } else{
                $('#jsEmployeeEmailLoader').hide(0);
                $('#jsEmployeeEmailLoader .jsLoaderText').html('Please wait, while we are processing your request.');
            }
        }
    });
</script>