<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">
            <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven">Section Seven <span class="glyphicon glyphicon-plus"></span></a>

        </h4>
    </div>
    <div id="collapseSeven" class="panel-collapse collapse">
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12">
                    <div>
                        <h2>Footer Content</h2>
                        <div class="universal-form-style-v2">
                            <ul>
                                <form id="form_save_footer_content" method="post" name="form_save_footer_content" enctype="multipart/form-data">

                                    <input type="hidden"
                                           name="theme_name"
                                           id="theme_name"
                                           value="<?php echo $theme['theme_name']; ?>"/>
                                    <input type="hidden"
                                           name="page_name"
                                           id="page_name" value="home"/>

                                    <input type="hidden"
                                           name="perform_action"
                                           id="perform_action"
                                           value="save_footer_content"/>

                                    <li class="form-col-100 autoheight">

                                        <label for="footer_title">Footer Title<span class="staric">*</span></label>
                                        <input class="invoice-fields" id="footer_title" name="footer_title" type="text" value="<?php echo ($footer_content['title'] != '' ? $footer_content['title'] : ''); ?>"/>

                                    </li>
                                    <li class="form-col-100 autoheight">
                                        <label for="footer_content">Footer Content <span class="staric">*</span></label>
                                        <div style='margin-bottom:5px;'><?php $this->load->view('templates/_parts/ckeditor_gallery_link'); ?></div>
                                        <textarea class="invoice-fields-textarea" type="text" id="footer_content" name="footer_content" rows="8" ><?php echo ($footer_content['title'] != '' ? $footer_content['content'] : ''); ?></textarea>
                                    </li>

                                    <li class="form-col-100 autoheight">
                                        <input id="footer_content_save" type="submit" class="submit-btn" value="Save" onclick="fValidateSaveFooterContent();" />
                                    </li>
                                </form>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        CKEDITOR.replace('footer_content');
    });
    
    function fValidateSaveFooterContent(){
        $('#form_save_footer_content').validate({
            ignore: [],
            rules : {
                footer_title : {
                    required : true
                },
                footer_content : {
                    required : true
                }
            },
            messages: {
                footer_title: {
                    required: 'Title is required',
                },
                footer_content: {
                    required: 'Section content is required',
                }
            }
        });
    }
</script>