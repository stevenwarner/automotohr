<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <strong>
                        Benefits
                    </strong>
                </h1>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <button class="btn csW csBG3 csF16 jsAddBenefit">
                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                    &nbsp;Add a benefit
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (!$benefits) { ?>
            <?php $this->load->view('v1/no_data', ['message' => 'No benefits found.']); ?>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col" class="text-right">Benefit<br />type</th>
                            <th scope="col" class="text-right">Pretax</th>
                            <th scope="col" class="text-right">Posttax</th>
                            <th scope="col" class="text-right">Imputed</th>
                            <th scope="col" class="text-right">Health<br />care</th>
                            <th scope="col" class="text-right">Retirement</th>
                            <th scope="col" class="text-right">Yearly<br />limit</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($benefits as $category) { ?>
                            <tr data-id="<?= $category['sid']; ?>">
                                <td class="vam">
                                    <?= $category['name']; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['benefit_type']; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['pretax'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['posttax'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['imputed'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['healthcare'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['retirement'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam text-right">
                                    <?= $category['yearly_limit'] ? 'Yes' : 'No'; ?>
                                </td>
                                <td class="vam text-right">
                                    <a href="<?php echo base_url("sa/benefits/plans_management/".$category['sid']) ?>" class="csW btn btn-success csF16">
                                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                                        Plans Management
                                    </a>
                                    <button class="btn btn-warning csF16 jsEditBenefit">
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