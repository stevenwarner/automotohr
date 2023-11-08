<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <strong>
                        Plans
                    </strong>
                </h1>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <button class="btn csW csBG3 csF16 jsAddBenefitPlan">
                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                    &nbsp;Add a plan
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (!$plans) { ?>
            <?php $this->load->view('v1/no_data', ['message' => 'No benefit plans found.']); ?>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Plan Name</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Eligible</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($plans as $plan) { ?>
                            <tr data-id="<?= $plan['sid']; ?>">
                                <td class="vam">
                                    <?= $plan['name']; ?>
                                </td>
                                <td class="vam">
                                    <?= $plan['end_date']; ?>
                                </td>
                                <td class="vam">
                                    -
                                </td>
                                <td class="vam">
                                    -
                                </td>
                                <td class="vam text-right">
                                    <button class="btn btn-warning csF16 jsBenefitPlanEdit">
                                        <i class="fa fa-edit csF16" aria-hidden="true"></i>
                                        &nbsp;Edit
                                    </button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>