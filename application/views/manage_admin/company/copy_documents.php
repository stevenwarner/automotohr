<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <!-- <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;"><i class="fa fa-users"></i><?php echo $page_title; ?><a href="<?=base_url('manage_admin/report/copy_documents_report/');?>" class="btn btn-success pull-right">Copy Applicant Report</a></h1>
                                    </div> -->
                                    <!-- Main Page -->
                                    <div id="js-main-page" class="js-block">
                                        <div class="hr-setting-page">
                                            <?php echo form_open('javascript:void(0)', array('id' => 'copy-form')); ?>
                                            <ul>
                                                <li>
                                                    <label>Copy From <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-from-company"></select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Copy To <span class="cs-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-to-company"></select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Document Types</label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-types">
                                                            <option value="all" selected="true">All</option>
                                                            <option value="uploaded">Uploaded</option>
                                                            <option value="generated">Generated</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Document Status</label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-status">
                                                            <option value="all" selected="true">All</option>
                                                            <option value="0">Active</option>
                                                            <option value="1">Archived</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <label>Move groups? &nbsp;<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="If 'Yes' then document groups will be moved along Documents. If 'No' then only documents will be moved."></i></label>
                                                    <div class="hr-fields-wrap">
                                                        <select style="width: 100%;" id="js-group">
                                                            <option value="0" selected="true">No</option>
                                                            <option value="1">Yes</option>
                                                        </select>
                                                    </div>
                                                </li>
                                                <li>
                                                    <a class="site-btn" id="js-fetch-all" href="#">Fetch All Documents</a>
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>

                                    <!-- Report Page -->
                                    <div id="js-report-page"  style="display: none;" class="js-block">
                                        <div class="hr-setting-page">
                                            <button class="btn btn-default pull-right js-reset-view"><i class="fa fa-refresh"></i>&nbsp; Reset</button>
                                            <br />
                                            <br />
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Document Title</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="js-report-area"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jobs listing Block -->
                            <div id="js-list-block" style="display: none;" class="js-block">
                                <h4 class="js-hide-fetch"><b>Total</b>: <span><span id="js-total-documents">0</span> documents found</span></h4>
                                <div class="hr-box js-hide-fetch">
                                    <div class="hr-box-header">
                                        <h4>Copy Specific Documents</h4>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="table-responsive">
                                            <form action="javascript:void(0)" id="js-job-form" method="POST">
                                                <button type="button" class="btn btn-success pull-right js-copy-btn" style="margin-bottom: 10px;">Copy Selected Documents</button>
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="js-check-all" /></th>
                                                            <th>Document Title</th>
                                                            <th>Document Type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="js-list-show-area"></tbody>
                                                </table>
                                                <input type="hidden" name="copy_to" id="form-copy" />
                                                <input type="hidden" name="form_action" />
                                                <button type="button" class="btn btn-success pull-right js-copy-btn">Copy Selected Documents</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .my_loader{ display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99; background-color: rgba(0,0,0,.7); }
    .loader-icon-box{ position: absolute; top: 50%; left: 50%; width: auto; z-index: 9999; -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
    .loader-icon-box i{ font-size: 14em; color: #81b431; }
    .loader-text{ display: inline-block; padding: 10px; color: #000; background-color: #fff !important; border-radius: 5px; text-align: center; font-weight: 600; }
</style>

<!-- Loader -->
<div id="js-loader" class="text-center my_loader">
    <div id="file_loader" class="file_loader cs-loader-file" style="display: none; height: 1353px;"></div>
    <div class="loader-icon-box cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text cs-loader-text"  id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we generate a preview...
        </div>
    </div>
</div>

<style>
    #js-job-list-block{ display: none; }
    .cs-required{ font-weight: bolder; color: #cc0000; }
    /* Alertify CSS */
    .ajs-header{ background-color: #81b431 !important; color: #ffffff !important; }
    .ajs-ok{ background-color: #81b431 !important; color: #ffffff !important; }
    .ajs-cancel{ background-color: #81b431 !important; color: #ffffff !important; }
</style>


<script>
    // Copy Applicants IIFE
    $(function copyDocuments(){
        //
        var
        companiesXHR = null,
        documentXHR = null,
        copyDocumentXHR = null,
        companiesList = [],
        baseURI = "<?=base_url('manage_admin/copy_documents/handler');?>",
        paginate = {
            documents:{
                currentPage: 1,
                totalPages: 0,
                limit: 0,
                totalRecords: 0,
                records: []
            }
        },
        megaOBJ = {},
        selectedDocuments = [],
        reportedDocuments = [],
        counter = 0;
        //
        $('#js-types').select2();
        $('#js-status').select2();
        $('#js-group').select2();
        // Fetch companies
        fetchCompanies();
        //
        $('#js-fetch-all').click((e) => {
            e.preventDefault();
            //
            megaOBJ.type = $('#js-types').val();
            megaOBJ.group = $('#js-group').val();
            megaOBJ.action = 'get_company_documents'
            megaOBJ.status = $('#js-status').val().toString();
            megaOBJ.toCompanyId = $('#js-to-company').val();
            megaOBJ.fromCompanyId = $('#js-from-company').val();
            // Validations
            // Check if from company is selected ot not
            if(megaOBJ.fromCompanyId == 0){
                alertify.alert('ERROR!', 'Please select from company.');
                return;
            }
            // Check if to company is selected ot not
            if(megaOBJ.toCompanyId == 0){
                alertify.alert('ERROR!', 'Please select to company.');
                return;
            }
            // Check if from and to companies are not same
            if(megaOBJ.fromCompanyId == megaOBJ.toCompanyId){
                alertify.alert('ERROR!', 'From and to companies can not be same.');
                return;
            }
            resetView();
            //
            megaOBJ.page = paginate.documents.currentPage;
            // Show loader
            loader();
            // Get all documents
            fetchCompanyDocuments();
        });
        //
        $('.js-copy-btn').click((e) => {
            e.preventDefault();
            selectedDocuments = [];
            counter = 0;
            //
            if($('input[name="txt_ids[]"]:checked').length == 0){
                alertify.alert('ERROR!', 'Please select atleast 1 document.');
                return;
            }
            //
            $('input[name="txt_ids[]"]:checked').map(function(row){
                var obj = {};
                obj.document_id = parseInt($(this).val());
                obj.document_title = $(this).closest('tr').find('td.js-job-title').text();
                obj.document_type = $(this).closest('tr').data('type');
                selectedDocuments.push(obj);
            });
            //
            startCopyProcess();
        });
        //
        $(document).on('click', '.js-check-all', selectAllInputs);
        $(document).on('click', '.js-tr', selectSingleInput);
        $('.js-reset-view').click(resetViewPage);
        //
        function startCopyProcess(){
            if(copyDocumentXHR != null) return;
            if(counter >= selectedDocuments.length) {
                genrateReport();
                return;
            }
            //
            if(counter == 0) {
                megaOBJ.action = 'copy_process';
                loader('show');
            }
            //
            megaOBJ.document = selectedDocuments[counter];
            var row = '';
            row += 'Please wait while we are copying documents <br />';
            row += 'This may take a few minutes <br />';
            row += 'Copying <strong>'+( counter + 1 )+' ('+( megaOBJ.document.document_title )+')</strong> of <strong>'+( selectedDocuments.length )+'</strong>';
            $('#js-loader-text').html(row);

            copyDocumentXHR = $.post(baseURI, megaOBJ, (resp) => {
                copyDocumentXHR = null;
                //
                if(resp.Status === false){
                    if(counter != 0){
                        // Show report page
                        genrateReport();
                    }
                    loader('hide');
                    return;
                }

                if(resp.Exists === true) megaOBJ.document.status = 0;
                else if(resp.Copied === true) megaOBJ.document.status = 1;
                else megaOBJ.document.status = 2;
                reportedDocuments.push(megaOBJ.document);
                counter++;
                setTimeout(() => {
                    startCopyProcess();
                }, 800);
            }).fail(function(resp){
                copyDocumentXHR = null;
                setTimeout(function(){
                    startCopyProcess();
                }, 1000);
                return;
            });


        }
        //
        function fetchCompanyDocuments(){
            if(documentXHR != null) return;
            $.post(baseURI, megaOBJ, function(resp) {
                documentXHR = null;
                if(resp.Status === false){
                    if(paginate.documents.currentPage == 1)
                    alertify.alert('NOTICE', resp.Response);
                    loader('hide');
                    return;
                }
                // Check for pages
                if(paginate.documents.currentPage == 1){
                    paginate.documents.limit = resp.Limit;
                    paginate.documents.records = resp.Data;
                    paginate.documents.totalPages = resp.TotalPages;
                    paginate.documents.totalRecords = resp.TotalRecords;
                } else paginate.documents.records = paginate.documents.records.concat(resp.Data);
                //
                var row = '';
                row += 'Please wait while we are fetching documents <br />';
                row += 'This may take a few minutes <br />';
                row += 'Fetching <strong>'+( paginate.documents.records.length )+'</strong> of <strong>'+( paginate.documents.totalRecords )+'</strong>';
                $('#js-loader-text').html(row);
                //
                makeJobView(resp.Data, paginate.documents.currentPage);
                if( paginate.documents.currentPage <= paginate.documents.totalPages) {
                    paginate.documents.currentPage++;
                    megaOBJ.page = paginate.documents.currentPage;
                    setTimeout(function(){
                        fetchCompanyDocuments();
                    }, 1000);
                } else loader('hide');
            }).fail(function(resp){
                documentXHR = null;
                setTimeout(function(){
                    fetchCompanyDocuments();
                }, 1000);
                return;
            });;
        }
        // Make job view
        function makeJobView(records, page){
            $('#js-list-block').show();
            var rows = '';
            $.each(records, function(i, v) {
                rows += '<tr class="js-tr" data-type="'+( v.type )+'">';
                rows += '   <td><input type="checkbox" name="txt_ids[]" value="'+( v.document_id )+'" /></td>';
                rows += '   <td class="js-job-title">'+( v.document_title )+'</td>';
                rows += '   <td class="js-job-title">'+( v.document_type )+'</td>';
                rows += '   <td class="'+( v.is_archived == 0 ? 'text-success' : 'text-danger' )+'">'+( v.is_archived == 1 ? 'Archived' : 'Active' )+'</td>';
                rows += '</tr>';
            });
            //
            if(page == 1) $('#js-list-show-area').html(rows);
            else $('#js-list-show-area').append(rows);

            $('#js-total-documents').text(paginate.documents.records.length);
        }
        //
        function fetchCompanies(){
            if(companiesXHR != null) companiesXHR.abort();
                companiesXHR = $.post(baseURI, {
                    action: 'get_all_companies'
                }, (resp) => {
                    if(resp.Status === false){
                        loader('hide');
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    companiesList = resp.Data;
                    var rows = companiesList.map((company) => {
                        return `<option value="${company.company_id}">${company.title}</option>`;
                    });
                    rows = '<option value="0">[Please select a company]</option>'+rows;
                    //
                    $('#js-from-company').html(rows);
                    $('#js-from-company').select2();
                    // $('#js-from-company').select2('val', 57);
                    $('#js-to-company').html(rows);
                    $('#js-to-company').select2();
                    // $('#js-to-company').select2('val', 51);
                    loader('hide');
                }
            );
        }
        //
        function resetView(){
            paginate.documents.currentPage = 1;
            paginate.documents.totalRecords = 0;
            paginate.documents.totalPages = 0;
            paginate.documents.records = [];
            paginate.documents.limit = 0;

            $('#js-loader-text').text('Please wait while we generate a preview...');

            $('#js-list-page').hide(0);
            $('#js-list-block').hide(0);
            $('#js-list-show-area').html('');
            $('#js-total-documents').text('0');
        }
        //
        function resetViewPage(){
            resetView();
            $('.js-block').hide(0);
            $('#js-main-page').fadeIn(100);
            $('#js-report-area').html('');
            selectedDocuments = [];
            reportedDocuments = [];
            //
            $('#js-from-company').select2('val', 0);
            $('#js-to-company').select2('val', 0);
            $('#js-types').select2('val', 'all');
            $('#js-status').select2('val', 'all');
            $('#js-group').select2('val', 0);
            $('.js-check-all').prop('checked', false);
        }
        //
        function loader(do_show){
            if(do_show == true || do_show == 'show' || do_show == undefined) $('#js-loader').show();
            else $('#js-loader').fadeOut(100);
        }
        // Select all input: checkbox
        function selectAllInputs(){
            $('.js-tr').find('input[name="txt_ids[]"]').prop('checked', $(this).prop('checked'));
        }
        // Select single input: checkbox
        function selectSingleInput(){
            $(this).find('input[name="txt_ids[]"]').prop('checked', !$(this).find('input[name="txt_ids[]"]').prop('checked'));
        }
        // Generate Report
        function genrateReport(){
            $('.js-block').hide(0);
            selectedDocuments.map((document) => {
                var
                row = '<tr>';
                row += '    <td>'+( document.document_title )+'</td>';
                row += '    <td class="'+( document.status == 0 ? 'text-warning' : ( document.status == 2 ? 'text-danger' : 'text-success' ))+'">'+( document.status == 0 ? 'Already Exists' : ( document.status == 2 ? 'Failed' : 'Copied' ))+'</td>';
                row += '</tr>';
                $('#js-report-area').append(row);
            })
            $('#js-report-page').fadeIn(100);
            loader('hide');
        }
    })
</script>
