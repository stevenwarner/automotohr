<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br />
        <!-- Main Content Area -->
        <div class="row">
            <!--  -->
            <div class="col-sm-12">
                 <!--  -->
                 <div class="row">
                        <div class="col-xs-12 text-right">
                            <button class="btn btn-orange" id="jsPayrollAdminAddBtn"><i class="fa fa-plus-circle" aria-hidden="true"></i>&nbsp;Add Admin</button>
                        </div>
                    </div>
                <div class="">
                    <span class="pull-left">
                        <h3>Payroll Admins</h3>
                    </span>
                    <span class="pull-right">
                        <h3>Total: <?=count($CompanyAdmins);?></h3>
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
                                            <th scope="col">Name</th>
                                            <th scope="col" class="text-right">Email</th>
                                            <th scope="col" class="text-right">Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($CompanyAdmins): ?>
                                            <?php foreach($CompanyAdmins as $ad): ?>
                                                <tr>
                                                    <td>
                                                        <p><?=ucwords($ad['first_name'].' '.$ad['last_name']);?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <p><?=$ad['email_address'];?></p>
                                                    </td>
                                                    <td class="text-right">
                                                        <p><?=formatDateToDB($ad['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);?></p>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else:?>
                                            <tr>
                                                <td colspan="3">
                                                    <p class="alert alert-info text-center">
                                                        No records found.
                                                    </p>
                                                </td>
                                            </tr>
                                        <?php endif;?>
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

<!-- Add System Model -->
<link rel="stylesheet" href="<?= base_url(_m("assets/css/SystemModel", 'css')); ?>">
<script src="<?= base_url(_m("assets/js/SystemModal")); ?>"></script>