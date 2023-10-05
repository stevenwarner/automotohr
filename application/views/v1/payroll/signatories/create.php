<?php
$formItems = [
    [
        'label' => 'First Name',
        'help' => 'The first name of the signatory.',
        'required' => true
    ],
    [
        'label' => 'Last Name',
        'help' => 'The last name of the signatory.',
        'required' => true
    ],
    [
        'label' => 'Middle Initial',
        'help' => 'The middle initial of the signatory.',
        'required' => false
    ],
    [
        'label' => 'Social Security Number',
        'help' => 'The Social Security Number (SSN) of the signatory.',
        'required' => true
    ],
    [
        'label' => 'Email',
        'help' => 'The email of the signatory.',
        'required' => true
    ],
    [
        'label' => 'Title',
        'help' => 'The title of the signatory.',
        'required' => true
    ],
    [
        'label' => 'Phone',
        'help' => 'The phone of the signatory.',
        'required' => false
    ],
    [
        'label' => 'Birthday',
        'help' => 'The birthday of the signatory.',
        'required' => true,
        'props' => 'id="jsBirthday" readonly="" placeholder="MM/DD/YYYY"'
    ],
    [
        'label' => 'Street 1',
        'help' => 'The street 1 of the signatory.',
        'required' => true,
    ],
    [
        'label' => 'Street 2',
        'help' => 'The street 2 of the signatory.',
        'required' => false
    ],
    [
        'label' => 'City',
        'help' => 'The city of the signatory.',
        'required' => true
    ],
    [
        'label' => 'State',
        'help' => 'The state code of the signatory.',
        'required' => true
    ],
    [
        'label' => 'Zip',
        'help' => 'The zip of the signatory.',
        'required' => true
    ],
];
?>

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
                                    Create Signatory
                                </span>
                            </div>
                        </div>
                    </div>

                    <!--  -->
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <a href="<?= base_url('payrolls/signatories'); ?>" class="btn btn-success csF16">
                                <i class="fa fa-chevron-left csF16" aria-hidden="true"></i>&nbsp;
                                Manage Signatories
                            </a>
                        </div>
                    </div>

                    <br>

                    <!--  -->
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h2 class="csF16 csW"><strong>Add a Signatory</strong></h2>
                        </div>
                        <div class="panel-body">

                            <p class="text-danger csF16">
                                <em>
                                    <strong>
                                        Please note that all fields with "*" must be completed.
                                    </strong>
                                </em>
                            </p>
                            <br>
                            <div class="alert alert-danger jsErrorDiv hidden"></div>
                            <br>
                            <!--  -->
                            <form action="javascript:void(0)" id="jsCreateForm">
                                <!--  -->
                                <div class="form-group">
                                    <label class="csF16">Choose an employee</label>
                                    <p class="text-danger"><strong><em><?= $item['help']; ?></em></strong></p>
                                    <select id="jsEmployeeChoose" class="form-control">
                                        <option value="0"></option>
                                        <?php foreach ($employees as $value) { ?>
                                            <option value="<?= $value['id']; ?>"><?= $value['value'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <hr />
                                <br>
                                <?php foreach ($formItems as $item) { ?>
                                    <div class="form-group">
                                        <label>
                                            <?= $item['label']; ?>
                                            <?= $item['required'] ? '<span class="text-danger">*</span>' : ''; ?>
                                        </label>
                                        <p class="text-danger"><strong><em><?= $item['help']; ?></em></strong></p>
                                        <input type="text" <?= $item['props'] ?? '' ?> class="form-control jsCreate<?= ucwords(preg_replace('/[^a-z0-9]/i', '', $item['label'])); ?>" />
                                    </div>
                                <?php } ?>
                                <div class="form-group text-right">
                                    <button class="btn btn-success jsSubmitBTN csF16">
                                        <i class="fa fa-save csF16" aria-hidden="true"></i>
                                        <span>Save Signatory</span>
                                    </button>
                                </div>
                            </form>
                            <!--  -->
                            <?php $this->load->view('v1/loader', ['id' => 'jsCreateLoader']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>