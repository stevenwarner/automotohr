<?php
    $slots = 100 / count($steps);
?>
<div class="arrow-links <?=$prop_class;?>">
    <ul>
        <?php foreach($steps as $step): ?>
            <li style="width: <?=$slots;?>%;" <?=$step['slug'] == $active ? 'class="active"' : '';?>>
                <a href="<?=$step['url'];?>">
                    <span class="csF16 csB7"><?=$step['text'];?></span>
                    <div class="step-text"><?=$step['sub_text'];?></div> 
                    <i class="star" aria-hidden="true"></i>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>