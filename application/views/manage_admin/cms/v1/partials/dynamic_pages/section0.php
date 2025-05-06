<?php
$ariaExpanded = $this->input->get("page") == 'details' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'details' ? "in" : "";
?>
<!-- Page details  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsPageDetails" aria-expanded="<?= $ariaExpanded; ?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Page details
            </strong>
        </h4>
    </div>
    <div id="jsPageDetails" class="panel-collapse collapse <?= $collapseIn; ?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsPageDetailsForm">
            <div class="panel-body">
                <div class="form-group">
                    <label>Page name</label>
                    <input type="text" class="form-control jsToSlug" data-target="slug" name="title" value="<?= $page["title"]; ?>" />
                </div>
                <div class="form-group">
                    <label>Page slug</label>
                    <input type="text" class="form-control" name="slug" value="<?= $page["slug"]; ?>" />
                </div>

                <div class="form-group">                
                    <label class="control control--checkbox">
                        <input type="checkbox" name="is_footer_link" id="jsSection2Status" <?= $page["is_footer_link"] ? "checked" :""; ?> /> Is Footer Link?
                        <div class="control__indicator"></div>
                    </label>
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success jsPageDetailsBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update page
                </button>
            </div>
        </form>
    </div>
</div>