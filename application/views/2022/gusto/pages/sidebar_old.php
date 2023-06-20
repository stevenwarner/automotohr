<?php 
    $menuArray = [];
    // Company
    $menuArray['company'] = [
        'name' => 'Company',
        'slug' => '',
        'sub' => []
    ];
    $menuArray['company']['sub'][] = [
        'name' => 'Company Address',
        'slug'=> 'company_address'
    ];
    $menuArray['company']['sub'][] = [
        'name' => 'Federal Tax Info',
        'slug'=> 'federal_tax_info'
    ];
    // $menuArray['company']['sub'][] = [
    //     'name' => 'Industry',
    //     'slug'=> 'industry'
    // ];
    $menuArray['bank'] = [
        'name' => 'Bank info',
        'slug' => 'bank_info',
        'sub' => []
    ];
    $menuArray['employee'] = [
        'name' => 'Employees',
        'slug' => 'employee',
        'sub' => []
    ];
    $menuArray['employee']['sub'][] = [
        'name' => 'Profile',
        'slug'=> 'employee_profile'
    ];
    $menuArray['employee']['sub'][] = [
        'name' => 'Address',
        'slug'=> 'employee_address'
    ];
    $menuArray['employee']['sub'][] = [
        'name' => 'Compensation',
        'slug'=> 'employee_compensation'
    ];
    $menuArray['employee']['sub'][] = [
        'name' => 'Federal Tax',
        'slug'=> 'employee_federal_tax'
    ];
    $menuArray['employee']['sub'][] = [
        'name' => 'State Tax',
        'slug'=> 'employee_state_tax'
    ];
    $menuArray['employee']['sub'][] = [
        'name' => 'Payment',
        'slug'=> 'employee_payment'
    ];
    $menuArray['payroll'] = [
        'name' => 'Payroll',
        'slug' => 'payroll',
        'sub' => []
    ];
    $menuArray['tax_details'] = [
        'name' => 'Tax Details',
        'slug' => 'tax_details',
        'sub' => []
    ];
    $menuArray['sign_documents'] = [
        'name' => 'Sign Documents',
        'slug' => 'sign_documents',
        'sub' => []
    ];
    $menuArray['bank_verification'] = [
        'name' => 'Bank Verification',
        'slug' => 'bank_verification',
        'sub' => []
    ];

?>
<div class="col-md-2 col-sm-12">
    <div class="panel panel-theme">
        <div class="panel-heading csF16 csB7">
            Onboarding
        </div>
        <div class="panel-body">
            <ul class="csSidebarUL">
            <?php foreach($menuArray as $index => $value): ?>
                <li <?=$index == $mainIndex ? 'class="active"' : ''; ?>>
                    <a class="csF16 js jsNavBarAction" data-id="<?=$value['slug'];?>" href="javascript:void(0)"><?=$value['name'];?></a>
                    <?php if(isset($value['sub'])): ?>
                    <ul>
                        <?php foreach($value['sub'] as $index2 => $value2):  ?>
                        <li <?=$value2['slug'] == $subIndex ? 'class="active"' : ''; ?>><a class="csF16 jsNavBarAction"  data-id="<?=$value2['slug'];?>" href="javascript:void(0)"><?=$value2['name'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>