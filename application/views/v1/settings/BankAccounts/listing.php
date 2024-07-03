<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <!--  -->
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-12">
                    <?php $this->load->view('main/employer_column_left_view'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-12">
                    <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                    <div class="dashboard-conetnt-wrp">
                        <div class="page-header-area">
                            <span class="page-heading down-arrow">
                                <?php $this->load->view('manage_employer/company_logo_name'); ?>
                            </span>
                        </div>
                    </div>
                    <!--  -->
                    <div class="text-right">
                        <button class="btn btn-orange jsCompanyBankAccountAddBtn">
                            <i class="fa fa-plus-circle"></i>
                            Add A Bank Account
                        </button>
                    </div>
                    <br>
                    <!--  -->
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="text-medium panel-heading-text">
                                <i class="fa fa-bank text-orange"></i>
                                Bank Accounts
                            </h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="bg-black">
                                            <th scope="col">
                                                Name
                                            </th>
                                            <th scope="col">
                                                Account<br />Type
                                            </th>
                                            <th scope="col">
                                                Routing<br />Number
                                            </th>
                                            <th scope="col">
                                                Account<br />Number
                                            </th>
                                            <th scope="col">
                                                Use for<br />payroll?
                                            </th>
                                            <th scope="col">
                                                Verified<br />status
                                            </th>
                                            <th scope="col">
                                                Status
                                            </th>
                                            <th scope="col">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($bankAccounts) {
                                            foreach ($bankAccounts as $v0) {
                                        ?>
                                                <tr class="bg-<?= $v0["is_active"] == 1 ? "success" : "danger"; ?>" data-id="<?= $v0["sid"]; ?>">
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= $v0["name"]; ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= $v0["account_type"]; ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= $v0["routing_number"]; ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= $v0["hidden_account_number"]; ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= $v0["gusto_uuid"]
                                                            ? '<i class="fa fa-check fa-2x text-success"></i>'
                                                            : '<i class="fa fa-times fa-2x text-danger"></i>'; ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= SlugToString($v0["verification_status"]); ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?= $v0["is_active"] == 1 ? "Active" : "Inactive"; ?>
                                                    </td>
                                                    <td class="csVerticalAlignMiddle">
                                                        <?php if($v0["is_active"]) { ?>
                                                            -
                                                        <?php } else {?>
                                                        <button class="btn btn-danger text-medium jsCompanyBankAccountDeleteBtn">
                                                            <i class="fa fa-times-circle"></i>
                                                            Delete
                                                        </button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                        } else {
                                            ?>
                                            <tr>
                                                <th colspan="8" scope="col">
                                                    <p class="alert alert-info text-center">
                                                        No company bank accounts found.
                                                    </p>
                                                </th>
                                            </tr>
                                        <?php
                                        }
                                        ?>
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