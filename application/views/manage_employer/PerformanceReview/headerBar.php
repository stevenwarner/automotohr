<div class="page-header-area">
    <span class="page-heading down-arrow">
        <a class="dashboard-link-btn" href="<?= $Link[0]; ?>"> <i class="fa fa-chevron-left"></i><?= $Link[1]; ?>
    </a><?= $Text; ?>
    <?php if(isset($Link2)){ ?>
        <a class="dashboard-link-btn pull-right" style="left: auto; right: 10px;" href="<?= $Link2[0]; ?>"> <i class="fa fa-chevron-left"></i><?= $Link2[1]; ?>
    <?php } ?>
    </a>
    </span>
</div>