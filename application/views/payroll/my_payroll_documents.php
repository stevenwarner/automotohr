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
                        <h3>Total: <?=count($formInfo);?></h3>
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
                                            <th scope="col" class="text-right">Status</th>
                                            <th scope="col" class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="jsPayrollEmployeePayStubsBox">
                                        <?php if(!empty($formInfo)) { ?>
                                            <?php foreach ($formInfo as $form) { ?>
                                                <tr>
                                                    <td>
                                                        <p><?=$form['title'];?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <p><?=$form['requires_signing'] == 1 ? 'Yes' : 'No';?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <p><?=$form['requires_signing'] == 1 && $form['is_signed'] == 0 ? 'PENDING' : 'COMPLETED';?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <a href="<?php echo base_url('payroll/my_document/').$form['sid']; ?>" class="btn btn-orange jsViewFile" title="View Form" placement="top">
                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <tr>
                                                <td colspan="3">
                                                    <p class="alert alert-info text-center">
                                                        No Payroll Documents Found.
                                                    </p>
                                                </td>
                                            </tr>
                                        <?php } ?>
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