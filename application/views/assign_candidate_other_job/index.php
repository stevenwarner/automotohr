<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/settings_left_menu_administration'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="page-header-area">
                        <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                            <div class="hr-box">
                                <div class="hr-innerpadding">
                                    <form id="form_export_employees" enctype="multipart/form-data" method="post" action="<?php echo current_url(); ?>">
                                        <div class="row">
                                            <!-- jobs div -->
                                            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                <div class="field-row">
                                                    <?php $own_job_sid = $this->uri->segment(2) != 'all' ? $this->uri->segment(2) : ''; ?>
                                                    <label>Own Jobs</label>
                                                    <div class="hr-select-dropdown ">
                                                        <select class="invoice-fields" name="own_job_sid" id="own_job_sid">
                                                            <?php if (!empty($jobs)) { ?>
                                                                <option value="all">All Jobs</option>
                                                                <?php foreach ($jobs as $value) { ?>
                                                                    <option <?php echo set_select('own_job_sid', $value['sid'], $own_job_sid == $value['sid']); ?> value="<?php echo $value['sid']; ?>"><?php echo $value['Title']; ?></option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option value="">No jobs found</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row" id="company_div">
                                                    <label>Assign to company <span class="staric">*</span></label>
                                                    <?php $com_select = $this->uri->segment(3) != 'all' ? urldecode($this->uri->segment(3)) : ''; ?>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="company_sid" id="company_sid">
                                                            <?php if (!empty($companies)) { ?>
                                                                <option value="">Select Company</option>
                                                                <?php foreach ($companies as $type) { ?>
                                                                    <option <?php echo set_select('company', $type['sid'], $com_select==$type['sid']); ?> value="<?php echo $type['sid']; ?>"><?php echo $type['sid']==$company_sid ? 'My Own Company': $type['CompanyName']; ?></option>
                                                                <?php } ?>
                                                            <?php } else { ?>
                                                                <option value="">No company found</option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                <div class="field-row">
                                                    <label>Assign to job <span class="staric">*</span></label>
                                                    <div class="hr-select-dropdown">
                                                        <select class="invoice-fields" name="other_job_sid" id="other_job_sid">
                                                            <option value="">Please Select Company</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>&nbsp;</label>
<!--                                                <a id="btn_apply_filters" class="btn btn-success btn-block" href="#" >Apply Filters</a>-->
                                            </div>

                                            <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                <label>&nbsp;</label>
<!--                                                <button type="submit" class="btn btn-block btn-success">Fetch</button>-->
                                                <a id="btn_apply_filters" class="btn btn-success btn-block" href="#" >Apply Filters</a>
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </form>
                                </div>
                            </div>



                        <div class="hr-box">
                            <div class="hr-box-header bg-header-green">
                                <span class="pull-left">
                                    <h1 class="hr-registered"><?php echo $title; ?></h1>
                                </span>
                                <span class="pull-right">
<!--                                                <h1 class="hr-registered">Total Records Found : --><?php //echo $applicants_count;?><!--</h1>-->
                                </span>
                            </div>
                            <div class="hr-innerpadding">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <form id="re-assign-form" method="post" action="<?php echo current_url();?>">
                                            <?php if (isset($applied_applicants) && sizeof($applied_applicants) > 0) { ?>
                                                <div class="btn-panel text-right">
                                                    <input type="submit" class="btn btn-success">
                                                </div>
                                            <?php }?>
                                            <div class="table-responsive full-width">
                                                <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th><input name="" type="checkbox" value="" id="selectall"></th>
                                                            <th class="col-xs-3">Job Title</th>
                                                            <th>First Name</th>
                                                            <th>Last Name</th>
                                                            <th>Email</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php if (isset($applied_applicants) && sizeof($applied_applicants) > 0) { ?>
                                                            <?php foreach ($applied_applicants as $applicant) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <input name="ej_active[]" type="checkbox" value="<?= $applicant['sid'] ?>-<?php echo $applicant['job_sid']?>" id="<?= $applicant['sid'] ?>"  class="checkbox1">
                                                                    </td>
                                                                    <td style="color: <?php echo'green'; ?>;">
                                                                        <p><?php echo $applicant['Title']?></p>
                                                                    </td>
                                                                    <td><?php echo ucwords($applicant['first_name']); ?></td>
                                                                    <td><?php echo ucwords($applicant['last_name']); ?></td>
                                                                    <td><?php echo $applicant['email'] ?></td>
                                                                </tr>
                                                            <?php } ?>
                                                        <?php } elseif(!isset($applied_applicants)) { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="8">
                                                                    <div class="no-data">Apply Filters!</div>
                                                                </td>
                                                            </tr>

                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-center" colspan="8">
                                                                    <div class="no-data">No candidate found.</div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                            </div>
                                            <?php if (isset($applied_applicants) && sizeof($applied_applicants) > 0) { ?>
                                                <div class="btn-panel text-right">
                                                    <input type="submit" class="btn btn-success">
                                                </div>
                                            <?php }?>
                                            <input name="action" type="hidden" value="re_assign">
                                        </form>
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
<script>

    $(document).keypress(function(e) {
        if(e.which == 13) {
            // enter pressed
            $('#btn_apply_filters').click();
        }
    });

    function generate_search_url(){
        var own_job_sid = $('#own_job_sid').val();
        var company_sid = $('#company_sid').val();
        var other_job_sid = $('#other_job_sid').val();

        own_job_sid = own_job_sid != '' && own_job_sid != null && own_job_sid != undefined && own_job_sid != 0 ? encodeURIComponent(own_job_sid) : 'all';
        company_sid = company_sid != '' && company_sid != null && company_sid != undefined && company_sid != 0 ? encodeURIComponent(company_sid) : 'all';
        other_job_sid = other_job_sid != '' && other_job_sid != null && other_job_sid != undefined && other_job_sid != 0 ? encodeURIComponent(other_job_sid) : 'all';


        var url = '<?php echo base_url('re_assign_candidate/'); ?>' + '/' + own_job_sid + '/' + company_sid + '/' + other_job_sid;

        $('#btn_apply_filters').attr('href', url);
    }

    $(document).ready(function () {

        load_jobs();
        $('#re-assign-form').submit(function(e){
            var company_sid = $('#company_sid').val();
            var other_job_sid = $('#other_job_sid').val();

            if(other_job_sid==''){
                alertify.error('Please select assign job');
                return false;
            }

            if(company_sid==''){
                alertify.error('Please select assign company');
                return false;
            }

            if ($(".checkbox1:checked").size() == 0) {
                alertify.alert('Error! No applicant selected', 'Please Select at-least one applicant');
                return false;
            }
        });
        //select all checkboxex on one click
        $('#selectall').click(function (event) {  //on click
            if (this.checked) { // check select status
                $('.checkbox1').each(function () { //loop through each checkbox
                    this.checked = true;  //select all checkboxes with class "checkbox1"
                });
            } else {
                $('.checkbox1').each(function () { //loop through each checkbox
                    this.checked = false; //deselect all checkboxes with class "checkbox1"
                });
            }
        });

        $('#btn_apply_filters').on('click', function(e){
            e.preventDefault();
            generate_search_url();

            window.location = $(this).attr('href').toString();
        });

        $('#other_job_sid').on('change',function (value){
            generate_search_url();
            $('#re-assign-form').attr('action',$('#btn_apply_filters').attr('href'));
        });
        $('#own_job_sid').on('change',function (value) {
            generate_search_url();
        });

        $("#company_sid").change(function () {
            load_jobs();
            $('#re-assign-form').attr('action',$('#btn_apply_filters').attr('href'));
        });
    });

    function load_jobs() {
        var company_sid = $("#company_sid").val();

        if (company_sid == 0 || company_sid == '') {
            $('#other_job_sid').find('option').remove().end();
            $('#other_job_sid').append('<option value="">Please Select Company</option>');
        } else {
            data = {'company_sid': company_sid, 'perform_action': 'load_jobs'};

            var myRequest = $.ajax({
                type: "POST",
                url: "<?php echo base_url('re_assign_candidate/ajax_responder'); ?>",
                data: data
            });

            myRequest.done(function (response) {
                $('#other_job_sid').find('option').remove().end();
                data = $.parseJSON(response);
                var selected_job = '<?php echo $this->uri->segment(4) != 'all' ? urldecode($this->uri->segment(4)) : ''; ?>';

                if(data.length>0){
                    $('#other_job_sid').append('<option value="">Select Job</option>');
                }
                else{

                    $('#other_job_sid').append('<option value="">No jobs found</option>');
                }
                $.each(data, function (i, item) {
                    var job_sid = item.sid;
                    var job_title = item.Title;
                    var status = item.active;
                    if(status==1){
                        status = ' (Active)';
                    }
                    else{
                        status = ' (In Active)';
                    }

                    if (selected_job != '' && selected_job == job_sid) {
                        $('#other_job_sid').append('<option value="' + job_sid + '" selected>' + job_title + status + '</option>');
                    } else {
                        $('#other_job_sid').append('<option value="' + job_sid + '">' + job_title + status + '</option>');
                    }
                });
            });
        }
    }

</script>