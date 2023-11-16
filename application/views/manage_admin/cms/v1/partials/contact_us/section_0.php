<?php
$ariaExpanded = $this->input->get("page") == 'section_0' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'section_0' ? "in" : "";
//
$contentToShow = $pageContent["page"]["sections"]["section_0"];
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsSection0" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Main Banner
            </strong>
        </h4>
    </div>
    <div id="jsSection0" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsSection0Form">
            <div class="panel-body">

                <div class=" form-group">
                    <label>
                        Main heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="mainHeading" value="<?= $contentToShow["mainHeading"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Sub heading&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="subHeading" value="<?= $contentToShow["subHeading"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        <strong>
                            Sales Support
                        </strong>
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Phone number&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="phoneNumberSales" value="<?= $contentToShow["phoneNumberSales"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Email&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="email" class="form-control" name="emailAddressSales" value="<?= $contentToShow["emailAddressSales"]; ?>" />
                </div>
                <div class="form-group">
                    <label>
                        <strong>
                            Technical Support
                        </strong>
                    </label>
                </div>
                <div class=" form-group">
                    <label>
                        Phone number&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="text" class="form-control" name="phoneNumberTechnical" value="<?= $contentToShow["phoneNumberTechnical"]; ?>" />
                </div>
                <div class=" form-group">
                    <label>
                        Email&nbsp;
                        <strong class="text-danger">*</strong>
                    </label>
                    <input type="email" class="form-control" name="emailAddressTechnical" value="<?= $contentToShow["emailAddressTechnical"]; ?>" />
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsSection0Btn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>