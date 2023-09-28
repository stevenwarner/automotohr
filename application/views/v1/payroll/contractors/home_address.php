<?php
$fields = [
    [
        'label' => 'Street 1',
        'required' => true,
        'help' => '',
        'type' => 'text',
        'id' => 'jsContractorStreet1',
        'props' => 'class="form-control input-lg"',
        'selected' => $home_address['street_1'],
    ],
    [
        'label' => 'Street 2',
        'required' => false,
        'help' => '',
        'type' => 'text',
        'id' => 'jsContractorStreet2',
        'props' => 'class="form-control input-lg"',
        'selected' => $home_address['street_2'],
    ],
    [
        'label' => 'City',
        'required' => true,
        'help' => '',
        'type' => 'text',
        'id' => 'jsContractorCity',
        'props' => 'class="form-control input-lg"',
        'selected' => $home_address['city'],
    ],
    [
        'label' => 'State',
        'required' => true,
        'help' => '',
        'type' => 'text',
        'id' => 'jsContractorState',
        'props' => 'class="form-control input-lg"',
        'selected' => $home_address['state'],
    ],
    [
        'label' => 'Zip',
        'required' => true,
        'help' => '',
        'type' => 'text',
        'id' => 'jsContractorZip',
        'props' => 'class="form-control input-lg"',
        'selected' => $home_address['zip'],
    ],
];
?>

<div class="panel panel-success">
    <div class="panel-heading">
        <p class="csW csF18" style="margin-bottom: 0">
            <strong>Contractor Address</strong>
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
        <button class="btn btn-success csF16 jsContractorHomeAddressSaveBtn">
            <i class="fa fa-save csF16" aria-hidden="true"></i>
            &nbsp;Save
        </button>
        <button class="btn csW csBG4 csF16 jsModalCancel">
            <i class="fa fa-times-circle csF16" aria-hidden="true"></i>
            &nbsp;Cancel
        </button>
    </div>
</div>