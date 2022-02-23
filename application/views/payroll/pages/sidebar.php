<?php 
    $menuArray = [];
    // 
    $menuArray['employee_profile'] = [
        'name' => 'Personal details',
        'slug' => 'employee_profile',
        'sub' => []
    ];
    $menuArray['employee_compensation'] = [
        'name' => 'Compensation',
        'slug' => 'employee_compensation',
        'sub' => []
    ];
    $menuArray['employee_address'] = [
        'name' => 'Home address',
        'slug' => 'employee_address',
        'sub' => []
    ];
    $menuArray['employee_federal_tax'] = [
        'name' => 'Federal tax',
        'slug' => 'employee_federal_tax',
        'sub' => []
    ];
    $menuArray['employee_state_tax'] = [
        'name' => 'State Tax',
        'slug' => 'employee_state_tax',
        'sub' => []
    ];
    $menuArray['employee_payment'] = [
        'name' => 'Payment method',
        'slug' => 'employee_payment',
        'sub' => []
    ];
?>
<div class="col-md-3 col-sm-12">
    <div class="panel panel-theme">
        <div class="panel-heading csF16 csB7">
            Employee Onboarding
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