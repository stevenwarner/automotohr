<style>
    table td{ vertical-align: middle;}
</style>
<div class="container-fluid">
    <div class="csPageWrap">
        <!-- Header -->
        <?php $this->load->view('payroll/navbar'); ?>
        <br>
        <div class="row">
            <div class="col-sm-12">
                <h3><strong>Company Documents</strong></h3>
            </div>
        </div>
        <br>
        <!-- Main area -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <th scope="col">Name</th>
                    <th scope="col">Title</th>
                    <th scope="col">Requires Signing</th>
                    <th scope="col">Actions</th>
                </thead>
                <tbody>
                    <?php if ($payrollDocuments) {
                        foreach ($payrollDocuments as $payrollDocument) {
                    ?>
                            <tr>
                                <td><?= $payrollDocument['document_name']; ?></td>
                                <td><?= $payrollDocument['document_title']; ?></td>
                                <td class="text-center text-<?= $payrollDocument['require_signing'] ? 'success' : 'danger'; ?>">
                                    <strong>
                                        <i class="fa fa-<?= $payrollDocument['require_signing'] ? "tick" : "times"; ?>" aria-hidden="true"></i>
                                    </strong>
                                </td>
                                <td>
                                    <?php if ($payrollDocument['require_signing']) {
                                    ?>
                                        <a href="<?= base_url('payroll/company/document/' . ($payrollDocument['sid']) . '/sign'); ?>" class="btn btn-success">Sign Document</a>
                                        <?php
                                    } ?>
                                    <a href="<?= base_url('payroll/company/document/' . ($payrollDocument['sid']) . '/view'); ?>" class="btn btn-success">View Document</a>
                                </td>
                            </tr>
                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4">
                                <p class="alert alert-info text-center">
                                    <strong>
                                        No payroll documents found!
                                    </strong>
                                </p>
                            </td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>