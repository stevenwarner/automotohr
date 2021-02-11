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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Time Off</h1>
                                    </div>
                                    <!-- Search Table Start -->
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Company Time Off Policies</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                               <div class="col-xs-12">
                                                    <span class="pull-right">
                                                        <!-- <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/time_off/manage_approvers/'.$company_sid); ?>' ">Manage Approvers</button> -->
                                                        <!-- <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/time_off/time_off_settings/'.$company_sid); ?>' ">Settings</button> -->
                                                       <button class="btn btn-black" onclick="window.location.href='<?php echo base_url('manage_admin/modules') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <tbody>
                                                                <?php if(!empty($company_default_policy))  {  ?>
                                                                    <tr>
                                                                        <td colspan="3">
                                                                            <h4 style="font-weight: 800;">Default</h4>
                                                                        </td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td>
                                                                            <?php echo $company_default_policy['title']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo date_with_time($company_default_policy['created_at']); ?>
                                                                        </td>
                                                                        <td>
                                                                            <label class="switch">
                                                                                <input type="checkbox" class="manage_policies" id="<?php echo $company_sid.'_'.$company_default_policy['sid']; ?>" data-pid="<?php echo $company_default_policy['sid']; ?>" data-cid="<?php echo $company_sid; ?>"  data-title="<?php echo $company_default_policy['title']; ?>" <?php echo $company_default_policy['is_archived'] == 0 ? 'checked' : ''; ?> >
                                                                                <span class="slider round"></span>
                                                                            </label>
                                                                        </td>
                                                                    </tr>
                                                                <?php } ?>    
                                                                <?php if(!empty($timeoff_categories) && !empty($company_policies))  {  ?>
                                                                    <?php foreach($timeoff_categories as $category)  {  ?>
                                                                        <tr>
                                                                            <td colspan="3">
                                                                                <h4 style="font-weight: 800;"><?php echo $category['category_name']; ?></h4>
                                                                            </td>
                                                                        </tr>  
                                                                        <?php foreach($company_policies as $policy)  {  ?>
                                                                            <?php if($category['sid'] == $policy['cat_sid'])  {  ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?php echo $policy['title']; ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php echo date_with_time($policy['created_at']); ?>
                                                                                    </td>
                                                                                    <td>
                                                                                        <label class="switch">
                                                                                            <input type="checkbox" class="manage_policies" id="<?php echo $company_sid.'_'.$policy['sid']; ?>" data-pid="<?php echo $policy['sid']; ?>" data-cid="<?php echo $company_sid; ?>"  data-title="<?php echo $policy['title']; ?>" <?php echo $policy['is_archived'] == 0 ? 'checked' : ''; ?> >
                                                                                            <span class="slider round"></span>
                                                                                        </label>
                                                                                    </td>
                                                                                </tr>
                                                                            <?php } ?>
                                                                        <?php } ?>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">
                                                       <button class="btn btn-black" onclick="window.location.href='<?php echo base_url('manage_admin/company_module/1') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
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
<style>
    .btn-black {
        color: #fff;
        background: #000;
    }
    
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    .switch input { 
      opacity: 0;
      width: 0;
      height: 0;
    }

    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: green;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
</style>
<script>
    $('.manage_policies').on('click', function () {
        var policy_sid = $(this).attr('data-pid');
        var company_sid = $(this).attr('data-cid');
        var policy_title= $(this).attr('data-title');
        var input_id = $(this).attr('id');
        if($(this).prop('checked') == true){
            request_handler(policy_sid,company_sid,1, policy_title);
        } else {
            alertify.confirm('Confirmation', 'Are you sure you want to deactivate "<b>'+policy_title+'</b>" policy?',
                function () {
                    request_handler(policy_sid,company_sid,0, policy_title);
                },
                function () {
                    $("#"+input_id).prop('checked', true);
                }
            );
        }
    });

    function request_handler (policy_sid, company_sid, status, title) {
        var baseURI = "<?=base_url();?>manage_admin/time_off/handler";

        var formData = new FormData();
        formData.append('policy_sid', policy_sid);
        formData.append('company_sid', company_sid);
        formData.append('status', status);
        formData.append('action', 'change_policy_status');

        $.ajax({
            url: baseURI,
            data: formData,
            method: 'POST',
            processData: false,
            contentType: false
        }).done(function(resp){
            var successMSG = 'You have successfully activated <b>"'+title+'"</b>.';
            if(status == 0){
                 successMSG = 'You have successfully deactivated <b>"'+title+'"</b>.'; 
            }

            alertify.alert('SUCCESS!', successMSG, function(){
                    
            });
        });
    }
</script>
