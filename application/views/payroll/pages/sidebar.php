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
        'name' => 'Company Address',
        'slug'=> 'company_address'
    ];
    $menuArray['company']['sub'][] = [
        'name' => 'Federal Tax Info',
        'slug'=> 'federal_tax_info'
    ];
    $menuArray['company']['sub'][] = [
        'name' => 'Industry',
        'slug'=> 'industry'
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
                    <a class="csF16 js" data-id="<?=$value['slug'];?>" href="javascript:void(0)"><?=$value['name'];?></a>
                    <?php if(isset($value['sub'])): ?>
                    <ul>
                        <?php foreach($value['sub'] as $index2 => $value2): ?>
                        <li <?=$index2 == $subIndex ? 'class="active"' : ''; ?>><a class="csF16"  data-id="<?=$value2['slug'];?>" href="javascript:void(0)"><?=$value2['name'];?></a></li>
                        <?php endforeach;?>
                    </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach;?>
            </ul>
        </div>
    </div>
</div>