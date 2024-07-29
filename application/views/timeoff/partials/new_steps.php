<!-- Steps -->
<div>
    <ul class="csProcessSidebar" id="js-step-bar-<?=$stepType;?>">
        <?php if($stepType == 'add') { ?>
        <li class="active"><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="0" class="js-step-tab"> Templates <i class="fa fa-long-arrow-right"></i></a></li>
        <?php } ?>
        <li <?= $stepType == 'edit' ? 'class="active"' : '';?> >
            <a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="1" class="js-step-tab"> Basic
            <?= $stepType == 'edit' ? '<i class="fa fa-long-arrow-right"></i>' : '';?>
            </a>
        </li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="2" class="js-step-tab">Accrual Setting</a></li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="3" class="js-step-tab">Carryover</a></li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="4" class="js-step-tab">Negative Balance</a></li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="5" class="js-step-tab">Applicable Date</a></li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="6" class="js-step-tab">Reset Date</a></li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="7" class="js-step-tab">New Hire</a></li>
        <li><a href="javascript:void(0)" data-type="<?=$stepType;?>" data-step="9" class="js-step-tab">Employee Accrual Setting</a></li>

    </ul>
</div>
