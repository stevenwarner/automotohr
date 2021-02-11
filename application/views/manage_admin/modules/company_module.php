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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"></i>Companies</h1>
                                    </div>
                                    <!-- Search Table Start -->
                                    <!-- Search Table End -->
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered">Companies</h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
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
                                                            <thead>
                                                                <tr>
                                                                    <th class=" text-center">Company Name</th>
                                                                    <th class="col-sm-4 text-center">Actions</th>
                                                                   
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                 <?php foreach($company_data as $company)  {  ?>
                                                                     <tr>
                                                                         <td>
                                                                            <?php echo $company['CompanyName'] ?>
                                                                        </td>
                                                                         <td>
                                                                            <button data-status="<?php echo $company['status'] ?>" company_sid="<?php echo $company['sid'] ?>" class="btn js-dynamic-module-btn btn-<?php echo $company['status']==0 ? "success" : "danger" ?>"><?php echo $company['status']==0 ? "Activate" : "Deactivate" ?></button>
                                                                            <a href="<?= base_url('manage_admin/manage_policies') ?>/<?= $company['sid'] ?>" class="btn btn-success">Manage</a>
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
<script>

      $(function(){
        $('.js-dynamic-module-btn').click(function(e){
            e.preventDefault();
            let megaOBJ = {};
            var _this = $(this);

             megaOBJ.Status = $(this).attr('data-status');
             megaOBJ.CompanyId=$(this).attr('company_sid');
             megaOBJ.Id=<?php echo $this->uri->segment(3); ?>;
            //
            
            //
            alertify.confirm('Do you really want to '+( megaOBJ.Status == 1 ? 'disable' : 'enable' )+' this MODULE?', function(){
                //
                $.post("<?=base_url('manage_admin/companies/update_module_status');?>", megaOBJ, function(resp){
                    if(resp.Status === false){
                        alertify.alert('ERROR!', resp.Response);
                        return;
                    }
                    alertify.alert('SUCCESS!', 'Module has been '+( megaOBJ.Status == 1 ? 'Disabled' : 'Enabled' )+'.');
                    console.log(megaOBJ);
                    if(megaOBJ.Status == 1) {
                        _this.attr('data-status', 0);
                        _this.removeClass('btn-danger').addClass('btn-success');
                        _this.text('Activate');
                    }
                    else {
                        _this.attr('data-status', 1);
                        _this.removeClass('btn-success').addClass('btn-danger');
                        _this.text('Deactivate');
                    }
                });
            }).set('labels', {
                ok: 'Yes',
                cancel: 'No'
            });
        });
    })

</script>

<style>
.full_width{
    width:100%;
}
</style>