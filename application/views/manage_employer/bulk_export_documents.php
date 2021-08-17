<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
            </div>
            <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                <div class="dashboard-conetnt-wrp">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php echo $title; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="well">
                                <div class="help-block">
                                    <h3><strong>Export Bulk Documents: </strong></h3>
                                    <h4>To export bulk documents, follow the steps below;</h4>
                                    <h4>1- Select an Employee or Applicant or Document(s). </h4>
                                    <h4>3- After selecting employees/applicants, click on the 'Export Document' button.</h4>
                                    <h4><strong>Depending on the number of documents this could take a bit of time to download, <span class="text-success">Please be patient.</span></strong></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <div role="tabpanel" id="js-main-page">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs cs-tab js-tab" role="tablist">
                                            <li role="presentation"  <?=$type == 'employee' ? 'class="active"' : '';?> >
                                                <a href="#employee-box" aria-controls="tab" role="tab" data-toggle="tab">Employee(s)</a>
                                            </li>
                                            <li role="presentation"  <?=$type == 'applicant' ? 'class="active"' : '';?>>
                                                <a href="#applicant-box" aria-controls="home" role="tab" data-toggle="tab">Applicant(s)</a>
                                            </li>
                                            <li role="presentation"  <?=$type == 'documents' ? 'class="active"' : '';?>>
                                                <a href="#documents-box" aria-controls="home" role="tab" data-toggle="tab">Document(s)</a>
                                            </li>
                                        </ul>

                                        <!-- Employee, Applicant boxes -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <!-- Tab panes -->
                                                <div class="tab-content">
                                                    <!-- Employee Box -->
                                                    <div role="tabpanel" class="tab-pane <?=$type == 'employee' ? 'active' : '';?>" id="employee-box">
                                                        <?php if($downloadDocumentData && count($downloadDocumentData) && $downloadDocumentData['user_type'] == 'employee' && $downloadDocumentData['download_type'] == 'bulk_download' && file_exists(APPPATH.'../temp_files/employee_export/'.$downloadDocumentData['folder_name'])){ ?>
                                                            <br />
                                                            <div class="alert alert-success">Last export was generated at <?=DateTime::createFromFormat('Y-m-d H:i:s', $downloadDocumentData['created_at'])->format('m/d/Y H:i');?>. <a class="btn btn-success" href="<?=base_url('hr_documents_management/generate_zip/'.($downloadDocumentData['folder_name']).'');?>">Download</a></div>
                                                        <?php } ?>
                                                        <div class="form-group">
                                                            <h4>Select status <span class="cs-required">*</span></h4>
                                                            <select class="invoice-fields form-control" id="js-employee-status" multiple="true">
                                                                <option value="all">All</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">In-Active</option>
                                                                <option value="terminated">Terminated</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group" style="display: none;">
                                                            <h4>Select employee(s) <span class="cs-required">*</span> <strong id="jsEmployeeCount">0</strong></h4>
                                                            <select class="invoice-fields form-control" id="js-employee-select" multiple="true"></select>
                                                        </div>
                                                        <?php if (!empty($download_document_link)) { ?>
                                                            <span class="download_document_note alert alert-success">
                                                                <?php echo reset_datetime(array('datetime' => $download_document_create_date, '_this' => $this)); ?>
                                                                <a href="javascript:;" target="_blank" class="btn btn-success pull-right">Download ZIP</a>
                                                            </span>
                                                        <?php } ?> 
                                                    </div>
                                                    <!-- Applicant Box -->
                                                    <div role="tabpanel" class="tab-pane  <?=$type == 'applicant' ? 'active' : '';?> cs-applicant-box" id="applicant-box">
                                                        <?php if($downloadDocumentData && count($downloadDocumentData) && $downloadDocumentData['user_type'] == 'applicant'){ ?>
                                                            <br />
                                                            <div class="alert alert-success">Last export was generated at <?=DateTime::createFromFormat('Y-m-d H:i:s', $downloadDocumentData['created_at'])->format('m/d/Y H:i');?>. <a class="btn btn-success" href="<?=base_url('hr_documents_management/generate_zip/'.($downloadDocumentData['folder_name']).'');?>">Download</a></div>
                                                        <?php } ?>
                                                        <div class="form-group">
                                                            <h4>Select applicant(s) <span class="cs-required">*</span></h4>
                                                            <select class="invoice-fields form-control" id="js-applicant-select" multiple="true"></select>
                                                        </div>
                                                        <?php if (!empty($download_document_link)) { ?>
                                                            <span class="download_document_note alert alert-success">
                                                                <?php echo reset_datetime(array('datetime' => $download_document_create_date, '_this' => $this)); ?>
                                                                <a href="javascript:;" target="_blank" class="btn btn-success pull-right">Download ZIP</a>
                                                            </span>
                                                        <?php } ?>
                                                    </div>
                                                    <!-- Document Box -->
                                                    <div role="tabpanel" class="tab-pane  <?=$type == 'documents' ? 'active' : '';?> cs-documents-box" id="documents-box">
                                                        <!-- Filter -->
                                                        <br>
                                                        <div class="panel panel-success">
                                                            <div class="panel-body">
                                                                <div class="row">
                                                                    <div class="col-sm-10">
                                                                        <select id="jsDocumentSearch" multiple>
                                                                        <?php 
                                                                            if(!empty($documents)) {
                                                                                foreach($documents as $document){
                                                                                    ?>
                                                                                    <option value="<?=$document['id'];?>"><?=$document['title'];?></option>
                                                                                    <?php
                                                                                }
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-sm-2">
                                                                        <button class="btn btn-success form-control" id="jsDocumentSearchBTN">Search</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--  -->
                                                        <?php
                                                            if(empty($documents)){
                                                                ?>
                                                                <br />
                                                                <div class="panel panel-success">
                                                                    <div class="panel-body">
                                                                        <p class="alert alert-info text-center">No completed documents found</p>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                            } else{
                                                                foreach($documents as $document){
                                                                ?>
                                                                <br />
                                                                <div class="panel panel-success jsDocumentPanel" data-id="<?=$document['id'];?>">
                                                                    <div class="panel-heading">
                                                                        <label class="control control--checkbox">
                                                                            <input type="checkbox" class="jsSelectAllDocuments" name="jsSelectAllDocuments<?=$document['id'];?>" />
                                                                            <div class="control__indicator" style="top: -10px;"></div>
                                                                        </label>
                                                                        &nbsp;&nbsp; <strong class="csW csF16"><?=$document['title']?></strong>
                                                                    </div>
                                                                    <div class="panel-body">
                                                                        <div class="row">
                                                                            <?php foreach($document['employees'] as $emp){ ?>
                                                                            <div class="col-sm-6">
                                                                                <label class="control control--checkbox">
                                                                                    <input type="checkbox" class="jsSelectSingleDocument" name="jsSelectSingleDocument<?=$document['id'];?>" data-id="<?=$emp['sid'];?>" data-aid="<?=$emp['a_sid'];?>" /> <?=$emp['name'];?>
                                                                                    <div class="control__indicator"></div>
                                                                                </label>
                                                                            </div>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        
                                        <br />
                                        <!-- Upload BTN  -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-success pull-right js-export-btn">Export Documents</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  -->
<div id="js-export-area" style="
position: fixed;
left: -1000px;
width: 800px;
top: 0;
overflow: hidden;
padding: 16px;
font-size: 16px,
word-break: break-all;
">
<div class="A4">
</div>
</div>

<div id="my_loader" class="text-center my_loader js-body-loader">
    <div id="file_loader" class="file_loader" style="display:block; height:1353px;"></div>
    <div class="loader-icon-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text js-body-loader-text" style="display:block; margin-top: 35px;">
            <p>Please wait, while we are generating a preview...</p>
        </div>
    </div>
</div>

<style>
    /* */
    .cs-tab li > a{ color: #000000; }
    .cs-tab li.active > a{ background-color: #81b431 !important; color: #ffffff !important; }
    .cs-applicant-box i{ position: absolute; top: 50%; right: 30px; font-size: 20px; margin-top: -16px; color: #81b431; }
    .cs-custom-input{ margin-bottom: 10px; }
    .cs-custom-input input{ height: 40px;}
    .cs-custom-input .input-group-addon{ background: 0; padding: 0; border: none; }
    .cs-custom-input .input-group-addon > input{ margin: 0; border-radius: 0; }
    .cs-error, .cs-required{ font-weight: bolder; color: #cc0000; }
    .cs-dropzone{ position: relative; display: inline-block; width: 100%; }
    .cs-drag-overlay{ position: absolute; top: 0; bottom: 0; left: 0; right: 0; width: 100%; background-color:  rgba(255,255,255,.7); z-index: 10; display: none; }
    .cs-drag-overlay p{ line-height: 40px; font-size: 18px; }
    .select2-container--default .select2-selection--single{ border: 1px solid #aaaaaa !important;  padding: 3px 5px !important; }
    .loader-text{ background-color: #fff !important; color: #000 !important; }
</style>

        <script type="text/javascript" src="<?php echo base_url('assets/employee_panel/js/kendoUI.min.js'); ?>"></script>

<script>
    $(function(){
        var megaOBJ = {
            type: '<?=$type;?>',
            ids: []
        },
        baseURI = "<?=base_url("assign-bulk-documents");?>/",
        xhr = {
            applicantXHR : null
        },
        loaders = {
            applicant: $('.js-applicant-loader'),
            body: $('.js-body-loader')
        },
        employeeList = [],
        applicantList = [],
        employeeListWI = {},
        applicantListWI = {},
        sendList = [],
        employees = [],
        token;
        //
        let selectedEmployeeList = [];
        let employeesListWithStatus = [];
        //
        var sc = 0;
        var currentEmployee = {};
        var currentApplicant = {};
        var cd = {};
        var totalAssigned = 0;
        var currentAssigned = 0;
        //
        var acc = 0;
        var currentF = null;
        var tat = null;
        var exportType = '';

        //
        $('#js-employee-status').select2();
        //
        $('#js-employee-status').change(function(e){
            //
            $('#js-employee-select').html(
                getEmployeeRows($(this).val())
            ).select2({
                closeOnSelect: false
            }).select2('val', selectedEmployeeList);
            //
            $('#js-employee-select').closest('div').show();
        });

        $('#js-employee-select').change(function(e){
            //
            selectedEmployeeList = $(this).val();
        });

        // Tab event
        $('.js-tab a').on('shown.bs.tab', function(event) {
            megaOBJ.type = $(this).text().toLowerCase().replace(/[^a-z]/g, '');
            megaOBJ.type = megaOBJ.type.substring(0, megaOBJ.type.length - 1);
        });

        //
        $('.js-export-btn').click((e) => {
            e.preventDefault();
            //
            token = new Date().getTime();
            //
            megaOBJ.ids = $(`#js-${megaOBJ.type}-select`).val();
            //
            if(megaOBJ.type == 'employee'){
                if($('#js-employee-status').val() === null){
                    alertify.alert(
                        'WARNING!',
                        `Please select the status first to load employees`,
                        () => {}
                    );
                    return;    
                }
            }
            //
            if(megaOBJ.ids === null){
                alertify.alert(
                    'WARNING!',
                    `Please select at least one ${megaOBJ.type}`,
                    () => {}
                );
                return;
            }
            //
            if(megaOBJ.type === 'employee'){
                sendList = megaOBJ.ids.indexOf('-1') !== -1 ? employeesListWithStatus : megaOBJ.ids;
                startEmployeeProcess();
                return;
            }

            if(megaOBJ.type === 'applicant'){
                sendList = megaOBJ.ids.indexOf('-1') !== -1 ? applicantList : megaOBJ.ids;
                startApplicantProcess();
                return;
            }
            //

            //
            if(megaOBJ.type === 'document'){
                //
                sendList = Object.keys(selectedDocuments);
                //
                if(sendList.length === 0){
                    alertify.alert('Please select at least one employee.');
                    return;
                }
                startEmployeeProcess();
                return;
            }
        });

        function startEmployeeProcess(){
            //
            let s = sendList[sc];
            acc = 0;
            //
            if(s === undefined){
                // Call main exporter
                finalExport();
                return;
            }
            //
            currentEmployee = employeeListWI[s];

            totalAssigned = 0;
            currentAssigned = 0;
            //
            loader(
                'show',
                `<p>Fetching documents for <strong>${currentEmployee.first_name} ${currentEmployee.last_name}</strong>.</p>`
            );
            //
            fetchEmployeeDocument();
        }
       
       
        function startApplicantProcess(){
            //
            let s = sendList[sc];
            acc = 0;
            //
            if(s === undefined){
                // Call main exporter
                finalExport();
                return;
            }
            //
            currentApplicant = applicantListWI[s];

            totalAssigned = 0;
            currentAssigned = 0;
            //
            loader(
                'show',
                `<p>Fetching documents for <strong>${currentApplicant.value}</strong>.</p>`
            );
            //
            fetchApplicantDocument();
        }

        //
        function fetchEmployeeDocument(){
            //
            var documentIds = Object.keys(selectedDocuments[currentEmployee.id]['documents']).join(':');
            //
            $.get(`<?=base_url('hr_documents_management/getDocuments');?>/${currentEmployee.id}/employee/${documentIds}`, (resp) => {
                cd = $.parseJSON(resp);
                exportType = 'employee';
                startExport();
            }).fail(() => {
                setTimeout(() => {
                    startEmployeeProcess();
                }, 1000);
            });
        }     
      
        //
        function fetchApplicantDocument(){
            $.get(`<?=base_url('hr_documents_management/getDocuments');?>/${currentApplicant.id}/applicant`, (resp) => {
                cd = $.parseJSON(resp);
                exportType = 'applicant';
                startExport();
            }).fail(() => {
                setTimeout(() => {
                    startApplicantProcess();
                }, 1000);
            });
        }     

        //
        function startExport(type){
            //
            totalAssigned = cd.Assigned.length + (cd.I9.sid !== undefined ? 1 : 0) + (cd.W9.sid !== undefined ? 1 : 0) + (cd.W4.sid !== undefined ? 1 : 0);
            totalAssigned = 
            parseInt(totalAssigned) 
            + (cd.direct_deposit != '' ? 1 : 0)
            + (cd.dependents != '' ? 1 : 0)
            + (cd.emergency_contacts != '' ? 1 : 0)
            + (cd.drivers_license != '' ? 1 : 0)
            + (cd.occupational_license != '' ? 1 : 0);
            //
            tat += totalAssigned;
            currentAssigned = 1;
            //
            if(tat === 0){
                sc++;
                if(exportType == 'employee') startEmployeeProcess();
                else startApplicantProcess();
                return;
            }
            //
            let n = exportType == 'employee' ? `${currentEmployee.first_name} ${currentEmployee.last_name}` : currentApplicant.value;
            //
            loader(
                'show', 
                `
                    <p>Exporting documents for <strong>${n}</strong>.</p>
                    <p><strong class="js-cd">0</strong> of <strong>${totalAssigned}</strong></p>
                    <div class="progress">
                        <div class="progress-bar bg-success js-progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                `
            );
            //
            exportI9();
        }

        //
        function exportI9(){
            //
            if(cd.I9.sid === undefined){
                exportW9();
                return;
            }
            //
            $('#js-export-area div').html(cd.TI9);
            generatePDF($('#js-export-area'), 'I9');
        }
        
        //
        function exportW9(){
            //
            if(cd.W9.sid === undefined){
                exportW4();
                return;
            }
            //
            $('.js-cd').text(currentAssigned);
            //
            $('#js-export-area div').html(cd.TW9);
            generatePDF($('#js-export-area'), 'W9');
        }

        //
        function exportW4(){
            //
            if(cd.W4.sid === undefined){
                getGIDocument('direct_deposit');
                return;
            }
            //
            $('.js-cd').text(currentAssigned);
            //
            $('#js-export-area div').html(cd.TW4);
            generatePDF($('#js-export-area'), 'W4');
        }
        
        //
        function getGIDocument(s){
            //
            if(s == 'direct_deposit' && cd[s] == ''){
                getGIDocument('dependents');
                return;
            }
            //
            if(s == 'dependents' && cd[s] == ''){
                getGIDocument('emergency_contacts');
                return;
            }
            //
            if(s == 'emergency_contacts' && cd[s] == ''){
                getGIDocument('drivers_license');
                return;
            }
            //
            if(s == 'drivers_license' && cd[s] == ''){
                getGIDocument('occupational_license');
                return;
            }
            //
            if(s == 'occupational_license' && cd[s] == ''){
                exportDocuments();
                return;
            }
            //
            $('.js-cd').text(currentAssigned);
            //
            $('#js-export-area div').html(cd[s]);
            generatePDF($('#js-export-area div'), s);
        }
       
        //
        function exportDocuments(){
            //
            let dct = cd.Assigned[acc];
            //
            if(cd.Assigned[acc] === undefined){
                sc++;
                if(exportType == 'employee') startEmployeeProcess();
                else  startApplicantProcess();
                return;
            }
            //
            let obj = {};
            //
            if (
                dct.document_type == 'uploaded' ||
                dct.offer_letter_type == 'uploaded'
            ) {
                obj = {
                    title: dct.document_title,
                    orig_filename: dct.document_original_name,
                    s3_filename: dct.document_s3_name
                };
                //
                uploadPDF(obj, 'document');
                //
                acc++;
            } else if (
                dct.document_type == 'generated' ||
                dct.offer_letter_type == 'generated'
            ) {
                getSubmittedDocument(dct);
            } else {
                getSubmittedDocument(dct);
            }
        }

        function getSubmittedDocument(dct){
            //
            $('#js-export-area div').css('padding', '20px');
            //
            $.get(`<?=base_url('hr_documents_management/getSubmittedDocument');?>/${dct.sid}/submitted/assigned_document/${dct.document_type}`, (resp) => {
               
                var obj = jQuery.parseJSON(resp);
                var html = obj.html;
                var form_input_data = obj.input_data;

                let o = {
                    title: dct.document_title,
                    content: html
                };
                // $('#js-export-area div').html(o.content);
                if(dct.document_type == 'hybrid_document') o.file = dct.document_s3_name;
                // Check for existing base64
                if(o.content.indexOf('data:application/pdf;base64,') !== -1 ){
                    o.content = o.content.replace(/data:application\/pdf;base64,/, '');
                    uploadPDF(o, o.title);
                } else{
                    $('#js-export-area div').html(html);
                    //
                    if($('#jsContentArea').find('select').length >= 0){
                        $('#jsContentArea').find('select').map(function(i){
                            //
                            $(this).addClass('js_select_document');
                            $(this).prop('name', 'selectDD'+i);
                        });
                    }
                    //
                    if(form_input_data != null && form_input_data != ''){
                        //
                        form_input_data = Object.entries(form_input_data);
                        //
                        
                        $.each(form_input_data, function(key ,input_value) { 
                            if(input_value[0].match(/select/) !== -1){
                                if(input_value[1] != null){
                                    let cc = get_select_box_value(input_value[0],input_value[1]);
                                    $(`select.js_select_document[name="${input_value[0]}"]`).html('');  
                                    $(`select.js_select_document[name="${input_value[0]}"]`).hide(0);    
                                    $(`select.js_select_document[name="${input_value[0]}"]`).after(`<strong style="font-size: 20px;">${cc}</strong>`);
                                }
                            }    
                        }); 
                    }
                    //
                    generatePDF($('#js-export-area div'), o);
                }
                acc++;
            });
        }
        //
        function get_select_box_value(select_box_name, select_box_val) {
            var data = select_box_val;
            let cc = '';

            if (select_box_val.indexOf(',') > -1) { 
                data = select_box_val.split(','); 
            }
            

            if($.isArray(data)) {
                let modify_string = '';
                $.each(data, function(key ,value) { 
                    if (modify_string == '') {
                        modify_string = ' '+$(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                    } else {
                        modify_string = modify_string + ', ' + $(`select.js_select_document[name="${select_box_name}"] option[value="${value}"]`).text();
                    }
                });
                cc = modify_string;
            } else {
                cc = $(`select.js_select_document[name="${select_box_name}"] option[value="${select_box_val}"]`).text();
            }
            
            return cc;
        }


        //
        function finalExport(){
            loader('hide');
            if(tat === 0){
                alertify.alert(
                    'WARNING!',
                    'No documents found against the selected applicants',
                    () => {}
                );
                return;
            }

            sc = 0;
            currentEmployee = {};
            currentApplicant = {};
            cd = {};
            totalAssigned = 0;
            currentAssigned = 0;
            //
            acc = 0;
            currentF = null;
            tat = null;
            
            var user_type;
            var company_sid = "<?=$company_sid;?>";
            if(megaOBJ.type === 'employee'){
                user_type = 'employee';
            } else if(megaOBJ.type === 'document'){
                user_type = 'documents';
            } else {
                user_type = 'applicant';
            }

            window.location.href = "<?=base_url('hr_documents_management/generate_zip');?>/"+token+"/0/bulk/"+user_type+"/"+company_sid;
            // setTimeout(() => {
            //     window.location.reload();
            // }, 3000);
        }

        //
        function generatePDF(
            target,
            o
        ){
            let b = {};
            if(typeof o === 'object'){
                b = o;
            } else b.title = o;
            //
            let draw = kendo.drawing;
            draw.drawDOM(target, {
                avoidLinks: false,
                paperSize: "A4",
                multiPage: true,
                height: 500,
                // forcePageBreak: '.js-break',
                margin: { bottom: "1cm", top: ".3cm", left: "1cm", right: "1cm" },
                scale: 0.8
            })
            .then(function(root) {
                currentF = b.title;
                return draw.exportPDF(root);
            })
            .done(function(data) {
                b.content = data.replace(/data:application\/pdf;base64,/,'');
                uploadPDF(b, b.title);
            });
        }


        function uploadPDF(data, typo){
            $.post("<?=base_url('hr_documents_management/upload');?>", {
                data: data,
                token: token,
                employeeSid: exportType == 'employee' ? currentEmployee.id : currentApplicant.id,
                userFullNameSlug: exportType == 'employee' ? currentEmployee.fullname : currentApplicant.value,
                type: typo,
                typo: 'document',
            }, () => {
                //
                $('.js-progress-bar').attr('aria-valuemin', (100 / totalAssigned) * currentAssigned);
                $('.js-progress-bar').attr('aria-valuemnow', (100 / totalAssigned) * currentAssigned);
                $('.js-progress-bar').css('width', (100 / totalAssigned) * currentAssigned+'%');
                // $('.js-progress-bar').text(Math.ceil((100 / totalAssigned) * currentAssigned)+'%');
                //
                currentAssigned++;
                //
                $('.js-cd').text(currentAssigned);
                //
                if(typo === 'I9') exportW9();
                else if(typo === 'W9') exportW4();
                else if(typo === 'direct_deposit') getGIDocument('dependents');
                else if(typo === 'dependents') getGIDocument('emergency_contacts');
                else if(typo === 'emergency_contacts') getGIDocument('drivers_license');
                else if(typo === 'drivers_license') getGIDocument('occupational_license');
                else exportDocuments();
            }).fail(() => {
                if(typo == 'I9') exportI9();
                else if(typo == 'W9') exportW9();
                else if(typo == 'W4') exportW4();
                else if(typo === 'direct_deposit') getGIDocument('direct_deposit');
                else if(typo === 'dependents') getGIDocument('dependents');
                else if(typo === 'emergency_contacts') getGIDocument('emergency_contacts');
                else if(typo === 'drivers_license') getGIDocument('drivers_license');
                else if(typo === 'occupational_license') getGIDocument('occupational_license');
                else {
                    acc--;
                    exportDocuments();
                }
            });
        }


        // Hide applicant loader
        // loaders.applicant.hide(0);

        // Fetch employee data
        fetchEmployees();
        // Fetch applicants data
        fetchApplicants();

        // Fetch employee data
        function fetchEmployees(){
            $.get('<?=base_url('hr_documents_management/fetchEmployees');?>', function(resp){
                //
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                //
                employees = resp.Data;
                // var rows = '<option value="-1">All</option>';
                // //
                $.each(resp.Data, function(i, v){ 
                    employeeListWI[v.id] = v; 
                    employeeList.push(v.id); 
                    // rows += '<option value="'+( v.id )+'">'+( remakeEmployeeName(v) )+'</option>'; 
                });
                // //
                // $('#js-employee-select').html(rows).select2({
                //     closeOnSelect: false
                // });
                loader('hide');
            });
        }
        
        // Fetch applicants data
        function fetchApplicants(){
            $.get("<?=base_url('assign_bulk_documents/fetch_applicants_all');?>", function(resp){
                //
                if(resp.Status === false){
                    alertify.alert('ERROR!', resp.Response);
                    return;
                }
                //
                var rows = '<option value="-1">All</option>';
                //
                $.each(resp.Data, function(i, v){ applicantListWI[v.id] = v; applicantList.push(v.id); rows += '<option value="'+( v.id )+'">'+( v.value )+'</option>'; });
                //
                $('#js-applicant-select').html(rows).select2({
                    closeOnSelect: false
                });
                loader('hide');
            });
        }

        //
        function remakeEmployeeName(
            o,
            d
        ){
            //
            var r = '';
            //
            if(d === undefined) r += o.first_name+' '+o.last_name;
            //
            r = r.ucwords();
            //
            if(o.job_title != '' && o.job_title != null) r += ' ('+( o.job_title )+')';
            //
            r += ' [';
            //
            if(typeof(o['is_executive_admin']) !== undefined && o['is_executive_admin'] != 0) r += 'Executive ';
            //
            if(o['access_level_plus'] == 1 && o['pay_plan_flag'] == 1)  r += o['access_level']+' Plus / Payroll';
            else if(o['access_level_plus'] == 1) r += o['access_level']+' Plus';
            else if(o['pay_plan_flag'] == 1) r += o['access_level']+' Payroll';
            else r += o['access_level'];
            //
            r += ']';
            //
            return r;
        }

        //
        function getEmployeeRows(s){
            //
            let rows = '<option value="-1">All</option>';
            let iCount = 0;
            employeesListWithStatus = [];
            //
            employees.map((v) => {
                //
                let alreadyAdded = false;
                // Check for null
                if(s === null) return;
                // Check for all
                if($.inArray('all', s) !== -1){
                    iCount++;
                    alreadyAdded = true;
                    employeesListWithStatus.push(v.id);
                    rows += '<option value="'+( v.id )+'">'+( remakeEmployeeName(v) )+' ('+( v.terminated_status == 1 ? 'Terminated' : (v.active == 1 ? 'Active' : 'In-Active') )+')</option>'; 
                }
                // Check for terminated
                if($.inArray('terminated', s) !== -1 && alreadyAdded === false && v.terminated_status == 1){
                    alreadyAdded = true;
                    iCount++;
                    employeesListWithStatus.push(v.id);
                    rows += '<option value="'+( v.id )+'">'+( remakeEmployeeName(v) )+' (Terminated)</option>'; 
                }
                // Check for active
                if($.inArray('active', s) !== -1 && alreadyAdded === false && v.active == 1){
                    iCount++;
                    employeesListWithStatus.push(v.id);
                    rows += '<option value="'+( v.id )+'">'+( remakeEmployeeName(v) )+' (Active)</option>'; 
                }
                // Check for inactive
                if($.inArray('inactive', s) !== -1 && alreadyAdded === false && v.active == 0){
                    iCount++;
                    employeesListWithStatus.push(v.id);
                    rows += '<option value="'+( v.id )+'">'+( remakeEmployeeName(v) )+'  (InActive)</option>'; 
                }
            });
            //
            $('#jsEmployeeCount').text(iCount);
            //
            return rows;
        }
        //
        String.prototype.ucwords = function() {
            str = this.toLowerCase();
            return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function(s){ return s.toUpperCase(); });
        };

        // loader
        function loader(show_it, msg){
            show_it = show_it === undefined || show_it == true || show_it === 'show' ? 'show' : show_it;
            if(show_it === 'show') $('.js-body-loader').fadeIn(150);
            else $('.js-body-loader').fadeOut(300);
            //
            msg = msg === undefined ? '<p> Please wait while we are generating a preview...</p>': msg;
            //
            $('.js-body-loader-text').html(msg);
        }

        //
        $('#jsDocumentSearch').select2();
        //
        $('#jsDocumentSearchBTN').click(function(e){
            //
            e.preventDefault();
            //
            var v = $('#jsDocumentSearch').val() || null;
            //
            if(!v){
                $('.jsDocumentPanel').show(0);
            } else{
                //
                $('.jsDocumentPanel').hide(0);
                //
                v.map(function(id){
                    $('.jsDocumentPanel[data-id="'+(id)+'"]').show(0);
                });
            }
        });
        //
        var selectedDocuments = {};
        //
        $('.jsSelectAllDocuments').click(function(){
            //
            var documentId = $(this).closest('.jsDocumentPanel').data('id');
            //
            var isChecked = $(this).prop('checked');
            //
            $(this).closest('.jsDocumentPanel').find('.jsSelectSingleDocument').prop('checked', $(this).prop('checked'));
            //
            $('.jsDocumentPanel[data-id="'+(documentId)+'"] .jsSelectSingleDocument').map(function(){
                //
                if(selectedDocuments[$(this).data('id')] === undefined){
                    //
                    selectedDocuments[$(this).data('id')] = { 'documents': {}};
                }
                //
                if(isChecked){
                    //
                    selectedDocuments[$(this).data('id')]['documents'][$(this).data('aid')] = true;
                } else{
                    delete selectedDocuments[$(this).data('id')]['documents'][$(this).data('aid')];
                }
                //
                if(Object.keys(selectedDocuments[$(this).data('id')]['documents']).length === 0){
                    delete selectedDocuments[$(this).data('id')];
                }
            });
        });

        //
        $('.jsSelectSingleDocument').click(function(){
            //
            var isChecked = $(this).prop('checked');
            //
            if(selectedDocuments[$(this).data('id')] === undefined){
                //
                selectedDocuments[$(this).data('id')] = { 'documents': {}};
            }
            //
            if(isChecked){
                //
                selectedDocuments[$(this).data('id')]['documents'][$(this).data('aid')] = true;
            } else{
                delete selectedDocuments[$(this).data('id')]['documents'][$(this).data('aid')];
            }
            //
            if(Object.keys(selectedDocuments[$(this).data('id')]['documents']).length === 0){
                    delete selectedDocuments[$(this).data('id')];
                }
        });

        window.dd = selectedDocuments
    });
</script>