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
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                        <a href="<?php echo base_url('manage_admin/companies/timeoff_approvers/' . $companySid); ?>" class="black-btn pull-right"><i class="fa fa-long-arrow-left"></i> Back to Approvers</a>
                                    </div>
                                    <div class="edit-email-template">
                                        <p>Fields marked with an asterisk (<span class="hr-required">*</span>) are mandatory</p>
                                        
                                        <div class="edit-template-from-main">
                                            <?php echo form_open('javascript:void(0)', array('class' => 'form-horizontal js-form','id'=> 'approver_edit')); ?>
                                            <ul>
                                                
                                                <li>
                                                    <label>Employee <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select id="js-employees">
                                                            <option value="0">[Select Employee]</option>
                                                            <?php
                                                                if(sizeof($employees)){
                                                                    foreach ($employees as $k => $v) {
                                                                        $text = ucwords($v['first_name'].' '.$v['last_name']);
                                                                        if($v['job_title'] != null && $v['job_title'] != '' ) $text .= ' ('.( $v['job_title'] ).')';
                                                                        $text .= ' ['.( remakeAccessLevel($v) ).']';
                                                                        echo '<option '.( $v['sid'] == $approver['employee_sid'] ? 'selected="true"' : '' ).' value="'.( $v['sid'] ).'">'.( $text ).'</option>';
                                                                    }
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Department <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <select id="js-departments">
                                                            <option value="0" <?=$approver['department_sid'] == null ? 'selected="true"' : '';?>>All Departments</option>
                                                            <?php
                                                                if(sizeof($departments)){
                                                                    foreach ($departments as $k => $v) {
                                                                        $text = $v['name'];
                                                                        echo '<option '.( $v['sid'] == $approver['department_sid'] ? 'selected="true"' : '' ).' value="'.( $v['sid'] ).'">'.( $text ).'</option>';
                                                                    }
                                                                } 
                                                            ?>
                                                        </select>
                                                    </div>
                                                </li>

                                                <li>
                                                    <label>Archive <span class="hr-required">*</span></label>
                                                    <div class="hr-fields-wrap">
                                                        <label class="control control--radio">
                                                            <input type="radio" value="yes" <?=$approver['is_archived'] == 1 ? 'checked="true"' : ''; ?> name="archive" /> Yes &nbsp;
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                        <label class="control control--radio">
                                                            <input type="radio" value="no" <?=$approver['is_archived'] == 0 ? 'checked="true"' : ''; ?> name="archive" /> No
                                                            <div class="control__indicator"></div>
                                                        </label>
                                                    </div>
                                                </li>
                                        
                                                
                                               
                                                 
                                                <li>
                                                    <input type="submit" name="submit" value="Save" class="search-btn" />
                                                </li>
                                            </ul>
                                            <?php echo form_close(); ?>
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
<style>
    .my_loader{ display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 99; background-color: rgba(0,0,0,.7); }
    .loader-icon-box{ position: absolute; top: 50%; left: 50%; width: auto; z-index: 9999; -webkit-transform: translate(-50%, -50%); -moz-transform: translate(-50%, -50%); -ms-transform: translate(-50%, -50%); -o-transform: translate(-50%, -50%); transform: translate(-50%, -50%); }
    .loader-icon-box i{ font-size: 14em; color: #81b431; }
    .loader-text{ display: inline-block; padding: 10px; color: #000; background-color: #fff !important; border-radius: 5px; text-align: center; font-weight: 600; }
</style>

<!-- Loader -->
<div id="js-loader" class="text-center my_loader" style="display: none;">
    <div id="file_loader" class="file_loader cs-loader-file" style="disheight: 1353px;"></div>
    <div class="loader-icon-box cs-loader-box">
        <i class="fa fa-refresh fa-spin my_spinner" style="visibility: visible;"></i>
        <div class="loader-text cs-loader-text"  id="js-loader-text" style="display:block; margin-top: 35px;">Please wait while we are processing your request...
        </div>
    </div>
</div>

<style>
  /* Remove the radius from left fro phone field*/
  .input-group input{ border-top-left-radius: 0; border-bottom-left-radius: 0; }
  .select2-container, .select2-drop, .select2-search, .select2-search input{ width: 100%; }
</style>


<script>
    $(function(){
        $('#js-employees').select2();
        $('#js-departments').select2();

        //
        $('#approver_edit').submit(function(e){
            e.preventDefault();
            //
            var megaOBJ = {};
            megaOBJ.approverSid = <?=$approverSid;?>;
            megaOBJ.companySid = <?=$companySid;?>;
            megaOBJ.employeeSid = $('#js-employees').val();
            megaOBJ.departmentSid = $('#js-departments').val();
            megaOBJ.isArchived = $('input[name="archive"]:checked').val() == 'no' ? 0 : 1;
            //
            if(megaOBJ.employeeSid == '0'){
                alertify.alert('ERROR!', 'Please select an Employee.', function(){ return; });
                return;
            }
            //
            loader(true);
            //
            $.post("<?=base_url('manage_admin/companies/edit_approver_process');?>", 
                megaOBJ, 
                function(resp) {
                    loader();
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    //
                    alertify.alert('SUCCESS!', resp.Response);

            });
        });

        //
        function loader(sow){
            if(sow == true) $('#js-loader').fadeIn(500);
            else $('#js-loader').fadeOut(500);
        }
    });
</script>