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
                                <div class="job-feature-main m_job">
                                    <div class="portalmid">
                                        <div id="file_loader" style="display:none; height:1353px;"></div>
                                        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: hidden;"></i>
                                        <div class="loader_message" style="display:none; margin-top: 35px;">Please wait while applicants are loading...</div>

                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="hr-setting-page">
                                        <?php echo form_open(base_url('manage_admin/copy_applicants/'), array('id' => 'copy-form')); ?>
                                        <ul>
                                            <li>
                                                <label>Copy From</label>
                                                <div class="hr-fields-wrap">
                                                    <select name="copy_from" style="width: 100%;" id="copy_from">
                                                        <?php   foreach ($active_companies as $company) { ?>
                                                                    <option value="<?php echo $company['sid']; ?>" <?php if ($source == $company['sid']) echo 'selected="selected"'; ?>><?php echo $company['CompanyName']; ?></option>
                                                        <?php   } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Applicants Type</label>
                                                <div class="hr-fields-wrap">
                                                    <?php echo form_dropdown('applicants_type', $applicants_type, set_value('applicants_type', $type), ' style="width: 100%;" id="app-type"'); ?>
                                                    <?php echo form_error('applicants_type'); ?>
                                                </div>
                                            </li>
                                            <li>
                                                <label>Copy To</label>
                                                <div class="hr-fields-wrap">
                                                    <select name="copy_to" style="width: 100%;" id="copy_to">
                                                        <?php foreach ($active_companies as $company) { ?>
                                                            <option value="<?php echo $company['sid']; ?>" <?php if ($destination == $company['sid']) echo 'selected="selected"'; ?>><?php echo $company['CompanyName']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </li>
                                            <li>
                                                <a class="site-btn" id="fetch-applicant" href="#">Fetch All Jobs</a>
                                                <?php echo form_submit('setting_submit', 'Copy All Applicants', array('class' => 'site-btn', 'id' => 'btn-submit')); ?>

                                            </li>
                                        </ul>
                                        <div class="custom_loader">
                                            <div id="copy-loader" class="copy-loader" style="display: none">
                                                <i style="font-size: 25px; color: #81b431;" class="fa fa-cog fa-spin"></i>
                                                <span>Copy Applicants Processing... </span>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                            <!-- fetch applicant -->
                            <div class="row js-hide-fetch">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="hr-setting-page">
                                        <ul>
                                            <li>
                                                <label>Filter Job</label>
                                                <div class="hr-fields-wrap">
                                                    <select name="job_select" class="invoice-fields" id="job_select">
                                                        <option value="0">Please Select</option>
                                                    </select>
                                                </div>
                                            </li>
                                            <li><a class="site-btn" id="jobs" href="#">Filter</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <h4 class="js-hide-fetch"><b>Total</b>: <span><span class="js-total-applicant">0</span> applicants found</span></h4>
                            <div class="hr-box js-hide-fetch">
                                <div class="hr-box-header">
                                    <h4>Copy Specific Applicants</h4>
                                </div>
                                <div class="hr-innerpadding">
                                    <div class="table-responsive">
                                        <form name="multiple_actions" id="multiple_actions_employer" method="POST">
                                            <button type="button" class="btn btn-success pull-right js-copy-selected" style="margin-bottom: 10px;">Copy Selected Applicants</button>
                                            <table class="table table-bordered table-hover table-striped">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="check_all"></th>
                                                        <th class="text-center">ID</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Jobs</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                            <input type="hidden" name="copy_to" value="" id="form-copy">
                                            <input type="hidden" name="form_action" value="copy_selected">
                                            <button type="button" class="btn btn-success pull-right js-copy-selected">Copy Selected Applicants</button>
                                        </form>
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
    .cs-loader-file{ z-index: 1061 !important; display: block !important; height: 1353px !important; }
    .cs-loader-box{ position: fixed; top: 50%; left: 50%; width: auto; z-index: 1061; margin-left: -102px; margin-top: -304px; }
    .cs-loader-box i{ font-size: 14em; color: #81b431; }
    .cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
    .cs-calendar{ margin-top: 10px; }
    .js-hide-fetch{ display: none; }
</style>

<!-- Loader -->
<div class="text-center cs-loader js-loader" style="display: none;">
    <div id="file_loader" class="cs-loader-file"></div>
    <div class="cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="cs-loader-text js-loader-text">Copied <strong class="js-copied">0</strong> applicants out of <strong class="cs-total">0</strong></div>
    </div>
</div>
<!--document.getElementById('form_id').action = 'somethingelse';-->


<script>
    // jQuery IFFY
    // Created on: 20-05-2019
    $(function(){
        // Set defaults
        var copied = 0,
        chunks = 1,
        limit = 0,
        copied = 0,
        stop_ajax = false,
        fetch_xhr = null,
        copy_xhr = null,
        jobs = [],
        total = null,
        loader_ref = $('.js-loader-text'),
        default_display_msg = 'Applicant(s) copy process is starting..',
        fetch_display_msg = 'Applicant(s) fetch process is starting..',
        filter_display_msg = 'Filtering Applicants..',
        copy_display_msg = 'Copied <strong class="js-copied">0</strong> applicants out of <strong class="js-total">0</strong> <br />. Please, be patient as it may take several minutes.',
        // copy_display_msg = 'Copied <strong class="js-copied">0</strong> applicants out of <strong class="js-total">0</strong> <br />. Please, be patient as it may take several minutes.  <br /> <button class="btn btn-default js-stop-fetch">Stop</button>',
        fetch_display_msg = 'Fetched <strong class="js-copied">0</strong> applicants out of <strong class="js-total">0</strong> <br />. Please, be patient as it may take several minutes.',
        // fetch_display_msg = 'Fetched <strong class="js-copied">0</strong> applicants out of <strong class="js-total">0</strong> <br />. Please, be patient as it may take several minutes. <br /> <button class="btn btn-default js-stop-fetch">Stop</button>',
        current_page = 0;

        $('#copy_to, #copy_from, #app-type').select2();
        //
        $('#copy_to, #copy_from').on('change', function () {
            copied = limit = current_page = chunks = 0;
            total = null;
            fetch_xhr = copy_xhr = null;
            stop_ajax = false;
            $('.js-hide-fetch').hide(0);
            $('.js-total-applicant').text(0);
        });

        // Copy applicant button
        $("#btn-submit").on("click", function(e){
            e.preventDefault();
            $('.js-error').remove();

            copied = limit = current_page = chunks = 0;
            total = null;
            fetch_xhr = copy_xhr = null;

            if($('#copy_from').find(':selected').val() == $('#copy_to').find(':selected').val()){
                alertify.error('Companies can\'t be same.', function(){ return; });
                return false;
            }

            start_copy_process();
        });

        // Copy selected applicant
        $('.js-copy-selected').click(function(e) {
            $('.js-error').remove();
            copied = limit = current_page = chunks = 0;
            total = null;
            fetch_xhr = copy_xhr = null;
            e.preventDefault();
            if ($('#copy_from').val() == $('#copy_to').val()) {
                alertify.error("Both companies can't be same");
            } else if ($(".my_checkbox:checked").size() == 0) {
                alertify.alert('Error! No applicant selected', 'Please Select at-least one applicant');
            } else {
                alertify.confirm(
                    'Confirmation',
                    'Are you sure you want to copy the applicants?',
                    function () {
                        start_copy_process(true);
                    },
                    function () {
                        alertify.error('Cancelled!');
                    }).set('labels', { ok: 'Yes', cancel: 'No'});
            }
        });

        //
        $("#jobs").on("click", function (event) {
            event.preventDefault();
            $('.js-error').remove();
            loader_ref.text(filter_display_msg);
            loader(true);
            var active_rows = 0,
            text = $('#job_select').find(':selected').text();
            if($('#job_select').find(':selected').val() == 0){
                $('.js-rows').show(0);
                $('.js-total-applicant').text($('.js-rows').length);
                loader(false);
                return;
            }
            $('.js-rows').hide(0);
            $('.js-total-applicant').text(0);
            //
            $.each($('.js-title'), function() {
                var search_in = $(this).text().trim();
                if(search_in.indexOf(text) != -1){
                    $(this).closest('tr').show();
                    active_rows++;
                }
            });
            //
            $('.js-total-applicant').text( active_rows );
            // Show error if no record is found
            if(active_rows == 0){
                $('.js-copy-selected').hide(0)
                $('#multiple_actions_employer thead').hide(0);
                $('#multiple_actions_employer tbody').append('<tr class="js-error"><td colspan="'+$('#multiple_actions_employer tbody tr td').length+'"><p class="alert alert-info">No Applicants found.</p></td></tr>');
                loader(false);
                return;
            }
            $('#multiple_actions_employer thead').show();
            $('.js-copy-selected').show(0)
            loader(false);
        });

        //
        $("#fetch-applicant").on("click", function (event) {
            event.preventDefault();
            $('.js-total-applicant').text(0);
            $('.js-error').remove();
            $('#multiple_actions_employer tbody').html('');
            copied = limit = current_page = chunks = 0;
            total = null;
            fetch_xhr = copy_xhr = null;
            if($('#copy_from').find(':selected').val() == $('#copy_to').find(':selected').val()){
                alertify.error('Companies can\'t be same.', function(){ return; });
                return false;
            }
            fetch_copy_process();
        });

        //
        $('#check_all').click(function () {
            if ($('#check_all').is(":checked")) {
                $('.my_checkbox:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.my_checkbox:checkbox').each(function () {
                    this.checked = false;
                });
            }
        });

        $(document).on('click', '.js-stop-fetch', function(event) {
            event.preventDefault();
            loader(false);
            stop_ajax = true;
            if(fetch_xhr != null) fetch_xhr.abort();
            if(copy_xhr != null) copy_xhr.abort();
        });


        // Send AJAX request to copy applicants
        // multiple times
        function start_copy_process(is_check){
            var ids = [];
            if(is_check !== undefined){
                $('#multiple_actions_employer').find('input.my_checkbox:checked').map(function(){
                    ids.push($(this).val());
                });
            }
            if(total === null) {
                loader_ref.text(default_display_msg);
                loader();
            }
            var post_obj = {
                page: current_page,
                chunk: chunks,
                copy_from: $('#copy_from').find(':selected').val(),
                copy_to: $('#copy_to').find(':selected').val(),
                applicants_type: $('#app-type').find(':selected').val()
            };
            if(total !== null) post_obj.total = total;
            if(ids.length != 0) post_obj.checkit = ids;

            copy_xhr = $.post("<?=base_url('manage_admin/copy_applicants/move_applicants');?>", post_obj, function(resp) {
                copy_xhr = null;
                if(resp.Status === false){
                    alertify.error(resp.Response, function(){ return; }); 
                    loader(false);
                    return;
                }

                // Stops ajax request
                if(stop_ajax === true) { stop_ajax = false; return ; }

                if(total === null){
                    loader_ref.html(copy_display_msg);
                    total = resp.Total;
                    chunks = resp.Chunks;
                    limit = resp.Limit;
                    $('.js-total').text(resp.Total);
                }

                ++current_page;
                
                if(current_page <= chunks){
                    copied += resp.Copied === undefined ? 0 : resp.Copied;
                    console.log(copied);
                    $('.js-copied').text(copied);
                    start_copy_process(is_check);
                }else{
                    copied += resp.Copied === undefined ? 0 : resp.Copied;
                    $('.js-copied').text(copied);
                    loader(false);
                    alertify.alert('Copied Applicants','Copied '+ copied +' Applicants out of '+total+'. '+( total - copied )+' already exists.', function(){ return; });
                }

            });
        }

        // Send AJAX request to fecth applicants
        // multiple times
        function fetch_copy_process(job_sid){
            var ids = [];
            if(total === null) {
                loader_ref.html(fetch_display_msg);
                loader();
            }

            var get_uri = '/',
            job = 1;
            // job = job_sid !== undefined ? job_sid :( $('#job_select').val() != 0 ? $('#job_select').val() : 1);
            get_uri += current_page+'/'; 
            get_uri += $('#copy_from').find(':selected').val()+'/'; 
            get_uri += $('#copy_to').find(':selected').val()+'/'; 
            get_uri += $('#app-type').find(':selected').val()+'/'; 
            get_uri += job; 

            // if(total !== null) post_obj.total = total;

            fetch_xhr = $.get("<?=base_url('manage_admin/copy_applicants/fetch_applicants_ajax');?>"+get_uri+"", function(resp) {
                fetch_xhr = null;
                if(resp.Status === false && current_page != chunks){
                    alertify.error(resp.Response, function(){ return; }); 
                    loader(false);
                    return;
                }

                console.log(stop_ajax, current_page);
                // Stops ajax request
                if(stop_ajax === true) { stop_ajax = false; return ; }

                if(total === null){
                    loader_ref.html(fetch_display_msg);
                    total = resp.Total;
                    chunks = resp.Chunks;
                    jobs = resp.Jobs;
                    limit = resp.Limit;
                    $('.js-total').text(resp.Total);

                    $('#job_select').html('<option value="0">Please Select</option>');
                    if(resp.Jobs.length !== 0){
                        $.each(resp.Jobs, function(i, v) {
                            $('#job_select').append('<option value="'+v.sid+'">'+v.Title+'</option>');
                        });

                        $('#job_select').select2();
                    }
                    $('.js-hide-fetch').show();
                }

                ++current_page;

                if(current_page <= chunks){
                    copied += resp.Copied === undefined ? 0 : resp.Copied;
                    $('.js-copied').text(copied);
                   
                    load_applicants(resp);
                    fetch_copy_process(job);
                }else{
                    load_applicants(resp);
                    copied += resp.Copied === undefined ? 0 : resp.Copied;
                    $('.js-copied').text(copied);
                    loader(false);
                }

            });
        }

        // Show/Hide loader
        function loader(show){
            if(show === undefined || show === true) $('.js-loader').show();
            else $('.js-loader').hide();
        }

        function load_applicants(resp){
            if(typeof(resp.Applicants) != 'undefined' && resp.Applicants.length != 0){
                $('.js-total-applicant').text(
                    +$('.js-total-applicant').text() + resp.Applicants.length
                );
                var row = '';
                $.each(resp.Applicants, function(i, v) {
                    row = '<tr id="parent" class="js-rows js-row-'+(v.job_sid == null ? 0 : v.job_sid)+'">';
                    row += '    <td><input type="checkbox" name="checkit[]" value="'+v.sid+'" class="my_checkbox"></td>';
                    row += '    <td class="text-center">';
                    row += '        <div class="employee-profile-info">';
                    row += '            <figure>';
                    if(v.pictures != "" && v.pictures != null)
                    row += '                    <img class="img-responsive" src="'+"<?=AWS_S3_BUCKET_URL;?>"+ v.pictures+'">';
                    else
                    row += '                    <img class="img-responsive" src="<?=base_url();?>assets/images/img-applicant.jpg">';
                    row += '            </figure>';
                    row += '        </div>';
                    row += '        <b>'+v.sid+'</b>';
                    row += '    </td>';
                    row += '    <td>';
                    row +=  v.first_name+" "+v.last_name;
                    row += '    </td>';
                    row += '    <td>'+v.email+'</td>';
                    row += '    <td class="js-title">';
                    if(v.job_title != null){
                        row += v.job_title;
                    } else {
                        row += '            Not Applied for job';
                    }
                    row += '    </td>';
                    row += '</tr>';
                    $('#multiple_actions_employer tbody').append(row);
                    $('#multiple_actions_employer thead').show();
                    $('.js-copy-selected').show(0)
                });
            } 
        }
    })
</script>