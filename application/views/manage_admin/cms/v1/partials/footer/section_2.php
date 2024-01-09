<?php
$ariaExpanded = $this->input->get("page") == 'section_2' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_2' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_2"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection2" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Menu
            </strong>
        </h4>
    </div>
    <div id="jsSection2" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection2Form">
            <div class="panel-body">
                <?php for ($i = 1; $i <= 10; $i++) { ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>
                                    Text&nbsp;
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="text" class="form-control" name="menu<?= $i; ?>Text" value="<?= $contentToShow["menu" . $i . "Text"]; ?>" />

                            </div>
                            <div class="col-sm-6">
                                <label>
                                    Link&nbsp;
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="text" class="form-control" name="menu<?= $i; ?>Link" value="<?= $contentToShow["menu" . $i . "Link"]; ?>" />
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection2Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>