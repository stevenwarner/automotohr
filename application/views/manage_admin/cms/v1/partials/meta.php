<?php
$ariaExpanded = $this->input->get("page") == 'meta' ? "true" : "false";
$collapseIn =  $this->input->get("page") == 'meta' ? "in" : "";
?>
<!-- Meta  -->
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-parent="#accordion" href="#jsMeta" aria-expanded="<?= $ariaExpanded;?>" aria-controls="collapseOne">
        <h4>
            <strong>
                Meta
            </strong>
        </h4>
    </div>
    <div id="jsMeta" class="panel-collapse collapse <?=$collapseIn;?>" role="tabpanel" aria-labelledby="headingOne">
        <form action="javascript:void(0)" id="jsMetaForm">
            <div class="panel-body">
                <div class=" form-group">
                    <label>Meta Title</label>
                    <p class="text-danger">
                        <strong>
                            <em>
                                Must be between 50 to 60 characters.
                            </em>
                        </strong>
                    </p>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" value="<?= $pageContent["page"]["meta"]["title"] ??  META_TITLE; ?>" />
                </div>
                <div class="form-group">
                    <label>Meta Description</label>
                    <p class="text-danger">
                        <strong>
                            <em>
                                Must be between 50 to 160 characters.
                            </em>
                        </strong>
                    </p>
                    <textarea class="form-control" id="meta_description" name="meta_description" rows="5"><?= $pageContent["page"]["meta"]["description"] ??  META_DESCRIPTION; ?></textarea>
                </div>
                <div class="form-group">
                    <label>Meta Keywords</label>
                    <p class="text-danger">
                        <strong>
                            <em>
                                Ideally 10 keywords are required.
                            </em>
                        </strong>
                    </p>
                    <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="5"><?= $pageContent["page"]["meta"]["keyword"] ??  META_KEYWORDS; ?></textarea>
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-success" id="jsMetaBtn">
                    <i class="fa fa-save" aria-hidden="true"></i>
                    &nbsp;Update
                </button>
            </div>
        </form>
    </div>
</div>