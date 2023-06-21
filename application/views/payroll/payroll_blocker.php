<div class="csPageWrap">
    <!-- Nav bar -->
    <div class="container-fluid">
        <?php $this->load->view('payroll/navbar'); ?>
    </div>
    <br>
    <!--  -->
    <div class="container-fluid">
        <!-- Main Content Area -->
        <div class="row">
            <div class="col-sm-12">
                <h1 class="m0 p0 csB7">
                    Payroll Blockers
                </h1>
                <hr />
            </div>
        </div>

        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <strong class="text-danger" style="font-size: 18px;">
                    <em>In order to process payroll, you must first accomplish the following tasks.</em>
                </strong>
            </div>
        </div>
        <!--  -->
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped" title="Payroll Blocker">
                        <caption></caption>
                        <tbody>
                            <?php if (isset($payrollBlockers['errors'])) { ?>
                                <?= implode('<br/>', $payrollBlockers['errors']); ?>
                            <?php } else {
                                foreach ($payrollBlockers as $blocker) {
                                    echo '<tr><th>' . ($blocker['message']) . '</th></tr>';
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>