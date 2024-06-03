<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('v1/payroll/sidebar'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <!-- Top bar -->
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow">
                                    <!-- Company details header -->
                                    <?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <!--  -->
                                    <a href="<?php echo base_url('dashboard'); ?>" class="dashboard-link-btn">
                                        <i class="fa fa-chevron-left"></i>Dashboard
                                    </a>
                                </span>
                            </div>

                            <!-- Regular payrolls -->
                            <div class="row">
                                <div class="col-sm-8 col-xs-12">
                                    <!-- Regular -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="text-large">
                                                <strong>
                                                    Regular payroll
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($regularPayrolls['current'] || $regularPayrolls['late']) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay period
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Pay day
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Due in
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Type
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Actions
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if ($regularPayrolls['current']) { ?>
                                                                <?php
                                                                $dueDate = getDueDate($regularPayrolls['current']['check_date']);
                                                                ?>
                                                                <tr class="<?= strpos($dueDate, '-') !== false ? 'bg-danger' : '' ?>">
                                                                    <td class="vam">
                                                                        <?= formatDateToDB(
                                                                            $regularPayrolls['current']['start_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $regularPayrolls['current']['end_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        Due in <?= $dueDate; ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= formatDateToDB(
                                                                            $regularPayrolls['current']['check_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= strpos($dueDate, '-') !== false ? 'Late' : 'Regular'; ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <a href="<?= base_url('payrolls/regular/' . $regularPayrolls['current']['sid']); ?>" class="btn csW csBG3 csF16">Run Payroll</a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            <?php foreach ($regularPayrolls['late'] as $value) { ?>
                                                                <?php
                                                                $dueDate = getDueDate($value['check_date']);
                                                                ?>
                                                                <tr class="bg-danger">
                                                                    <td class="vam">
                                                                        <?= formatDateToDB(
                                                                            $value['start_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $value['end_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        Due in <?= $dueDate; ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= formatDateToDB(
                                                                            $value['check_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        Late
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <a href="<?= base_url('payrolls/regular/' . $value['sid']); ?>" class="btn csW csBG3 csF16">Run Payroll</a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No regular payrolls.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('payrolls/regular'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                &nbsp;View more
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Pay stubs -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    Pay stubs
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($payStubs) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay period
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Pay day
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Employees
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Actions
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($payStubs as $value) { ?>
                                                                <tr>
                                                                    <td class="vam">
                                                                        <?= formatDateToDB(
                                                                            $value['start_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $value['end_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= formatDateToDB(
                                                                            $value['check_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= $value['count']; ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <a href="<?= base_url('payrolls/pay-stubs/report/' . ($value['sid']) . ''); ?>" class="btn csW csBG3 csF16">
                                                                            View
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No pay stubs found.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('payrolls/pay-stubs/report'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                &nbsp;View more
                                            </a>
                                        </div>
                                    </div>
                                    <!-- External -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    External Payrolls
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($externalPayrolls) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay period
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Pay day
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Actions
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($externalPayrolls as $value) { ?>
                                                                <tr>
                                                                    <td class="vam">
                                                                        <?= formatDateToDB(
                                                                            $value['payment_period_start_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $value['payment_period_end_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= formatDateToDB(
                                                                            $value['check_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <a href="<?= base_url('payrolls/external/' . $value['sid']); ?>" class="btn csW csBG3 csF16">
                                                                            View
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No external payrolls.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('payrolls/external'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                &nbsp;View more
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Benefits -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    Benefits
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($benefits) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Name
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Employees<br />enrolled
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Status
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($benefits as $value) { ?>
                                                                <tr>
                                                                    <td class="vam">
                                                                        <?= $value['description']; ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= $value['employee_count']; ?>
                                                                    </td>
                                                                    <td class="vam text-right text-<?= $value['active'] == 1 ? 'success' : 'danger'; ?>">
                                                                        <strong>
                                                                            <?= $value['active'] == 1 ? 'ACTIVE' : 'INACTIVE'; ?>
                                                                        </strong>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No benefits found.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('benefits'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                &nbsp;View more
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- right side -->
                                <div class="col-sm-4 col-xs-12">
                                    <!-- bank account -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    Bank account
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($bankAccount) { ?>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h1 class="csF16">
                                                            <strong>
                                                                Account name
                                                            </strong>
                                                        </h1>
                                                        <p class="csF16">
                                                            <?= $bankAccount['name']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h1 class="csF16">
                                                            <strong>
                                                                Account type
                                                            </strong>
                                                        </h1>
                                                        <p class="csF16">
                                                            <?= $bankAccount['account_type']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h1 class="csF16">
                                                            <strong>
                                                                Routing number
                                                            </strong>
                                                        </h1>
                                                        <p class="csF16">
                                                            <?= $bankAccount['routing_number']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <h1 class="csF16">
                                                            <strong>
                                                                Account number
                                                            </strong>
                                                        </h1>
                                                        <p class="csF16">
                                                            <?= $bankAccount['hidden_account_number']; ?>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No bank account found.']); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- Payroll history -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    Payroll receipts
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($payrolls) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay period
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Pay day
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Actions
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($payrolls as $value) { ?>
                                                                <tr>
                                                                    <td class="vam">
                                                                        <?= formatDateToDB(
                                                                            $value['start_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $value['end_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= formatDateToDB(
                                                                            $value['check_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <a href="<?= base_url('payrolls/history/' . $value['sid']); ?>" class="btn csW csBG3 csF16">
                                                                            View
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No payroll history found.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('payrolls/history'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                &nbsp;View more
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Payroll history -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    Off Cycle Payroll receipts
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($offCyclePayrolls) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Pay period
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Pay day
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Reason
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Actions
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php foreach ($offCyclePayrolls as $value) { ?>
                                                                <tr>
                                                                    <td class="vam">
                                                                        <?= formatDateToDB(
                                                                            $value['start_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?> -
                                                                        <?= formatDateToDB(
                                                                            $value['end_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?= formatDateToDB(
                                                                            $value['check_date'],
                                                                            DB_DATE,
                                                                            DATE
                                                                        ); ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <?=$value['off_cycle_reason']; ?>
                                                                    </td>
                                                                    <td class="vam text-right">
                                                                        <a href="<?= base_url('payrolls/history/' . $value['sid']); ?>" class="btn csW csBG3 csF16">
                                                                            View
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No payroll history found.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('payrolls/history'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-eye csF16" aria-hidden="true"></i>
                                                &nbsp;View more
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Payroll employees -->
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <h1 class="csF16 csW m0">
                                                <strong>
                                                    Payroll employees
                                                </strong>
                                            </h1>
                                        </div>
                                        <div class="panel-body">
                                            <?php if ($payrollEmployees) { ?>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <caption></caption>
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="csW csBG4">
                                                                    Employee
                                                                </th>
                                                                <th scope="col" class="text-right csW csBG4">
                                                                    Status
                                                                </th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($payrollEmployees as $value) { ?>
                                                                <tr>
                                                                    <td class="vam">
                                                                        <?= $value['name']; ?>
                                                                    </td>
                                                                    <td class="vam text-right bg-<?= $value['is_onboard'] == 1 ? 'success' : 'warning'; ?>">
                                                                        <strong>
                                                                            <?= $value['is_onboard'] == 1 ? 'COMPLETED' : 'PENDING'; ?>
                                                                        </strong>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            <?php } else { ?>
                                                <?php $this->load->view('v1/no_data', ['message' => 'No external payrolls.']); ?>
                                            <?php } ?>
                                        </div>
                                        <div class="panel-footer text-center">
                                            <a href="<?= base_url('payrolls/employees'); ?>" class="btn csW csBG3 csF16">
                                                <i class="fa fa-users csF16" aria-hidden="true"></i>
                                                &nbsp;Manage employees
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>