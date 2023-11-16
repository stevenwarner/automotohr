<?php
$ariaExpanded = $this->input->get("page") == 'slider' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'slider' ? "in" : "";
$contentToShow = $pageContent["page"]["sections"]["banner"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsProductSlider" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Banner
            </strong>
        </h4>
    </div>
    <div id="jsProductSlider" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsProductSliderForm">
            <div class="panel-body">
                <div class="form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" name="mainHeading" id="mainHeading" class="form-control" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        Details&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <textarea rows="5" name="details" id="details" class="form-control"><?= $contentToShow["details"]; ?></textarea>
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsProductSliderBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>