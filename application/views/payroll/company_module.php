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
                                        <h1 class="page-title"><i class="fa fa-envelope-o" aria-hidden="true"></i>Companies</h1>
                                    </div>
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered"><?=ucwords($module_data['module_name']);?></h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="csIPLoader jsIPLoader" data-page="main"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></div>
                                            <div class="row">
                                               <div class="col-xs-12">
                                                    <span class="pull-right">
                                                       <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/modules') ?>' ">Back</button>
                                                    </span>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <caption></caption>
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col" class="text-center">Company Name</th>
                                                                    <th scope="col" class="text-center">Actions</th>
                                                                   
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                $passData = [];
                                                                //
                                                                foreach($company_data as $company)  { $passData[$company['sid']] = $company;  ?>
                                                                    <tr data-id="<?=$company['sid'];?>">
                                                                         <td>
                                                                            <?php echo $company['CompanyName']; ?>
                                                                        </td>
                                                                        <td>
                                                                            <button 
                                                                                data-status="<?php echo $company['is_active'] ?>" 
                                                                                company_sid="<?php echo $company['sid'] ?>" 
                                                                                class="btn js-dynamic-module-btn btn-<?php echo $company['is_active']==0 ? "success" : "danger" ?>"
                                                                            >
                                                                                <?php echo $company['is_active']==0 ? "Activate" : "Deactivate" ?>
                                                                            </button>
                                                                            <button class="btn btn-success jsSetSSN">
                                                                                Update EIN Number
                                                                            </button>
                                                                            <?php if($company['access_token']) { ?>
                                                                            <button class="btn btn-success jsDetails">
                                                                                Details
                                                                            </button>
                                                                            <?php } ?>
                                                                        </td>
                                                                     </tr>
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
                                                       <button class="btn btn-success" onclick="window.location.href='<?php echo base_url('manage_admin/modules') ?>' ">Back</button>
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
<!-- Include Modal -->
<link rel="stylesheet" href="<?=base_url("assets/css/SystemModel.css");?>" />
<script src="<?=base_url("assets/js/SystemModal.js");?>"></script>
<!-- Include moment -->
<script src="<?=base_url("assets/js/moment.min.js");?>"></script>

<script>

      $(function(){

        //
        var obj = {};
        //
        obj.companyId = 0;
        obj.moduleId = 0;
        obj.action = '';
        //
        var companyData = <?=json_encode($passData);?>;
        //
        $('.js-dynamic-module-btn').click(function(e){
            //
            e.preventDefault();
            //
            obj.companyId = $(this).attr('company_sid');
            //
            if(!companyData[obj.companyId]['ssn']){
                alertify.alert('Error!', 'To activate payroll for this company, the EIN number is required.');
                return;
            }
            //
            obj.status = $(this).attr('data-status');
            obj.action = 'update_status';
            //
            alertify.confirm(
                'Do you really want to '+( obj.status == 1 ? 'disable' : 'enable' )+' the module against this company?', function(){
                    ml(true, 'main');
                    UpdateAction();
            });
        });
        
        //
        $('.jsDetails').click(function(event){
            //
            event.preventDefault();
            //
            obj.companyId = $(this).closest('tr').data('id');
            //
            var o = companyData[obj.companyId];
            //
            var html = '';
            html += '<div class="container">';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label>Company UUID</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['gusto_company_uid'] != null ? o['gusto_company_uid'] : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label>Access Token</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['access_token'] != null ? o['access_token'] : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label>Refresh Token</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['refresh_token'] != null ? o['refresh_token'] : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label>Old Access Token</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['old_access_token'] != null ? o['old_access_token'] : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label>Old Refresh Token</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['old_refresh_token'] != null ? o['old_refresh_token'] : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '   <div class="row">';
            html += '       <div class="col-sm-6">';
            html += '           <label>Created At</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['created_at'] != null ? moment(o['created_at']).format('MMM DD YYYY, ddd hh:mm a') : '')+'" />';
            html += '       </div>';
            html += '       <div class="col-sm-6">';
            html += '           <label>Updated At</label>';
            html += '           <input type="text" class="form-control" disabled value="'+(o['updated_at'] != null ? moment(o['updated_at']).format('MMM DD YYYY, ddd hh:mm a') : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '</div>';
            //
            Modal({
                Id: 'jsCompanyDetailModal',
                Title: 'Details for '+o['CompanyName'],
                Loader: 'jsCompanyDetailModalLoader',
                Buttons: [
                    '<button class="btn btn-success jsRefreshToken">Refresh Token</button>'
                ],
                Body: html
            }, function(){
                //
                ml(false, 'jsCompanyDetailModalLoader');
            });
        });

        //
        $('.jsSetSSN').click(function(event){
            //
            event.preventDefault();
            //
            obj.companyId = $(this).closest('tr').data('id');
            //
            var html = '';
            html += '<div class="container">';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label>EIN Number</label>';
            html += '           <input type="text" class="form-control" id="jsEinNumberText" value="'+(companyData[obj.companyId]['ssn'] != null ? companyData[obj.companyId]['ssn'] : '')+'" />';
            html += '       </div>';
            html += '   </div>';
            html += '   <br>';
            html += '   <div class="row">';
            html += '       <div class="col-sm-12">';
            html += '           <label></label>';
            html += '           <button class="btn btn-success" id="jsUpdateEINNumber">Update EIN Number</button>';
            html += '       </div>';
            html += '   </div>';
            html += '</div>';
            //
            Modal({
                Id: 'jsEINModal',
                Title: 'Update EIN Number for '+companyData[obj.companyId]['CompanyName'],
                Loader: 'jsEINModalLoader',
                Body: html
            }, function(){
                //
                ml(false, 'jsEINModalLoader');
            });
        });

        //
        $(document).on('click', '.jsRefreshToken', function(event){
            //
            event.preventDefault();
            //
            ml(true, 'jsCompanyDetailModalLoader');
            //
            obj.action = 'refresh_token';
            //
            UpdateAction();
        });

        //
        $(document).on('click', '#jsUpdateEINNumber', function(event){
            //
            event.preventDefault();
            //
            var newEIN = $('#jsEinNumberText').val().trim().replace(/[^0-9]/g, '').substr(0,9);
            //
            if(newEIN.length != 9){
                alertify.alert(
                    "Warning!",
                    "EIN number must be of 9 numbers"
                );
                return;
            }
            //
            ml(true, 'jsEINModalLoader');
            //
            obj.ein = newEIN;
            obj.action = 'update_ein';
            //
            UpdateAction();
        });
       
        //
        $(document).on('keyup', '#jsEinNumberText', function(event){
            //
            $(this).val(
                $(this).val().trim().replace(/[^0-9]/g, '').substr(0,9)
            );
        });


        //
        function UpdateAction(){
            //
            $.post(
                "<?=base_url("update_payroll_module")?>", 
                obj
            ).done(function(resp){
                //
                if(resp.Status === false){
                    alertify.alert('Error!', resp.Response);
                    return;
                }
                //
                obj = {};
                //
                $('.jsModalCancel').click();
                //
                alertify.alert('Success!', resp.Response, function(){
                    //
                    window.location.reload();
                });
            });
        }
        //
        ml(false, 'main');
    })

</script>

<style>
.full_width{
    width:100%;
}
</style>