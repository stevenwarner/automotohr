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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Edit Module</h1>
                                    </div>
                                    <!-- Search Table Start -->
                                    <div class="hr-search-main" style="display: block;">
                                    <form enctype="multipart/form-data"  method="post" >
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>Module Name</label><b class="text-danger"> *</b>
                                                    <input type="text" class="invoice-fields" name="module_name" id="module_name" value="<?php echo $module_data['module_name']!='' ? $module_data['module_name'] : '' ?>" />
                                                    <?php echo form_error('module_name'); ?>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="field-row">
                                                    <label>Stage</label>
                                                    <select name="stage" id="stage" class="js-from full_width" >
                                                        <option  value="staging" <?php echo $module_data['stage'] =='staging' ? 'selected' : '' ?> >Staging</option>
                                                        <option  value="production" <?php echo $module_data['stage'] =='production' ? 'selected' : '' ?>>Production</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <div class="field-row">
                                                      <label>Disabled </label><br>
                                                      <select name="is_disabled" class="js-from full_width" id="is_disabled">
                                                            <option value="all"<?php echo $module_data['is_disabled']=='all' ? 'selected' : ''?> >All</option>
                                                            <option value="0"  <?php echo $module_data['is_disabled']=='0' ? 'selected' : ''?> >Yes</option>
                                                            <option value="1"  <?php echo $module_data['is_disabled']=='1' ? 'selected' : ''?>>No</option>
                                                       </select>
                                                </div>
                                            </div>
                                             <div class="col-xs-3">
                                                <div class="field-row">
                                                      <label>Available on Ems </label><br>
                                                       <select name="is_ems_module" class="js-from full_width" id="is_ems_module">
                                                            <option value="all"<?php echo $module_data['is_ems_module']=='all' ? 'selected' : ''?> >All</option>
                                                            <option value="1"  <?php echo $module_data['is_ems_module']=='1' ? 'selected' : ''?> >Yes</option>
                                                            <option value="0"  <?php echo $module_data['is_ems_module']=='0' ? 'selected' : ''?>>No</option>
                                                       </select>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <hr />
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                              
                                                <input type="submit"  name="submit_button" class="btn btn-success" value="Save">
                                                <a class="btn btn-default" href='<?php echo base_url('manage_admin/modules') ?>'>Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                  
                                           
                                         
                                            
                                        </div>
                                    </div>
                                    <!-- Email Logs End -->
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
$(".js-from").select2();
    function resend(id) {
        alertify.dialog('confirm')
            .set({
                'title ': 'Confirmation',
                'labels': {ok: 'Yes', cancel: 'No'},
                'message': 'Are you sure you want to Resend this Email?',
                'onok': function () {
                    url = "<?= base_url('manage_admin/resend_email') ?>" + '/' + id;
                    window.location.href = url;
                },
                'oncancel': function () {
                    alertify.error('Cancelled!');
                }
            }).show();
    }


</script>
<style>
.full_width{
    width:100%;
}
</style>