<?php
$ariaExpanded = $this->input->get("page") == 'section_1' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_1' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_1"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection1" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Menu
            </strong>
        </h4>
    </div>
    <div id="jsSection1" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection1Form">
            <div class="panel-body">
                <?php for ($i = 1; $i <= 5; $i++) { ?>
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

                <!-- sub menu -->
                <div class="form-group bg-success" style="padding: 10px">
                    <label>
                        Sub Menu
                    </label>
                </div>
                <?php for ($i = 1; $i <= 6; $i++) { ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label>
                                    Text&nbsp;
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="text" class="form-control" name="subMenu<?= $i; ?>Text" value="<?= $contentToShow["subMenu" . $i . "Text"]; ?>" />

                            </div>
                            <div class="col-sm-6">
                                <label>
                                    Link&nbsp;
                                    <strong class="text-danger">*</strong>
                                </label>
                                <input type="text" class="form-control" name="subMenu<?= $i; ?>Link" value="<?= $contentToShow["subMenu" . $i . "Link"]; ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label>
                                    Details&nbsp;
                                    <strong class="text-danger">*</strong>
                                </label>
                                <textarea type="text" class="form-control" name="subMenu<?= $i; ?>Details"><?= $contentToShow["subMenu" . $i . "Details"]; ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection1Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>