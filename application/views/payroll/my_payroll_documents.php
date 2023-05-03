<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!-- Sidebar -->
            <!--  -->
            <div class="col-sm-12 col-xs-12">
                <div class="">
                    <span class="pull-left">
                        <h3 class="">My Payroll Documents</h3>
                    </span>
                    <span class="pull-right">
                        <h3>Total: <?=count($formInfo['Forms']);?></h3>
                    </span>
                </div>
                <div class="">
                    <!--  -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <caption></caption>
                                    <thead>
                                        <tr>
                                            <th scope="col">Title</th>
                                            <th scope="col" class="text-right">Required</th>
                                            <th scope="col" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsPayrollEmployeePayStubsBox">
                                        <?php if($formInfo['Status'] && !empty($formInfo['Forms'])) { ?>
                                            <?php foreach ($formInfo['Forms'] as $form) { ?>
                                                <tr>
                                                    <td>
                                                        <p><?=$form['title'];?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <p><?=$form['requires_signing'] == 1 ? 'Yes' : 'No';?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="<?php echo base_url('payroll/my_document/').$form['uuid']; ?>" class="btn btn-orange jsViewFile" title="View Form" placement="top">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else {?>
                                            <tr>
                                                <td colspan="3">
                                                    <p class="alert alert-info text-center">
                                                        No Payroll Documents Found.
                                                    </p>
                                                </td>
                                            </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('iframeLoader'); ?>

<!-- Add System Model -->
<link rel="stylesheet" href="<?= base_url(_m("assets/css/SystemModel", 'css')); ?>">
<script src="<?= base_url(_m("assets/js/SystemModal")); ?>"></script>

<!--  -->
<script>
    $(function(){
        $('.jsViewFile').click(function(event){
            //
            event.preventDefault();
            //
            var data = $(this).closest('.jsPayrollEPSId').data();
            //
            $('#jsPayrollEPSModal .modal-title').text();
            //
            $('#jsPayrollEPSModal').modal();
            //
            Model({
                Id: "jsPayrollEPSModal",
                Title: 'Pay Stub - '+data.date,
                Body: '<div class="row"><div class="col-sm-12"><iframe src="" id="jsPayrollEPSModalIframe" frameborder="0" style="width:100%; height: 500px"></iframe></div></div>',
                Buttons: [
                    '<a href="" class="btn btn-cancel jsDownloadBTN csW" style="margin-top: -5px;" title="Download Pay Stub" placement="top"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download Pay Stub</a>'
                ],
                Loader: 'jsPayrollEPSModalLoader'
            }, function(){
                //
                $('.jsDownloadBTN').prop('href', "<?=base_url('payroll/p/download');?>/"+data.id)
                //
                loadIframe(
                    "<?=AWS_S3_BUCKET_URL;?>"+data.file,
                    "#jsPayrollEPSModalIframe",
                    false,
                    '.jsIPLoader[data-page="jsPayrollEPSModalLoader"]'
                );
            });
        });
    })
</script>