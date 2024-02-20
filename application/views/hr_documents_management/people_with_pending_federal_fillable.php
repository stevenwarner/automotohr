
<style>
.select2-container {
/*    max-height: 100px;
    overflow-y: auto;*/
  /*min-width: 400px;*/
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
  font-family:fontAwesome;
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
    box-shadow: 0 0 10px rgba(0,0,0,0.5);

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
.select-icon  .select2-search--dropdown {
    display: none;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice{
    height: 25px !important;
}
.select2-container--default .select2-selection--multiple .select2-selection__rendered{
    max-height: 100px;
    overflow-y: auto;
}

.popover-content p{
    padding: 10px;
}
.popover-content p:nth-child(odd){
    background: #eee;
}
</style>

<div class="main-content">
    <!--  -->
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('main/manage_ems_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <?php $this->load->view('loader', ['props' => 'id="jsEmployeeEmailLoader"']);?>
                    <div class="row">
                        <div class="col-xs-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="page-header-area">
                                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                            <a class="dashboard-link-btn" href="<?php echo base_url('hr_documents_management'); ?>"><i class="fa fa-chevron-left"></i>Document Management</a>
                                            <?php echo $title; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Filter -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-success">
                                        <div class="panel-heading" style="background-color: #81b431; color: #ffffff;">
                                            <h3 class="panel-title">Search</h3>
                                        </div>
                                        <div class="panel-body">
                                            <form action="javascript:void(0)" id="js-filter-form">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Employee(s)</label>
                                                            <select id="js-filter-employees" name="employees[]" multiple="true">
                                                                <?php 
                                                                    foreach ($employeesList as $k => $v) {
                                                                        echo '<option value="'.( $v['sid'] ).'" '.( isset($selectedEmployeeList[$v['sid']]) ? 'selected="selected"' : ''  ).'>'.( remakeEmployeeName( $v ) ).'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Federal Document(s)</label>
                                                            <select id="js-filter-documents" name="documents[]" multiple="true">
                                                                <option value="w4" <?php echo in_array('w4', $selectedDocumentList) ? 'selected="selected"' : ''; ?>>W4</option>
                                                                <option value="i9" <?php echo in_array('i9', $selectedDocumentList) ? 'selected="selected"' : ''; ?>>I9</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <span class="pull-right">
                                                            <button class="btn btn-success js-filter-search">Search</button>
                                                            <button type="button" class="btn btn-success js-filter-clear">Clear Filter</button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <!--  <div class="row">-->
                                        <!-- <div class="col-xs-12">-->
                                            <!-- <h3 class="">Employees</h3> -->
                                        <!-- </div>-->
                                    <!-- </div>-->


                                    <?php
                                    if ( isset($employees) && count($employees) > 0) {
                                        ?>
                                        <div class="table-responsive">
                                            <h3>Employees with Pending Document Actions 
                                                <span class="pull-right">
                                                    <button class="btn btn-success jsSendEmailReminder">
                                                        Send Email Reminder
                                                    </button>
                                                    <button class="btn btn-success js-action-btn" data-type="print">Print</button>
                                                    <button class="btn btn-success js-action-btn" data-type="export">Export</button>
                                                </span>
                                            </h3>
                                            <div class="hr-document-list">
                                                <table class="hr-doc-list-table">
                                                    <caption></caption>
                                                    <thead>
                                                    <tr>
                                                        <th scope="col" style="vertical-align: middle;">
                                                            <label class="control control--checkbox">
                                                                <input type="checkbox" id="jsSelectAll" />
                                                                <div class="control__indicator" style="top: -7px;"></div>
                                                            </label>
                                                        </th>
                                                        <th scope="col">Employee Name</th>

                                                        <?php if (in_array('w4', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                                            <th scope="col">W4 Status</th>
                                                        <?php } ?>

                                                        <?php if (in_array('i9', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                                            <th scope="col">I9 Status</th>
                                                        <?php } ?>
                                                        
                                                        <th scope="col" style="text-align: right" >View Document(s)</th>
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
                                                            $i9_popup_text = '';
                                                            $w4_popup_text = '';
                                                            $show_view_btn = false;
                                                            $w4_status = '<strong class="text-warning">Not Assigned</strong>';
                                                            $i9_status = '<strong class="text-warning">Not Assigned</strong>';
                                                            //
                                                            foreach ($employee['Documents'] as $ke => $v) {
                                                                //
                                                                
                                                                
                                                                //
                                                                if ($v['Title'] == "W4 Fillable") { 
                                                                    if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                                                        if ($v['Status'] == "pending") {
                                                                            $w4_status = '<strong class="text-danger">Pending</strong>';
                                                                            $show_view_btn = true;
                                                                        } else {
                                                                            $w4_status = '<strong class="text-success">Completed</strong>';
                                                                        }
                                                                        
                                                                        //
                                                                        $w4_popup_text = '<p>';
                                                                        $w4_popup_text .= ' <strong>'.( $v['Title'] ).'</strong> ('.($v['Type']).')';
                                                                        $w4_popup_text .= ' <br /> <em><strong>Assigned On:</strong> '.($v['AssignedOn']).'';
                                                                        if(!empty($v['Days'])){
                                                                            $w4_popup_text .= ' ('.$v['Days'].' day'.($v['Days'] == 1 ? '' : 's').' ago)';
                                                                        }
                                                                        $w4_popup_text .= ' </em>';
                                                                        $w4_popup_text .= '</p>';    
                                                                    } else { 
                                                                        $w4_popup_text = '<p>';
                                                                        $w4_popup_text .= ' <strong>'.( $v['Title'] ).'</strong> ('.($v['Type']).')';
                                                                        $w4_popup_text .= ' <br /> <em><strong>W4 Federal Fillable</strong> not assigned to '.remakeEmployeeName($employee).' </em>';
                                                                        $w4_popup_text .= '</p>';
                                                                    }
                                                                }
                                                                //
                                                                
                                                                
                                                                //
                                                                if ($v['Title'] == "I9 Fillable") {
                                                                    if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                                                        if ($v['Status'] == "pending") {
                                                                            $i9_status = '<strong class="text-danger">Pending</strong>';
                                                                            $show_view_btn = true;
                                                                        } else {
                                                                            $i9_status = '<strong class="text-success">Completed</strong>';
                                                                        }
                                                                        
                                                                        //
                                                                        $i9_popup_text = '<p>';
                                                                        $i9_popup_text .= ' <strong>'.( $v['Title'] ).'</strong> ('.($v['Type']).')';
                                                                        $i9_popup_text .= ' <br /> <em><strong>Assigned On:</strong> '.($v['AssignedOn']).'';
                                                                        if(!empty($v['Days'])){
                                                                            $i9_popup_text .= ' ('.$v['Days'].' day'.($v['Days'] == 1 ? '' : 's').' ago)';
                                                                        }
                                                                        $i9_popup_text .= ' </em>';
                                                                        $i9_popup_text .= '</p>';
                                                                    } else {

                                                                        $i9_popup_text = '<p>';
                                                                        $i9_popup_text .= ' <strong>'.( $v['Title'] ).'</strong> ('.($v['Type']).')';
                                                                        $i9_popup_text .= ' <br /> <em><strong>I9 Federal Fillable</strong> not assigned to '.remakeEmployeeName($employee).' </em>';
                                                                        $i9_popup_text .= '</p>';
                                                                    }
                                                                }
                                                                //
                                                                
                                                            }
                                                        }
                                                        ?>
                                                        <tr data-id="<?=$employee['sid'];?>">
                                                            <td style="vertical-align: middle;">
                                                                <?php if ($employee['any_pending'] == 'yes') { ?>
                                                                    <label class="control control--checkbox">
                                                                        <input type="checkbox" class="jsSelectSingle" />
                                                                        <div class="control__indicator" style="top: -7px;"></div>
                                                                    </label>
                                                                <?php } else { ?>
                                                                    <label class="control control--checkbox">
                                                                        <input type="checkbox" class="" disabled />
                                                                        <div class="control__indicator" style="top: -7px;"></div>
                                                                    </label>
                                                                <?php } ?>
                                                            </td>
                                                            <td><a href="<?php echo getEmployeeProfileLink($employee['sid']);?>" target="_blank" class="text" style="font-weight: bold; color:#272727;"><?=remakeEmployeeName($employee);?><br><?php echo $employee['email']; ?></a></td>
                                                            <?php if (in_array('w4', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                                                <td
                                                                    style="cursor: pointer"
                                                                    data-container="body" 
                                                                    data-toggle="cpopover"
                                                                    data-placement="left" 
                                                                    data-title="W4 Status"
                                                                    data-content="<?=$w4_popup_text;?>">
                                                                    <?php echo $w4_status; ?>
                                                                </td>
                                                            <?php } ?>
                                                            <?php if (in_array('i9', $selectedDocumentList) || in_array('all', $selectedDocumentList)) { ?>
                                                                <td
                                                                    style="cursor: pointer"
                                                                    data-container="body" 
                                                                    data-toggle="cpopover"
                                                                    data-placement="left" 
                                                                    data-title="I9 Status"
                                                                    data-content="<?=$i9_popup_text;?>">
                                                                    <?php echo $i9_status; ?>
                                                                </td>
                                                            <?php } ?>
                                                            <td class="text-right">
                                                                <?php if ($show_view_btn) { ?>
                                                                    <a data-toggle="tooltip" title="View all pending Documents" data-placement="left" href="<?php echo base_url('hr_documents_management/employee_document'); ?>/<?php echo $employee['sid']; ?>" class=" btn-sm btn-default">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                <?php } ?>
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
                                            <h3>Employees with Pending Federal Document Actions</h3>
                                            <div class="hr-document-list">
                                                <table class="hr-doc-list-table">
                                                    <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Email</th>
                                                        <th style="text-align: right" >View Document(s)</th>
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function fLaunchModal(source) {
        console.log(source);

        var document_preview_url = $(source).attr('data-preview-url');
        var document_download_url = $(source).attr('data-download-url');
        var document_title = $(source).attr('data-document-title');
        var document_file_name = $(source).attr('data-file-name');

        var file_extension = document_file_name.substr(document_file_name.lastIndexOf('.') + 1, document_file_name.length);

        var modal_content = '';
        var footer_content = '';

        var iframe_url = '';

        if (document_preview_url != '') {
            switch (file_extension.toLowerCase()) {
                case 'doc':
                case 'docx':
                    console.log('in office docs check');
                    //using office docs
                    iframe_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' + document_preview_url;
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
                case 'jpg':
                case 'jpe':
                case 'jpeg':
                case 'png':
                case 'gif':
                    console.log('in images check');
                    modal_content = '<img src="' + document_preview_url + '" style="width:100%; height:500px;" />';
                default :
                    console.log('in google docs check');
                    //using google docs
                    iframe_url = 'https://docs.google.com/gview?url=' + document_preview_url + '&embedded=true';
                    modal_content = '<iframe src="' + iframe_url + '" id="preview_iframe" class="uploaded-file-preview"  style="width:100%; height:500px;" frameborder="0"></iframe>';
                    break;
            }

            footer_content = '<a target="_blank" download="download" class="btn btn-success" href="' + document_download_url + '">Download</a>';
        } else {
            modal_content = '<h5>No ' + document_title + ' Uploaded.</h5>';
            footer_content = '';
        }

        $('#document_modal_body').html(modal_content);
        $('#document_modal_footer').html(footer_content);
        $('#document_modal_title').html(document_title);
        $('#document_modal').modal("toggle");
        $('#document_modal').on("shown.bs.modal", function () {
            if (iframe_url != '') {
                $('#preview_iframe').attr('src', iframe_url);
                //document.getElementById('preview_iframe').contentWindow.location.reload();
            }
        });


    }

    function func_get_generated_document_preview(document_sid, user_type, user_sid) {
        var my_request;
        my_request = $.ajax({
            'url': '<?php echo base_url('documents_management/ajax_responder'); ?>',
            'type': 'POST',
            'data': {
                'perform_action': 'get_generated_document_preview',
                'document_sid': document_sid,
                'user_type': user_type,
                'user_sid': user_sid,
                'source': 'assigned'
            }
        });

        my_request.done(function (response) {
            $('#popupmodalbody').html(response);
            $('#popupmodallabel').html('Preview Generated Document');

            $('#popupmodal .modal-dialog').css('width', '60%');
            $('#popupmodal').modal('toggle');
        });
    }
</script>

<!-- Modal -->
<div id="document_modal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-header-bg">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="document_modal_title">Modal title</h4>
            </div>
            <div id="document_modal_body" class="modal-body">

            </div>
            <div id="document_modal_footer" class="modal-footer">

            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        //
        $('#js-filter-employees').select2({ closeOnSelect: false });
        //
        $('#js-filter-documents').select2({ closeOnSelect: false });
        //
        $('#js-filter-form').submit(function(e){
            //
            e.preventDefault();
            //
            var url = '';
            //
            url += ( $('#js-filter-employees').val() == null ? 'all' : $('#js-filter-employees').val().join(':') )+'/';
            url += ( $('#js-filter-documents').val() == null ? 'all' : $('#js-filter-documents').val().join(':') )+'/';
            //
            window.location = "<?=base_url('hr_documents_management/people_with_pending_federal_fillable');?>/" + url;
        });

        //
        $('.js-action-btn').click(function(e){
            //
            e.preventDefault();
            //
            var url = '';
            //
            url += ( $('#js-filter-employees').val() == null ? 'all' : $('#js-filter-employees').val().join(':') )+'/';
            url += ( $('#js-filter-documents').val() == null ? 'all' : $('#js-filter-documents').val().join(':') )+'/';
            url += $(this).data('type');

            if( $(this).data('type') == 'print' ){
                openInNewTab("<?=base_url('hr_documents_management/people_with_pending_federal_fillable');?>/" + url);
            }else{
                //
                window.location = "<?=base_url('hr_documents_management/people_with_pending_federal_fillable');?>/" + url;
            }
        });

        function openInNewTab(url) {
            var win = window.open(url, '_blank');
            win.focus();
        }

        //
        $('[data-toggle="cpopover"]').popover({
            trigger: 'hover click',
            placement: 'left auto',
            html: true
        });
        //
        $('.js-filter-clear').click(function(e) {
            e.preventDefault();
            window.location = "<?=base_url('hr_documents_management/people_with_pending_federal_fillable');?>";
        });
    });


    $(function(){
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
                employeeWithDocuments.map(function(employee){
                    if (employee.any_pending == 'yes') {
                        senderList.push(employee);
                    }
                })
                console.log(senderList)
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
                company_sid: "<?=$session['company_detail']['sid'];?>",
                company_name: "<?=$session['company_detail']['CompanyName'];?>"
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