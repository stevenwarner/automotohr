<form method="POST" action="<?php echo current_url(); ?>" id="form_save_email_template" enctype="multipart/form-data">
    <input type="hidden" id="perform_action" name="perform_action" value="save_template" />
    <input type="hidden" id="company_sid" name="company_sid" value="<?php echo $company_id; ?>" />
    <input type="hidden" id="company_name" name="company_name" value="<?php echo $company_name; ?>" />
    <div class="universal-form-style-v2">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <ul>
                    <li class="form-col-100">
                        <label>Name:<span class="staric">*</span></label>
                        <input type="text" class="invoice-fields" name="template_name" id="template_name" value="">
                    </li>
                    <li class="form-col-100">
                        <label>Subject:<span class="staric">*</span></label>
                        <input type="text" class="invoice-fields" name="template_subject" id="template_subject" value="">
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="description-editor template-letter-body">
                            <div class="row">
                                <div class="col-md-8 col-xs-12">
                                    <label>Body:<span class="staric">*</span></label>
                                    <textarea class="ckeditor"  name="template_body" id="template_body" rows="10"></textarea>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="offer-letter-help-widget">
                                        <div class="how-it-works-insturction">
                                            <strong>How it's Works :</strong>
                                            <p class="how-works-attr">1. Add template name</p>
                                            <p class="how-works-attr">2. Add template subject</p>
                                            <p class="how-works-attr">3. Add template body</p>
                                            <p class="how-works-attr">4. Add data from tags below</p>
                                            <p class="how-works-attr">5. Save the template</p>
                                        </div>

                                        <div class="tags-arae">
                                            <strong>Tags :</strong> (select tag from below)
                                            <ul class="tags">
                                                <li>{{company_name}}</li>
                                                <li>{{date}}</li>
                                                <li>{{first_name}}</li>
                                                <li>{{last_name}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="form-col-100 autoheight">
                        <label>Attachment</label>
                        <div class="upload-file invoice-fields">
                            <span id="name_message_attachment" class="selected-file">No file selected</span>
                            <input type="file" name="message_attachment" id="message_attachment" class="image">
                            <a href="javascript:;">Choose File</a>
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div class="form-col-100" id="add_answer">
                            <a href="javascript:;" onclick="addattachmentblock(); return false;" class="add"> + Attachment</a>
                        </div>
                    </li>
                    <li class="form-col-100 autoheight">
                        <div id="dynamicattachment"></div>
                    </li>
                </ul>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="submit" class="btn btn-success pull-right" >Save Template</button>
            </div>
        </div>
    </div>
</form>
<div class="clearfix"></div>