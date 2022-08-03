<div class="row">
                                            <div class="col-xs-12">
                                                <div class="hr-box">
                                                    <div class="hr-box-header">
                                                        <strong>Document Library?</strong>
                                                    </div>
                                                    <div class="hr-innerpadding">
                                                        <?php
                                                        if ($document_info['isdoctolibrary'] == 1) {
                                                            $isdoctolibrary1 = 'checked="true"';
                                                        } else {
                                                            $isdoctolibrary0 = 'checked="true"';
                                                        } ?>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <p class="text-danger"><strong><em>If marked "Yes", this document will appear in the Employee Document library and allow Employees to initiate this document themselves.</em></strong></p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <p>Add this document to the Employee library?</p>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <label class="control control--radio font-normal">
                                                                    <input class="disable_doc_checkbox" name="isdoctolibrary" type="radio" value="0" <?php echo $isdoctolibrary0; ?> />
                                                                    No &nbsp;
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                                <label class="control control--radio font-normal">
                                                                    <input class="disable_doc_checkbox" name="isdoctolibrary" type="radio" value="1" <?php echo $isdoctolibrary1; ?> />
                                                                    Yes &nbsp;
                                                                    <div class="control__indicator"></div>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <?php if (false) { ?>
                                                            <hr>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <p class="text-danger"><strong><em>If "No", the document will not visible to employee on document center.</em></strong></p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-xs-12">
                                                                    <p>Is the document visible to employee on document center?</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <?php
                                                                if ($document_info['visible_to_document_center'] == 1) {
                                                                    $visibletodocumentcenter1 = 'checked="true"';
                                                                } else {
                                                                    $visibletodocumentcenter0 = 'checked="true"';
                                                                } ?>
                                                                <div class="col-xs-12">
                                                                    <label class="control control--radio font-normal">
                                                                        <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="0" <?php echo $visibletodocumentcenter0; ?> />
                                                                        No &nbsp;
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                    <label class="control control--radio font-normal">
                                                                        <input class="disable_doc_checkbox" name="visibletodocumentcenter" type="radio" value="1" <?php echo $visibletodocumentcenter1; ?> />
                                                                        Yes &nbsp;
                                                                        <div class="control__indicator"></div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>