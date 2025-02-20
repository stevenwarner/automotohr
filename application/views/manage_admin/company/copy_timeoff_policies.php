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
                                <div class="col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title" style="width: 100%;">
                                            <i class="fa fa-users" aria-hidden="true"></i>
                                            Copy Time Off Policies
                                        </h1>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <br>
                                <div class="col-sm-12">
                                        <p class="text-danger" style="font-size: 16px;">
                                            <em><strong>Note:</strong>  This will copy the time off <strong>policies</strong> from source company to selected company.</em>
                                        </p>
                                </div>
                            </div>
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
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
                                                    <a class="site-btn" id="js-fetch-all" href="#">fetch Policies</a>
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
                                                            <th>Policy Title</th>
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
                                <h4 class="js-hide-fetch"><b>Total</b>: <span><span id="js-total-documents">0</span> policies found</span></h4>
                                <div class="hr-box js-hide-fetch">
                                    <div class="hr-box-header">
                                        <h4>Copy Specific Policies</h4>
                                    </div>
                                    <div class="hr-innerpadding">
                                        <div class="table-responsive">
                                            <form action="javascript:void(0)" id="js-job-form" method="POST">
                                                <button type="button" class="btn btn-success pull-right js-copy-btn" style="margin-bottom: 10px;">Copy Selected Policies</button>
                                                <table class="table table-bordered table-hover table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="js-check-all" /></th>
                                                            <th>Policy Title</th>
                                                            <th>Policy Type</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="js-list-show-area"></tbody>
                                                </table>
                                                <input type="hidden" name="copy_to" id="form-copy" />
                                                <input type="hidden" name="form_action" />
                                                <button type="button" class="btn btn-success pull-right js-copy-btn">Copy Selected Policies</button>
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
    $(function copyPolicies(){
        //
        var
        companiesXHR = null,
        policiesXHR = null,
        copyDocumentXHR = null,
        companiesList = [],
        baseURI = "<?=base_url('manage_admin/copy_policies/handler');?>",
        paginate = {
            policies:{
                currentPage: 1,
                totalPages: 0,
                limit: 0,
                totalRecords: 0,
                records: []
            }
        },
        megaOBJ = {},
        selectedPolicies = [],
        reportedDocuments = [],
        counter = 0;
        //
        $('#js-status').select2();
        //
        $('#js-from-company').change(function(){
            $('#js-to-company').find('option').prop('disabled', false)
            $('#js-to-company').find('[value="'+($(this).val())+'"]').prop('disabled', true)
        });
        // Fetch companies
        fetchCompanies();

        //
        $('#jsFetchTimeOffPolicies').click(function(e){
            //
            e.preventDefault();
            //
            var baseCompanyId = $('#js-from-company').val();
            var toCompanyIds = $('#js-to-company').val() || [];

            //
            if(!baseCompanyId || baseCompanyId == 0){
                return alertify.alert('Warning!', 'Please select the source company.', function(){});
            }
            //
            if(toCompanyIds.length == 0){
                return alertify.alert('Warning!', 'Please select at least one destination company.', function(){});
            }
            //
            fetchCompanyActivePolicies(baseCompanyId);
        });
        //

        //
        $('#jsMoveTimeOff').click(function(e){
            //
            e.preventDefault();
            //
            var baseCompanyId = $('#js-from-company').val();
            var toCompanyIds = $('#js-to-company').val() || [];

            //
            if(!baseCompanyId || baseCompanyId == 0){
                return alertify.alert('Warning!', 'Please select the source company.', function(){});
            }
            //
            if(toCompanyIds.length == 0){
                return alertify.alert('Warning!', 'Please select at least one destination company.', function(){});
            }
            //
            var baseCompanyName = $('#js-from-company').find('[value="'+(baseCompanyId)+'"]').text();

            //
            return alertify.confirm(
                '<p>This will copy the time off types, policies, holidays, and settings from '+(baseCompanyName)+' company to selected companies.<p><br /><p><strong>Are you sure you want to proceed?</strong></p>',
                function(){
                    startCopyTimeOffProcess(
                        baseCompanyId,
                        toCompanyIds
                    )
                },
                function(){}
            );
        });



        //
        $('#js-fetch-all').click((e) => {
            // e.preventDefault();
            //
            megaOBJ.action = 'get_company_policies'
            megaOBJ.status = 'active';
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
            megaOBJ.page = paginate.policies.currentPage;
            // Show loader
            loader();
            // Get all documents
            fetchCompanyPolicies();
        });
        //
        $('.js-copy-btn').click((e) => {
            e.preventDefault();
            selectedPolicies = [];
            counter = 0;
            //
            if($('input[name="txt_ids[]"]:checked').length == 0){
                alertify.alert('ERROR!', 'Please select atleast 1 policy.');
                return;
            }
            //
            $('input[name="txt_ids[]"]:checked').map(function(row){
                var obj = {};
                obj.policy_id = parseInt($(this).val());
                obj.policy_title = $(this).closest('tr').find('td.js-policy-title').text();
                obj.policy_category = $(this).closest('tr').find('td.js-policy-category').text();
                selectedPolicies.push(obj);
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
            if(counter >= selectedPolicies.length) {
                genrateReport();
                return;
            }
            //
            if(counter == 0) {
                megaOBJ.action = 'copy_process';
                loader('show');
            }
            //
            megaOBJ.policy = selectedPolicies[counter];
            var row = '';
            row += 'Please wait while we are copying policies <br />';
            row += 'This may take a few minutes <br />';
            row += 'Copying <strong>'+( counter + 1 )+' ('+( megaOBJ.policy.policy_title )+')</strong> of <strong>'+( selectedPolicies.length )+'</strong>';
            $('#js-loader-text').html(row);

            console.log(megaOBJ)
            console.log(baseURI)

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

                if(resp.Exists === true) megaOBJ.policy.status = 0;
                else if(resp.Copied === true) megaOBJ.policy.status = 1;
                else megaOBJ.policy.status = 2;
                reportedDocuments.push(megaOBJ.policy);
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
        function fetchCompanyPolicies(){
            if(policiesXHR != null) return;
            $.post(baseURI, megaOBJ, function(resp) {
                policiesXHR = null;
                if(resp.Status === false){
                    if(paginate.policies.currentPage == 1)
                    alertify.alert('NOTICE', resp.Response);
                    loader('hide');
                    return;
                }
                // Check for pages
                if(paginate.policies.currentPage == 1){
                    paginate.policies.limit = resp.Limit;
                    paginate.policies.records = resp.Data;
                    paginate.policies.totalPages = resp.TotalPages;
                    paginate.policies.totalRecords = resp.TotalRecords;
                } else paginate.policies.records = paginate.policies.records.concat(resp.Data);
                //
                var row = '';
                row += 'Please wait while we are fetching policies <br />';
                row += 'This may take a few minutes <br />';
                row += 'Fetching <strong>'+( paginate.policies.records.length )+'</strong> of <strong>'+( paginate.policies.totalRecords )+'</strong>';
                $('#js-loader-text').html(row);
                //
                makePoliciesView(resp.Data, paginate.policies.currentPage);
                if( paginate.policies.currentPage <= paginate.policies.totalPages) {
                    paginate.policies.currentPage++;
                    megaOBJ.page = paginate.policies.currentPage;
                    setTimeout(function(){
                        fetchCompanyPolicies();
                    }, 1000);
                } else loader('hide');
            }).fail(function(resp){
                policiesXHR = null;
                setTimeout(function(){
                    fetchCompanyPolicies();
                }, 1000);
                return;
            });;
        }

        // Make job view
        function makePoliciesView(records, page){
            $('#js-list-block').show();
            var rows = '';
            $.each(records, function(i, v) {
                rows += '<tr class="js-tr" data-type="'+( v.type )+'">';
                rows += '   <td><input type="checkbox" name="txt_ids[]" value="'+( v.policie_id )+'" /></td>';
                rows += '   <td class="js-policy-title">'+( v.title )+'</td>';
                rows += '   <td class="js-policy-category">'+( v.category )+'</td>';
                rows += '   <td class="'+( v.is_archived == 0 ? 'text-success' : 'text-danger' )+'">'+( v.is_archived == 1 ? 'Archived' : 'Active' )+'</td>';
                rows += '</tr>';
            });
            //
            if(page == 1) $('#js-list-show-area').html(rows);
            else $('#js-list-show-area').append(rows);

            $('#js-total-documents').text(paginate.policies.records.length);
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
                    // 
                    $('#js-to-company').html(rows);
                    $('#js-to-company').select2();
                    // 
                    loader('hide');
                }
            );
        }
        //
        function resetView(){
            paginate.policies.currentPage = 1;
            paginate.policies.totalRecords = 0;
            paginate.policies.totalPages = 0;
            paginate.policies.records = [];
            paginate.policies.limit = 0;

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
            selectedPolicies = [];
            reportedDocuments = [];
            //
            $('#js-from-company').select2('val', 0);
            $('#js-to-company').select2('val', 0);
            $('#js-status').select2('val', 'all');
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
            // $(this).find('input[name="txt_ids[]"]').prop('checked', !$(this).find('input[name="txt_ids[]"]').prop('checked'));
        }
        // Generate Report
        function genrateReport(){
            $('.js-block').hide(0);
            selectedPolicies.map((policy) => {
                var
                row = '<tr>';
                row += '    <td>'+( policy.policy_title )+'</td>';
                row += '    <td class="'+( policy.status == 0 ? 'text-warning' : ( policy.status == 2 ? 'text-danger' : 'text-success' ))+'">'+( policy.status == 0 ? 'Already Exists' : ( policy.status == 2 ? 'Failed' : 'Copied' ))+'</td>';
                row += '</tr>';
                $('#js-report-area').append(row);
            })
            $('#js-report-page').fadeIn(100);
            loader('hide');
        }

        var fromCompanyId = 0,
        toCompanyIds = [],
        current = 1,
        xhr = null;

        /**
         * 
         */
        function startCopyTimeOffProcess(
            baseCompanyId,
            destinationCompanyIds
        ){
            //
            fromCompanyId = baseCompanyId;
            toCompanyIds = destinationCompanyIds;
            current = 1;
            //
            return copyTimeOff();
        }

        function copyTimeOff(){
            //
            if(xhr !== null){
                return false;
            }
            //
            if(current > toCompanyIds.length){
                //
                loader(false);
                //
                return alertify.alert(
                    'Success!',
                    'Time off is copied to selected companies.',
                    function(){
                        window.location.reload();
                    }
                );
            }
            //
            var row = '';
            row += 'Please wait while we are copying time off <br />';
            row += 'This may take a few minutes <br />';
            row += 'Copying time off <strong>'+( current )+' ('+( $('#js-from-company').find('[value="'+(toCompanyIds[current - 1])+'"]').text() )+')</strong> of <strong>'+( toCompanyIds.length )+'</strong>';
            $('#js-loader-text').html(row);
            //
            loader('show');
            //

            xhr = $.post(
                baseURI, {
                    action: 'copy_timeoff',
                    fromCompanyId: fromCompanyId,
                    toCompanyId: toCompanyIds[current-1],
                }
            ).success(function(resp){
                //
                xhr = null;
                //
                current++;
                //
                setTimeout(copyTimeOff, 1000);
            })
            .fail(function(){
                //
                xhr = null;
                //
                setTimeout(copyTimeOff, 1000);
            });
        }
    })
</script>
