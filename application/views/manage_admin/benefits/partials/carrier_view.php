<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <strong>
                        Carriers
                    </strong>
                </h1>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <button class="btn csW csBG3 csF16 jsAddBenefitCarrier">
                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                    &nbsp;Add a carrier
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (!$carriers) { ?>
            <?php $this->load->view('v1/no_data', ['message' => 'No benefit carrier found.']); ?>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Carrier Name</th>
                            <th scope="col">Carrier Code</th>
                            <th scope="col">Plan Categories</th>
                            <th scope="col">Number of Plans</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carriers as $carrier) { ?>
                            <tr data-id="<?= $carrier['sid']; ?>">
                                <td class="vam">
                                    <?= $carrier['name']; ?>
                                </td>
                                <td class="vam">
                                    <?= $carrier['code']; ?>
                                </td>
                                <td class="vam">
                                    -
                                </td>
                                <td class="vam">
                                    -
                                </td>
                                <td class="vam text-right">
                                    <button class="btn btn-warning csF16 jsBenefitCarrierEdit">
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