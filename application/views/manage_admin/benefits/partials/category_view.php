<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6 col-xs-12">
                <h1 class="csF16 m0" style="padding-top: 10px;">
                    <strong>
                        Benefit categories
                    </strong>
                </h1>
            </div>
            <div class="col-sm-6 col-xs-12 text-right">
                <button class="btn csW csBG3 csF16 jsAddBenefitCategory">
                    <i class="fa fa-plus-circle csF16" aria-hidden="true"></i>
                    &nbsp;Add a benefit category
                </button>
            </div>
        </div>
    </div>
    <div class="panel-body">

        <?php if (!$categories) { ?>
            <?php $this->load->view('v1/no_data', ['message' => 'No benefit categories found.']); ?>
        <?php } else { ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <caption></caption>
                    <thead>
                        <tr>
                            <th scope="col">Category<br />name</th>
                            <th scope="col" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category) { ?>
                            <tr data-id="<?= $category['sid']; ?>">
                                <td class="vam">
                                    <?= $category['name']; ?>
                                </td>
                                <td class="vam text-right">
                                    <button class="btn btn-warning csF16 jsBenefitCategoryEdit">
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