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
                                        <h1 class="page-title"><i class="fa fa-envelope-o"
                                                aria-hidden="true"></i>Companies</h1>
                                    </div>
                                    <!-- Email Logs Start -->
                                    <div class="hr-box">
                                        <div class="hr-box-header bg-header-green">
                                            <span class="pull-left">
                                                <h1 class="hr-registered"><?=ucwords($module_data['module_name']);?>
                                                </h1>
                                            </span>
                                        </div>
                                        <div class="hr-innerpadding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span class="pull-right">
                                                        <button class="btn btn-success"
                                                            onclick="window.location.href='<?php echo base_url('manage_admin/modules') ?>' ">Back</button>
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
                                                                <?php foreach($company_data as $company)  {  
                                                                    //
                                                                    $class = "js-dynamic-module-btn";
                                                                    ?>
                                                                <tr data-id="<?=$company['sid'];?>">
                                                                    <td>
                                                                        <?php echo $company['CompanyName'] ?>
                                                                    </td>
                                                                    <td>
                                                                        <button
                                                                            data-status="<?php echo $company['status'] ?>"
                                                                            company_sid="<?php echo $company['sid'] ?>"
                                                                            class="btn <?=$class;?> btn-<?php echo $company['status']==0 ? "success" : "danger" ?>"><?php echo $company['status']==0 ? "Activate" : "Deactivate" ?></button>

                                                                        <?php if($module_data['sid'] == 1) { ?>
                                                                        <a href="<?= base_url('manage_admin/manage_policies') ?>/<?= $company['sid'] ?>"
                                                                            class="btn btn-success">Manage</a>
                                                                        <?php } ?>

                                                                        <?php if($module_data['module_slug'] == 'assurehire') { ?>
                                                                        <a href="javascript:void(0)"
                                                                            class="btn btn-success jsAssurehireCredentials">Update
                                                                            Credentials</a>
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
                                                        <button class="btn btn-success"
                                                            onclick="window.location.href='<?php echo base_url('manage_admin/modules') ?>' ">Back</button>
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
$(function() {
    $('.js-dynamic-module-btn').click(function(e) {
        e.preventDefault();
        let megaOBJ = {};
        var _this = $(this);

        megaOBJ.Status = $(this).attr('data-status');
        megaOBJ.CompanyId = $(this).attr('company_sid');
        megaOBJ.Id = <?php echo $this->uri->segment(3); ?>;
        //
        alertify.confirm('Do you really want to ' + (megaOBJ.Status == 1 ? 'disable' : 'enable') +
            ' this MODULE?',
            function() {
                //
                $.post("<?=base_url('manage_admin/companies/update_module_status');?>", megaOBJ,
                    function(resp) {
                        if (resp.Status === false) {
                            alertify.alert('ERROR!', resp.Response);
                            return;
                        }
                        alertify.alert('SUCCESS!', 'Module has been ' + (megaOBJ.Status == 1 ?
                            'Disabled' : 'Enabled') + '.');
                        if (megaOBJ.Status == 1) {
                            _this.attr('data-status', 0);
                            _this.removeClass('btn-danger').addClass('btn-success');
                            _this.text('Activate');
                        } else {
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
//
<?php if($module_data['module_slug'] == 'assurehire'){ ?>
//
$(function Assurehire() {
    var
        companyId = 0,
        credOBJ = <?=json_encode($assureHireCreds);?>;
    //
    $('.jsAssurehireCredentials').click(function(event) {
        //
        event.preventDefault();
        //
        companyId = $(this).closest('tr').data('id')
        //
        var modal = $('#jsModalContainer2').html();
        $('#jsModalContainer2').remove();
        $('body').append(modal)
        //
        $('.jsCompanyName').html('<strong>'+$(this).closest('tr').find('td:first').text()+'</strong>');
        $('.jsUsername').val(credOBJ[companyId] !== undefined ? credOBJ[companyId]['username'] : '');
        $('.jsPassword').val(credOBJ[companyId] !== undefined ? credOBJ[companyId]['password'] : '');
        //
        $('#jsModaleContainer2').modal('show');
    });
    //
    $(document).on('click', '.jsSaveCredentials', function(event) {
        //
        event.preventDefault();
        //
        var o = {};
        o.username = $('.jsUsername').val().trim();
        o.password = $('.jsPassword').val().trim();
        //
        if (!o.username) {
            return alertify.alert('Error!', 'Username is required.', function() {
                return true;
            });
        }
        //
        if (!o.password) {
            return alertify.alert('Error!', 'Password is required.', function() {
                return true;
            });
        }
        //
        $(this).text('Saving.....');
        //
        $.post(
            "<?=base_url("manage_admin/companies/assurehire_creds");?>/"+(companyId)+"",
            o
        ).done(function(resp) {
            //
            if (!resp.status) {
                return alertify.alert('Error!', resp.response, function() {
                    return true;
                });
            }
            //
            return alertify.alert('Success!', resp.response, function() {
                $('#jsModaleContainer2').modal('hide');
                window.location.reload();
            });
        });
    });
});
<?php } ?>
</script>

<style>
.full_width {
    width: 100%;
}

</style>


<!-- Assure Modal -->
<div id="jsModalContainer2">
    <div class="modal fade" id="jsModaleContainer2">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Update Assurehire credentials for <span class="jsCompanyName" ></span></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Username <span class="csRequired">*</span> </label>
                            <input type="text" class="form-control jsUsername"/>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Password <span class="csRequired">*</span> </label>
                            <input type="text" class="form-control jsPassword"/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success jsSaveCredentials">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>