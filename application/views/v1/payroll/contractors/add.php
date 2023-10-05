<?php

$fields = [
    [
        'label' => 'Type',
        'required' => true,
        'help' => 'The contractor type.',
        'type' => 'select',
        'id' => 'jsContractorType',
        'props' => 'class="form-control input-lg"',
        'selected' => '',
        'options' => [
            'Individual' => 'Individual',
            'Business' => 'Business',
        ],
    ],
    [
        'label' => 'Wage Type',
        'required' => true,
        'help' => 'The contractor\'s wage type.',
        'type' => 'select',
        'id' => 'jsContractorWageType',
        'props' => 'class="form-control input-lg"',
        'selected' => '',
        'options' => [
            'Fixed' => 'Fixed',
            'Hourly' => 'Hourly',
        ],
    ],
    [
        'label' => 'Start Date',
        'required' => true,
        'help' => 'The day when the contractor will start working for the company.',
        'type' => 'text',
        'props' => 'readonly class="form-control input-lg jsContractorStartDate jsDatePicker"',
        'id' => 'jsContractorStartDate',
        'selected' => ''
    ],
    [
        'label' => 'Hourly Rate',
        'required' => false,
        'help' => 'The contractor\'s hourly rate. This attribute is required if the wage_type is Hourly.',
        'type' => 'number',
        'props' => 'class="form-control input-lg jsContractorHourlyRate"',
        'id' => 'jsContractorHourlyRate',
        'selected' => ''
    ],
    [
        'label' => 'Email',
        'required' => false,
        'help' => "The contractor's email address.",
        'type' => 'email',
        'props' => 'class="form-control input-lg jsContractorEmail"',
        'id' => 'jsContractorEmail',
        'selected' => ''
    ],
    [
        'label' => 'First Name',
        'required' => true,
        'help' => "The contractor's first name.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorFirstName jsContractorIndividual"',
        'id' => 'jsContractorFirstName',
        'selected' => ''
    ],
    [
        'label' => 'Last Name',
        'required' => true,
        'help' => "The contractor's last name.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorLastName jsContractorIndividual"',
        'id' => 'jsContractorLastName',
        'selected' => ''
    ],
    [
        'label' => 'Middle Initial',
        'required' => false,
        'help' => "The contractor's middle initial.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorMiddleInitial jsContractorIndividual"',
        'id' => 'jsContractorMiddleInitial',
        'selected' => ''
    ],
    [
        'label' => 'File New Hire Report',
        'required' => true,
        'help' => "The boolean flag indicating whether Gusto will file a new hire report for the contractor.",
        'id' => 'jsContractorFileNewHireReport',
        'type' => 'select',
        'props' => 'class="form-control input-lg jsContractorIndividual"',
        'selected' => '',
        'options' => [
            '1' => 'Yes, file the state tax new hire report for me.',
            '0' => 'No, I have already filed.',
        ]
    ],
    [
        'label' => 'Work State',
        'required' => true,
        'help' => "State where the contractor will be conducting the majority of their work for the company.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorWorkState jsContractorIndividual"',
        'id' => 'jsContractorWorkState',
        'selected' => ''
    ],
    [
        'label' => 'Social Security number (SSN)',
        'required' => true,
        'help' => "Social security number is needed to file the annual 1099 tax form. It must be of 9 digits.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorSSN jsContractorIndividual"',
        'id' => 'jsContractorSSN',
        'selected' => ''
    ],
    [
        'label' => 'Business Name',
        'required' => true,
        'help' => "The name of the contractor business.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorBusinessName jsContractorBusiness"',
        'id' => 'jsContractorBusinessName',
        'selected' => ''
    ],
    [
        'label' => 'EIN',
        'required' => true,
        'help' => "The employer identification number of the contractor business.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorEIN jsContractorBusiness"',
        'id' => 'jsContractorEIN',
        'selected' => ''
    ],
    [
        'label' => 'Is Active',
        'required' => true,
        'help' => "The status of the contractor.",
        'id' => 'jsContractorIsActive',
        'props' => 'class="form-control input-lg"',
        'type' => 'select',
        'selected' => '',
        'options' => [
            '1' => 'Yes',
            '0' => 'No',
        ]
    ],
];
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-3">
            <div class="sidebar-nav">
                <div class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <span class="visible-xs navbar-brand">Contractor payroll menu</span>
                    </div>
                    <div class="navbar-collapse collapse sidebar-navbar-collapse">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#" class="csF16">Personal details</a></li>
                            <li class="disabled"><a href="#" class="csF16">Address</a></li>
                            <li class="disabled"><a href="#" class="csF16">Payment method</a></li>
                            <li class="disabled"><a href="#" class="csF16">Documents</a></li>
                            <li class="disabled"><a href="#" class="csF16">Summary</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-9">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <p class="csW csF18" style="margin-bottom: 0">
                        <strong>Add a Contractor</strong>
                    </p>
                </div>
                <div class="panel-body">
                    <form action="javascript:void(0)">
                        <!--  -->
                        <?php foreach ($fields as $field) : ?>
                            <div class="form-group">
                                <label class="csF16"><?= $field['label']; ?><?= $field['required'] ? ' <strong class="text-danger">*</strong>' : ''; ?></label>
                                <p class="text-danger">
                                    <strong><em><?= $field['help']; ?></em></strong>
                                </p>
                                <?php if ($field['type'] === 'select') : ?>
                                    <select name="<?= $field['id']; ?>" id="<?= $field['id']; ?>" <?= $field['props']; ?>>
                                        <?php foreach ($field['options'] as $index => $value) : ?>
                                            <option value="<?= $index; ?>" <?= $index === $field['selected'] ? 'selected' : ''; ?>><?= $value; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else : ?>
                                    <input type="<?= $field['type']; ?>" <?= $field['props']; ?> id="<?= $field['id']; ?>" value="<?= $field['selected']; ?>" />
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </form>
                </div>
                <div class="panel-footer text-right">
                    <button class="btn btn-success csF16 jsContractorSaveBtn">
                        <i class="fa fa-save csF16" aria-hidden="true"></i>
                        &nbsp;Save
                    </button>
                    <button class="btn csW csBG4 csF16 jsModalCancel">
                        <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
                        &nbsp;Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* make sidebar nav vertical */
    @media (min-width: 768px) {
        .sidebar-nav .navbar .navbar-collapse {
            padding: 0;
            max-height: none;
        }

        .sidebar-nav .navbar ul {
            float: none;
        }

        .sidebar-nav .navbar ul:not {
            display: block;
        }

        .sidebar-nav .navbar li {
            float: none;
            display: block;
        }

        .sidebar-nav .navbar li a {
            padding-top: 12px;
            padding-bottom: 12px;
        }
    }

    .navbar-default .navbar-nav>.active>a,
    .navbar-default .navbar-nav>.active>a:hover,
    .navbar-default .navbar-nav>.active>a:focus {
        background-color: #81b431;
        color: #fff;
        font-weight: 700;
    }
</style>