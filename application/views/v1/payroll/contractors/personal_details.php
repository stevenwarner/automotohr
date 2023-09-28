<?php
$fields = [
    [
        'label' => 'Type',
        'required' => true,
        'help' => 'The contractor type.',
        'type' => 'select',
        'id' => 'jsContractorType',
        'props' => 'class="form-control input-lg" disabled',
        'selected' => $contractor['contractor_type'],
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
        'selected' => $contractor['wage_type'],
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
        'selected' => formatDateToDB($contractor['start_date'], DB_DATE, SITE_DATE)
    ],
    [
        'label' => 'Hourly Rate',
        'required' => false,
        'help' => 'The contractor\'s hourly rate. This attribute is required if the wage_type is Hourly.',
        'type' => 'number',
        'props' => 'class="form-control input-lg jsContractorHourlyRate"',
        'id' => 'jsContractorHourlyRate',
        'selected' => $contractor['hourly_rate']
    ],
    [
        'label' => 'Email',
        'required' => false,
        'help' => "The contractor's email address.",
        'type' => 'email',
        'props' => 'class="form-control input-lg jsContractorEmail"',
        'id' => 'jsContractorEmail',
        'selected' => $contractor['email']
    ],
    [
        'label' => 'First Name',
        'required' => true,
        'help' => "The contractor's first name.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorFirstName jsContractorIndividual"',
        'id' => 'jsContractorFirstName',
        'selected' => $contractor['first_name']
    ],
    [
        'label' => 'Last Name',
        'required' => true,
        'help' => "The contractor's last name.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorLastName jsContractorIndividual"',
        'id' => 'jsContractorLastName',
        'selected' => $contractor['last_name']
    ],
    [
        'label' => 'Middle Initial',
        'required' => false,
        'help' => "The contractor's middle initial.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorMiddleInitial jsContractorIndividual"',
        'id' => 'jsContractorMiddleInitial',
        'selected' => $contractor['middle_initial']
    ],
    [
        'label' => 'File New Hire Report',
        'required' => true,
        'help' => "The boolean flag indicating whether Gusto will file a new hire report for the contractor.",
        'id' => 'jsContractorFileNewHireReport',
        'type' => 'select',
        'props' => 'class="form-control input-lg jsContractorIndividual"',
        'selected' => $contractor['file_new_hire_report'],
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
        'selected' => $contractor['work_state']
    ],
    [
        'label' => 'Social Security number (SSN)',
        'required' => true,
        'help' => "Social security number is needed to file the annual 1099 tax form. It must be of 9 digits.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorSSN jsContractorIndividual"',
        'id' => 'jsContractorSSN',
        'selected' => $contractor['ein'] ? _secret($contractor['ein']) : ''
    ],
    [
        'label' => 'Business Name',
        'required' => true,
        'help' => "The name of the contractor business.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorBusinessName jsContractorBusiness"',
        'id' => 'jsContractorBusinessName',
        'selected' => $contractor['business_name']
    ],
    [
        'label' => 'EIN',
        'required' => true,
        'help' => "The employer identification number of the contractor business.",
        'type' => 'text',
        'props' => 'class="form-control input-lg jsContractorEIN jsContractorBusiness"',
        'id' => 'jsContractorEIN',
        'selected' => $contractor['ein']
    ],
    [
        'label' => 'Is Active',
        'required' => true,
        'help' => "The status of the contractor.",
        'id' => 'jsContractorIsActive',
        'props' => 'class="form-control input-lg"',
        'type' => 'select',
        'selected' => $contractor['is_active'],
        'options' => [
            '1' => 'Yes',
            '0' => 'No',
        ]
    ],
];
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <p class="csW csF18" style="margin-bottom: 0">
            <strong>Contractor details</strong>
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
                                <option value="<?= $index; ?>" <?= $index == $field['selected'] ? 'selected' : ''; ?>><?= $value; ?></option>
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
        <button class="btn btn-success csF16 jsContractorSaveEditBtn">
            <i class="fa fa-save csF16" aria-hidden="true"></i>
            &nbsp;Save
        </button>
        <button class="btn csW csBG4 csF16 jsModalCancel">
            <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
            &nbsp;Cancel
        </button>
    </div>
</div>