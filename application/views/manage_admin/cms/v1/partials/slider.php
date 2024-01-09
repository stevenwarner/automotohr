<?php
$ariaExpanded = $this->input->get("page") == 'slider' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'slider' ? "in" : "";
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSlider" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Slider
            </strong>
        </h4>
    </div>
    <div id="jsSlider" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
            <?php if ($pageContent["page"]['slider']) { ?>
                <?php foreach ($pageContent["page"]['slider'] as $key => $value) { ?>
                    <div class="row" data-key="<?= $key; ?>">
                        <div class="col-sm-7">
                            <h3><?= convertToStrip($value["heading"]); ?></h3>
                            <p><?= convertToStrip($value["headingDetail"]); ?></p>
                            <a href="<?= main_url($value["btnSlug"]); ?>" target="_blank" class="btn btn-success">
                                <?= $value["btnText"]; ?>
                            </a>
                        </div>
                        <div class="col-sm-3">
                            <img src="<?= AWS_S3_BUCKET_URL . $value["image"]; ?>" style="width: 100%" alt="banner image" />
                        </div>
                        <div class="col-sm-2 text-center" style="margin-top: 50px">
                            <button class="btn btn-warning jsEditBanner">
                                <i class="fa fa-edit" aria-hidden="true"></i>
                                &nbsp;Edit
                            </button>
                            <button class="btn btn-danger jsDeleteBanner">
                                <i class="fa fa-trash" aria-hidden="true"></i>
                                &nbsp;Delete
                            </button>
                        </div>
                    </div>
                    <hr />
                <?php } ?>
            <?php } ?>
        </div>
        <div class="panel-footer text-center">
            <button class="btn btn-success jsAddBanner">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
                &nbsp;Add a banner
            </button>
        </div>
    </div>
</div>